<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\TextService;
use Service\DestinationService;
use Service\ItineraryService;
use Service\CustomerSubmissionService;

class ShuttleServiceController extends AbstractActionController {
	
	public function indexAction()
    {
    	$view = new ViewModel();
		$textService = new TextService($this->serviceLocator);
		try {
			$textResults = $textService->getShuttleServiceMainDescription();
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		
		$destinationService = new DestinationService($this->serviceLocator);
		try{
			$destinationResults = $destinationService->getDestinations();
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		
		$results = new \stdClass();
		if($textResults != null){
			$text = $textResults[0]->Text;
			$results->text = $text;
		}else{
			$results->text = null;
		}
		if($destinationResults != null){
			$results->destinations = $destinationResults; 
		}else{
			$results->destinations = null;
		}
		$view->setVariables(array('results'=>$results));
		return $view;
    }
    
    public function listItineraryAction()
    {
    	$view = new JsonModel();
    	$view->setTerminal(true);
    	if($this->getRequest()->isPost()){
    		$postParams = $this->params()->fromPost();
    		$itineraryService = new ItineraryService($this->serviceLocator);
    		$results = $itineraryService->getQualifiedItineraries($postParams);
    		if($results != null){
    			$response = array('success'=>true, 'result'=>$results);
    		}else{
    			$response = array('success'=>false, 'result'=>null);
    		}
    	}else{
			$response = array('success'=>false, 'result'=>null);
		}
		$view->setVariables($response);
		return $view;
    }
    
    public function submitCustomerItineraryAction()
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
    		}else{
    			$data['Message'] = "(No message)";
    		}
    		if(count($errorArray)>0){
    			$response = array('success'=>false, 'result'=>array('errors'=>$errorArray));
    		}else{
    			$csService = new CustomerSubmissionService($this->serviceLocator);
    			$csSubmitResult = $csService->addShuttleServiceSubmission($data);
    			if($csSubmitResult != null){
    				if(isset($postParams['itineraryId']) && $postParams['itineraryId']!=null){
    					//add data to CustomerItinerary
    					$itineraryService = new ItineraryService($this->serviceLocator);
    					$addCustomerItineraryResult = $itineraryService->addCustomerItinerary(array(
    						'ItineraryId'=>intval($postParams['itineraryId']),
    						'CustomerSubmissionId'=>intval($csSubmitResult),	
    					));
    					if($addCustomerItineraryResult != null){
    						$response = array('success'=>true, 'result'=>null);
    					}
    				}else{
    					$response = array('success'=>true, 'result'=>null);
    				}    				
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