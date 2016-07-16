<h2>Nutzer entsperren</h2>

<p>Bitte bestätige, dass du <?= $username ?> entsperren möchtest.</p>

<form method="post" action="?p=ban&user=<?= $userId ?>&action=unban&token=<?= $token ?>">
	<a class="button primary" href="?p=user&id=<?= $userId ?>">Abbrechen</a>
	<button class="subtle" type="submit" name="submit"><?= $username ?> entsperren</button>
</form>