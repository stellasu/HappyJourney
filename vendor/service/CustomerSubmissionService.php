<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;

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
}