<h2>Chat</h2>

<div class="chat">

	<div class="chat-header">
		<div class="grid">
			<div class="column">
				<button class="small primary"><i class="fa fa-refresh"></i> Aktualisieren</button>
				<span>zuletzt aktualisiert am 15.10.2016, 14:12</span>
			</div>
			<div class="column">
				<button class="small subtle"><i class="fa fa-list"></i> Chat-Archiv</button>
			</div>
		</div>
	</div>

	<div class="chat-messages">

		<?php foreach ($messages as $message): ?>

			<div class="chat-message">
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
	<div class="chat-footer">
		<form>
			<textarea id="message" name="message"
					  style="display: inline-block; width: 50rem; height: 2.2rem; font-size: 0.9rem;"></textarea>
			<button class="primary" type="submit">Senden</button>
		</form>
	</div>
</div>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript">

    var editor = new CuteEdit('message');

</script>