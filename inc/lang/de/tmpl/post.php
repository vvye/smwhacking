<div class="post" id="post-<?= $id ?>">

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

		<p>Beiträge: <?= $author['currentPostNumber'] ?> / <?= $author['numTotalPosts'] ?></p>
		<p>Registriert seit: <?= $author['registrationTime'] ?></p>

		<?php foreach ($author['favoriteMedals'] as $medal): ?>
			<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>"
			     title="<?= $medal['name'] ?> &mdash; <?= $medal['description'] ?>" />
		<?php endforeach; ?>

	</div>

	<div class="content">
		<div class="topbar grid">
			<div class="column">
				<?php if ($inThread && $unread): ?>
					<span class="status"><?= MSG_NEW ?></span>
				<?php endif; ?>
				geschrieben am <?= $postTime ?>
				<?php if (!$inThread): ?>
					in <a href="?p=thread&id=<?= $threadId ?>"><?= $threadName ?></a>
				<?php endif; ?>
				<?php if ($lastEdit !== null): ?>
					<br />zuletzt bearbeitet von <a
						href="?p=user&id=<?= $lastEdit['editor_id'] ?>"><?= $lastEdit['editor_name'] ?></a>
					am <?= date(DEFAULT_DATE_FORMAT, $lastEdit['edit_time']) ?>.
				<?php endif; ?>
			</div>
			<div class="column">
				(
				<a href="?p=thread&id=<?= $threadId ?>&page=<?= $pageInThread ?>#post-<?= $id ?>">Link</a>
				<?php if ($inThread): ?>
					<?php if ($canPost): ?>
						| <a href="?p=new-post&thread=<?= $threadId ?>&quote=<?= $id ?>">zitieren</a>
					<?php endif; ?>
					<?php if ($canModifyPost): ?>
						| <a href="?p=edit-post&id=<?= $id ?>&token=<?= $token ?>">bearbeiten</a>
						| <a href="?p=delete-post&id=<?= $id ?>&token=<?= $token ?>">löschen</a>
					<?php endif; ?>
				<?php endif; ?>
				)
			</div>
		</div>
		<?= $content ?>
		<?php if ($author['signature'] !== ''): ?>
			<div class="signature"><?= $author['signature'] ?></div>
		<?php endif; ?>
	</div>

	<div class="clearfix"></div>
</div>