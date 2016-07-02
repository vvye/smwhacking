<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/register.php';
	require_once __DIR__ . '/../functions/form.php';

	do
	{
		if (isLoggedIn())
		{
			renderMessage('Du bist schon registriert.');
			break;
		}

		if (isset($_POST['submit']))
		{
			$database = getDatabase();

			$errorMessages = validateRegistrationForm($database);

			if (!empty($errorMessages))
			{
				renderErrorMessage(join('<br />', $errorMessages));
				break;
			}
			$email = htmlspecialchars(trim(getFieldValue('email')));
			$username = getFieldValue('username');
			$passwordHash = password_hash(getFieldValue('password'), PASSWORD_DEFAULT);

			startRegistration($email, $username, $passwordHash);

			echo '<div class="message">Alles klar! Wir haben dir eine Mail geschickt. Klicke auf den Link in der Mail, um die Registrierung abzuschließen.</div>';
		}

		renderTemplate('register_form', [
			'email'           => getFieldValue('email'),
			'emailConfirm'    => getFieldValue('email-confirm'),
			'username'        => getFieldValue('username'),
			'password'        => getFieldValue('password'),
			'passwordConfirm' => getFieldValue('password-confirm'),
			'securityAnswer'  => getFieldValue('security-answer')
		]);

	}
	while (false);