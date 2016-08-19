<?php
namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\Adapter\Adapter as dbAdapter;

use Service\AuthStorage;
use Service\Service;

class AuthController extends AbstractActionController
{
	protected $form;
	protected $storage;
	protected $authservice;
	 
	public function getAuthService()
	{
		if (! $this->authservice) {
			//$this->authservice = $this->getServiceLocator()
			//->get('AuthService');
			$dbAdapter           = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'Administrator','Username','Password');
			$authService = new AuthenticationService();
			$authService->setAdapter($dbTableAuthAdapter);
			$authService->setStorage(new AuthStorage("hj"));
			$this->authservice = $authService;
			
		}
		 
		return $this->authservice;
	}
	 
	public function getSessionStorage()
	{
		if (! $this->storage) {
			$this->storage = $this->getServiceLocator()
			->get('Administration\Model\AuthStorage');
		}
		 
		return $this->storage;
	}
	 
	public function loginAction()
	{
		//if already login, redirect to success page
		if ($this->getAuthService()->hasIdentity()){
			return $this->redirect()->toRoute('index');
		}
		 
		return array(
				'authenticated' => false,
		);
	}
	 
	public function authenticateAction()
	{
		$redirect = 'login';
		 
		$request = $this->getRequest();
		if ($request->isPost()){
			error_log("post: ".json_encode($this->params()->fromPost()));
			$this->getAuthService()->getAdapter()
				->setIdentity($request->getPost('username'))
				->setCredential($request->getPost('password'));

				$result = $this->getAuthService()->authenticate();
				 
				if ($result->isValid()) {
					$redirect = 'index';
					//check if it has rememberMe :
					if ($request->getPost('rememberme') == 1 ) {
						$this->getSessionStorage()
						->setRememberMe(1);
						//set storage again
						$this->getAuthService()->setStorage($this->getSessionStorage());
					}
					$this->getAuthService()->getStorage()->write($request->getPost('username'));
				}
		}
		 
		$view = new JsonModel();
		$view->setTerminal(true);
		$view->setVariables(array('success'=>true));
		return $view;
	}
	 
	public function logoutAction()
	{
		$this->getSessionStorage()->forgetMe();
		$this->getAuthService()->clearIdentity();
		 
		$this->flashmessenger()->addMessage("You've been logged out");
		return $this->redirect()->toRoute('login');
	}
}