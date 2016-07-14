<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/pagination.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isset($_GET['id']))
		{
			include __DIR__ . '/404.php';
			break;
		}
		$forumId = $_GET['id'];

		$forum = getForum($forumId);
		if ($forum === null)
		{
			include __DIR__ . '/404.php';
			break;
		}

		if (!canView($forum['min_powerlevel']))
		{
			renderErrorMessage(MSG_VIEW_FORUM_NOT_ALLOWED);
			break;
		}

		if (isset($_GET['mark-read']))
		{
			markForumAsRead($forumId);
		}

		$forumName = $forum['name'];

		renderTemplate('forum_top', [
			'forumName'     => $forumName,
			'forumId'       => $forumId,
			'loggedIn'      => isLoggedIn(),
			'canMakeThread' => !isBanned()
		]);

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;

		$numTotalThreads = $forum['threads'];
		$threads = getThreadsInForum($forumId, $page);
		$numStickies = getNumStickiesInForum($forumId);

		$numPages = (int)ceil($numTotalThreads / THREADS_PER_PAGE);
		makeBetween($page, 1, $numPages);

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);

		$threadsForTemplate = [];
		foreach ($threads as $index => $thread)
		{
			$unread = isLoggedIn() && $thread['last_read_time'] < $thread['last_post_time'];

			$lastPost = getPostById($thread['last_post']);
			$lastPostPage = ($lastPost !== null) ? getPostPageInThread($lastPost['id'], $thread['id']) : '';

			$threadsForTemplate[] = [
				'closed'       => $thread['closed'],
				'sticky'       => $thread['sticky'],
				'lastSticky'   => $thread['sticky'] && $index === $numStickies - 1,
				'unread'       => $unread,
				'id'           => $thread['id'],
				'name'         => $thread['name'],
				'numReplies'   => $thread['posts'] - 1,
				'numViews'     => $thread['views'],
				'lastPost'     => $lastPost,
				'lastPostPage' => $lastPostPage,
				'authorId'     => $thread['author_id'],
				'authorName'   => $thread['author_name'],
				'creationTime' => date(DEFAULT_DATE_FORMAT, $thread['creation_time']),
			];
		}

		renderTemplate('thread_list', [
			'numTotalThreads' => $numTotalThreads,
			'threads'         => $threadsForTemplate
		]);

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);
	}
	while (false);
