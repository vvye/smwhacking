<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/session.php';


	function canView($minPowerlevel)
	{
		if (!isLoggedIn() || isBanned())
		{
			return (int)$minPowerlevel === 0;
		}

		return (int)$minPowerlevel <= $_SESSION['powerlevel'];
	}


	function canPostInThread($thread)
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}
		if (isModerator())
		{
			return true;
		}
		if (!canView($thread['min_powerlevel']) || $thread['closed'])
		{
			return false;
		}

		return true;
	}


	function canModifyPost($post)
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}
		if (isModerator())
		{
			return true;
		}

		return $post['author_id'] === $_SESSION['userId'];
	}