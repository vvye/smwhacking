<?php

	require_once __DIR__ . '/../../functions/medals.php';

	do
	{
		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}

		$success = false;

		if (isset($_POST['submit']))
		{
			$name = trim($_POST['name']);

			if ($name === '')
			{
				renderErrorMessage(MSG_NAME_EMPTY);
			}
			else
			{
				addCategory($name);
				renderSuccessMessage(MSG_CATEGORY_ADDED);
				$success = true;
			}
		}

		if (!$success)
		{
			renderTemplate('new_medal_category', [
				'token' => getCsrfToken()
			]);
		}

	} while (false);