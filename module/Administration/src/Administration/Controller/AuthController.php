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
	protected $form;
	protected $authservice;
	protected $session;
	 
	public function getAuthService()
	{
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator()
			->get('AuthService');
		}
		 
		return $this->authservice;
	}
	 
	public function getSessionStorage()
	{
		if (! $this->storage) {
			$this->storage = $this->getServiceLocator()
			->get('AuthStorage');
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
		$request = $this->getRequest();
		if ($request->isPost()){
			$this->getAuthService()->getAdapter()
				->setIdentity($request->getPost('username'))
				->setCredential($request->getPost('password'));

				$result = $this->getAuthService()->authenticate();
				 
				if ($result->isValid()) {
					//check if it has rememberMe :
					if ($request->getPost('rememberme') == 1 ) {
						$this->getSessionStorage()
						->setRememberMe(1);
						//set storage again
						//$this->getAuthService()->setStorage($this->getSessionStorage());
					}
					//$this->getAuthService()->getStorage()->write($request->getPost('username'));
					$this->session = new Container('HJ');
					$this->session->authenticated = true;
					$this->session->offsetSet('username', $request->getPost('username'));
				}
		}
		 
		$view = new JsonModel();
		$view->setTerminal(true);
		$view->setVariables(array('success'=>true));
		return $view;
	}
	 
	public function logoutAction()
	{
		//$this->getSessionStorage()->forgetMe();
		//$this->getAuthService()->clearIdentity();
		 
		$this->session->getManager()->getStorage()->clear('HJ');
		return $this->redirect()->toRoute('administration/login');
	}
}