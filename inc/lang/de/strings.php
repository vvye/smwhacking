<?php

	define('POWERLEVEL_DESCRIPTIONS', [
		0 => 'Normaler Nutzer',
		1 => 'Moderator',
		2 => 'Administrator'
	]);


	define('MSG_FINISH_REGISTRATION_GENERAL_FAILURE',
		'Das Abschließen der Registrierung hat nicht geklappt. '
		. 'Hast du diese Seite wirklich aus einer E-Mail heraus aufgerufen?<br />'
		. 'Wenn du Probleme beim Registrieren hast, wende dich an info@smwhacking.de.');


	define('MSG_FINISH_REGISTRATION_NO_USER',
		'Das Abschließen der Registrierung hat nicht geklappt &mdash; '
		. 'Entweder stimmt der Link nicht, oder der Nutzer ist schon registriert.<br />'
		. 'Wenn du Probleme beim Registrieren hast, wende dich an info@smwhacking.de.');


	define('MSG_FINISH_REGISTRATION_SUCCESS',
		'Alles klar, die Registrierung ist abgeschlossen!<br />'
		. 'Du kannst dich jetzt mit deiner E-Mail-Adresse und deinem Passwort <a href="?p=login">einloggen</a>.');


	define('MSG_ALREADY_LOGGED_IN',
		'Du bist schon eingeloggt.');


	define('MSG_LOGIN_FAILURE',
		'Das Einloggen hat nicht geklappt. Stimmen E-Mail-Adresse und Passwort?<br />'
		. 'Wenn das Problem weiterhin auftritt, wende dich an info@smwhacking.de.');


	define('MSG_USER_DOESNT_EXIST',
		'Diesen Nutzer gibt es nicht.');


	define('MSG_ALREADY_REGISTERED',
		'Du bist schon registriert.');


	define('MSG_USERNAME_TAKEN',
		'Dieser Nutzername ist schon registriert.');


	define('MSG_EMAIL_TAKEN',
		'Diese E-Mail-Adresse ist schon registriert.');


	define('MSG_WRONG_SECURITY_ANSWER',
		'Die Antwort auf die Sicherheitsfrage stimmt nicht.');


	define('MSG_PASSWORDS_DONT_MATCH',
		'Die beiden Passwörter stimmen nicht überein.');


	define('MSG_PASSWORD_PENIS',
		'Komm erst mal in die Pubertät.');


	define('MSG_PASSWORD_TOO_SHORT',
		'Das Passwort ist zu kurz.');


	define('MSG_INVALID_USERNAME',
		'Der Nutzername ist nicht erlaubt.');


	define('MSG_EMAILS_DONT_MATCH',
		'Die E-Mail-Adressen stimmen nicht überein.');


	define('MSG_EMAIL_MISSING',
		'Gib eine E-Mail-Adresse ein.');


	define('MSG_REGISTER_SUCCESS',
		'Alles klar! Wir haben dir eine Mail geschickt. '
		. 'Klicke auf den Link in der Mail, um die Registrierung abzuschließen.'
		. '<br />Wenn du keine Mail bekommen hast, wende dich an info@smwhacking.de.');


	define('MSG_NONE',
		'keiner');


	define('MSG_MARK_READ_NOT_LOGGED_IN',
		'Du kannst Foren nur als gelesen markieren, wenn du eingeloggt bist.');


	define('MSG_MARK_READ_SUCCESS',
		'Dieses Forum wurde als gelesen markiert.');


	define('MSG_MARK_ALL_READ_SUCCESS',
		'Alle Foren wurden als gelesen markiert.');


	define('MSG_MARK_READ_ERROR',
		'Das Markieren hat nicht geklappt.');


	define('MSG_REGISTRATION_EMAIL_SUBJECT',
		'smwhacking.de - Registrierung');


	define('SECURITY_QUESTION',
		'Wofür steht die Abkürzung "SMW"?');


	define('SECURITY_ANSWER',
		'supermarioworld');


	define('CAPTION_HOME',
		'Startseite');


	define('CAPTION_ABOUT',
		'Was ist SMW-Hacken?');


	define('CAPTION_FORUM',
		'Forum');


	define('CAPTION_CHAT',
		'Chat');


	define('CAPTION_DISCORD',
		'Discord');


	define('CAPTION_FILES',
		'Dateiablage');


	define('CAPTION_SECRET',
		'XXX');


	define('CAPTION_USERS',
		'Mitglieder');


	define('MSG_NEW',
		'NEU');


	define('MSG_OFF',
		'OFF');


	define('MSG_NEW_POST_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Beiträge schreiben zu können.');


	define('MSG_NEW_POST_BANNED',
		'Du darfst keine Beiträge schreiben.');


	define('MSG_NEW_POST_NOT_ALLOWED',
		'Du darfst in diesem Thema keine Beiträge schreiben.');


	define('MSG_NEW_THREAD_NOT_ALLOWED',
		'Du darfst in diesem Forum keine Themen erstellen.');


	define('MSG_THREAD_DOESNT_EXIST',
		'Dieses Thema gibt es nicht.');


	define('MSG_POST_TEXT_EMPTY',
		'Der Beitrags-Text darf nicht leer sein.');


	define('MSG_THREAD_TITLE_EMPTY',
		'Das Thema muss einen Titel haben.');


	define('MSG_NEW_POST_SUCCESS',
		'Der Beitrag wurde abgeschickt!');


	define('MSG_NEW_THREAD_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Themen erstellen zu können.');


	define('MSG_NEW_THREAD_BANNED',
		'Du darfst keine Themen erstellen.');


	define('MSG_FORUM_DOESNT_EXIST',
		'Dieses Forum gibt es nicht.');


	define('MSG_NEW_THREAD_SUCCESS',
		'Das Thema wurde erstellt!');


	define('MSG_GENERAL_ERROR',
		'Irgendwas ist schiefgelaufen.');


	define('MSG_NOT_ALLOWED',
		'Du darfst diese Aktion nicht ausführen.');


	define('MSG_PARAMETERS_MISSING',
		'Einige nötige Parameter wurden nicht angegeben.');


	define('MSG_UNKNOWN_ACTION',
		'Unbekannte Aktion.');


	define('MSG_THREAD_ALREADY_CLOSED',
		'Das Thema ist schon geschlossen.');


	define('MSG_THREAD_ALREADY_OPEN',
		'Das Thema ist schon offen.');


	define('MSG_THREAD_ALREADY_STICKIED',
		'Das Thema ist schon als wichtig markiert.');


	define('MSG_THREAD_ALREADY_UNSTICKIED',
		'Das Thema war noch nicht als wichtig markiert.');


	define('MSG_CLOSE_THREAD_SUCCESS',
		'Das Thema wurde geschlossen.');


	define('MSG_OPEN_THREAD_SUCCESS',
		'Das Thema wurde geöffnet.');


	define('MSG_STICKY_THREAD_SUCCESS',
		'Das Thema wurde als wichtig markiert.');


	define('MSG_UNSTICKY_THREAD_SUCCESS',
		'Das Thema wurde von der Liste der wichtigen Themen abgelöst.');


	define('MSG_EDIT_POST_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Beiträge bearbeiten zu können.');


	define('MSG_EDIT_POST_BANNED',
		'Du darfst keine Beiträge bearbeiten.');


	define('MSG_POST_DOESNT_EXIST',
		'Der Beitrag existiert nicht.');


	define('MSG_EDIT_POST_NOT_ALLOWED',
		'Du darfst diesen Beitrag nicht bearbeiten.');


	define('MSG_EDIT_POST_SUCCESS',
		'Der Beitrag wurde bearbeitet.');


	define('MSG_DELETE_POST_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Beiträge löschen zu können.');


	define('MSG_DELETE_POST_BANNED',
		'Du darfst keine Beiträge löschen.');


	define('MSG_DELETE_POST_NOT_ALLOWED',
		'Du darfst diesen Beitrag nicht löschen.');


	define('MSG_DELETE_POST_SUCCESS',
		'Der Beitrag wurde gelöscht.');


	define('MSG_DELETE_THREAD_SUCCESS',
		'Das Thema wurde gelöscht.');


	define('MSG_BAD_TOKEN',
		'Das Token stimmt nicht.');


	define('MSG_VIEW_POST_NOT_ALLOWED',
		'Dieser Beitrag ist für dich nicht sichtbar.');


	define('MSG_VIEW_FORUM_NOT_ALLOWED',
		'Dieses Forum ist für dich nicht sichtbar.');


	define('MSG_VIEW_THREAD_NOT_ALLOWED',
		'Dieses Thema ist für dich nicht sichtbar.');


	define('MSG_USERCP_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um dein Profil bearbeiten zu können.');


	define('MSG_USERCP_BANNED',
		'Du darfst dein Profil nicht bearbeiten.');


	define('MSG_USERCP_NOT_ADMIN',
		'Du darfst nur dein eigenes Profil bearbeiten.');


	define('MSG_WRONG_PASSWORD',
		'Das Passwort stimmt nicht.');


	define('MSG_USERCP_SUCCESS',
		'Die Einstellungen wurden gespeichert.');


	define('MSG_AVATAR_GENERAL_ERROR',
		'Das Hochladen des Avatars hat nicht geklappt.');


	define('MSG_AVATAR_WRONG_FILE_FORMAT',
		'Der Avatar hat ein falsches Dateiformat.');


	define('MSG_BAN_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Nutzer sperren zu können.');


	define('MSG_BAN_NOT_MODERATOR',
		'Nur Moderatoren und Administratoren können Nutzer sperren.');


	define('MSG_BAN_CANT_BAN_YOURSELF',
		'Du kannst dich nicht selbst sperren.');


	define('MSG_BAN_SUCCESS',
		'Der Nutzer wurde gesperrt.');


	define('MSG_BAN_USER_ALREADY_BANNED',
		'Der Nutzer ist schon gesperrt.');


	define('MSG_UNBAN_USER_ALREADY_UNBANNED',
		'Der Nutzer wurde gesperrt.');


	define('MSG_UNBAN_SUCCESS',
		'Der Nutzer wurde entsperrt.');


	define('MSG_USER_NO_MEDALS',
		'Dieser Nutzer hat noch keine Medaillen.');


	define('MSG_AWARD_MEDAL_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Medaillen verwalten zu können.');


	define('MSG_AWARD_MEDAL_NOT_ALLOWED',
		'Du darfst keine Medaillen verwalten.');


	define('MSG_AWARD_MEDAL_SUCCESS',
		'Die Änderungen wurden gespeichert.');


	define('MSG_AUTOMATIC_MEDALS_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um auf neue Medaillen prüfen zu können.');


	define('MSG_AUTOMATIC_MEDALS_CHECKING',
		'Prüfe Medaille "{{name}}"&hellip;<br />');


	define('MSG_AUTOMATIC_MEDALS_NONE_AWARDED',
		'Es gibt keine neuen Medaillen zu verleihen.');


	define('MSG_AUTOMATIC_MEDALS_AWARDED_INCLUDING_YOU',
		'Es wurden neue Medaillen an {{num}} Nutzer verliehen. Du bist auch darunter!');


	define('MSG_AUTOMATIC_MEDALS_AWARDED',
		'Es wurden neue Medaillen an {{num}} Nutzer verliehen. Du bist leider nicht darunter.');


	define('MSG_AUTOMATIC_MEDALS_TOO_SOON',
		'Es darf nur einmal am Tag auf neue Medaillen geprüft werden - möglich ist es wieder am {{time}}.');


	define('BBCODE_QUOTE',
		'Zitat');


	define('BBCODE_QUOTE_BY',
		'Zitat von');


	define('BBCODE_CODE',
		'Code');


	define('BBCODE_SPOILER',
		'Spoiler');


	define('BBCODE_SPOILER_SHOW',
		'anzeigen');


	define('BBCODE_SPOILER_HIDE',
		'verbergen');


	define('MSG_MOVE_THREAD_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Themen verschieben zu können.');


	define('MSG_MOVE_THREAD_BANNED',
		'Du darfst keine Themen verschieben.');


	define('MSG_MOVE_THREAD_NOT_ALLOWED',
		'Du darfst dieses Thema nicht verschieben.');


	define('MSG_MOVE_THREAD_SAME_FORUM',
		'Das Thema befindet sich schon in diesem Forum.');


	define('MSG_MOVE_THREAD_SUCCESS',
		'Das Thema wurde verschoben.');


	define('MSG_WATCH_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um Themen abonnieren zu können.');


	define('MSG_THREAD_NOT_WATCHED',
		'Du hast dieses Thema noch nicht abonniert.');


	define('MSG_THREAD_ALREADY_WATCHED',
		'Du hast dieses Thema schon abonniert.');


	define('MSG_WATCH_SUCCESS',
		'Du hast das Thema abonniert. Du wirst über neue Beiträge in diesem Thema per Mail benachrichtigt.');


	define('MSG_UNWATCH_SUCCESS',
		'Du hast dein Abo für das Thema gekündigt.');


	define('NOTIFICATION_MEDAL_AWARD_SUBJECT',
		'Dir wurde eine Medaille verliehen!');


	define('NOTIFICATION_AUTOMATIC_MEDAL_AWARD_BODY',
		'Dir wurde die Medaille "{{medalName}}" verliehen!');


	define('NOTIFICATION_MEDAL_STATUS_CHANGED_SUBJECT',
		'Neues bei deinen Medaillen!');


	define('MSG_PM_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um private Nachrichten lesen zu können.');


	define('MSG_PM_NOT_FOUND',
		'Diese Nachricht gibt es nicht.');


	define('MSG_PM_NOT_ALLOWED',
		'Du darfst diese Nachricht nicht anzeigen.');


	define('MSG_NEW_PM_NOT_LOGGED_IN',
		'Du musst eingeloggt sein, um private Nachrichten schreiben zu können.');


	define('MSG_PM_TEXT_EMPTY',
		'Der Nachrichten-Text darf nicht leer sein.');


	define('MSG_PM_SUBJECT_EMPTY',
		'Der Betreff darf nicht leer sein.');


	define('MSG_PM_UNKNOWN_RECIPIENT',
		'Den Empfänger gibt es nicht.');


	define('MSG_NEW_PM_SUCCESS',
		'Die Nachricht wurde versendet!');


	define('NOTIFICATION_NEW_PM',
		'Neue private Nachricht erhalten!');


	define('MSG_MANAGE_RANKS_SUCCESS',
		'DIe Änderungen wurden gespeichert.');


	define('MEDAL_MANUAL',
		'manuell');


	define('MEDAL_POST_COUNT',
		'Anzahl Beiträge');


	define('MEDAL_REGISTRATION_TIME',
		'Registrierungszeit');


	define('MSG_ENTER_NAME_AND_DESCRIPTION',
		'Gib einen Namen und eine Beschreibung ein.');


	define('MSG_MEDAL_CREATED',
		'Die Medaille wurde erstellt.');


	define('MSG_MEDAL_EDITED',
		'Die Medaille wurde bearbeitet.');


	define('MSG_MEDAL_DELETED',
		'Die Medaille wurde gelöscht. Den {{NUM}} Nutzern, die sie besaßen, wurde sie aberkannt.');


	define('MSG_MEDAL_DOESNT_EXIST',
		'Diese Medaille gibt es nicht.');


	define('MSG_MEDAL_CATEGORY_DOESNT_EXIST',
		'Diese Kategorie gibt es nicht.');


	define('MSG_NAME_EMPTY',
		'Gib einen Namen ein.');


	define('MSG_CATEGORY_EDITED',
		'Die Kategorie wurde bearbeitet.');


	define('MSG_CATEGORY_DELETED',
		'Die Kategorie wurde gelöscht.');


	define('MSG_INVALID_REPLACEMENT',
		'Gib eine gültige Kategorie zum Verschieben an.');


	define('MSG_FILE_DOESNT_EXIST',
		'Diese Datei scheint es nicht zu geben.');


	define('MSG_FILE_DELETED',
		'Die Datei wurde gelöscht.');


	define('MSG_FILE_NAME_MISSING',
		'Bitte gib einen Namen für die Datei ein.');


	define('MSG_NO_FILE_SELECTED',
		'Bitte wähle eine Datei zum Hochladen aus.');


	define('MSG_SHORT_DESCRIPTION_MISSING',
		'Bitte gib eine kurze Beschreibung für die Datei ein.');


	define('MSG_UPLOAD_GENERAL_ERROR',
		'Beim Hochladen ist ein Fehler aufgetreten.');


	define('MSG_FILE_UPLOADED',
		'Die Datei wurde hochgeladen.');


	define('MSG_THREAD',
		'Thema');


	define('MSG_REPLIES',
		'Antworten');


	define('MSG_VIEWS',
		'Zugriffe');


	define('MSG_LAST_POST',
		'Letzter Beitrag');


	define('MSG_NO_THREADS',
		'In diesem Forum gibt es noch keine Themen.');


	define('MSG_STICKY',
		'Wichtig:');


	define('MSG_BY',
		'von');


	define('MSG_CREATED_BY',
		'erstellt von');


	define('MSG_AT',
		'am');


	define('MSG_CATEGORY_ADDED',
		'Die Kategorie wurde hinzugefügt.');


	define('MSG_SECRET_MEDAL_DESCRIPTION',
		'<em>[geheim!]</em>');


	define('MSG_INBOX_EMPTY',
		'Dein Posteingang ist leer.');


	define('MSG_OUTBOX_EMPTY',
		'Dein Postausgang ist leer.');


	define('MSG_DATABASE_ERROR',
		'Die Verbindung zur Datenbank ist fehlgeschlagen. Wenn das Problem weiterhin auftritt, wende dich an
		info@smwhacking.de.');
