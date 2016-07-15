<h2>Nutzer: <?= $name ?></h2>

<form>
	<a class="subtle button" href="?p=posts&user=<?= $id ?>"><i class="fa fa-list"></i> Beiträge ansehen</a>
	<a class="subtle button" href="?p=new-pm&user=<?= $id ?>"><i class="fa fa-envelope"></i> Nachricht
		schreiben</a>
	<?php if ($canEditProfile): ?>
		<a class="subtle button" href="?p=edit-profile&user=<?= $id ?>&token=<?= $token ?>"><i class="fa fa-edit"></i>
			Profil bearbeiten</a>
	<?php endif; ?>
	<?php if ($canBan): ?>

		<?php if ($banned): ?>
			<a class="subtle button" href="?p=ban&user=<?= $id ?>&action=unban&token=<?= $token ?>"><i
					class="fa fa-ban"></i> Nutzer entsperren</a>
		<?php else: ?>
			<a class="subtle button" href="?p=ban&user=<?= $id ?>&token=<?= $token ?>"><i class="fa fa-ban"></i> Nutzer
				sperren</a>
		<?php endif; ?>

	<?php endif; ?>
	<?php if ($canGiveMedal): ?>
	<a class="subtle button" href="?p=award-medal&user=<?= $id ?>&token=<?= $token ?>"><i class="fa fa-certificate"></i>
		Medaille verleihen</a>
	<?php endif; ?>
</form>