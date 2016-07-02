<?php

	require_once __DIR__ . '/../functions/session.php';

	do
	{
		if (isLoggedIn())
		{
			renderMessage('Du bist schon eingeloggt.');
			break;
		}

		if (isset($_POST['submit']))
		{
			$loginSuccess = doLogin();

			if (!$loginSuccess)
			{
				renderErrorMessage('Das Einloggen hat nicht geklappt. Stimmen E-Mail-Adresse und Passwort?<br />'
					. 'Wenn das Problem weiterhin auftritt, wende dich an info@smwhacking.de.');

			}
			else
			{
				header('Location: ?p=home');
			}
		}

		renderTemplate('login_form', []);
		
	}
	while (false);


