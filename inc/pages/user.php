<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/user.php';


	do
	{
		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}
		$userId = (int)$_GET['id'];

		$user = getUser($userId);

		if ($user === null)
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}

		// TODO permission to see last post
		$lastPost = getLastPost($userId);
		$lastPostPage = ($lastPost !== null) ? getPostPageInThread($lastPost['id'], $lastPost['thread_id']) : '';

		renderTemplate('user_top', [
			'name' => $user['name'],
			'id'   => $userId
		]);

		renderTemplate('user_info', [
			'id'               => $userId,
			'powerlevel'       => POWERLEVEL_DESCRIPTIONS[$user['powerlevel']],
			'rank'             => getRank($userId),
			'title'            => $user['title'],
			'registrationTime' => date(DEFAULT_DATE_FORMAT, $user['registration_time']),
			'lastLoginTime'    => date(DEFAULT_DATE_FORMAT, $user['last_login_time']),
			'numPosts'         => getNumPostsByUser($userId),
			'lastPost'         => $lastPost,
			'lastPostPage'     => $lastPostPage,
			'website'          => $user['website'],
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

