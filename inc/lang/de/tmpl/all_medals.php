<h2>Alle Medaillen</h2>

<div class="medals all-medals">

	<?php foreach ($medalsByCategory as $category => $medals): ?>

		<h3><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h3>

		<?php foreach ($medals as $medal): ?>
			<div class="medal">
				<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
				<div>
					<h4><?= $medal['name'] ?></h4>
					<p><?= $medal['description'] ?></p>
					<?php if (in_array($medal['id'], $awardedMedalIds)): ?>
						<p class="got-medal"><i class="fa fa-check"></i> Du hast diese Medaille!</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

	<?php endforeach; ?>

</div>