<h2><?= $forumName ?></h2>

<div class="grid">
	<p class="column breadcrumbs">
		<a href="?p=forums">Foren-Übersicht</a> &rarr; <strong><?= $forumName ?></strong>
	</p>

	<div class="column">
		<?php if ($loggedIn): ?>
			<a class="subtle button" href="?p=forum&id=<?= $forumId ?>&mark-read">Forum als gelesen
				markieren</a>
			<?php if ($canMakeThread): ?>
				<a class="button primary" href="?p=new-thread&forum=<?= $forumId ?>">Neues Thema erstellen</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>