<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\TextService;

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
	
}