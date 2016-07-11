<div class="post" id="post-<?= $id ?>">
	<div class="sidebar">
		<h3><a href="?p=user&id=<?= $author['id'] ?>"><?= $author['name'] ?></a></h3>
		<?php if ($author['powerlevelId'] !== 0): ?>
			<?= $author['powerlevel'] ?>
		<?php endif; ?>
		<p><?= $author['rank']['name'] ?></p>
		<?php if ($author['rank']['has_image']): ?>
			<img src="img/ranks/<?= $author['rank']['id'] ?>.png" alt="<?= $author['rank']['name'] ?>" />
		<?php endif; ?>
		<p class="title"><?= $author['title'] ?></p>
		<img class="avatar" src="img/avatars/<?= $author['id'] ?>.png" alt="Avatar" />
		<p>Beiträge: <?= $author['currentPostNumber'] ?> / <?= $author['numTotalPosts'] ?></p>
		<p>Registriert seit: <?= $author['registrationTime'] ?></p>
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
					| <a href="?p=new-reply&thread=<?= $threadId ?>&quote=<?= $id ?>">zitieren</a>
					| <a href="?p=edit-reply&id=<?= $id ?>">bearbeiten</a>
					| <a href="?p=delete-reply&id=<?= $id ?>">löschen</a>
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