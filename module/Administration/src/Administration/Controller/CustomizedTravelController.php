<?php
namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\AreaService;
use Service\TextService;
use Service\CustomerSubmissionService;

class CustomizedTravelController extends AbstractActionController {
	
	public function listCustomerMessageAction()
	{
		$view = new ViewModel();
		$postParams = $this->params()->fromRoute();
		if(isset($postParams['page']) && $postParams['page']!=null){
			$page = $postParams['page'];
		}else{
			$page = 1;
		}
		$csService = new CustomerSubmissionService($this->serviceLocator);
		$result = null;
		try {
			$result = $csService->getCustomizedTravelMesssage(array('page'=>$page));
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}		 
		$view->setVariables(array('result'=>$result));
		return $view;
	}
	
	public function deleteMessageAction()
	{
		$view = new JsonModel();
		$view->setTerminal(true);
		if($this->getRequest()->isPost()){
			$postParams = $this->params()->fromPost();
			if(isset($postParams['Id'])){
				error_log("id: ".$postParams['Id']);
				$csService = new CustomerSubmissionService($this->serviceLocator);
				$data = array('Id'=>$postParams['Id'],
						'Deleted'=>true,
						'UpdatedTime'=>gmdate("Y-m-d H:i:s"),
				);
				$result = $csService->editMessage($data);
				if($result){
					$response = array('success'=>true);
				}else{
					$response = array('success'=>false);
				}
			}else{
				$response = array('success'=>false);
			}
		}else{
			$response = array('success'=>false);
		}
		$view->setVariables($response);
		return $view;
	}
	
	
}