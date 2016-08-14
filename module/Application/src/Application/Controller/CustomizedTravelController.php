<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\AreaService;
use Service\TextService;
use Service\CustomerSubmissionService;

class CustomizedTravelController extends AbstractActionController {
	
	public function indexAction()
	{
		$view = new ViewModel();
		$textService = new TextService($this->serviceLocator);
		try {
			$textResults = $textService->getCustomizedTravelMainDescription();
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		$results = new \stdClass();
		if($textResults != null){
			$text = $textResults[0]->Text;
			$results->text = $text;
			$view->setVariables(array('status'=>0, 'results'=>$results));
		}else{
			$view->setVariables(array('status'=>1, 'results'=>null));
		}
		return $view;
	}
	
	public function detailAction()
	{
		$view = new ViewModel();
		$postParams = $this->params()->fromRoute();
		$areaId = $postParams['id'];
		$areaService = new AreaService($this->serviceLocator);
		$areaResult = null;
		try {
			$areaResult = $areaService->getArea(array('id'=>intval($areaId)));
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}		 
		$view->result = $areaResult;
		return $view;
	}
	
	public function submitCustomizedTravelMessageAction()
	{
		$view = new JsonModel();
		$view->setTerminal(true);
		if($this->getRequest()->isPost()){
			$postParams = $this->params()->fromPost();
			$errorArray = array();
			$data = array();
			if(isset($postParams['firstname']) && $postParams['firstname']!=null){
				$data['FirstName'] = htmlspecialchars($postParams['firstname']);
			}
			if(isset($postParams['lastname']) && $postParams['lastname']!=null){
				$data['LastName'] = htmlspecialchars($postParams['lastname']);
			}
			if(isset($postParams['email']) && $postParams['email']!=null){
				if(!filter_var($postParams['email'], FILTER_VALIDATE_EMAIL)){
					array_push($errorArray, "请填写正确的邮箱地址。");
				}else{
					$data['Email'] = $postParams['email'];
				}
			}
			if(isset($postParams['phone']) && $postParams['phone']!=null){
				if(!preg_match('/^\d{10}$/i', $postParams['phone'])){
					array_push($errorArray, "请填写正确的美国电话号码。");
				}else{
					$data['PhoneNumber'] = (int)$postParams['phone'];
				}
			}
			if(isset($postParams['wechat']) && $postParams['wechat']!=null){
				//only letters, digits and _, more than 5
				if(!preg_match('/^[a-zA-Z\d_]{5,}$/i', $postParams['wechat'])){
					array_push($errorArray, "请填写正确的微信号。");
				}else{
					$data['Wechat'] = $postParams['wechat'];
				}
			}
			if(isset($postParams['message']) && $postParams['message']!=null){
				$data['Message'] = htmlspecialchars($postParams['message']);
			}
			if(count($errorArray)>0){
				$response = array('success'=>false, 'result'=>array('errors'=>$errorArray));
			}else{
				$csService = new CustomerSubmissionService($this->serviceLocator);
				if($csService->addCustomizedTravelSubmission($data) != null){
					$response = array('success'=>true, 'result'=>null);
				}else{
					$response = array('success'=>false, 'result'=>null);
				}
			}
		}else{
			$response = array('success'=>false, 'result'=>null);
		}
		$view->setVariables($response);
		return $view;
	}
	
	
}