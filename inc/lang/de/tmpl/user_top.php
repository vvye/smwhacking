<h2>Nutzer: <?= $name ?></h2>

<form>
	<a class="subtle button" href="?p=posts&user=<?= $id ?>"><i class="fa fa-list"></i> Beiträge ansehen</a>
	<a class="subtle button" href="?p=new-pm&user=<?= $id ?>"><i class="fa fa-envelope"></i> Nachricht
		schreiben</a>
	<?php if ($isAdmin): ?>
		<a class="subtle button" href="?p=edit-profile&user=<?= $id ?>"><i class="fa fa-cog"></i>
			Nutzer bearbeiten</a>
	<?php endif; ?>
	<?php if ($isModerator): ?>
	<a class="subtle button" href="?p=ban&user=<?= $id ?>"><i class="fa fa-ban"></i> Nutzer bannen</a>
	<?php endif; ?>
</form>