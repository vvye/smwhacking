<h2>Nutzer: <?= $name ?></h2>

<form>
	<a class="subtle button" href="?p=posts&user=<?= $id ?>"><i class="fa fa-list"></i> Beiträge ansehen</a>
	<a class="subtle button" href="?p=new-pm&user=<?= $id ?>"><i class="fa fa-envelope"></i> Nachricht
		schreiben</a>
	<?php if ($isAdmin): ?>
		<a class="subtle button" href="?p=usercp&user=<?= $id ?>"><i class="fa fa-cog"></i>
			Nutzer administrieren</a>
	<?php endif; ?>
</form>