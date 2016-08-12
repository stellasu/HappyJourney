<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\TextService;
use Service\DestinationService;
use Service\ItineraryService;
use Service\Service;

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
    	
    	return $view;
    }
	
}