<div class="grid">
	<p class="column breadcrumbs">
		<?php if ($ownPm): ?>
			<a href="?p=pm&outbox">Postausgang</a> &rarr;
		<?php else: ?>
			<a href="?p=pm">Posteingang</a> &rarr;
		<?php endif; ?>
		<strong><?= $subject ?></strong>
	</p>
	<?php if ($canReply && !$ownPm): ?>
		<div class="column">
			<a class="primary button" href="?p=new-pm&reply=<?= $id ?>&token=<?= $token ?>"><i class="fa fa-send"></i>
				Antworten</a>
		</div>
	<?php endif; ?>
</div>


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

		<p>Beiträge: <?= $author['numTotalPosts'] ?></p>
		<p>Registriert seit: <?= $author['registrationTime'] ?></p>

		<?php foreach ($author['favoriteMedals'] as $medal): ?>
			<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>"
			     title="<?= $medal['name'] ?> &mdash; <?= $medal['description'] ?>" />
		<?php endforeach; ?>

	</div>

	<div class="content">
		<div class="topbar grid">
			<div class="column">
				<?php if ($unread): ?>
					<span class="status"><?= MSG_NEW ?></span>
				<?php endif; ?>
				geschrieben am <?= $sendTime ?>
			</div>

		</div>
		<?= $content ?>
		<?php if ($author['signature'] !== ''): ?>
			<div class="signature"><?= $author['signature'] ?></div>
		<?php endif; ?>
	</div>

	<div class="clearfix"></div>
</div>