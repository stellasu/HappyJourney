<?php
namespace Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Zend\Db\Sql;

class AreaService {
	
	protected $serviceLocator;
	protected $db;
	
	public function __construct(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
		$this->db = $this->serviceLocator->get('database');		
	}
	
	public function getAreas(){
		$sql = new Sql($this->db);
		$sql->select()->from("Area");
		$selectString = $sql->buildSqlString($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
		return $results;
	}
}