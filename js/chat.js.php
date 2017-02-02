<?php

require_once __DIR__ . '/../inc/config/chat.php';
require_once __DIR__ . '/../inc/config/ajax.php';

?>

(function () {

    var container = document.getElementsByClassName('chat-messages')[0];
    var refreshButton = document.getElementById('refresh');
    var refreshIcon = document.getElementById('refresh-icon');
    var refreshDate = document.getElementById('refresh-date');
    var messageContent = document.getElementById('message-content');
    var sendButton = document.getElementById('send');

    scrollToLastMessage();


    function scrollToLastMessage() {
        container.scrollTop = container.scrollHeight;
    }

    function getMessages() {
        return document.getElementsByClassName('chat-message');
    }

    function removeFirstMessage() {

        var firstMessage = getMessages()[0];
        container.removeChild(firstMessage);

    }

    function addMessage(message) {

        var messageTemplate = '<div class="chat-message" id="message-{id}" data-id="{id}">'
            + '<div class="chat-sidebar">'
            + (message.has_avatar ? '<img class="avatar" src="img/avatars/{author_id}.png" />' : '<img class="avatar" src="img/avatars/default.png" />')
            + '</div>'
            + '<div class="chat-topbar">'
            + '<a href="?p=user&id={author_id}" class="username">{author_name}</a>'
            + '<span> {post_time} (<a href="#">bearbeiten</a> | <a href="#">löschen</a>)</span>'
            + '</div>'
            + '<div class="chat-message-content">{content}</div>'
            + '<div class="clearfix"></div>'
            + '</div>';
        var messageHTML = nano(messageTemplate, message);
        container.innerHTML += messageHTML;

    }

    function getNumMessages() {

        var messages = getMessages();
        return messages.length;

    }

    function getLastMessageId() {

        var messages = getMessages();
        return messages[messages.length - 1].dataset.id;

    }

    function deactivateRefreshButton() {

        refreshButton.setAttribute('disabled', 'disabled');
        refreshIcon.classList.add('fa-spin');

    }

    function showCheckmark() {

        refreshIcon.classList.remove('fa-spin');
        refreshIcon.classList.remove('fa-refresh');
        refreshIcon.classList.add('fa-check');

    }

    function activateRefreshButton() {

        refreshButton.removeAttribute('disabled');
        refreshIcon.classList.remove('fa-check');
        refreshIcon.classList.add('fa-refresh');

    }

    function deactivateSendButton() {
        sendButton.setAttribute('disabled', 'disabled');
    }

    function activateSendButton() {
        sendButton.removeAttribute('disabled');
        messageContent.onchange();
    }

    // http://stackoverflow.com/questions/11076975/insert-text-into-textarea-at-cursor-position-javascript
    function insertAtCursor(myField, myValue) {
        //IE support
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
        }
        //MOZILLA and others
        else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos)
                + myValue
                + myField.value.substring(endPos, myField.value.length);
            myField.selectionStart = startPos + myValue.length;
            myField.selectionEnd = startPos + myValue.length;
        } else {
            myField.value += myValue;
        }
    }

    function addMessages(messages) {

        for (var i = 0; i < messages.length; i++) {
            addMessage(messages[i]);
        }

        while (getNumMessages() > <?= MAX_CHAT_MESSAGES ?>) {
            removeFirstMessage();
        }

        if (messages.length) {
            scrollToLastMessage();
        }

    }

    refreshButton.onclick = function () {

        deactivateRefreshButton();

        nanoajax.ajax({
            url: 'inc/ajax/chat.php?action=last_unread_messages'
            + '&last_id=' + getLastMessageId()
        }, function (status, response) {

            showCheckmark();
            setTimeout(activateRefreshButton, <?= CHAT_REFRESH_COOLDOWN_TIME * 1000 ?>);
            refreshDate.innerHTML = new Date();

            if (status !== 200) {
                return;
            }
            if (response === undefined || response === '') {
                return;
            }

            var messages = JSON.parse(response);
            addMessages(messages);

        });

    };

    messageContent.oninput = messageContent.onchange = messageContent.onpropertychange = function () {

        if (this.value.trim() === '') {
            sendButton.setAttribute('disabled', 'disabled');
        } else {
            sendButton.removeAttribute('disabled');
        }

    };

    messageContent.onkeydown = function (e) {

        // ctrl+enter
        if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
            insertAtCursor(this, '\n');
            if (this.value.length === this.selectionEnd) {
                this.style.height = (this.offsetHeight + 16) + 'px';
            }
        }

        else if (e.keyCode == 13) {
            postMessage();
        }

    };


    function postMessage() {

        deactivateSendButton();

        nanoajax.ajax({
            url: 'inc/ajax/chat.php?action=post_message'
            + '&content=' + encodeURIComponent(messageContent.value)
            + '&last_id=' + getLastMessageId()
        }, function (status, response) {

            if (status === 403) {
                alert('Die Nachricht wurde zu schnell nach der letzten gesendet. Lass dir etwas mehr Zeit.');
                return;
            }
            if (status !== 200) {
                alert('Das Senden hat nicht geklappt.');
                return;
            }

            setTimeout(activateSendButton, <?= CHAT_MESSAGE_POST_COOLDOWN_TIME * 1000 ?>);

            messageContent.value = '';

            var messages = JSON.parse(response);
            addMessages(messages);

        });

    }

    sendButton.onclick = postMessage;

})();