<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->initSession(array(
        		'remember_me_seconds' => 604800, //one week
        		'use_cookies' => true,
        		'cookie_httponly' => true,
        ));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        	'Zend\Loader\ClassMapAutoloader' => array(
        			__DIR__ . '/../../autoload_classmap.php',
        	),
        );
    }
    
    public function getServiceConfig()
    {
    	return array(
    		'factories'=>array(
    			 'AuthStorage' => function($sm){
    				return new SessionStorage();
    			}, 
    					 
    			'AuthService' => function($sm) {
	    			$dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
	    			$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'Administrator','Username','Password');
					$authService = new AuthenticationService();
	    			$authService->setAdapter($dbTableAuthAdapter);
	    			$authService->setStorage($sm->get('AuthStorage'));
	    			
	    			return $authService;
    			},
    		),
    	);
    }
    
    public function initSession($config)
    {
    	$sessionConfig = new SessionConfig();
    	$sessionConfig->setOptions($config);
    	$sessionManager = new SessionManager($sessionConfig);
    	$sessionManager->start();
    	Container::setDefaultManager($sessionManager);
    }
    
    public function init(ModuleManager $mm)
    {
    	$mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__,
    		'dispatch', function($e) {
    			$e->getTarget()->layout('admin/layout');
    		});
    }
}
