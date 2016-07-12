<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadName ?></strong>
</p>

<h2>Beitrag bearbeiten</h2>

<p>Bitte bestätige, dass du diesen Beitrag löschen möchtest.</p>
<?php if ($firstPost): ?>
	<div class="message">Weil er der erste Beitrag des Themas ist, wird dadurch das gesamte Thema gelöscht!</div>
<?php endif; ?>

<form method="post" action="?p=delete-post&id=<?= $postId ?>&token=<?= $token ?>">
	<a class="button primary" href="?p=thread&id=<?= $threadId ?>">Abbrechen</a>
	<button class="subtle" type="submit" name="submit">
		<?php if ($firstPost): ?>
			Gesamtes Thema löschen
		<?php else: ?>
			Beitrag löschen
		<?php endif; ?>
	</button>
</form>