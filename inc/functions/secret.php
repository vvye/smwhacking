<?php
	require_once __DIR__ . '/../config/misc.php';

	function getAllSecrets()
	{
		global $database;

		$secrets = $database->query('
			SELECT secrets.id,
				secrets.name,
				secrets.is_link,
				secrets.content
			FROM secrets
			ORDER BY secrets.id ASC
		');

		return $secrets;
	}


	function getSecret($secretId)
	{
		global $database;				

		$secrets = $database->query('
			SELECT secrets.id,
				secrets.name,
				secrets.is_link,
				secrets.content
			FROM secrets
			WHERE secrets.id = ' . $database->quote($secretId) . ';
		')->fetchAll();

		if (count($secrets) !== 1 || $secrets[0]['id'] == '')
		{
			return null;
		}

		return $secrets[0];
	}