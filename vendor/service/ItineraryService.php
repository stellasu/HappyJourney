<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;

class ItineraryService {
	
	protected $serviceLocator;
	protected $db;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	/**
	 * get itineraries that qualify some requirements
	 * @param array $data{DestinationId, Date, Time}
	 * @return array
	 */
	public function getQualifiedItineraries(Array $data = null){
		if(isset($data['DestinationId'])&&$data['DestinationId']!=null
				&& isset($data['Date'])&&$data['Date']!=null
				&& isset($data['Hour'])&&$data['Hour']!=null){
			try {
				$query = "Select * from Itinerary Where DestinationId = ? And Date = ? And Hour = ? And Deleted = 0";
				$values = array(intval($data['DestinationId']), $data['Date'], $data['Hour']);
				$sqlResult = $this->db->createStatement($query, $values)->execute();
				$returnArray = null;
				if ($sqlResult instanceof ResultInterface && $sqlResult->isQueryResult()) {
					$resultSet = new ResultSet;
					$resultSet->initialize($sqlResult);
					foreach($resultSet as $row){
						$returnArray[] = $row;
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
	 * add customer submitted itinerary into CustomerItinerary
	 * @param array $data
	 */
	public function addCustomerItinerary(Array $data = null)
	{
		if(isset($data['ItineraryId']) && $data['ItineraryId']!=null
				&& isset($data['CustomerSubmissionId']) && $data['CustomerSubmissionId']!=nulll){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('CustomerItinerary');
				$insert->values($data);
				$statement = $sql->prepareStatementForSqlObject($insert);
				$results = $statement->execute();
				$lastInsertId = $this->db->getDriver()->getLastGeneratedValue();
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
				return null;
			}
			return $lastInsertId;
		}else{
			return null;
		}
	}
	
	/**
	 * edit CustomerItinerary
	 * @param array $data{Id}
	 */
	public function editCustomerItinerary(Array $data = null)
	{
		if(isset($data['Id']) && $data['Id']!=null){
			try {
				$sql = new Sql($this->db);
				$update = $sql->update("CustomerItinerary");
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
				return true;
			} catch (\Exception $e) {
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * get all itineraries
	 * @param array $data{page}
	 */
	public function getItineraries(Array $data = null)
	{
		if(isset($data['page']) && $data['page']>0){
			try {
				$query = "Select Count(*) as c From Itinerary Where Deleted = 0";
				$sqlResult = $this->db->createStatement($query)->execute();
				$total = 0;
				if($sqlResult != null){
					$result = $sqlResult->current();
					$total = $result['c'];
				}
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
			}
			if($total == 0){
				return null;
			}
		
			$limit = 5;
			$last = ceil($total/$limit);
		
			if($data['page']>$last){
				return null;
			}
		
			$offset = $limit*($data['page']-1);
			if($data['page']<$last){
				$next = $data['page']+1;
			}else{
				$next = null;
			}
			if($data['page']>1){
				$previous = $data['page']-1;
			}else{
				$previous = null;
			}
		
			try {
				$query2 = "Select I.*, D.Name From Itinerary as I Inner Join Destination as D On I.DestinationId = D.Id".
				" Where I.Deleted = 0 Limit ? Offset ?";
				$sqlResult2  = $this->db->createStatement($query2, array($limit, $offset))->execute();
				$returnArray = null;
				if ($sqlResult2 instanceof ResultInterface && $sqlResult2->isQueryResult()) {
					$resultSet = new ResultSet;
					$resultSet->initialize($sqlResult2);
					foreach($resultSet as $row){
						$returnArray[] = $row;
					}
				}
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
			}
			$results = array('current'=>$data['page'], 'previous'=>$previous, 'next'=>$next, 'last'=>$last, 'itineraries'=>$returnArray);
			return $results;
		}else{
			return null;
		}
	}
	
	/**
	 * edit an itinerary
	 * @param array $data{Id}
	 */
	public function editItinerary(Array $data = null)
	{
		if(isset($data['Id']) && $data['Id']!=null){
			try {
				$sql = new Sql($this->db);
				$update = $sql->update("Itinerary");
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
				return true;
			} catch (\Exception $e) {
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * add a new itinerary
	 * @param array $data{DestinationId, Date, Hour, Minute, Vehicle}
	 */
	public function addItinerary(Array $data = null)
	{
		if(isset($data['DestinationId']) && $data['DestinationId']!=null
				&& isset($data['Date']) && $data['Date']!=null
				&& isset($data['Hour']) && $data['Hour']!=null
				&& isset($data['Minute']) && $data['Minute']!=null
				&& isset($data['Vehicle']) && $data['Vehicle']!=null){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('Itinerary');
				$insert->values($data);
				$statement = $sql->prepareStatementForSqlObject($insert);
				$results = $statement->execute();
				$lastInsertId = $this->db->getDriver()->getLastGeneratedValue();
			} catch (\Exception $e) {
				error_log("error: ".$e->getMessage());
				return null;
			}
			return $lastInsertId;
		}else{
			return null;
		}
	}

}