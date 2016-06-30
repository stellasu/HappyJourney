<?php
namespace Application\Service\Factory;

use Zend\ServiceManager\FactoryInterface; 
use Zend\ServiceManager\ServiceLocatorInterface; 
use Doctrine\ORM\Tools\Setup; 
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
require_once "vendor/autoload.php";

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
		$doctrineConfig = Setup::createAnnotationMetadataConfiguration($config['doctrine']['entity_path'], false, null, null, false); 
		$entityManager = DoctrineEntityManager::create($doctrineDbConfig, $doctrineConfig); 

		return $entityManager;  
		
	} 

	
}

