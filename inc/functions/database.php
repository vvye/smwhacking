<?php

	require_once __DIR__ . '../config/database.php';
	require_once __DIR__ . '../vendor/medoo.php';

	function getDatabase()
	{
		return new medoo(array(
			'server'        => DB_SERVER,
			'username'      => DB_USERNAME,
			'password'      => DB_PASSWORD,
			'database_name' => DB_DATABASENAME,
			'database_type' => 'mysql',
			'charset'       => DB_CHARSET
		));
	}