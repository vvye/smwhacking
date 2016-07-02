<?php

	require_once __DIR__ . '/../functions/forums.php';
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

		$forums = getForum($forumId);

		if (count($forums) !== 1)
		{
			include __DIR__ . '/404.php';
			break;
		}
		$forum = $forums[0];

		if (isset($_GET['mark-read']))
		{
			markForumAsRead($forumId);
		}

		$forumName = $forum['name'];

		renderTemplate('forum_top', [
			'forumName' => $forumName,
			'forumId'   => $forumId
		]);

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;

		$threads = getThreadsInForum($forumId, $page);
		$numTotalThreads = getNumThreadsInForum($forumId);
		$numStickies = getNumStickiesInForum($forumId);

		$numPages = (int)ceil($numTotalThreads / THREADS_PER_PAGE);
		makeBetween($page, 1, $numPages);

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);

		$threadsForTemplate = [];
		foreach ($threads as $index => $thread)
		{
			$threadsForTemplate[] = [
				'sticky'              => $thread['sticky'],
				'lastSticky'          => $thread['sticky'] && $index === $numStickies,
				'new'                 => isLoggedIn() && $thread['last_read_time'] < $thread['last_post_time'] ? 'NEU' : '',
				'id'                  => $thread['id'],
				'name'                => $thread['name'],
				'numReplies'          => getNumPostsInThread($thread['id']) - 1,
				'numViews'            => $thread['views'],
				'lastPostCellContent' => getLastPostCellContent(getLastPostInThread($thread['id'])),
				'authorId'            => $thread['author_id'],
				'authorName'          => $thread['author_name'],
				'creationTime'        => date(DEFAULT_DATE_FORMAT, $thread['creation_time']),
			];
		}
		
		renderTemplate('thread_list', [
			'numTotalThreads' => $numTotalThreads,
			'threads'         => $threadsForTemplate
		]);

		?>

		<?php

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);
	}
	while (false);
