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
}