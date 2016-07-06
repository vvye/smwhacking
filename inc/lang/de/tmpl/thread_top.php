<?php if ($top): ?>
	<h2><?= $threadName ?></h2>
<?php endif; ?>


<div class="grid">
	<p class="column breadcrumbs">
		<a href="?p=forums">Foren-Übersicht</a> &rarr;
		<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
		<strong><?= $threadName ?></strong>
	</p>
	<?php if ($loggedIn): ?>
		<form class="column">
			<a class="primary button" href="?p=new-reply&thread=<?= $threadId ?>">Antworten</a>
		</form>
	<?php endif; ?>
</div>