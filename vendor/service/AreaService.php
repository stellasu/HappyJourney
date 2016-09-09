<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
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
	
	/**
	 * add an area
	 * @param array $data{Name, Description}
	 */
	public function addArea(Array $data = null)
	{
		if(isset($data['Name']) && $data['Name']!=null
				&& isset($data['Description']) && $data['Description']!=null){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('Area');
				$insert->values($data);
				$statement = $sql->prepareStatementForSqlObject($insert);
				$results = $statement->execute();
				$lastInsertId = $this->db->getDriver()->getLastGeneratedValue();
				if($lastInsertId > 0){
					return array('success'=>true, 'result'=>$lastInsertId);
				}else{
					return array('success'=>false, 'message'=>'insertId incorrect');
				}
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
				return array('success'=>false, 'message'=>'insert failed');
			}
		}else{
			return array('success'=>false, 'message'=>'invalid data');
		}
	}
	
	/**
	 * edit an area
	 * @param array $data{Id}
	 */
	public function editArea(Array $data = null)
	{
		if(isset($data['Id']) && $data['Id']!=null){
			try {
				$sql = new Sql($this->db);
				$update = $sql->update("Area");
				$values = array();
				foreach($data as $k=>$v){
					if($k != 'Id'){
						$values[$k] = $v;
					}
				}
				$update->set($values);
				$update->where(array('Id' => $data['Id']));
				$statement = $sql->prepareStatementForSqlObject($update);
				$results = $statement->execute();
				return array('success'=>true);
			} catch (\Exception $e) {
				return array('success'=>false, 'message'=>'update failed');
			}
		}else{
			return array('success'=>false, 'message'=>'invalid data');
		}
	}
}