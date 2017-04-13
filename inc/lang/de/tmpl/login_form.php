<h2>Einloggen</h2>

<form action="session.php?action=login" method="post">
	<table class="login form">
		<tr>
			<td>E-Mail-Adresse:</td>
			<td><label><input type="email" name="email" /></label></td>
		</tr>
		<tr>
			<td>Passwort:</td>
			<td><label><input type="password" name="password" /></label></td>
		</tr>
		<tr>
			<td colspan="2">
				Wenn du deine Zugangsdaten vergessen hast, wende dich an
				<a href="mailto:info@smwhacking.de">info@smwhacking.de</a>.
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="custom-checkbox-group">
					<input type="checkbox" class="custom-checkbox" name="remember-me" id="remember-me" />
					<label class="custom-checkbox-label" for="remember-me">beim nächsten Mal automatisch
						einloggen<br />(<em>auf eigene Gefahr &mdash; eventuelles Sicherheitsrisiko!</em>)</label>
				</div>
			</td>
		</tr>
		<tr>
			<td class="final-action" colspan="2">
				<button class="primary" type="submit" name="submit">Einloggen</button>
			</td>
		</tr>
	</table>
</form>