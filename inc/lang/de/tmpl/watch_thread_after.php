<ul>
	<li><a href="?p=thread&id=<?= $threadId ?>">Zurück zum Thema</a></li>
	<li>
		<?php if ($unwatch): ?>
			<a href="?p=watch-thread&id=<?= $threadId ?>">Aktion rückgängig machen</a>
		<?php else: ?>
			<a href="?p=watch-thread&id=<?= $threadId ?>&action=unwatch">Aktion rückgängig machen</a>
		<?php endif; ?>
	</li>
</ul>

