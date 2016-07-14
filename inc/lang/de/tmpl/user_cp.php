<h2>Einstellungen</h2>

<form>

	<fieldset>
		<legend>Daten des Nutzerkontos</legend>
		<table>
			<tr>
				<td>
					<h3>E-Mail-Adresse: <span>*</span></h3>
					<p>Mit dieser Adresse loggst du dich ein. Merk dir gut, wenn du sie geändert hast!</p>
				</td>
				<td>
					<label><input type="email" name="email" value="" required /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Altes Passwort:</h3>
					<p>Wenn du dein Passwort ändern möchtest, gib hier zuerst dein altes Passwort ein.</p>
				</td>
				<td>
					<label><input type="password" name="old-password"></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Neues Passwort:</h3>
					<p>Wenn du dein Passwort ändern möchtest, gib hier ein neues ein (mindestens 8 Zeichen). Gib es zwei
						Mal ein, um Tippfehlerm
						vorzubeugen.</p>
				</td>
				<td class="stacked-input">
					<label><input type="password" name="new-password" /></label>
					<label><input type="password" name="new-password-confirm" /></label>
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
					<label><input type="file" class="file-input" name="avatar" /></label><br />
					<div class="custom-checkbox-group">
						<input type="checkbox" class="custom-checkbox" name="delete-avatar" id="delete-avatar" />
						<label class="custom-checkbox-label" for="delete-avatar"> Avatar löschen</label>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Titel:</h3>
					<p>Wird im Forum unter deinem Rang angezeigt.</p>
				</td>
				<td>
					<label><input type="text" name="title" maxlength="255" value="" /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Wohnort:</h3>
					<p>Teile den anderen Nutzern mit, woher du kommst oder wo du gerade bist.</p>
				</td>
				<td>
					<label><input type="text" name="location" maxlength="100" value="" /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Website:</h3>
					<p>Wenn du eine eigene Website hast, kannst du hier die Adresse eingeben.</p>
				</td>
				<td>
					<label><input type="url" name="website" maxlength="100" value="" /></label>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Biografie:</h3>
					<p>Schreib ein bisschen über dich selbst.</p>
				</td>
				<td>
					<label><textarea class="bio" name="bio"></textarea></label>
				</td>
			</tr>

			<tr>
				<td>
					<h3>Signatur:</h3>
					<p>Wird unter jedem deiner Beiträge angezeigt.</p>
				</td>
				<td>
					<label><textarea class="signature" name="signature" maxlength="1024"></textarea></label>
				</td>
			</tr>
		</table>
	</fieldset>

	<input type="submit" class="primary" value="Einstellungen speichern" />

</form>


<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript">

	fileInput('file-input', {
		buttonText: 'Durchsuchen&hellip;',
		noFileText: 'Keine Datei ausgewählt.'
	})

</script>