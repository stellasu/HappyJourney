<?php 
	return array( 
		'db' => array( 
			// The database driver. Mysqli, Sqlsrv, Pdo_Sqlite, Pdo_Mysql, Pdo = OtherPdoDriver
			'driver' => 'Pdo_Mysql',
			'database' => 'StellaDB', // generally required the name of the database (schema) 
			'username' => 'root', // generally required the connection username 
			'password' => 'stella', // generally required the connection password 
			// not generally required the IP address or hostname to connect to 
			'hostname' => '127.0.0.1', // 
			//'port' => 1234, // not generally required the port to connect to (if applicable) 
			//'charset' => 'utf8', // not generally required the character set to use 
			'options' => array( 'buffer_results' => 1),
			'driver_options' => array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
	        ),
		) 		
	);
				
				