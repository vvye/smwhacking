<?php if (!$loggedIn): ?>
	<nav class="user-menu">
		<ul>
			<li><a href="?p=login">Einloggen</a></li>
			<li><a href="?p=register">Registrieren</a></li>
		</ul>
	</nav>
<?php else: ?>
	<nav class="user-menu">
		<ul>
			<li>
				<a href="?p=user&id=<?= $userId ?>">
					Eingeloggt als <strong><?= $username ?></strong>
				</a>
			</li>
			<li><a href="?p=pm">Private Nachrichten (0)</a></li>
			<li><a href="?p=usercp">Einstellungen</a></li>
			<?php if ($admin): ?>
				<li><a href="?p=admin">Administration</a></li>
			<?php endif; ?>
			<li><a href="?p=logout">Ausloggen</a></li>
		</ul>
	</nav>
<?php endif; ?>