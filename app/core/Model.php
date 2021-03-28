<?php

namespace app\core;
use app\lib\Db;

abstract class Model 

{
	
	public $db;

	public function __construct()
	{	
			$this->db = new Db;
	}

	public function getAll()
	{
			$res = $this->db->query('SELECT * FROM users');
			print_r($res->fetchAll());

	}


}


