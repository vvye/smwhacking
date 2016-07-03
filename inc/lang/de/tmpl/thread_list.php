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

	<?php if ($numTotalThreads === 0): ?>
		<tr>
			<td colspan="5" style="text-align: center;">
				<em>In diesem Forum gibt es noch keine Themen.</em>
			</td>
		</tr>
	<?php endif; ?>

	<?php foreach ($threads as $thread): ?>

		<?php if ($thread['lastSticky']): ?>
			<tr class="last sticky">
		<?php elseif ($thread['sticky']): ?>
			<tr class="sticky">
		<?php else: ?>
			<tr>
		<?php endif; ?>
		<td class="new"><?= $thread['new'] ?></td>
		<td>
			<h3>
				<?php if ($thread['sticky']): ?>
					Wichtig:
				<?php endif; ?>
				<a href="?p=thread&id=<?= $thread['id'] ?>"><?= $thread['name'] ?></a>
			</h3>
			<p>erstellt von <a href="?p=user&id=<?= $thread['authorId'] ?>"><?= $thread['authorName'] ?></a>
				am <?= $thread['creationTime'] ?></p>
		</td>
		<td class="num-replies"><?= $thread['numReplies'] ?></td>
		<td class="num-views"><?= $thread['numViews'] ?></td>
		<td class="last-post"><?= $thread['lastPostCellContent'] ?></td>
		</tr>
		
	<?php endforeach; ?>

	</tbody>
</table>