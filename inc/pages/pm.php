<?php
	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/../functions/pm.php';
	require_once __DIR__ . '/../functions/bbcode.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/pagination.php';

	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_PM_NOT_LOGGED_IN);
			break;
		}

		if (isset($_GET['id']) && is_int($_GET['id'] * 1))
		{
			$pmId = $_GET['id'] * 1;

			$pm = getPm($pmId);

			if ($pm === null)
			{
				renderErrorMessage(MSG_PM_NOT_FOUND);
				break;
			}

			if (!canViewPm($pm))
			{
				renderErrorMessage(MSG_PM_NOT_ALLOWED);
				break;
			}

			$isOwnPm = $pm['author_id'] === $_SESSION['userId'] && $pm['recipient_id'] !== $_SESSION['userId'];
			if (!$isOwnPm)
			{
				markPmAsRead($pmId);
			}

			renderTemplate('pm', [
				'ownPm'    => $isOwnPm,
				'id'       => $pmId,
				'sendTime' => date(DEFAULT_DATE_FORMAT, $pm['send_time']),
				'subject'  => $pm['subject'],
				'content'  => parseBBCode($pm['content']),
				'unread'   => $pm['unread'],
				'canReply' => isLoggedIn(),
				'token'    => getCsrfToken(),
				'author'   => [
					'id'               => $pm['author_id'],
					'name'             => $pm['author_name'],
					'powerlevelId'     => (int)$pm['author_powerlevel'],
					'powerlevel'       => POWERLEVEL_DESCRIPTIONS[$pm['author_powerlevel']],
					'banned'           => $pm['author_banned'],
					'title'            => $pm['author_title'],
					'rank'             => getRank($pm['author_id']),
					'hasAvatar'        => hasAvatar($pm['author_id']),
					'favoriteMedals'   => getFavoriteMedals($pm['author_id']),
					'registrationTime' => date(DEFAULT_DATE_FORMAT, $pm['author_registration_time']),
					'numTotalPosts'    => getNumPostsByUser($pm['author_id']),
					'signature'        => parseBBCode($pm['author_signature'])
				]
			]);

			renderTemplate('spoiler_js', []);
		}
		else
		{
			$inbox = !isset($_GET['outbox']);

			renderTemplate('pm_top', [
				'inbox' => $inbox,
				'token' => getCsrfToken()
			]);

			$userId = $_SESSION['userId'];

			if ($inbox)
			{
				$numPms = getNumPmsToUser($userId);
				$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;
				$numPages = (int)ceil($numPms / PMS_PER_PAGE);
				makeBetween($page, 1, $numPages);
				renderPagination('?p=pm', $page, $numPages);

				$pms = getPmsToUser($userId, $page);

				renderTemplate('pm_list_inbox', [
					'numPms' => $numPms,
					'pms'    => $pms
				]);

				renderPagination('?p=pm', $page, $numPages);
			}
			else
			{
				$numPms = getNumPmsFromUser($userId);
				$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;
				$numPages = (int)ceil($numPms / PMS_PER_PAGE);
				makeBetween($page, 1, $numPages);
				renderPagination('?p=pm&outbox', $page, $numPages);

				$pms = getPmsFromUser($userId, $page);

				renderTemplate('pm_list_outbox', [
					'numPms' => $numPms,
					'pms'    => $pms
				]);

				renderPagination('?p=pm', $page, $numPages);
			}
		}

	}
	while (false);