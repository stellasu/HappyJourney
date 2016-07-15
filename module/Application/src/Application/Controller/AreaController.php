<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class DestinationController extends AbstractActionController {
	
	public function detailAction()
	{
		$view = new ViewModel();
		return $view;
	}
	
	
}