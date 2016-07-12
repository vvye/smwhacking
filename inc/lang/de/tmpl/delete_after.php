<?php if ($firstPost): ?>
	<a href="?p=forum&id=<?= $forumId ?>">Zurück zum Forum</a>
<?php else: ?>
	<a href="?p=thread&id=<?= $threadId ?>">Zurück zum Thema</a>
<?php endif; ?>
