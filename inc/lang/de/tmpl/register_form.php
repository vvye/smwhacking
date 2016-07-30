<h2>Registrieren</h2>

<form action="?p=register" method="post">
	<table class="form">
		<tr>
			<td>
				<h3>E-Mail-Adresse:</h3>
				<p>An diese Adresse schicken wir dir eine Mail, mit der du die Anmeldung bestätigen kannst. Gib sie zwei
					Mal ein, um Tippfehler auszuschließen.</p>
			</td>
			<td class="stacked-input">
				<label><input type="email" name="email" value="<?= $email ?>"
				              required="required" /></label>
				<label><input type="email" name="email-confirm" value="<?= $emailConfirm ?>"
				              required="required" /></label>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Nutzername:</h3>
				<p>Der Name, unter dem du im Forum bekannt sein willst. (Zwischen 3 und 30 Zeichen; erlaubt sind nur
					Buchstaben, Zahlen und Leerzeichen.)</p>
			</td>
			<td><label><input type="text" name="username" value="<?= $username ?>"
			                  required="required" /></label></td>
		</tr>
		<tr>
			<td>
				<h3>Passwort:</h3>
				<p>Wähle ein sicheres Passwort (mindestens 8 Zeichen). Gib es zwei Mal ein, um Tippfehlern
					vorzubeugen.</p>
			</td>
			<td class="stacked-input">
				<label><input type="password" name="password" value="<?= $password ?>"
				              required="required" /></label>
				<label><input type="password" name="password-confirm"
				              value="<?= $passwordConfirm ?>" required="required" /></label>
			</td>
		</tr>
		<tr>
			<td>
				<h3><?= SECURITY_QUESTION ?></h3>
				<p>Beanwtorte die Frage, um zu beweisen, dass du ein Mensch bist.<br />
					Wenn du Probleme beim Beantworten der Frage hast, wende dich an info@smwhacking.de.</p>
			</td>
			<td>
				<label><input type="text" name="security-answer" value="<?= $securityAnswer ?>"
				              required="required" /></label>
			</td>
		</tr>
		<tr>
			<td class="final-action" colspan="2">
				<button class="primary" type="submit" name="submit">Registrieren</button>
			</td>
		</tr>
	</table>
</form>