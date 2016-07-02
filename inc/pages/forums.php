<h2>Forum</h2>

<?php
	
	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/database.php';


	$database = getDatabase();

	$categories = getForumCategories($database);

	foreach ($categories as $category)
	{

		?>
		<table class="forum forum-category">
			<thead>
			<tr>
				<th class="category-name" colspan="2"><?php echo $category['name']; ?></th>
				<th class="num-threads">Themen</th>
				<th class="num-posts">Beiträge</th>
				<th class="last-post">letzter Beitrag</th>
			</tr>
			</thead>
			<tbody>
			<?php

				$forums = getForumsByCategory($category['id']);

				foreach ($forums as $forum)
				{
					$id = $forum['id'];
					$name = $forum['name'];
					$new = isLoggedIn() && $forum['last_read_time'] < $forum['last_post_time'] ? 'NEU' : '';
					$description = $forum['description'];
					$numThreads = getNumThreadsInForum($id);
					$numPosts = getNumPostsInForum($id);
					$lastPostCellContent = getLastPostCellContent(getLastPostInForum($id));

					?>
					<tr>
						<td class="new"><?php echo $new; ?></td>
						<td class="forum-description">
							<h3><a href="?p=forum&id=<?php echo $id; ?>"><?php echo $name; ?></a></h3>
							<p><?php echo $forum['description']; ?></p>
						</td>
						<td class="num-threads"><?php echo $numThreads; ?></td>
						<td class="num-posts"><?php echo $numPosts; ?></td>
						<td class="last-post"><?php echo $lastPostCellContent; ?></td>
					</tr>
					<?php

				}

			?>

			</tbody>
		</table>

		<?php

	}

?>
