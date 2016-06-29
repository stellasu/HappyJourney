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
use Zend\View\Model\ViewModel;
use Service\AreaService;

class IndexController extends AbstractActionController
{
	
	/**
	 * @var DoctrineORMEntityManager
	 */
	protected $em;
	
    public function indexAction()
    {
    	$view = new ViewModel();   	
    	return $view;
    }
    
    public function listAreaAction()
    {
    	error_log("listarea");
    	$areaService = new AreaService($this->serviceLocator);
    	error_log("0");
    	try {
    		$results = $areaService->getAreas();
    	} catch (\Exception $e) {
    		error_log("error: ".$e->getMessage());
    	}
    	
    	error_log("results: ".json_encode($results));
    	if($results != null){
    		$view = new JsonModel(array('status'=>0, 'results'=>$results));
    	}else{
    		$view = new JsonModel(array('status'=>0, 'results'=>null));
    	}
    	return $view;
    }
}
