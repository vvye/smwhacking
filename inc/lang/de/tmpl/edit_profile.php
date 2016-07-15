<?php if ($isOwnProfile): ?>
	<h2>Einstellungen</h2>
<?php else: ?>
	<h2>Einstellungen: <a href="?p=user&id=<?= $userId ?>"><?= $username ?></a></h2>
<?php endif; ?>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data">

	<fieldset>
		<legend>Zugangsdaten</legend>
		<table>
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
		<legend>Persönliche Daten</legend>
		<table>
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
					<div style="padding-left: 150px;">
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
					<label><textarea class="bio" name="bio"><?= $bio ?></textarea></label>
				</td>
			</tr>

			<tr>
				<td>
					<h3>Signatur:</h3>
					<p>Wird unter jedem deiner Beiträge angezeigt.</p>
				</td>
				<td>
					<label><textarea class="signature" name="signature"
					                 maxlength="1024"><?= $signature ?></textarea></label>
				</td>
			</tr>
		</table>
	</fieldset>

	<?php if ($canChangePowerlevel): ?>
		<fieldset>
			<legend>Administration</legend>
			<table>
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
			</table>
		</fieldset>
	<?php endif; ?>

	<input type="submit" name="submit" class="primary" value="Einstellungen speichern" />

</form>


<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript">

	fileInput('file-input', {
		buttonText: 'Durchsuchen&hellip;',
		noFileText: 'Keine Datei ausgewählt.'
	});

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

</script>