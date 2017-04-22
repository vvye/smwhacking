<?php

	require_once __DIR__ . '/../functions/secret.php';


	do
	{
		if (!isset($_GET['id']))
		{
			$secrets = getAllSecrets();

			renderTemplate('secret_list', [
				'secrets' => $secrets
			]);
		}
		else
		{
			$secretId = $_GET['id'];
			$secret = getSecret($secretId);

			if ($secret === null)
			{
				include __DIR__ . '/error.php';
				break;
			}

			if ($secret['is_link'])
			{
				header('Location: ' . $secret['content']);
			}
			else
			{
				renderTemplate('secret_page', [
					'secret' => $secret
				]);
			}
		}
	} while (false);
