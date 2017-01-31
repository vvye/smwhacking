<h2>Chat</h2>

<div class="chat">

	<div class="chat-header">
		<div class="grid">
			<div class="column">
				<button id="refresh" class="small primary"><i id="refresh-icon" class="fa fa-refresh"></i> Aktualisieren
				</button>
				<span>zuletzt aktualisiert am 15.10.2016, 14:12</span>
			</div>
			<div class="column">
				<button class="small subtle"><i class="fa fa-list"></i> Chat-Archiv</button>
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
<script type="text/javascript">

    var editor = new CuteEdit('message');

    function getMessages() {

        return document.getElementsByClassName('chat-message');
    }

    function removeFirstMessage() {

        var firstMessage = getMessages()[0];
        var container = document.getElementsByClassName('chat-messages')[0];
        container.removeChild(firstMessage);

    }

    function addMessage(message) {

        var container = document.getElementsByClassName('chat-messages')[0];
        container.innerHTML += '<div class="chat-message" id="message-' + message.id + '" data-id="' + message.id + '">'
            + '<div class="chat-sidebar">'
            + '<img class="avatar" src="img/avatars/' + message.author_id + '.png" />'
            + '</div>'
            + '<div class="chat-topbar">'
            + '<a href="?p=user&id=' + message.author_id + '" class="username">' + message.author_name + '</a> '
            + '<span>' + message.post_time + ' (<a href="#">bearbeiten</a> | '
            + '<a href="#">löschen</a>)</span>'
            + '</div>'
            + '<div class="chat-message-content">' + message.content + '</div>'
            + '<div class="clearfix"></div>'
            + '</div>';

    }

    function getNumMessages() {

        var messages = getMessages();
        return messages.length;

    }

    function getLastMessageId() {

        var messages = getMessages();
        return messages[messages.length - 1].dataset.id;

    }

    // refreshing
    document.getElementById('refresh').onclick = function () {

        this.setAttribute('disabled', 'disabled');
        document.getElementById('refresh-icon').classList.add('fa-spin');

        nanoajax.ajax({
            url: 'inc/ajax/chat.php?action=last_unread_messages&last_id=' + getLastMessageId()
        }, function (status, response) {

            document.getElementById('refresh-icon').classList.remove('fa-spin');
            document.getElementById('refresh-icon').classList.remove('fa-refresh');
            document.getElementById('refresh-icon').classList.add('fa-check');
            setTimeout(function () {
                document.getElementById('refresh').removeAttribute('disabled');
                document.getElementById('refresh-icon').classList.remove('fa-check');
                document.getElementById('refresh-icon').classList.add('fa-refresh');
            }, <?= REQUEST_COOLDOWN_TIME * 1000 ?>);

            if (status !== 200) {
                return;
            }

            if (response === undefined || response === '') {
                return;
            }

            var messages = JSON.parse(response);

            for (var i = 0; i < messages.length; i++) {
                var message = messages[i];
                addMessage(message);
            }

            while (getNumMessages() > <?= MAX_CHAT_MESSAGES ?>) {
                removeFirstMessage();
            }

            if (messages.length) {
                var container = document.getElementsByClassName('chat-messages')[0];
                container.scrollTop = container.scrollHeight;
            }

        });

    }

</script>