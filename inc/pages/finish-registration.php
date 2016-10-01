<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (isLoggedIn() || !isset($_GET['id']) || !isset($_GET['token']))
		{
			renderErrorMessage(MSG_FINISH_REGISTRATION_GENERAL_FAILURE);
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
			renderErrorMessage(MSG_FINISH_REGISTRATION_NO_USER);
			break;
		}

		$database->update('users', [
			'activated' => 1
		], [
			'id' => $_GET['id']
		]);

		renderSuccessMessage(MSG_FINISH_REGISTRATION_SUCCESS);

	}
	while (false);