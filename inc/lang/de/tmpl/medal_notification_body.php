<?php if (!empty($awardedMedals)): ?>

	<a href="<?= WEBSITE_URL ?>/?p=user&id=<?= $awarderId ?>"><?= $awarderName ?></a> hat dir folgende Medaille(n) verliehen:
	<ul>
		<?php foreach ($awardedMedals as $medal): ?>
			<li><strong><?= $medal['name'] ?></strong> &mdash; <?= $medal['description'] ?></li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>

<?php if (!empty($removedMedals)): ?>

	<a href="<?= WEBSITE_URL ?>/?p=user&id=<?= $awarderId ?>"><?= $awarderName ?></a> hat dir folgende Medaille(n) aberkannt:
	<ul>
		<?php foreach ($removedMedals as $medal): ?>
			<li><strong><?= $medal['name'] ?></strong> &mdash; <?= $medal['description'] ?></li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>

<a href="<?= WEBSITE_URL ?>/?p=user&id=<?= $userId ?>">Besuche dein
	Profil</a>, um dir die Medaillen-Änderungen anzusehen.
