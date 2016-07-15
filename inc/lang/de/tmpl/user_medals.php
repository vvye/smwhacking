<section class="user-section">
	<?php if ($numTotalMedals !== 0): ?>
		<h3>Medaillen (<?= $numTotalMedals ?>)</h3>
	<?php else: ?>
		<h3>Medaillen</h3>
	<?php endif ?>

	<div class="content medals">

		<?php if ($numTotalMedals === 0): ?>
			<em><?= MSG_USER_NO_MEDALS ?></em>
		<?php endif; ?>

		<?php foreach ($medalsByCategory as $category => $medals): ?>

			<h4><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h4>

			<?php foreach ($medals as $medal): ?>
				<div class="medal">
					<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
					<div>
						<h5><?= $medal['name'] ?></h5>
						<p><?= $medal['description'] ?></p>
						<p>verliehen am <?= date(DEFAULT_DATE_FORMAT, $medal['award_time']) ?></p>
					</div>
				</div>
			<?php endforeach; ?>

		<?php endforeach; ?>

	</div>
</section>