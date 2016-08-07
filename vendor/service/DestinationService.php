<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;

class DestinationService {
	
	protected $serviceLocator;
	protected $db;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	/**
	 * get all destinations
	 * @return array
	 */
	public function getDestinations(){
		try {
			$query = "Select * from Destination Where Deleted = 0";
			$sqlResult = $this->db->createStatement($query)->execute();
			$returnArray = array();
			if ($sqlResult instanceof ResultInterface && $sqlResult->isQueryResult()) {
				$resultSet = new ResultSet;
    			$resultSet->initialize($sqlResult);
    			foreach($resultSet as $row){
    				$returnArray[] = array('Id'=>$row->Id, 'Name'=>$row->Name, 'Description'=>$row->Description);
    			}
			}
		} catch (\Exception $e) {
			error_log("error: ".$e->getMessage());
		}
		return $returnArray;
		
	}

}