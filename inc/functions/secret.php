<?php

	require_once __DIR__ . '/../config/misc.php';


	function getAllSecrets()
	{
		global $database;

		$secrets = $database->select('secrets', '*', [
			'ORDER' => 'id ASC'
		]);

		return $secrets;
	}


	function getSecret($secretId)
	{
		global $database;

		$secrets = $database->select('secrets', '*', [
			'id' => $secretId
		]);

		if (count($secrets) !== 1 || $secrets[0]['id'] == '')
		{
			return null;
		}

		return $secrets[0];
	}
