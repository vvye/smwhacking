<?php

	require_once __DIR__ . '/../config/database.php';


	function validateRegistrationForm($database = null)
	{
		global $database;
		$errorMessages = [];

		if (trim(getFieldValue('email')) === '')
		{
			$errorMessages[] = 'Gib eine E-Mail-Adresse ein.';
		}
		if (getFieldValue('email') !== getFieldValue('email-confirm'))
		{
			$errorMessages[] = 'Die E-Mail-Adressen stimmen nicht überein.';
		}
		if (!preg_match('/^[a-zA-Z0-9 _-]{3,30}$/', getFieldValue('username')))
		{
			$errorMessages[] = 'Der Nutzername ist nicht erlaubt.';
		}
		if (strlen(getFieldValue('password')) < 8)
		{
			$errorMessages[] = 'Das Passwort ist zu kurz.';
		}
		if (strtolower(getFieldValue('password')) === 'penis')
		{
			$errorMessages[] = 'Komm erst mal in die Pubertät.';
		}
		if (getFieldValue('password') !== getFieldValue('password-confirm'))
		{
			$errorMessages[] = 'Die beiden Passwörter stimmen nicht überein.';
		}
		if (str_ireplace(' ', '', strtolower(getFieldValue('security-answer'))) !== 'supermarioworld')
		{
			$errorMessages[] = 'Die Antwort auf die Sicherheitsfrage stimmt nicht.';
		}
		if (emailExists(getFieldValue('email'), $database))
		{
			$errorMessages[] = 'Diese E-Mail-Adresse ist schon registriert.';
		}
		if (usernameExists(getFieldValue('username'), $database))
		{
			$errorMessages[] = 'Dieser Nutzername ist schon registriert.';
		}

		return $errorMessages;
	}


	function emailExists($email)
	{
		global $database;

		$numEmails = $database->count('users', [
			'email' => $email
		]);

		return (is_int($numEmails) && $numEmails > 0);
	}


	function usernameExists($username)
	{
		global $database;

		$numUsers = $database->count('users', [
			'name' => $username
		]);

		return (is_int($numUsers) && $numUsers > 0);
	}


	function startRegistration($email, $username, $passwordHash)
	{
		global $database;

		$activationToken = bin2hex(random_bytes(16));

		$userId = $database->insert('users', [
			'id'               => null,
			'email'            => strtolower(htmlspecialchars($email)),
			'name'             => htmlspecialchars($username),
			'password'         => $passwordHash,
			'legacy_login'     => 0,
			'activated'        => 0,
			'activation_token' => $activationToken
		]);

		$messageBody = 'Hallo! Du bekommst diese Mail, weil du dich bei smwhacking.de registrieren willst.
			Öffne folgende Seite, um fortzufahren:
			http://www.smwhacking.de/?p=finish-registration&id=' . $userId . '&token=' . $activationToken . '
			 Wenn du dich nicht registrieren wolltest, dann ignoriere diese Mail einfach.';

		// TODO remove this
		echo $messageBody . '<br />';

		mail($email, 'smwhacking.de - Registrierung', $messageBody);
	}
	
	