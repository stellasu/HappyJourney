<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;
use Service\AreaService;

class CustomerSubmissionService {
	
	protected $serviceLocator;
	protected $db;
	
	/*
	 * define submission type
	 */
	const CT_MAIN_CUSTOMER_SUBMISSION = 1;
	const SS_CUSTOMER_SUBMISSION = 2;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	/**
	 * add ct_main_page user submission data into CustomerSubmission
	 * @param array $data
	 */
	public function addCustomizedTravelSubmission(Array $data = null)
	{
		if(isset($data['FirstName']) && $data['FirstName']!=null
				&& isset($data['LastName']) && $data['LastName']!=null
				&& isset($data['Message']) && $data['Message']!=null){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('CustomerSubmission');
				$data['Type'] = self::CT_MAIN_CUSTOMER_SUBMISSION;
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
	 * add ss_main_page user submission data into CustomerSubmission
	 * @param array $data
	 */
	public function addShuttleServiceSubmission(Array $data = null)
	{
		if(isset($data['FirstName']) && $data['FirstName']!=null
				&& isset($data['LastName']) && $data['LastName']!=null
				&& isset($data['Message']) && $data['Message']!=null){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('CustomerSubmission');
				$data['Type'] = self::SS_CUSTOMER_SUBMISSION;
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
	 * get ct user messages
	 * @param array $data{page}
	 */
	public function getCustomizedTravelMesssage(Array $data = null)
	{
		if(isset($data['page']) && $data['page']>0){
			try {
				$query = "Select Count(*) as c From CustomerSubmission Where Deleted = 0 And Type = ".self::CT_MAIN_CUSTOMER_SUBMISSION;
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
				$query2 = "Select * From CustomerSubmission Where Deleted = 0 And Type = ".self::CT_MAIN_CUSTOMER_SUBMISSION." Limit ? Offset ? ";
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
			
			$results = array('current'=>$data['page'], 'previous'=>$previous, 'next'=>$next, 'last'=>$last, 'messages'=>$returnArray);
			return $results;
		}else{
			return null;
		}
	}
	
	/**
	 * edit a message
	 * @param array $data{Id}
	 */
	public function editMessage(Array $data = null)
	{
		if(isset($data['Id']) && $data['Id']!=null){			
			try {
				$sql = new Sql($this->db);
				$update = $sql->update("CustomerSubmission");
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
				error_log("error: ".$e->getMessage());
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * get customer itineraries
	 * @param array $data{page}
	 */
	public function getCustomerItineraries(Array $data = null)
	{
		if(isset($data['page']) && $data['page']>0){
			try {
				$query = "Select Count(*) as c From CustomerSubmission Where Deleted = 0 And Type = ".self::SS_CUSTOMER_SUBMISSION;
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
				$query2 = "Select cs.*, d.Name as Destination, ci.Id as CustomerItineraryId, i.Id as ItineraryId, i.Date, i.Hour, i.Minute, i.Vehicle".
					" From CustomerSubmission as cs Left Join CustomerItinerary as ci On ci.CustomerSubmissionId = cs.Id Left Join Itinerary as I On ci.ItineraryId = I.Id".
					" Left Join Destination as d On i.DestinationId = d.Id Where cs.Type = ".self::SS_CUSTOMER_SUBMISSION.
					" Order By cs.Deleted Limit ? Offset ?";
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
}