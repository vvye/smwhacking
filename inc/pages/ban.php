<?php

	require_once __DIR__ . '/../functions/user.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_BAN_NOT_LOGGED_IN);
			break;
		}
		if (!isModerator())
		{
			renderErrorMessage(MSG_BAN_NOT_MODERATOR);
			break;
		}

		if (!isset($_GET['user']) && !is_int($_GET['user'] * 1))
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}
		$userId = (int)$_GET['user'];

		$user = getUser($userId);
		if ($user === null)
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		};

		if ($userId === $_SESSION['userId'])
		{
			renderErrorMessage(MSG_BAN_CANT_BAN_YOURSELF);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		$unban = isset($_GET['action']) && $_GET['action'] === 'unban';

		if (!$unban && $user['banned'])
		{
			renderErrorMessage(MSG_BAN_USER_ALREADY_BANNED);
			break;
		}
		if ($unban && !$user['banned'])
		{
			renderErrorMessage(MSG_UNBAN_USER_ALREADY_UNBANNED);
			break;
		}

		if (isset($_POST['submit']))
		{
			if (!$unban)
			{
				banUser($userId);
				renderSuccessMessage(MSG_BAN_SUCCESS);
			}
			else
			{
				unbanUser($userId);
				renderSuccessMessage(MSG_UNBAN_SUCCESS);
			}
			renderTemplate('ban_success', [
				'userId'   => $userId,
				'username' => $user['name']
			]);
		}
		else
		{
			$templateName = $unban ? 'unban' : 'ban';
			renderTemplate($templateName, [
				'userId'   => $userId,
				'username' => $user['name'],
				'token'    => $token
			]);
		}
	}
	while (false);

