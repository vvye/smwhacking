<h2>Vorschau</h2>
<div class="post">

	<div class="sidebar">
		<h3>
			<a href="?p=user&id=<?= $author['id'] ?>">
				<?php if ($author['banned']): ?>
					<i class="fa fa-ban"></i>
				<?php endif; ?>
				<?= $author['name'] ?>
			</a>
		</h3>

		<?php if ($author['banned']): ?>
			gesperrt
		<?php elseif ($author['powerlevelId'] !== 0): ?>
			<?= $author['powerlevel'] ?>
		<?php endif; ?>

		<p><?= $author['rank']['name'] ?></p>

		<?php if ($author['rank']['has_image']): ?>
			<img src="img/ranks/<?= $author['rank']['id'] ?>.png" alt="<?= $author['rank']['name'] ?>" />
		<?php endif; ?>

		<p class="title"><?= $author['title'] ?></p>

		<?php if ($author['hasAvatar']): ?>
			<img class="avatar" src="img/avatars/<?= $author['id'] ?>.png" alt="Avatar" />
		<?php else: ?>
			<img class="avatar" src="img/avatars/default.png" alt="Avatar" />
		<?php endif; ?>

	</div>

	<div class="content">
		<div class="topbar">
			geschrieben am <?= $postTime ?>
		</div>
		<?= $content ?>
		<?php if ($author['signature'] !== ''): ?>
			<div class="signature"><?= $author['signature'] ?></div>
		<?php endif; ?>
	</div>

	<div class="clearfix"></div>
</div>