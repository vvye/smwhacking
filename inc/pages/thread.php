<?php

	require_once __DIR__ . '/../config/user.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/database.php';


	do
	{
		if (!isset($_GET['id']))
		{
			include __DIR__ . '/404.php';
			break;
		}
		$threadId = $_GET['id'];

		$database = getDatabase();

		$threads = $database->select('threads', [
			'[>]forums' => ['forum' => 'id']
		], [
			'threads.id',
			'threads.name',
			'forums.id(forum_id)',
			'forums.name(forum_name)'
		], [
			'threads.id' => $threadId
		]);

		if (count($threads) !== 1)
		{
			include __DIR__ . '/404.php';
			break;
		}
		$thread = $threads[0];
		
		addView($threadId, $database);

		$threadName = $thread['name'];
		$forumId = $thread['forum_id'];
		$forumName = $thread['forum_name'];

		?>

		<h2><?php echo $threadName; ?></h2>

		<div class="grid">
			<p class="column breadcrumbs">
				<a href="?p=forums">Foren-Übersicht</a> &rarr;
				<a href="?p=forum&id=<?php echo $forumId; ?>"><?php echo $forumName; ?></a> &rarr;
				<strong><?php echo $threadName; ?></strong>
			</p>
			<form class="column">
				<a class="primary button" href="?p=new-reply&thread=<?php echo $threadId; ?>">Antworten</a>
			</form>
		</div>

		<?php

		$posts = getPostsInThread($threadId, $database);

		foreach ($posts as $post)
		{
			$id = $post['id'];
			$authorId = $post['author_id'];
			$authorName = $post['author_name'];
			$authorPowerlevel = ($post['author_powerlevel'] !== 0)
				? '<p class="powerlevel">' . POWERLEVEL_DESCRIPTIONS[$post['author_powerlevel']] . '</p>'
				: '';
			$authorTitle = $post['author_title'];
			$authorAvatarHtml = getAvatarHtml($authorId);
			$authorRegistrationTime = date(DEFAULT_DATE_FORMAT, $post['author_registration_time']);
			$authorCurrentPostNumber = getCurrentPostNumber($authorId, $id, $database);
			$authorNumTotalPosts = getNumPostsByUser($authorId, $database);

			$postTime = date(DEFAULT_DATE_FORMAT, $post['post_time']);
			$content = nl2br($post['content']);
			$authorSignature = (trim($post['author_signature']) !== '')
				? '<div class="signature">' . nl2br($post['author_signature']) . '</div>'
				: '';

			?>
			<div class="post" id="post-<?php echo $id; ?>">
				<div class="sidebar">
					<h3><a href="?p=user&id=<?php echo $authorId ?>"><?php echo $authorName; ?></a></h3>
					<?php echo $authorPowerlevel; ?>
					<p class="title"><?php echo $authorTitle; ?></p>
					<?php echo $authorAvatarHtml; ?>
					<p>Beiträge: <?php echo $authorCurrentPostNumber; ?> / <?php echo $authorNumTotalPosts; ?></p>
					<p>Registriert seit: <?php echo $authorRegistrationTime; ?></p>
				</div>
				<div class="content">
					<div class="topbar">geschrieben am <?php echo $postTime; ?></div>
					<?php echo $content; ?>
					<?php echo $authorSignature; ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php
		}
	}
	while (false);

?>

<form class="right">
	<button class="primary">Antworten</button>
</form>

<ul class="pagination">
	<li><a href="page/1">1</a></li>
	<li><a href="page/2">2</a></li>
	<li class="selected"><a href="page/3">3</a></li>
	<li><a href="page/4">4</a></li>
	<li><a href="page/5">5</a></li>
</ul>