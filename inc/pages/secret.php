<?php
	require_once __DIR__ . '/../config/misc.php';
	
	require_once __DIR__ . '/../functions/secret.php';


	do
	{
		if (!isset($_GET['id']))
		{
			// No ID is passed, display list of secrets
			$secrets = getAllSecrets();
			
			renderTemplate('secret_list', [
				'secrets'  => $secrets
			]);
		}
		else
		{
			// We passed an ID, so display the page of that particular secret
			$secretId = $_GET['id'];
			$secret = getSecret($secretId);				
			
			if ($secret == null)
			{
				// Our secret page doesn't seem to exist
				include __DIR__ . '/error.php';
				break;				
			}
			
			if ($secret['is_link'] == true)
			{
				// If our secret is a link, just forward us
				header('Location: ' . $secret['content']);
			}
			else
			{
				// Otherwise display the contents of our secret page			
				renderTemplate('secret_page', [
					'secret'  => $secret
				]);
			}
		}
	}
	while (false);