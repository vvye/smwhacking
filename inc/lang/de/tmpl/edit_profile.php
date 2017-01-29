<?php if ($isOwnProfile): ?>
	<h2>Einstellungen</h2>
<?php else: ?>
	<h2>Einstellungen: <a href="?p=user&id=<?= $userId ?>"><?= $username ?></a></h2>
<?php endif; ?>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data">

	<fieldset>
		<legend>Zugangsdaten</legend>
		<table class="form">
			<tr>
				<td>
					<h3>E-Mail-Adresse: <span>*</span></h3>
					<p>Mit dieser Adresse loggst du dich ein. Merk dir gut, wenn du sie geändert hast!</p>
				</td>
				<td>
					<label><input type="email" name="email" value="<?= $email ?>" required /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Altes Passwort:</h3>
					<p>Wenn du dein Passwort ändern möchtest, gib hier zuerst dein altes Passwort ein.</p>
				</td>
				<td>
					<label><input type="password" name="old-password" value=""></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Neues Passwort:</h3>
					<p>Wenn du dein Passwort ändern möchtest, gib hier ein neues ein (mindestens 8 Zeichen). Gib es zwei
						Mal ein, um Tippfehlern vorzubeugen.</p>
				</td>
				<td class="stacked-input">
					<label><input type="password" name="new-password" value="<?= $newPassword ?>" /></label>
					<label><input type="password" name="new-password-confirm"
								  value="<?= $newPasswordConfirm ?>" /></label>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Persönliche Daten und Einstellungen</legend>
		<table class="form">
			<tr>
				<td>
					<h3>Avatar:</h3>
					<p>Lade einen Avatar hoch (erlaubte Formate sind <em>png</em>, <em>gif</em> und <em>jpg</em>). Wenn
						das Bild größer ist als 150x150 Pixel, wird es verkleinert.</p>
				</td>
				<td>
					<div style="float: left;">
						<?php if ($hasAvatar): ?>
							<img class="avatar" src="img/avatars/<?= $userId ?>.png" alt="Avatar" />
						<?php else: ?>
							<img class="avatar" src="img/avatars/default.png" alt="Avatar" />
						<?php endif; ?>
					</div>
					<div style="padding-left: 166px;">
						<div class="custom-checkbox-group">
							<input type="checkbox" class="custom-checkbox" name="change-avatar" id="change-avatar" />
							<label class="custom-checkbox-label" for="change-avatar"> Avatar ändern</label>
						</div>
						<div id="edit-avatar">
							<input type="file" class="file-input" name="avatar" id="avatar" />
							<div class="custom-checkbox-group">
								<input type="checkbox" class="custom-checkbox" name="delete-avatar"
									   id="delete-avatar" />
								<label class="custom-checkbox-label" for="delete-avatar"> Avatar löschen</label>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<?php if ($canChangeTitle): ?>
				<tr>
					<td>
						<h3>Titel:</h3>
						<p>Wird im Forum unter deinem Rang angezeigt.</p>
					</td>
					<td>
						<label><input type="text" name="title" maxlength="255" value="<?= $title ?>" /></label>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td>
					<h3>Wohnort:</h3>
					<p>Teile den anderen Nutzern mit, woher du kommst oder wo du gerade bist.</p>
				</td>
				<td>
					<label><input type="text" name="location" maxlength="100" value="<?= $location ?>" /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Website:</h3>
					<p>Wenn du eine eigene Website hast, kannst du hier die Adresse eingeben.</p>
				</td>
				<td>
					<label><input type="url" name="website" maxlength="100" value="<?= $website ?>" /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Biografie:</h3>
					<p>Schreib ein bisschen über dich selbst.</p>
				</td>
				<td>
					<label><textarea class="bio" name="bio" id="bio"><?= $bio ?></textarea></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Signatur:</h3>
					<p>Wird unter jedem deiner Beiträge angezeigt.</p>
				</td>
				<td>
					<label><textarea class="signature" name="signature" id="signature"
									 maxlength="1024"><?= $signature ?></textarea></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Theme:</h3>
					<p>Wähle ein Theme aus, in dem die Seite für dich dargestellt wird.</p>
				</td>
				<td>
					<label>
						<select name="theme">
							<?php foreach ($themes as $theme): ?>
								<option value="<?= $theme ?>" <?= $theme == $selectedTheme ? 'selected="selected"' :
									'' ?>><?= $theme ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Benachrichtigungen aktivieren</h3>
					<p>Wähle aus, ob du per Mail benachrichtigt werden willst, wenn du eine Medaille oder private
						Nachricht erhältst oder wenn jemand in einem Thema, das du abonniert hast, einen Beitrag
						schreibt.</p>
				</td>
				<td>
					<div class="custom-checkbox-group">
						<input type="checkbox" class="custom-checkbox" name="enable-notifications"
							   id="enable-notifications" <?= $enableNotifications ? 'checked="checked"' : '' ?> />
						<label class=" custom-checkbox-label" for="enable-notifications"> Benachrichtigungen
							aktivieren</label>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Medaillen</legend>
		<table class="form">
			<tr>
				<td>
					<h3>Lieblings-Medaillen:</h3>
					<p>Du kannst bis zu <?= MAX_FAVORITE_MEDALS ?> Medaillen auswählen, auf die du am stolzesten bist.
						Sie werden im Forum unter deinem Avatar angezeigt und auf deinem Profil hervorgehoben.</p>
					<?php if ($numAwardedMedals !== 0): ?>
						<p><br />Du kannst außerdem die Reihenfolge auswählen (je höher, desto weiter vorne). Wenn du
							nicht jede Zahl genau einmal vergibst, wird die Reihenfolge automatisch von oben nach unten
							vergeben!</p>
					<?php endif; ?>
				</td>
				<td class="medals">
					<?php if ($numAwardedMedals === 0): ?>
						<em><?= MSG_USER_NO_MEDALS ?></em>
					<?php endif; ?>

					<?php foreach ($medalsByCategory as $category => $medals): ?>

						<h4><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h4>

						<?php foreach ($medals as $medal): ?>
							<div class="medal">
								<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
								<div>
									<h5><?= $medal['name'] ?></h5>
									<p><?= $medal['description'] ?></p>
								</div>
								<div class="custom-checkbox-group">
									<input type="checkbox" class="custom-checkbox" name="favorite[<?= $medal['id'] ?>]"
										   id="favorite-<?= $medal['id'] ?>" <?= array_key_exists($medal['id'], $favoriteMedals) ?
										'checked="checked"' : '' ?>/>
									<label class="custom-checkbox-label"
										   for="favorite-<?= $medal['id'] ?>">auswählen</label>
									<br />
									<label><input type="number" class="favorite-medal-rank"
												  name="favorite-rank[<?= $medal['id'] ?>]"
												  id="favorite-rank-<?= $medal['id'] ?>" min="1"
												  max="<?= MAX_FAVORITE_MEDALS ?>"
												  style="display: <?= array_key_exists($medal['id'], $favoriteMedals) ?
													  'block' : 'none' ?>"
												  value="<?= $favoriteMedals[$medal['id']] ?? '' ?>" placeholder="Rang"></label>
								</div>
							</div>
						<?php endforeach; ?>

					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Auf neue Medaillen prüfen:</h3>
					<p>Manche Medaillen werden automatisch nach bestimmten Kriterien verliehen (z.&nbsp;B. Anzahl der
						Beiträge oder Zeit seit der Registrierung). Hier kannst du prüfen, ob du schon neue Medaillen
						verdienst, und sie dir automatisch verleihen lassen.</p>
				</td>
				<td style="vertical-align: middle;">
					<a class="button" href="?p=award-automatic-medals" target="_blank"><i
								class="fa fa-external-link"></i> Auf neue Medaillen prüfen</a>
					<p>(öffnet sich in einem neuen Fenster)</p>
					<p>&nbsp;</p>
					<p>Das Prüfen kann einige Zeit dauern! Manchmal brauchst du ein bis zwei Minuten Geduld.</p>
				</td>
			</tr>
		</table>
	</fieldset>

	<?php if ($canChangePowerlevel || $canActivate): ?>
		<fieldset>
			<legend>Administration</legend>
			<table class="form">
				<?php if ($canChangePowerlevel): ?>
					<tr>
						<td>
							<h3>Powerlevel:</h3>
							<p>Moderatoren können alle Beiträge bearbeiten, Nutzer bannen und Medaillen verleihen,
								Administratoren können alle Profile bearbeiten und Foren und Medaillen verwalten.</p>
						</td>
						<td>
							<select name="powerlevel">
								<option value="0" <?= $powerlevel === 0 ? 'selected="selected"' : '' ?>>
									Normaler Nutzer
								</option>
								<option value="1" <?= $powerlevel === 1 ? 'selected="selected"' : '' ?>>
									Moderator
								</option>
								<option value="2" <?= $powerlevel === 2 ? 'selected="selected"' : '' ?>>
									Administrator
								</option>
							</select>
						</td>
					</tr>
				<?php endif ?>
				<?php if ($canActivate): ?>
					<tr>
						<td>
							<h3>Registrierung abschließen:</h3>
							<p>Neu registrierte Nutzer müssen den Link in einer E-Mail öffnen, um die Registrierung
								abzuschließen. Wenn das nicht klappen sollte, kann ein Administrator die Registrierung
								hier von Hand abschließen.</p>
						</td>
						<td>
							<a class="button"
							   href="?p=finish-registration&id=<?= $userId ?>&token=<?= $activationToken ?>"
							   target="_blank"><i class="fa fa-external-link"></i> Registrierung abschließen</a>
							<p>(öffnet sich in einem neuen Fenster)</p>
						</td>
					</tr>
				<?php endif ?>
			</table>
		</fieldset>
	<?php endif; ?>

	<input type="submit" name="submit" class="primary" value="Einstellungen speichern" />

