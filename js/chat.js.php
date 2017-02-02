<?php

require_once __DIR__ . '/../inc/config/chat.php';
require_once __DIR__ . '/../inc/config/ajax.php';

?>


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


function deactivateRefreshButton() {

    refreshButton.setAttribute('disabled', 'disabled');
    refreshIcon.classList.add('fa-spin');

}


function showCheckmark() {

    refreshIcon.classList.remove('fa-spin');
    refreshIcon.classList.remove('fa-refresh');
    refreshIcon.classList.add('fa-check');

    setTimeout(activateRefreshButton, <?= REQUEST_COOLDOWN_TIME * 1000 ?>);

}


function activateRefreshButton() {

    refreshButton.removeAttribute('disabled');
    refreshIcon.classList.remove('fa-check');
    refreshIcon.classList.add('fa-refresh');

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


messageContent.onkeyup = messageContent.onchange = function () {

    if (this.value.trim() === '') {
        sendButton.setAttribute('disabled', 'disabled');
    } else {
        sendButton.removeAttribute('disabled');
    }

};


sendButton.onclick = function () {

    nanoajax.ajax({
        url: 'inc/ajax/chat.php?action=post_message'
        + '&content=' + encodeURIComponent(messageContent.value)
        + '&last_id=' + getLastMessageId()
    }, function (status, response) {

        if (status !== 200) {
            alert('Das Senden hat nicht geklappt.');
            return;
        }

        messageContent.value = '';

        var messages = JSON.parse(response);
        addMessages(messages);

    });

};