<?php if (!$loggedIn): ?>
	<nav class="user-menu not-logged-in">
		<ul>
			<li><a href="?p=login">Einloggen</a></li>
			<li><a href="?p=register">Registrieren</a></li>
		</ul>
	</nav>
<?php else: ?>
	<input type="checkbox" class="menu-toggle" id="user-menu-toggle">
	<label for="user-menu-toggle" class="menu-toggle-label user-menu-toggle-label">
		<span>&#9776;</span> <strong><?= $username ?></strong>
		<?php if ($numUnreadPms > 0): ?>
			<span class="badge"><?= $numUnreadPms ?></span>
		<?php endif; ?>
	</label>
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