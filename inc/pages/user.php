<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/user.php';


	do
	{
		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}
		$userId = (int)$_GET['id'];

		$user = getUser($userId);

		if ($user === null)
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}

		$lastPost = getLastPost($userId);
		if ($lastPost === null)
		{
			$lastPostHtml = '<em>keiner</em>';
		}
		else
		{
			// TODO permission to see last post
			$page = getPostPageInThread($lastPost['id'], $lastPost['thread_id']);
			$lastPostHtml = date(DEFAULT_DATE_FORMAT, $lastPost['post_time']) . ' in <a href="?p=thread&id='
				. $lastPost['thread_id'] . '&page=' . $page . '#post-' . $lastPost['id'] . '">' . $lastPost['thread_name'] . '</a>';
		}

		renderTemplate('user_top', [
			'name' => $user['name'],
			'id'   => $userId
		]);

		renderTemplate('user_info', [
			'avatarHtml'       => getAvatarHtml($userId),
			'powerlevel'       => POWERLEVEL_DESCRIPTIONS[$user['powerlevel']],
			'rankHtml'         => getProfileRankHtml($userId),
			'title'            => $user['title'],
			'registrationTime' => date(DEFAULT_DATE_FORMAT, $user['registration_time']),
			'lastLoginTime'    => date(DEFAULT_DATE_FORMAT, $user['last_login_time']),
			'numPosts'         => getNumPostsByUser($userId),
			'lastPostHtml'     => $lastPostHtml,
			'websiteHtml'      => ($user['website'] !== '') ? '<a href="' . $user['website'] . '">' . $user['website'] . '</a>' : '',
			'emailHtml'        => obfuscateEmail($user['email'])
		]);

		renderTemplate('user_bio', [
			'bio'       => nl2br($user['bio']),
			'signature' => nl2br($user['signature'])
		]);


		$medals = getMedals($userId);
		$numTotalMedals = count($medals);

		$medalsByCategory = [];
		foreach ($medals as $key => $medal)
		{
			$medalsByCategory[$medal['category_name']][$key] = $medal;
			$medalsByCategory[$medal['category_name']] = array_values($medalsByCategory[$medal['category_name']]);
		}

		renderTemplate('user_medals', [
			'numTotalMedals'   => $numTotalMedals,
			'medalsByCategory' => $medalsByCategory,
		]);
	}
	while (false);

