<?php

abstract class AbstractPdoManager {
	
	const DRIVER = 'mysql';
	const HOST = 'localhost';
	const PORT = '3306';
	const DATABASE_NAME = 'leagueoflegends';
	const USER = 'root';
	const PASSWORD = '';
	
	protected $pdo;
	
	
	public function __construct() {
		$dsn = self::DRIVER.':host='.self::HOST.';port='.self::PORT.';dbname='.self::DATABASE_NAME;
		$this->pdo = new PDO($dsn, self::USER, self::PASSWORD);
	}

}

?>