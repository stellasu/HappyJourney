<?php
namespace Administration\Controller;

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
		$csService = new CustomerSubmissionService($this->serviceLocator);
		$postParams = $this->params()->fromRoute();
		if(isset($postParams['page']) && $postParams['page']!=null){
			$page = $postParams['page'];
		}else{
			$page = 1;
		}
		try {
			$results = $csService->getCustomerItineraries(array('page'=>$page));
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		$view->setVariables(array('result'=>$results));
		return $view;
    }
    
    public function closeCustomerItineraryAction()
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
    			$deleteMessageResult = $csService->editMessage($data);
     			
    			if($deleteMessageResult){
    				if(isset($postParams['CustomerItineraryId']) && $postParams['CustomerItineraryId']!=null){
    					$itineraryService = new ItineraryService($this->serviceLocator);
    					$data = array('Id'=>$postParams['CustomerItineraryId'], 'Closed'=>true, 'UpdatedTime'=>gmdate("Y-m-d H:i:s"),);
    					$editItineraryResult = $itineraryService->editCustomerItinerary($data);
    					if($editItineraryResult){
    						$response = array('success'=>true);
    					}else{
    						$response = array('success'=>false);
    					}
    				}else{
    					$response = array('success'=>true);
    				}   				  				
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
    
    public function manageItineraryAction()
    {
    	$view = new ViewModel();
    	$itineraryService = new ItineraryService($this->serviceLocator);
    	$postParams = $this->params()->fromRoute();
    	if(isset($postParams['page']) && $postParams['page']!=null){
    		$page = $postParams['page'];
    	}else{
    		$page = 1;
    	}
    	try {
    		$results = $itineraryService->getItineraries(array('page'=>$page));
    	} catch (\Exception $e) {
    		error_log("error: ".$e->getMessage());
    	}
    	$view->setVariables(array('result'=>$results));
    	return $view;
    }
    
    public function addItineraryAction()
    {
    	if($this->getRequest()->isPost()){
    		$view = new JsonModel();
    		$view->setTerminal(true);
    		$postParams = $this->params()->fromPost();
    		if((isset($postParams['DestinationId'])&&$postParams['DestinationId']!=null ||
    				isset($postParams['Name'])&&$postParams['Name']!=null) &&
    				isset($postParams['Date'])&&$postParams['Date']!=null &&
    				isset($postParams['Hour'])&&$postParams['Hour']!=null &&
    				isset($postParams['Minute'])&&$postParams['Minute']!=null){
    			if(isset($postParams['Name']) && $postParams['Name']!=null){
    				$data['Name'] = $postParams['Name'];
    				if(isset($postParams['Description']) && $postParams['Description']==null){
    					$data['Description'] = $postParams['Description'];
    				}else{
    					$data['Description'] = "No description";
    				}
    				$destinationService = new DestinationService($this->serviceLocator);
    				$addDestinationResult = $destinationService->addDestination($data);
    				if(intval($addDestinationResult)>=0){
    					unset($postParams['Name']);
    					unset($postParams['Description']);
    					$postParams['DestinationId'] = $addDestinationResult;
    					$postParams['TimeZone'] = 'America/Los_Angeles';
    					$itineraryService = new ItineraryService($this->serviceLocator);
    					$addResult = $itineraryService->addItinerary($postParams);
    					if(intval($addResult)>=0){
    						$response = array('success'=>true);
    					}else{
    						$response = array('success'=>false, 'message'=>'Adding itinerary failed');
    					}
    				}else{
    					$response = array('success'=>false, 'message'=>'Adding destination failed');
    				}
    			}else{
    				unset($postParams['Name']);
    				unset($postParams['Description']);
    				$postParams['TimeZone'] = 'America/Los_Angeles';
    				$itineraryService = new ItineraryService($this->serviceLocator);
    				$addResult = $itineraryService->addItinerary($postParams);
    				if(intval($addResult)>=0){
    					$response = array('success'=>true);
    				}else{
    					$response = array('success'=>false, 'message'=>'Adding itinerary failed');
    				}
    			}
    		}else{
    			$response = array('success'=>false, 'message'=>'invalid data');
    		}
    		$view->setVariables($response);
    		return $view;
    	}else{
    		$view = new ViewModel();
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
    
    public function editItineraryAction()
    {
    	$view = new JsonModel();
    	$view->setTerminal(true);
    	if($this->getRequest()->isPost()){
    		$postParams = $this->params()->fromPost();
    		if(isset($postParams['Id']) && $postParams['Id']!=null){
    			$itineraryService = new ItineraryService($this->serviceLocator);
    			$data = array('Id'=>$postParams['Id'],
    					'Deleted'=>true,
    					'UpdatedTime'=>gmdate("Y-m-d H:i:s"),
    			);
    			$editResult = $itineraryService->editItinerary($data);    
    			if($editResult){
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