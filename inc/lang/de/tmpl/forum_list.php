<h2>Forum</h2>

<?php foreach ($categories as $category): ?>

	<table class="forum forum-category">
		<thead>
		<tr>
			<th class="category-name" colspan="2"><?= $category['name']; ?></th>
			<th class="num-threads">Themen</th>
			<th class="num-posts">Beiträge</th>
			<th class="last-post">letzter Beitrag</th>
		</tr>
		</thead>
		<tbody>

		<?php foreach ($category['forums'] as $forum): ?>
			<tr>
				<td class="status">
					<span class="new"><?= $forum['unread'] ? MSG_NEW : ''; ?></span></td>
				<td class="forum-description">
					<h3><a href="?p=forum&id=<?= $forum['id']; ?>"><?= $forum['name']; ?></a></h3>
					<p><?= $forum['description']; ?></p>
				</td>
				<td class="num-threads"><?= $forum['numThreads']; ?></td>
				<td class="num-posts"><?= $forum['numPosts']; ?></td>
				<td class="last-post">
					<?php if ($forum['lastPost'] === null): ?>
						<em><?= MSG_NONE ?></em>
					<?php else: ?>
						von <a href="?p=user&id=<?= $forum['lastPost']['author_id'] ?>">
							<?= $forum['lastPost']['author_name'] ?>
						</a>
						<a href="?p=thread&id=<?= $forum['lastPost']['thread_id'] ?>&page=<?= $forum['lastPostPage'] ?>#post-<?= $forum['lastPost']['id'] ?>">
							<i class="fa fa-arrow-right"></i>
						</a>
						<p><?= date(DEFAULT_DATE_FORMAT, $forum['lastPost']['post_time']) ?></p>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>

<?php endforeach; ?>