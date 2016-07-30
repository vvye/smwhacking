<?php

	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/notifications.php';

	require_once __DIR__ . '/../config/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_AUTOMATIC_MEDALS_NOT_LOGGED_IN);
			break;
		}

		$lastUpdateFilename = __DIR__ . '/../last_medal_update_time';
		if (file_exists($lastUpdateFilename))
		{
			$lastUpdateTime = file_get_contents($lastUpdateFilename);
			if (is_int($lastUpdateTime * 1) && $lastUpdateTime >= strtotime('-1 day'))
			{
				$nextPossibleUpdateTime = strtotime('+1 day', $lastUpdateTime);
				$message = str_replace('{{time}}', date(DEFAULT_DATE_FORMAT, $nextPossibleUpdateTime), MSG_AUTOMATIC_MEDALS_TOO_SOON);
				renderErrorMessage($message);
				break;
			}
		}
		file_put_contents($lastUpdateFilename, time());

		$numAwardedMedals = 0;
		$usersAwarded = [];

		$automaticMedals = getAutomaticMedals();

		foreach ($automaticMedals as $medal)
		{
			$message = str_replace('{{name}}', $medal['name'], MSG_AUTOMATIC_MEDALS_CHECKING);
			echo $message;

			$userIds = getUserIdsEligibleForAutomaticMedal($medal);

			awardMedalToMultipleUsers($userIds, $medal['id']);

			$notificationMessage = str_replace('{{medalName}}', $medal['name'], NOTIFICATION_AUTOMATIC_MEDAL_AWARD_BODY);
			sendNotification($userIds, NOTIFICATION_MEDAL_AWARD_SUBJECT, $notificationMessage);

			$numAwardedMedals += count($userIds);
			$usersAwarded = array_merge($usersAwarded, $userIds);
		}

		if ($numAwardedMedals === 0)
		{
			$message = MSG_AUTOMATIC_MEDALS_NONE_AWARDED;
		}
		else
		{
			$usersAwarded = array_unique($usersAwarded);
			$numUsersAwarded = count($usersAwarded);

			if (in_array($_SESSION['userId'], $usersAwarded))
			{
				$message = str_replace('{{num}}', $numUsersAwarded, MSG_AUTOMATIC_MEDALS_AWARDED_INCLUDING_YOU);
			}
			else
			{
				$message = str_replace('{{num}}', $numUsersAwarded, MSG_AUTOMATIC_MEDALS_AWARDED);
			}
		}

		renderSuccessMessage($message);
	}
	while (false);