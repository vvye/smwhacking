<h2>Chat</h2>

<div class="chat">

	<div class="chat-header">
		<div class="grid">
			<div class="column">
				<button id="refresh" class="small primary"><i id="refresh-icon" class="fa fa-refresh fa-fw"></i>
					Aktualisieren
				</button>
				<span>zuletzt aktualisiert am <span id="refresh-date"><?= date(DEFAULT_DATE_FORMAT) ?></span></span>
			</div>
			<div class="column">
				<a class="small subtle button" href="#"><i class="fa fa-list"></i> Chat-Archiv</a>
			</div>
		</div>
	</div>

	<div class="chat-messages">

		<?php foreach ($messages as $message): ?>

			<div class="chat-message" id="message-<?= $message['id'] ?>" data-id="<?= $message['id'] ?>">
				<div class="chat-sidebar">
					<img class="avatar" src="img/avatars/<?= $message['author_id'] ?>.png" />
				</div>
				<div class="chat-topbar">
					<a href="?p=user&id=<?= $message['author_id'] ?>"
					   class="username"><?= $message['author_name'] ?></a>
					<span><?= date(DEFAULT_DATE_FORMAT, $message['post_time']) ?> (<a href="#">bearbeiten</a> | <a
								href="#">löschen</a>)</span>
				</div>
				<div class="chat-message-content">
					<?= $message['content'] ?>
				</div>
				<div class="clearfix"></div>
			</div>

		<?php endforeach; ?>

	</div>

	<?php if ($loggedIn): ?>

		<div class="chat-footer">
			<form>
				<textarea id="message" name="message"></textarea>
				<button class="primary" type="submit">Senden</button>
			</form>
		</div>

	<?php endif; ?>

</div>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript" src="js/nanoajax.min.js"></script>
<script type="text/javascript" src="js/chat.js.php"></script>
<script type="text/javascript">

    var editor = new CuteEdit('message');

    document.getElementById('refresh').onclick = refresh;

</script>