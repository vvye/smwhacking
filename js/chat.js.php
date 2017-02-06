<?php

require_once __DIR__ . '/../inc/config/chat.php';
require_once __DIR__ . '/../inc/config/ajax.php';

session_start();

?>

(function () {

    var container = document.getElementById('chat-container');
    var refreshButton = document.getElementById('refresh');
    var refreshIcon = document.getElementById('refresh-icon');
    var refreshTime = document.getElementById('refresh-date');
    var messageContent = document.getElementById('message-content');
    var sendButton = document.getElementById('send');

    setupDeleteLinks();
    resizeMessageList();
    scrollToLastMessage();

    function resizeMessageList() {

        var height = localStorage.getItem('smwh-chat-height') || 400;
        container.style.height = height + 'px';
        addResizeListener(container, function () {
            var newHeight = container.offsetHeight;
            localStorage.setItem('smwh-chat-height', newHeight);
        });

    }

    function scrollToLastMessage() {
        setTimeout(function () {
            container.scrollTop = container.scrollHeight;
        }, 100); // why is timeout needed
    }

    function getMessages() {
        return document.getElementsByClassName('chat-message');
    }

    function removeFirstMessage() {

        var firstMessage = getMessages()[0];
        firstMessage.remove();

    }

    function addMessage(message) {

        var messageTemplate = '<div class="chat-message" id="message-{id}" data-id="{id}">'
            + '<div class="chat-sidebar">'
            + '<img class="avatar" src="{avatar_url}" />'
            + '</div>'
            + '<div class="chat-topbar">'
            + '<a href="?p=user&id={author_id}" class="username">{author_name}</a>'
            + '<span>'
            + ' {post_time} '
            + (message.can_delete ? '(<a class="delete" href="#" title="löschen"><i class="fa fa-trash"></i></a>)' : '')
            + '</span>'
            + '</div>'
            + '<div class="chat-message-content">{content}</div>'
            + '<div class="clearfix"></div>'
            + '</div>';
        container.innerHTML += nano(messageTemplate, message);

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
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
        }
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
            while (getNumMessages() > <?= MAX_CHAT_MESSAGES ?>) {
                removeFirstMessage();
            }
        }

        if (messages.length) {
            scrollToLastMessage();
        }

    }

    function setupDeleteLinks() {

        var deleteLinks = document.getElementsByClassName('delete');
        for (var i = 0; i < deleteLinks.length; i++) {
            (function (i) {
                var link = deleteLinks[i];
                link.onclick = function () {
                    var id = this.parentNode.parentNode.parentNode.dataset.id;
                    deleteMessage(id);
                }
            })(i);
        }

    }

    function deleteMessage(id) {

        var doDelete = confirm('Willst du die Nachricht wirklich löschen?');
        if (doDelete) {
            nanoajax.ajax({
                url: 'inc/ajax/chat.php?action=delete'
                + '&id=' + id
            }, function (status) {

                if (status === 403) {
                    alert('Du darfst diese Nachricht nicht löschen.');
                    return;
                }
                else if (status !== 200) {
                    return;
                }

                var messages = getMessages();
                for (var i = 0; i < messages.length; i++) {
                    var message = messages[i];
                    if (message.dataset.id === id) {
                        message.remove();
                        break;
                    }
                }

            });
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

            if (status !== 200) {
                return;
            }
            if (response === undefined || response === '') {
                return;
            }

            var data = JSON.parse(response);
            addMessages(data.messages);
            refreshTime.innerHTML = data.refreshTime;
            setupDeleteLinks();

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

		<?php if ($_SESSION['chatKeyBehavior'] === 'enter-to-send'): ?>

        if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
            insertAtCursor(messageContent, '\n');
            if (this.value.length === this.selectionEnd) {
                this.style.height = (this.offsetHeight + 16) + 'px';
            }
        }
        else if (e.keyCode == 13) {
            e.preventDefault();
            postMessage();
        }

		<?php else: ?>

        if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
            postMessage();
        }
        else if (e.keyCode == 13) {
            if (this.value.length === this.selectionEnd) {
                this.style.height = (this.offsetHeight + 16) + 'px';
            }
        }

		<?php endif ?>

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

            var data = JSON.parse(response);
            addMessages(data.messages);
            refreshTime.innerHTML = data.refreshTime;
            setupDeleteLinks();

        });

    }

    sendButton.onclick = postMessage;

})();