<h2><?= $forumName ?></h2>

<div class="grid">
	<p class="column breadcrumbs">
		<a href="?p=forums">Foren-Übersicht</a> &rarr; <strong><?= $forumName ?></strong>
	</p>
	<?php if (isLoggedIn()): ?>
		<form class="column">
			<a class="subtle button" href="?p=forum&id=<?= $forumId ?>&mark-read">Forum als gelesen
				markieren</a>
			<button class="primary">Neues Thema erstellen</button>
		</form>
	<?php endif; ?>
</div>