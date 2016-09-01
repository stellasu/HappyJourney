<?php
namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\Adapter\Adapter as dbAdapter;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Session\Zend\Session;

class AuthController extends AbstractActionController
{
	protected $authservice;
	protected $session;
	
	public function loginAction()
	{
		$view = new ViewModel();
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		//if already login, redirect to success page
		if ($this->getAuthService()->hasIdentity()){
			return $this->redirect()->toRoute("administration");
		}
			
		return $view;
	}
	
	public function logoutAction()
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$this->getAuthService()->clearIdentity();
		$this->session = new Container('HJ');
		$this->session->getManager()->forgetMe();
		if($this->session != null){
			$this->session->getManager()->getStorage()->clear('HJ');
		}
		return $this->redirect()->toUrl('/administration/login');
	}
	
	public function authenticateAction()
	{
		$view = new JsonModel();
		$view->setTerminal(true);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$request = $this->getRequest();
		if ($request->isPost()){
			$this->getAuthService()->getAdapter()
			->setIdentity($request->getPost('username'))
			->setCredential($request->getPost('password'));
	
			$result = $this->getAuthService()->authenticate();
				
			if ($result->isValid()) {
				$this->getAuthService()->getStorage()->write($request->getPost('username'));
				$this->session = new Container('HJ');
				$this->session->authenticated = true;
				$this->session->offsetSet('username', $request->getPost('username'));
				//check if it has rememberMe 
				if ($request->getPost('rememberme')=='rememberme') {
					//$this->getSessionStorage()
					//->setRememberMe(1);
					//set storage again
					//$this->getAuthService()->setStorage($this->getSessionStorage());
					$this->session->getManager()->rememberMe();
				}
				$response = array('success'=>true);
				$view->setVariables($response);
				return $view;
				
			}else{
				$response = array('success'=>false, 'message'=>'authentication failed');
				$view->setVariables($response);
				return $view;
			}
		}else{
			$response = array('success'=>false, 'message'=>'no data');
			$view->setVariables($response);
			return $view;
		}
			
		
	}
	 
	public function getAuthService()
	{
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator()
			->get('AuthService');
		}
		 
		return $this->authservice;
	}
	 
	/* public function getSessionStorage()
	{
		if (!$this->storage) {
			$this->storage = $this->getServiceLocator()
			->get('AuthStorage');
		}		 
		return $this->storage;
	} */
	 	 
	
}