</form>


<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript">

    var bioEditor = new CuteEdit('bio');
    var signatureEditor = new CuteEdit('signature');

    fileInput('file-input', {
        buttonText: 'Durchsuchen&hellip;',
        noFileText: 'Keine Datei ausgewählt.'
    });

    // avatar checkboxes
    var editAvatarBox = document.getElementById('edit-avatar');
    var avatarInput = document.getElementById('avatar');
    var changeAvatarCheckbox = document.getElementById('change-avatar');
    var deleteAvatarCheckbox = document.getElementById('delete-avatar');

    (changeAvatarCheckbox.onchange = function () {
        editAvatarBox.style.display = changeAvatarCheckbox.checked ? '' : 'none';
    })();

    (deleteAvatarCheckbox.onchange = function () {
        avatarInput.style.display = deleteAvatarCheckbox.checked ? 'none' : '';
    })();

    // medal checkboxes
    var medalCheckboxes = document.querySelectorAll('input[name*="favorite"]');
    var limit = <?= MAX_FAVORITE_MEDALS ?>;
    for (var i = 0; i < medalCheckboxes.length; i++) {
        (function (i) {
            medalCheckboxes[i].onchange = function () {
                var rankInputId = this.id.replace('favorite', 'favorite-rank');
                var rankInput = document.getElementById(rankInputId);
                rankInput.style.display = this.checked ? 'block' : 'none';

                var numChecked = 0;
                for (var i = 0; i < medalCheckboxes.length; i++) {
                    numChecked += medalCheckboxes[i].checked;
                }
                if (numChecked > limit) {
                    this.checked = false;
                    rankInput.style.display = 'none';
                }
            };
        })(i);
    }

</script>