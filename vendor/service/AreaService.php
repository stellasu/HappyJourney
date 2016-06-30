<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;

class AreaService {
	
	protected $serviceLocator;
	protected $db;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	public function getAreas(){
		try {
			$query = "Select * from Area";
			$sqlResult = $this->db->createStatement($query)->execute();
			$returnArray = array();
			// iterate through the rows
			foreach ($sqlResult as $result) {
				$returnArray[] = $result;
			}
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		return $returnArray;
	}
}