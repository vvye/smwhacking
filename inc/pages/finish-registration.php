<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/misc.php';
	

	do
	{
		if (isLoggedIn() || !isset($_GET['id']) || !isset($_GET['token']))
		{
			renderErrorMessage('Das Abschließen der Registrierung hat nicht geklappt. '
				. 'Hast du diese Seite wirklich aus einer E-Mail heraus aufgerufen?<br />'
				. 'Wenn du Probleme beim Registrieren hast, wende dich an info@smwhacking.de.');
			break;
		}

		$database = getDatabase();
		$numUsersToRegister = $database->count('users', [
			'AND' => [
				'id'               => $_GET['id'],
				'activated'        => 0,
				'activation_token' => $_GET['token']
			]
		]);
		if ($numUsersToRegister !== 1)
		{
			renderErrorMessage('Das Abschließen der Registrierung hat nicht geklappt &mdash; '
				. 'Entweder stimmt der Link nicht, oder der Nutzer ist schon registriert.<br />'
				. 'Wenn du Probleme beim Registrieren hast, wende dich an info@smwhacking.de.');
			break;
		}
		
		$database->update('users', [
			'activated' => 1,
			'id' => $_GET['id']
		]);
		
		renderSuccessMessage('Alles klar, die Registrierung ist abgeschlossen!<br />'
		. 'Du kannst dich jetzt mit deiner E-Mail-Adresse und deinem Passwort <a href="?p=login">einloggen</a>.');


	}
	while (false);