<?php

	require_once __DIR__ . '/../functions/session.php';

	do
	{
		if (isLoggedIn())
		{
			renderMessage(MSG_ALREADY_LOGGED_IN);
			break;
		}

		if (isset($_GET['error']))
		{
			renderErrorMessage(MSG_LOGIN_FAILURE);
		}

		$_SESSION['referrer'] = $_SERVER['HTTP_REFERER'];

		renderTemplate('login_form', []);

	} while (false);
