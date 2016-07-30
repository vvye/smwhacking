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
					<?php if (true): // $numPms > 0 ?>
						<span class="badge">5 neue</span>
					<?php endif; ?>
				</a></li>
			<li><a href="?p=edit-profile&token=<?= $token ?>">Einstellungen</a></li>
			<?php if ($admin): ?>
				<li><a href="?p=admin">Administration</a></li>
			<?php endif; ?>
			<li><a href="?p=logout">Ausloggen</a></li>
		</ul>
	</nav>
<?php endif; ?>