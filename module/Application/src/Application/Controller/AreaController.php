<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\AreaService;

class AreaController extends AbstractActionController {
	
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
	
	
}