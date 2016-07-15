<?php

	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/avatar.php';
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

		$userId = (int)(isset($_GET['user']) && is_int($_GET['user'] * 1) ? $_GET['user'] : $_SESSION['userId']);

		$isOwnProfile = ($userId === (int)$_SESSION['userId']);
		$canEditProfile = $isOwnProfile || isAdmin();
		$canChangeTitle = isAdmin() || (isModerator() && $isOwnProfile);
		$canChangePowerlevel = !$isOwnProfile && isAdmin();

		if (!$canEditProfile)
		{
			renderErrorMessage(MSG_USERCP_NOT_ADMIN);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		$user = getUser($userId);
		$username = htmlspecialchars_decode($user['name']);

		if (!isset($_POST['submit']))
		{
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
		}
		else
		{
			$email = trim(getFieldValue('email'));
			$oldPassword = getFieldValue('old-password');
			$newPassword = getFieldValue('new-password');
			$newPasswordConfirm = getFieldValue('new-password-confirm');
			$title = trim(getFieldValue('title'));
			$location = trim(getFieldValue('location'));
			$website = trim(getFieldValue('website'));
			$bio = trim(getFieldValue('bio'));
			$signature = trim(getFieldValue('signature'));

			$powerlevel = (int)getFieldValue('powerlevel');
			$banned = (bool)getFieldValue('banned');

			$error = false;

			if ($email === '')
			{
				renderErrorMessage(MSG_EMAIL_MISSING);
				$error = true;
			}

			$oldEmail = htmlspecialchars_decode($user['email']);
			$changeEmail = (strtolower($oldEmail) !== strtolower($email));
			if ($changeEmail && emailExists($email))
			{
				renderErrorMessage(MSG_EMAIL_TAKEN);
				$error = true;
			}

			$changePassword = ($newPassword !== '');
			if ($changePassword)
			{
				if (!isPasswordCorrect($userId, $oldPassword))
				{
					renderErrorMessage(MSG_WRONG_PASSWORD);
					$error = true;
				}
				if (strlen($newPassword) < 8)
				{
					renderErrorMessage(MSG_PASSWORD_TOO_SHORT);
					$error = true;
				}
				if (strtolower($newPassword) === 'penis')
				{
					renderErrorMessage(MSG_PASSWORD_PENIS);
					$error = true;
				}
				if ($newPassword !== $newPasswordConfirm)
				{
					renderErrorMessage(MSG_PASSWORDS_DONT_MATCH);
					$error = true;
				}
			}

			$changeAvatar = isset($_POST['change-avatar']);
			if ($changeAvatar)
			{
				$deleteAvatar = isset($_POST['delete-avatar']);
				if ($deleteAvatar)
				{
					deleteAvatar($userId);
				}
				else
				{
					$errorMessages = processUploadedAvatar($userId);
					if (!empty($errorMessages))
					{
						foreach ($errorMessages as $errorMessage)
						{
							renderErrorMessage($errorMessage);
						}
						$error = true;
					}
				}
			}

			if (!$error)
			{
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

				if ($canChangeTitle)
				{
					setUserTitle($userId, $title);
				}

				if ($canChangePowerlevel)
				{
					makeBetween($powerlevel, 0, 2);
					setPowerlevel($userId, $powerlevel);
				}

				renderSuccessMessage(MSG_USERCP_SUCCESS);
			}
		}

		renderTemplate('edit_profile', [
			'action'              => '?p=edit-profile' . ($isOwnProfile ? '' : ('&user=' . $userId)) . '&token=' . getCsrfToken(),
			'isOwnProfile'        => $isOwnProfile,
			'canEditProfile'      => $canEditProfile,
			'canChangeTitle'      => $canChangeTitle,
			'canChangePowerlevel' => $canChangePowerlevel,
			'userId'              => $userId,
			'username'            => $username,
			'powerlevel'          => $powerlevel,
			'banned'              => $banned,
			'email'               => $email,
			'hasAvatar'           => hasAvatar($userId),
			'oldPassword'         => $oldPassword,
			'newPassword'         => $newPassword,
			'newPasswordConfirm'  => $newPasswordConfirm,
			'title'               => $title,
			'location'            => $location,
			'website'             => $website,
			'bio'                 => $bio,
			'signature'           => $signature,
			'token'               => $token
		]);
	}
	while (false);