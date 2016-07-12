<?php if ($top): ?>
	<h2><?= $threadName ?></h2>
<?php endif; ?>


<div class="grid">
	<p class="column breadcrumbs">
		<a href="?p=forums">Foren-Übersicht</a> &rarr;
		<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
		<strong><?= $threadName ?></strong>
	</p>
	<?php if ($canTakeAction): ?>
		<form class="column">
			<?php if ($moderator): ?>

				<?php if (!$closed): ?>
					<a class="subtle button" href="?p=moderate-thread&action=close&id=<?= $threadId ?>">Thema
						schließen</a>
				<?php else: ?>
					<a class="subtle button" href="?p=moderate-thread&action=open&id=<?= $threadId ?>">Thema öffnen</a>
				<?php endif; ?>

				<?php if (!$sticky): ?>
					<a class="subtle button" href="?p=moderate-thread&action=sticky&id=<?= $threadId ?>">Thema als
						wichtig markieren</a>
				<?php else: ?>
					<a class="subtle button" href="?p=moderate-thread&action=unsticky&id=<?= $threadId ?>">Thema
						ablösen</a>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ($canPost): ?>
				<a class="primary button" href="?p=new-post&thread=<?= $threadId ?>">Antworten</a>
			<?php endif; ?>
			
		</form>
	<?php endif; ?>
</div>