<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;

class AreaService {
	
	protected $serviceLocator;
	protected $em;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->em = $this->serviceLocator->get('entity-manager');	
	}
	
	public function getAreas(){
		error_log("1");
		try {
			$results = $this->em->getRepository('Application\Model\Entity\Area')->findAll();
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		error_log("2");
		return $results;
	}
}