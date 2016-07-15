<?php

	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/medals.php';

	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_AWARD_MEDAL_NOT_LOGGED_IN);
			break;
		}
		if (isBanned() || !isModerator())
		{
			renderErrorMessage(MSG_AWARD_MEDAL_NOT_ALLOWED);
			break;
		}

		if (!isset($_GET['user']) || !is_int($_GET['user'] * 1))
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}
		$userId = $_GET['user'];
		$user = getUser($userId);

		if ($user === null)
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		$awardableMedals = getAwardableMedalsByUser($userId);
		$awardedMedals = getAwardedMedalsByUser($userId);

		$awardableMedalsByCategory = getMedalsByCategory($awardableMedals);
		$awardedMedalsByCategory = getMedalsByCategory($awardedMedals);

		$success = false;

		if (isset($_POST['submit']))
		{
			$medalIdsToAward = array_keys($_POST['award'] ?? []);
			$medalIdsToRemove = array_keys($_POST['remove'] ?? []);

			$awardedMedalIds = array_map('getMedalId', $awardedMedals);
			$awardableMedalIds = array_map('getMedalId', $awardableMedals);

			// make sure we aren't awarding already awarded medals, or removing medals the user doesn't have
			// probably not strictly necessary, but here we go
			$medalIdsToAward = array_diff($medalIdsToAward, $awardedMedalIds);
			$medalIdsToRemove = array_diff($medalIdsToRemove, $awardableMedalIds);

			awardMedals($userId, $medalIdsToAward);
			removeMedals($userId, $medalIdsToRemove);

			// TODO send notifications

			renderSuccessMessage(MSG_AWARD_MEDAL_SUCCESS);

			// reload medal data
			$awardableMedals = getAwardableMedalsByUser($userId);
			$awardedMedals = getAwardedMedalsByUser($userId);
			$awardableMedalsByCategory = getMedalsByCategory($awardableMedals);
			$awardedMedalsByCategory = getMedalsByCategory($awardedMedals);
		}

		renderTemplate('award_medal', [
			'userId'                    => $userId,
			'username'                  => $user['name'],
			'awardableMedalsByCategory' => $awardableMedalsByCategory,
			'awardedMedalsByCategory'   => $awardedMedalsByCategory,
			'numAwardedMedals'          => count($awardedMedals),
			'token'                     => $token
		]);
	}
	while (false);