<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;

class TextService {
	
	protected $serviceLocator;
	protected $db;
	
	/*
	 * define text types
	 */
	const CT_MAIN_DESCRIPTION = 1; //Description on main page of customized travel
	const SS_MAIN_DESCRIPTION = 2; //Description on main page of shuttle service
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	public function getCustomizedTravelMainDescription()
	{
		return $this->getMostRecentText(self::CT_MAIN_DESCRIPTION);
	}
	
	public function getShuttleServiceMainDescription()
	{
		return $this->getMostRecentText(self::SS_MAIN_DESCRIPTION);
	}
	
	/**
	 * get most updated version of text by type
	 * @param int $type
	 * @return array
	 */
	public function getMostRecentText($type)
	{
		if(is_int($type) && $type>0){
			try {
				$query = "Select Text.* from Text Inner Join (Select MAX(Version) Version, Type From Text Where Type = ?) S On Text.Version = S.Version where Text.Type = ? And Deleted = 0";
				$sqlResult = $this->db->createStatement($query, array(intval($type), intval($type)))->execute();
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
	
	public function updateCustomizedTravelMainDescription(Array $data = null){
		$data['Type'] = self::CT_MAIN_DESCRIPTION;
		$data['Url'] = '/customizedtravel';
		return $this->updateText($data);
	}
	
	public function updateShuttleServiceMainDescription(Array $data = null){
		$data['Type'] = self::SS_MAIN_DESCRIPTION;
		$data['Url'] = '/shuttleservice';
		return $this->updateText($data);
	}
	
	/**
	 * update text
	 * @param array $data{Text, Version, Type, Url}
	 */
	public function updateText(Array $data = null)
	{
		if(isset($data['Text'])&&$data['Text']!=null 
				&& isset($data['Version'])&&$data['Version']!=null 
				&& isset($data['Type'])&&$data['Type']!=null){
			try {
				$sql = new Sql($this->db);
				$insert = $sql->insert('Text');
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