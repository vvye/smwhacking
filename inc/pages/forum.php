<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/database.php';
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

		$database = getDatabase();

		$forums = $database->select('forums', '*', [
			'id' => $forumId
		]);

		if (count($forums) !== 1)
		{
			include __DIR__ . '/404.php';
			break;
		}
		$forum = $forums[0];

		if (isset($_GET['mark-read']))
		{
			markForumAsRead($forumId, $database);
			renderSuccessMessage('Dieses Forum wurde als gelesen markiert.');
		}

		$forumName = $forum['name'];

		?>
		<h2><?php echo $forumName; ?></h2>

		<div class="grid">
			<p class="column breadcrumbs">
				<a href="?p=forums">Foren-Übersicht</a> &rarr; <strong><?php echo $forumName; ?></strong>
			</p>
			<form class="column">
				<a class="pseudo button" href="?p=forum&id=<?php echo $forumId; ?>&mark-read">Forum als gelesen
					markieren</a>
				<button class="primary">Neues Thema erstellen</button>
			</form>
		</div>

		<?php

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;

		$threads = getThreadsInForum($forumId, $page, $database);
		$numTotalThreads = getNumThreadsInForum($forumId, $database);
		$numStickies = getNumStickiesInForum($forumId, $database);

		$numPages = (int)ceil($numTotalThreads / THREADS_PER_PAGE);
		makeBetween($page, 1, $numPages);

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);

		?>

		<table class="forum thread-list">
			<thead>
			<tr>
				<th class="thread" colspan="2">Thema</th>
				<th class="num-replies">Antworten</th>
				<th class="num-views">Zugriffe</th>
				<th class="last-post">letzter Beitrag</th>
			</tr>
			</thead>
			<tbody>
			<?php

				if ($numTotalThreads === 0)
				{
					?>
					<tr>
						<td colspan="5" style="text-align: center;">
							<em>In diesem Forum gibt es noch keine Themen.</em>
						</td>
					</tr>
					<?php
				}

				$i = 0;
				foreach ($threads as $thread)
				{
					if ($thread['sticky'])
					{
						$stickyCssClass = (++$i === $numStickies) ? ' class="last sticky"' : ' class="sticky"';
						$stickyPrefix = 'Wichtig: ';
					}
					else
					{
						$stickyCssClass = $stickyPrefix = '';
					}

					$new = $thread['last_read_time'] < $thread['last_post_time'] ? 'NEU' : '';
					$id = $thread['id'];
					$name = $thread['name'];
					$numReplies = getNumPostsInThread($id, $database) - 1;
					$numViews = $thread['views'];
					$lastPostCellContent = getLastPostCellContent(getLastPostInThread($id, $database));
					$authorId = $thread['author_id'];
					$authorName = $thread['author_name'];
					$creationTime = date(DEFAULT_DATE_FORMAT, $thread['creation_time']);

					?>

					<tr<?php echo $stickyCssClass; ?>>
						<td class="new"><?php echo $new; ?></td>
						<td>
							<h3><?php echo $stickyPrefix; ?>
								<a href="?p=thread&id=<?php echo $id; ?>"><?php echo $name; ?></a>
							</h3>
							<p>erstellt von <a href="?p=user&id=<?php echo $authorId; ?>"><?php echo $authorName; ?></a>
								am <?php echo $creationTime; ?></p>
						</td>
						<td class="num-replies"><?php echo $numReplies; ?></td>
						<td class="num-views"><?php echo $numViews; ?></td>
						<td class="last-post"><?php echo $lastPostCellContent; ?></td>
					</tr>


					<?php
				}

			?>

			</tbody>
		</table>

		<?php

		renderPagination('?p=forum&id=' . $forumId, $page, $numPages);
	}
	while (false);
