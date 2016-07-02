<?php
	
	require_once __DIR__ . '/../config/database.php';


	function validateRegistrationForm()
	{
		$errorMessages = [];

		if (trim(getFieldValue('email')) === '')
		{
			$errorMessages[] = MSG_EMAIL_MISSING;
		}
		if (getFieldValue('email') !== getFieldValue('email-confirm'))
		{
			$errorMessages[] = MSG_EMAILS_DONT_MATCH;
		}
		if (!preg_match('/^[a-zA-Z0-9 _-]{3,30}$/', getFieldValue('username')))
		{
			$errorMessages[] = MSG_INVALID_USERNAME;
		}
		if (strlen(getFieldValue('password')) < 8)
		{
			$errorMessages[] = MSG_PASSWORD_TOO_SHORT;
		}
		if (strtolower(getFieldValue('password')) === 'penis')
		{
			$errorMessages[] = MSG_PASSWORD_PENIS;
		}
		if (getFieldValue('password') !== getFieldValue('password-confirm'))
		{
			$errorMessages[] = MSG_PASSWORDS_DONT_MATCH;
		}
		if (str_ireplace(' ', '', strtolower(getFieldValue('security-answer'))) !== 'supermarioworld')
		{
			$errorMessages[] = MSG_WRONG_SECURITY_ANSWER;
		}
		if (emailExists(getFieldValue('email')))
		{
			$errorMessages[] = MSG_EMAIL_TAKEN;
		}
		if (usernameExists(getFieldValue('username')))
		{
			$errorMessages[] = MSG_USERNAME_TAKEN;
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
	
	