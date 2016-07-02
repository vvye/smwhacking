<?php

	require_once __DIR__ . '/../functions/session.php';

	do
	{
		if (isLoggedIn())
		{
			renderMessage(MSG_ALREADY_LOGGED_IN);
			break;
		}

		if (isset($_POST['submit']))
		{
			$loginSuccess = doLogin();

			if (!$loginSuccess)
			{
				renderErrorMessage(MSG_LOGIN_FAILURE);

			}
			else
			{
				header('Location: ?p=home');
			}
		}

		renderTemplate('login_form', []);
		
	}
	while (false);


