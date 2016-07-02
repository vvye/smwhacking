<div class="post" id="post-<?= $userId ?>">
	<div class="sidebar">
		<h3><a href="?p=user&id=<?= $author['id'] ?>"><?= $author['name'] ?></a></h3>
		<?php if ($author['powerlevelId'] !== 0): ?>
			<?= $author['powerlevel'] ?>
		<?php endif; ?>
		<?= $author['rankHtml'] ?>
		<p class="title"><?= $author['title'] ?></p>
		<?= $author['avatarHtml'] ?>
		<p>Beiträge: <?= $author['currentPostNumber'] ?> / <?= $author['numTotalPosts'] ?></p>
		<p>Registriert seit: <?= $author['registrationTime'] ?></p>
	</div>
	<div class="content">
		<div class="topbar grid">
			<div class="column">
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
				<a href="?p=thread&id=<?= $threadId ?>&page=<?= $pageInThread ?>#post-<?= $userId ?>">Link</a>
				<?php if ($inThread): ?>
					| <a href="?p=new-reply&thread=<?= $threadId ?>&quote=<?= $userId ?>">zitieren</a>
					| <a href="?p=edit-reply&id=<?= $userId ?>">bearbeiten</a>
					| <a href="?p=delete-reply&id=<?= $userId ?>">löschen</a>
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