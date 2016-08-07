<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\TextService;
use Service\DestinationService;

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
	
}