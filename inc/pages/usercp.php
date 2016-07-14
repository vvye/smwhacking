<?php

	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_USERCP_NOT_LOGGED_IN);
			break;
		}
		if (isBanned())
		{
			renderErrorMessage(MSG_USERCP_BANNED);
			break;
		}

		if (isset($_GET['user']) && is_int($_GET['user'] * 1))
		{
			$userId = $_GET['user'];
			$isOwnProfile = ((int)$userId === (int)$_SESSION['userId']);

			if (!$isOwnProfile && !isAdmin())
			{
				renderErrorMessage(MSG_USERCP_NOT_ADMIN);
				break;
			}
		}
		else
		{
			$userId = $_SESSION['userId'];
			$isOwnProfile = true;
		}

		$user = getUser($userId);

		$username = htmlspecialchars_decode($user['name']);

		$email = htmlspecialchars_decode($user['email']);
		$oldPassword = '';
		$newPassword = $newPasswordConfirm = '';
		$title = htmlspecialchars_decode($user['title']);
		$location = htmlspecialchars_decode($user['location']);
		$website = htmlspecialchars_decode($user['website']);
		$bio = htmlspecialchars_decode($user['bio']);
		$signature = htmlspecialchars_decode($user['signature']);

		$powerlevel = (int)$user['powerlevel'];
		$banned = (bool)$user['banned'];

		$canAdministerUser = isAdmin() && !$isOwnProfile;

		if (isset($_POST['submit']))
		{
			$oldEmail = $email;
			$email = trim(getFieldValue('email'));
			$oldPassword = getFieldValue('old-password');
			$newPassword = getFieldValue('new-password');
			$newPasswordConfirm = getFieldValue('new-password-confirm');
			$location = trim(getFieldValue('location'));
			$website = trim(getFieldValue('website'));
			$bio = trim(getFieldValue('bio'));
			$signature = trim(getFieldValue('signature'));

			$powerlevel = (int)getFieldValue('powerlevel');
			$banned = (bool)getFieldValue('baned');


			if ($email === '')
			{
				renderErrorMessage(MSG_EMAIL_MISSING);
				break;
			}
			if (strtolower($email) !== strtolower($oldEmail) && emailExists($email))
			{
				renderErrorMessage(MSG_EMAIL_TAKEN);
				break;
			}

			if ($newPassword === '')
			{
				$changePassword = false;
			}
			else
			{
				$changePassword = true;

				if (!isPasswordCorrect($userId, $oldPassword))
				{
					renderErrorMessage(MSG_WRONG_PASSWORD);
					break;
				}
				if (strlen($newPassword) < 8)
				{
					renderErrorMessage(MSG_PASSWORD_TOO_SHORT);
					break;
				}
				if (strtolower($newPassword) === 'penis')
				{
					renderErrorMessage(MSG_PASSWORD_PENIS);
					break;
				}
				if ($newPassword !== $newPasswordConfirm)
				{
					renderErrorMessage(MSG_PASSWORDS_DONT_MATCH);
					break;
				}
			}

			setUserData($userId, [
				'email'     => $email,
				'location'  => $location,
				'website'   => $website,
				'bio'       => $bio,
				'signature' => $signature
			]);

			if ($changePassword)
			{
				updatePassword($userId, $newPassword);
			}

			if (isAdmin())
			{
				$title = trim(getFieldValue('title'));
				setUserTitle($userId, $title);
			}

			if ($canAdministerUser)
			{
				if ($banned)
				{
					banUser($userId);
				}
				else
				{
					setPowerlevel($userId, $powerlevel);
				}
			}

			renderSuccessMessage(MSG_USERCP_SUCCESS);
		}

		renderTemplate('user_cp', [
			'action'             => '?p=usercp' . $isOwnProfile ? '' : ('&user=' . $userId),
			'isOwnProfile'       => $isOwnProfile,
			'userId'             => $userId,
			'username'           => $username,
			'isModerator'        => isModerator(),
			'isAdmin'            => isAdmin(),
			'powerlevel'         => $powerlevel,
			'banned'             => $banned,
			'email'              => $email,
			'oldPassword'        => $oldPassword,
			'newPassword'        => $newPassword,
			'newPasswordConfirm' => $newPasswordConfirm,
			'title'              => $title,
			'location'           => $location,
			'website'            => $website,
			'bio'                => $bio,
			'signature'          => $signature
		]);

	}
	while (false);