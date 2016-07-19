<ul>
	<li><a href="?p=thread&id=<?= $threadId ?>">Zurück zum Thema</a></li>
	<?php if ($oppositeAction !== ''): ?>
		<li><a href="?p=moderate-thread&action=<?= $oppositeAction ?>&id=<?= $threadId ?>&token=<?= $token ?>">Aktion
				rückgängig machen</a>
		</li>
	<?php endif; ?>
</ul>