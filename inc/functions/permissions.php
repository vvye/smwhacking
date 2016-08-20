<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/forums.php';
	require_once __DIR__ . '/thread.php';
	require_once __DIR__ . '/post.php';
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
		if (!canView($thread['view_powerlevel']))
		{
			return false;
		}
		if ((int)$thread['post_powerlevel'] > $_SESSION['powerlevel'])
		{
			return false;
		}
		if (isModerator())
		{
			return true;
		}
		if ($thread['closed'])
		{
			return false;
		}

		return true;
	}


	function canMakeThread($forum)
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}
		if (!canView($forum['view_powerlevel']))
		{
			return false;
		}
		if ((int)$forum['thread_powerlevel'] > $_SESSION['powerlevel'])
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