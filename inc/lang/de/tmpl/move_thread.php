<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadTitle ?></strong>
</p>

<h2>Thema verschieben</h2>

<form method="post" action="?p=move-thread&id=<?= $threadId ?>&token=<?= $token ?>">
	<label>
		<select name="target-forum-id">
			<option disabled="disabled" selected="selected">verschieben nach&hellip;</option>
			<?php foreach ($targetForums as $forum): ?>
				<option value="<?= $forum['id'] ?>" <?= $forum['current'] ? 'disabled="disabled"' : '' ?>>
					<?= $forum['name'] ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<br /><br />
	<button class="primary" type="submit" name="submit">Thema verschieben</button>
</form>