<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/register.php';
	require_once __DIR__ . '/../functions/form.php';

	do
	{
		if (isLoggedIn())
		{
			renderMessage('Du bist schon registriert.');
			break;
		}

		if (isset($_POST['submit']))
		{
			$database = getDatabase();

			$errorMessages = validateRegistrationForm($database);

			if (!empty($errorMessages))
			{
				renderErrorMessage(join('<br />', $errorMessages));
				break;
			}
			$email = htmlspecialchars(trim(getFieldValue('email')));
			$username = getFieldValue('username');
			$passwordHash = password_hash(getFieldValue('password'), PASSWORD_DEFAULT);

			startRegistration($email, $username, $passwordHash, $database);

			echo '<div class="message">Alles klar! Wir haben dir eine Mail geschickt. Klicke auf den Link in der Mail, um die Registrierung abzuschließen.</div>';
		}
		?>
		<h2>Registrieren</h2>

		<form action="?p=register" method="post">
			<table>
				<tr>
					<td>
						<h3>E-Mail-Adresse:</h3>
						<p>An diese Adresse schicken wir dir eine Mail, mit der du die Anmeldung bestätigen kannst. Gib sie zwei
							Mal ein, um Tippfehler auszuschließen.</p>
					</td>
					<td class="stacked-input">
						<label><input type="email" name="email" value="<?php echo getFieldValue('email'); ?>"
						              required="required" /></label>
						<label><input type="email" name="email-confirm" value="<?php echo getFieldValue('email-confirm'); ?>"
						              required="required" /></label>
					</td>
				</tr>
				<tr>
					<td>
						<h3>Nutzername:</h3>
						<p>Der Name, unter dem du im Forum bekannt sein willst. (Zwischen 3 und 30 Zeichen; erlaubt sind nur
							Buchstaben, Zahlen und Leerzeichen.)</p>
					</td>
					<td><label><input type="text" name="username" value="<?php echo getFieldValue('username'); ?>"
					                  required="required" /></label></td>
				</tr>
				<tr>
					<td>
						<h3>Passwort:</h3>
						<p>Wähle ein sicheres Passwort (mindestens 8 Zeichen). Gib es zwei Mal ein, um Tippfehler
							auszuschließen.</p>
					</td>
					<td class="stacked-input">
						<label><input type="password" name="password" value="<?php echo getFieldValue('password'); ?>"
						              required="required" /></label>
						<label><input type="password" name="password-confirm"
						              value="<?php echo getFieldValue('password-confirm'); ?>" required="required" /></label>
					</td>
				</tr>
				<tr>
					<td>
						<h3>Wofür steht die Abkürzung "SMW"?</h3>
						<p>Beanwtorte die Frage, um zu beweisen, dass du ein Mensch bist.<br />
							Wenn du Probleme beim Beantworten der Frage hast, wende dich an info@smwhacking.de.</p>
					</td>
					<td>
						<label><input type="text" name="security-answer" value="<?php echo getFieldValue('security-answer'); ?>"
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
		<?php
	}
	while (false);