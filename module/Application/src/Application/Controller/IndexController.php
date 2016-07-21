<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Service\AreaService;
use Service\TextService;

class IndexController extends AbstractActionController
{
	
	public function indexAction()
    {
    	$view = new ViewModel();
    	//$config = $this->getServiceLocator()->get('config');
    	//$view->configs = $config['configs'];
    	return $view;
    }
    
    public function listAreaAction()
    {
    	$view = new JsonModel();
    	$view->setTerminal(true);
    	$areaService = new AreaService($this->serviceLocator);
    	try {
    		$results = $areaService->getAreas();
    	} catch (\Exception $e) {
    		error_log("error: ".$e->getMessage());
    	}
    	if($results != null){
    		$view->setVariables(array('status'=>0, 'results'=>$results));
    	}else{
    		$view->setVariables(array('status'=>1, 'results'=>null));
    	}
    	return $view;
    }
    
    public function customizedTravelAction()
    {
    	$view = new ViewModel();
    	$textService = new TextService($this->serviceLocator);
    	try {
    		$results = $textService->getCustomizedTravelMainDescription();
    	} catch (\Exception $e) {
    		error_log("error: ".$e->getMessage());
    	}
    	error_log("results: ".json_encode($results));
    	if($results != null){
    		$view->setVariables(array('status'=>0, 'results'=>$results));
    	}else{
    		$view->setVariables(array('status'=>1, 'results'=>null));
    	}
    	return $view;
    }
}
