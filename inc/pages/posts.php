<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/pagination.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/misc.php';


	$database = getDatabase();

	do
	{
		if (!isset($_GET['user']) || !is_int($_GET['user'] * 1))
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}
		$userId = (int)$_GET['user'];

		$user = getUser($userId, $database);

		if ($user === null)
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}

		echo '<h2>Beiträge von ' . $user['name'] . '</h2>';

		$numPosts = getNumPostsByUser($userId, $database);

		if ($numPosts === 0)
		{
			echo '<p><em>Dieser Nutzer hat keine Beiträge geschrieben.</em></p>';
		}

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;
		$numPages = (int)ceil($numPosts / POSTS_PER_PAGE);
		makeBetween($page, 1, $numPages);
		renderPagination('?p=posts&user=' . $userId, $page, $numPages);

		$posts = getPostsByUser($userId, $page, $database);

		foreach ($posts as $post)
		{
			$id = $post['id'];
			$threadId = $post['thread_id'];
			$threadName = $post['thread_name'];
			$authorId = $userId;
			$authorName = $user['name'];
			$authorPowerlevel = ((int)$user['powerlevel'] !== 0)
				? '<p class="powerlevel">' . POWERLEVEL_DESCRIPTIONS[$user['powerlevel']] . '</p>'
				: '';
			$authorTitle = $user['title'];
			$authorRankHtml = getRankHtml($authorId);
			$authorAvatarHtml = getAvatarHtml($authorId);
			$authorRegistrationTime = date(DEFAULT_DATE_FORMAT, $user['registration_time']);
			$authorCurrentPostNumber = getCurrentPostNumber($authorId, $id, $database);
			$authorNumTotalPosts = getNumPostsByUser($authorId, $database);

			$postTime = date(DEFAULT_DATE_FORMAT, $post['post_time']);
			$content = nl2br($post['content']);
			$authorSignature = (trim($user['signature']) !== '')
				? '<div class="signature">' . nl2br($user['signature']) . '</div>'
				: '';
			$pageInThread = getPostPageInThread($id, $threadId, $database);

			?>
			<div class="post" id="post-<?php echo $id; ?>">
				<div class="sidebar">
					<h3><a href="?p=user&id=<?php echo $authorId ?>"><?php echo $authorName; ?></a></h3>
					<?php echo $authorPowerlevel; ?>
					<?php echo $authorRankHtml; ?>
					<p class="title"><?php echo $authorTitle; ?></p>
					<?php echo $authorAvatarHtml; ?>
					<p>Beiträge: <?php echo $authorCurrentPostNumber; ?> / <?php echo $authorNumTotalPosts; ?></p>
					<p>Registriert seit: <?php echo $authorRegistrationTime; ?></p>
				</div>
				<div class="content">
					<div class="topbar grid">
						<div class="column">geschrieben am <?php echo $postTime; ?>
						in <a href="?p=thread&id=<?php echo $threadId; ?>"><?php echo $threadName; ?></a></div>
						<div class="column">
							(<a href="?p=thread&id=<?php echo $threadId; ?>&page=<?php echo $pageInThread; ?>#post-<?php echo $id; ?>">Link</a>)
						</div>
					</div>
					<?php echo $content; ?>
					<?php echo $authorSignature; ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php
		}

		renderPagination('?p=posts&user=' . $userId, $page, $numPages);

	} while (false);

?>