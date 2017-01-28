<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/bbcode.php';
	require_once __DIR__ . '/../functions/smileys.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_NEW_PM_NOT_LOGGED_IN);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		if (!isset($_POST['submit']))
		{
			if (isset($_GET['reply']) && is_int($_GET['reply'] * 1))
			{
				$pmId = $_GET['reply'] * 1;

				$pm = getPm($pmId);

				if ($pm === null || !canViewPm($pm))
				{
					$recipientName = $subject = $pmText = '';
				}
				else
				{
					$recipientName = $pm['author_name'];
					$subject = startsWith($pm['subject'], 'Re:') ? $pm['subject'] : 'Re: ' . $pm['subject'];
					$pmText = '[quote="' . $pm['author_name'] . '"]' . $pm['content'] . '[/quote]';
				}
			}
			else if (isset($_GET['user']) && is_int($_GET['user'] * 1))
			{
				$userId = $_GET['user'] * 1;
				$user = getUser($userId);
				$recipientName = $user === null ? '' : $user['name'];

				$subject = $pmText = '';
			}
			else
			{
				$recipientName = $subject = $pmText = '';
			}

			if (isset($_POST['preview']))
			{
				$subject = trim(getFieldValue('subject'));
				$recipientName = trim(getFieldValue('recipient'));
				$pmText = trim(getFieldValue('pm-text'));

				renderTemplate('post_preview', [
					'postTime' => date(DEFAULT_DATE_FORMAT, time()),
					'content'  => parseBBCode(delimitSmileys(htmlspecialchars($pmText))),
					'author'   => [
						'id'           => $_SESSION['userId'],
						'name'         => $_SESSION['username'],
						'powerlevelId' => (int)$_SESSION['powerlevel'],
						'powerlevel'   => POWERLEVEL_DESCRIPTIONS[$_SESSION['powerlevel']],
						'banned'       => $_SESSION['banned'],
						'title'        => $_SESSION['title'],
						'rank'         => getRank($_SESSION['userId']),
						'hasAvatar'    => hasAvatar($_SESSION['userId']),
						'signature'    => parseBBCode(delimitSmileys($_SESSION['signature']))
					]
				]);
			}

			renderTemplate('new_pm', [
				'recipientName' => htmlspecialchars($recipientName),
				'subject'       => htmlspecialchars($subject),
				'pmText'        => htmlspecialchars($pmText),
				'token'         => $token
			]);

			renderTemplate('spoiler_js', []);
		}
		else
		{
			$error = false;

			$subject = trim(getFieldValue('subject'));
			$recipientId = getUserIdByName(trim(getFieldValue('recipient')));
			$recipientName = trim(getFieldValue('recipient'));
			$pmText = trim(getFieldValue('pm-text'));

			if ($recipientId === null)
			{
				renderErrorMessage(MSG_PM_UNKNOWN_RECIPIENT);
				$error = true;
			}

			if ($subject === '')
			{
				renderErrorMessage(MSG_PM_SUBJECT_EMPTY);
				$error = true;
			}

			if ($pmText === '')
			{
				renderErrorMessage(MSG_PM_TEXT_EMPTY);
				$error = true;
			}

			if ($error)
			{
				renderTemplate('new_pm', [
					'recipientName' => htmlspecialchars($recipientName),
					'subject'       => htmlspecialchars($subject),
					'pmText'        => htmlspecialchars($pmText)
				]);
			}
			else
			{
				$newPmId = createPm($recipientId, $subject, $pmText);
				if ($newPmId === null)
				{
					renderErrorMessage(MSG_GENERAL_ERROR);
					break;
				}

				renderSuccessMessage(MSG_NEW_PM_SUCCESS);
				renderTemplate('new_pm_success', [
					'pmId' => $newPmId
				]);

				ob_start();
				renderTemplate('pm_notification_body', [
					'pmId'       => $newPmId,
					'authorName' => $_SESSION['username'],
					'subject'    => htmlspecialchars($subject)
				]);
				$notificationBody = ob_get_clean();

				sendNotification([$recipientId], NOTIFICATION_NEW_PM, $notificationBody);
			}
		}
	}
	while (false);