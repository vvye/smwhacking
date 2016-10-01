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
			<li><a href="?p=pm">Private Nachrichten
					<?php if ($numUnreadPms > 0): ?>
						<span class="badge"><?= $numUnreadPms ?> neue</span>
					<?php endif; ?>
				</a></li>
			<li><a href="?p=edit-profile&token=<?= $token ?>">Einstellungen</a></li>
			<?php if ($admin): ?>
				<li><a href="?p=admin">Administration</a></li>
			<?php endif; ?>
			<li><a href="session.php?action=logout">Ausloggen</a></li>
		</ul>
	</nav>
<?php endif; ?>