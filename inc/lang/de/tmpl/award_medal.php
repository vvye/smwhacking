<h2>Medaille verleihen: <a href="?p=user&id=<?= $userId ?>"><?= $username ?></a></h2>

<form action="?p=award-medal&user=<?= $userId ?>&token=<?= $token ?>" method="post">

	<section class="user-section">
		<h3>verleihbare Medaillen</h3>
		<div class="content medals">
			<?php foreach ($awardableMedalsByCategory as $category => $medals): ?>

				<h4><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h4>

				<?php foreach ($medals as $medal): ?>
					<div class="medal">
						<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
						<div>
							<h5><?= $medal['name'] ?></h5>
							<p><?= $medal['description'] ?></p>
						</div>
						<div class="custom-checkbox-group">
							<input type="checkbox" class="custom-checkbox" name="award[<?= $medal['id'] ?>]"
							       id="award-<?= $medal['id'] ?>" />
							<label class="custom-checkbox-label" for="award-<?= $medal['id'] ?>">verleihen</label>
						</div>
					</div>
				<?php endforeach; ?>

			<?php endforeach; ?>
		</div>
	</section>

	<section class="user-section">
		<h3>verliehene Medaillen</h3>
		<div class="content medals">
			<?php if ($numAwardedMedals === 0): ?>
				<em><?= MSG_USER_NO_MEDALS ?></em>
			<?php endif; ?>

			<?php foreach ($awardedMedalsByCategory as $category => $medals): ?>

				<h4><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h4>

				<?php foreach ($medals as $medal): ?>
					<div class="medal">
						<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
						<div>
							<h5><?= $medal['name'] ?></h5>
							<p><?= $medal['description'] ?></p>
						</div>
						<div class="custom-checkbox-group">
							<input type="checkbox" class="custom-checkbox" name="remove[<?= $medal['id'] ?>]"
							       id="remove-<?= $medal['id'] ?>" />
							<label class="custom-checkbox-label" for="remove-<?= $medal['id'] ?>">aberkennen</label>
						</div>
					</div>
				<?php endforeach; ?>

			<?php endforeach; ?>
		</div>
	</section>

	<br />

	<input type="submit" name="submit" class="primary" value="Änderungen speichern" />

</form>





