<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Session\Zend\Session;

class IndexController extends AbstractActionController
{
	
	public function indexAction()
    {
    	$view = new ViewModel();
    	$authService = $this->getServiceLocator()->get('AuthService');
    	$session = new Container('HJ');
    	//if ($authService->getIdentity() != null){
    	if($session->authenticated){
    		return $view;
    	}
    	return $this->redirect()->toRoute('/administration/login');    	
    }
    
}
