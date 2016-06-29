<?php
namespace Application\Service\Factory;

use Zend\ServiceManager\FactoryInterface; 
use Zend\ServiceManager\ServiceLocatorInterface; 
use Doctrine\ORM\Tools\Setup; 
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class EntityManager implements FactoryInterface {
	
	public function createService(ServiceLocatorInterface $serviceLocator){
		$config =$serviceLocator->get('config'); 
		// The parameters in Doctrine 2 and ZF2 are slightly different. 
		// Below is an example how we can reuse the db settings 
		$doctrineDbConfig = (array)$config['db'];
		$doctrineDbConfig['driver'] = strtolower($doctrineDbConfig['driver']);
		if(!isset($doctrineDbConfig['dbname'])){ 
			$doctrineDbConfig['dbname'] = $doctrineDbConfig['database']; 
		} 
		if(!isset($doctrineDbConfig['host'])){
			$doctrineDbConfig['host'] = $doctrineDbConfig['hostname']; 
		} 
		if(!isset($doctrineDbConfig['user'])){ 
			$doctrineDbConfig['user'] = $doctrineDbConfig['username'];
		} 
		//$doctrineConfig = Setup::createAnnotationMetadataConfiguration($config['doctrine']['entity_path'], true, null, null, false); 
		//$entityManager = DoctrineEntityManager::create($doctrineDbConfig, $doctrineConfig); 

		
		$isDevMode = false;
		$doctrineConfig = Setup::createConfiguration($isDevMode);
		$driver = new AnnotationDriver(new AnnotationReader(), $config['doctrine']['entity_path']);
		
		// registering noop annotation autoloader - allow all annotations by default
		AnnotationRegistry::registerLoader('class_exists');
		$doctrineConfig->setMetadataDriverImpl($driver);
		
		$entityManager = DoctrineEntityManager::create($doctrineDbConfig, $doctrineConfig);
		return $entityManager; 
	} 

	
}

