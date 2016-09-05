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
use Zend\View\Model\Zend\View\Model;
use Service\TextService;

class IndexController extends AbstractActionController
{
	
	public function indexAction()
    {
    	$view = new ViewModel();
    	error_reporting(E_ERROR | E_WARNING | E_PARSE);
    	$authService = $this->getServiceLocator()->get('AuthService');
    	$session = new Container('HJ');
    	if ($authService->getIdentity() != null){
    		return $view;
    	}
    	return $this->redirect()->toRoute("login");
    }
    
    public function updateTextAction()
    {
    	$view = new JsonModel();
    	$view->setTerminal(true);
    	if($this->getRequest()->isPost()){
    		$postParams = $this->params()->fromPost();
    		if(isset($postParams['text']) && $postParams['text']!=null
    			&& isset($postParams['type']) && $postParams['type']!=null){
    			$textService = new TextService($this->serviceLocator);
    			try {
    				if($postParams['type'] == 'ct'){
    					$textResults = $textService->getCustomizedTravelMainDescription();
    				}else if($postParams['type'] == 'ss'){
    					$textResults = $textService->getShuttleServiceMainDescription();
    				}    				
    			} catch (\Exception $e) {
    				error_log("error: ".$e->getMessage());
    			}
    			$version = 1;
    			if($textResults != null){
    				$version = $textResults[0]->Version+1;
    			}
    			$textId = null;
    			if($postParams['type'] == 'ct'){
    				$textId = $textService->updateCustomizedTravelMainDescription(array('Text'=>$postParams['text'], 'Version'=>$version));
    			}else if($postParams['type'] == 'ss'){
    				$textId = $textService->updateShuttleServiceMainDescription(array('Text'=>$postParams['text'], 'Version'=>$version));
    			}
    			if($textId != null){
    				$response = array('success'=>true);
    			}else{
    				$response = array('success'=>false, 'message'=>'update failed');
    			}
    		}else{
    			$response = array('success'=>false, 'message'=>'invalid data');
    		}
    	}else{
    		$response = array('success'=>false, 'message'=>'no data');
    	}
    	
    	$view->setVariables($response);    	
    	return $view;
    }
    
}
