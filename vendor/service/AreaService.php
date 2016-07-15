<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;

class AreaService {
	
	protected $serviceLocator;
	protected $db;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	/**
	 * get all areas
	 * @return array
	 */
	public function getAreas(){
		try {
			$query = "Select * from Area Where Deleted = 0";
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

	/**
	 * get one area by id
	 * @param array $data{id}
	 */
	public function getArea(Array $data = null){
		if(isset($data['id']) && is_int($data['id'])){
			try {
				$query = "Select * from Area Where Id = ? And Deleted = 0";
				$sqlResult = $this->db->createStatement($query, array(intval($data['id'])))->execute();
				$returnArray = null;
				if ($sqlResult instanceof ResultInterface && $sqlResult->isQueryResult()) {
					$resultSet = new ResultSet;
					$resultSet->initialize($sqlResult);
					foreach($resultSet as $row){
						$returnArray = array('Id'=>$row->Id, 'Name'=>$row->Name, 'Description'=>$row->Description);
					}
				}
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
			}
			return $returnArray;
		}else{
			return null;
		}
	}
}