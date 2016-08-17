<?php
namespace Administration\Service\Factory;

use Zend\ServiceManager\FactoryInterface; 
use Zend\Db\Adapter\Adapter as DbAdapter; 
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Zend\Db\Adapter;

class Database implements FactoryInterface {
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $serviceLocator->get('config');
		$adapter = new DbAdapter($config['db']);
		return $adapter;
	}
}
