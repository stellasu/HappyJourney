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
	
	public function addAreaAction()
	{
		if($this->getRequest()->isPost()){
			$postParams = $this->params()->fromPost();			
			if($postParams != null){
				$view = new JsonModel();
				$view->setTerminal(true);
				$areaService = new AreaService($this->serviceLocator);
				$addResult = $areaService->addArea($postParams);
				$view->setVariables($addResult);
				return $view;
			}else{
				$view->setVariables(array('success'=>false, 'message'=>'no data'));
				return $view;
			}
		}else{
			$view = new ViewModel();
			return $view;
		}
	}
	
	
	public function editAreaAction()
	{
		if($this->getRequest()->isPost()){
			$postParams = $this->params()->fromPost();
			$view = new JsonModel();
			$view->setTerminal(true);
			if($postParams != null){
				$areaService = new AreaService($this->serviceLocator);
				$postParams['UpdateTime'] = gmdate("Y-m-d H:i:s");
				$editResult = $areaService->editArea($postParams);
				$view->setVariables($editResult);
				return $view;
			}else{
				$view->setVariables(array('success'=>false, 'message'=>'no data'));
				return $view;
			}
		}else{
			//list areas
			$view = new ViewModel();
			$areaService = new AreaService($this->serviceLocator);
			try {
				$results = $areaService->getAreas();
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
			}
			if($results != null){
				$view->setVariables(array('result'=>$results));
			}else{
				$view->setVariables(array('result'=>null));
			}
			return $view;
		}
	}
	
	
}