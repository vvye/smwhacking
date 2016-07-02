<?php foreach ($categories as $category): ?>

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

		<?php foreach ($category['forums'] as $forum): ?>
			<tr>
				<td class="new"><?php echo $forum['new']; ?></td>
				<td class="forum-description">
					<h3><a href="?p=forum&id=<?php echo $forum['id']; ?>"><?php echo $forum['name']; ?></a></h3>
					<p><?php echo $forum['description']; ?></p>
				</td>
				<td class="num-threads"><?php echo $forum['numThreads']; ?></td>
				<td class="num-posts"><?php echo $forum['numPosts']; ?></td>
				<td class="last-post"><?php echo $forum['lastPostCellContent']; ?></td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>

<?php endforeach; ?>