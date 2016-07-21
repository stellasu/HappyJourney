<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Driver\ResultInterface;

class TextService {
	
	protected $serviceLocator;
	protected $db;
	
	/*
	 * define text types
	 */
	const CT_MAIN_DESCRIPTION = 1; //Description on main page of customized travel
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');	
	}
	
	public function getCustomizedTravelMainDescription()
	{
		return $this->getMostRecentText(self::CT_MAIN_DESCRIPTION);
	}
	
	/**
	 * get most updated version of text by type
	 * @param int $type
	 * @return array
	 */
	public function getMostRecentText($type)
	{
		error_log("type: ".$type);
		if(is_int($type) && $type>0){
			try {
				$query = "Select Text.* From Text Inner Join (Select Id, MAX(Version) Version From Text Where Type = ? Group By Id) S On Text.Version = S.Version Where Text.Type = ? And Deleted = 0";
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
}