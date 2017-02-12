<?php

	require_once __DIR__ . '/../config/database.php';
	require_once __DIR__ . '/../vendor/medoo.php';


	function getDatabase()
	{
		try
		{
			return new medoo([
				'server'        => DB_SERVER,
				'username'      => DB_USERNAME,
				'password'      => DB_PASSWORD,
				'database_name' => DB_DATABASENAME,
				'database_type' => 'mysql',
				'charset'       => DB_CHARSET
			]);
		}
		catch (Exception $e)
		{
			die(MSG_DATABASE_ERROR);
		}
	}