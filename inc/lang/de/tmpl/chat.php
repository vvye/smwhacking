<h2>Chat</h2>

<div class="chat" <?= $loggedIn ? 'style="margin-bottom: 20rem;"' : '' ?>>

	<div class="chat-header">
		<div class="grid">
			<div class="column">
				<button id="refresh" class="small primary"><i id="refresh-icon" class="fa fa-refresh fa-fw"></i>
					Aktualisieren
				</button>
				<span>zuletzt aktualisiert am <span id="refresh-date"><?= date(DEFAULT_DATE_FORMAT) ?></span></span>
			</div>
			<div class="column">
				<a class="small subtle button" href="?p=chat-archive"><i class="fa fa-list"></i> Chat-Archiv</a>
			</div>
		</div>
	</div>

	<div id="chat-container" class="chat-messages">

		<?php foreach ($messages as $message): ?>

			<div class="chat-message" id="message-<?= $message['id'] ?>" data-id="<?= $message['id'] ?>">
				<div class="chat-sidebar">
					<img class="avatar" src="<?= $message['avatar_url'] ?>" />
				</div>
				<div class="chat-topbar">
					<a href="?p=user&id=<?= $message['author_id'] ?>"
					   class="username"><?= $message['author_name'] ?></a>
					<span>
						<?= $message['post_time'] ?>
						<?php if ($message['can_delete']): ?>
							<a class="delete" href="#" title="löschen"><i class="fa fa-trash-o"></i></a>
						<?php endif ?>
					</span>
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
			<div class="message-form">
				<div id="message-content-wrapper">
					<textarea id="message-content" name="message"></textarea>
					<button id="send" class="primary" disabled="disabled"><i class="fa fa-send"></i> Senden
						<?php if ($enterToSend): ?>
							<small>(Enter)</small>
						<?php else: ?>
							<small>(Strg+Enter)</small>
						<?php endif ?>
					</button>
				</div>
			</div>
		</div>

	<?php endif; ?>

</div>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript" src="js/nano.js"></script>
<script type="text/javascript" src="js/nanoajax.min.js"></script>
<script type="text/javascript" src="js/onresize.js"></script>
<script type="text/javascript" src="js/chat.js.php"></script>

<?php if ($loggedIn): ?>
	<script type="text/javascript">

        var editor = new CuteEdit('message-content');

	</script>
<?php endif; ?>