<h2>Medaillen-Kategorie löschen: <?= $category['name'] ?></h2>

<p>Möchtest du die Kategorie <?= $category['name'] ?> endgültig löschen? Alle Medaillen in dieser Kategorie werden
	vorher in eine andere Kategorie verschoben.</p>

<form action="?p=admin&s=delete-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>" method="post">

	Kategorie, in die bestehende Medaillen verschoben werden sollen:

	<select name="replacement">
		<?php foreach ($replacements as $replacement): ?>
			<option value="<?= $replacement['id'] ?>"><?= $replacement['name'] ?></option>
		<?php endforeach; ?>
	</select>
	<br /><br />
	<a class="button primary" href="?p=admin&s=manage-medal-categories">Abbrechen</a>
	<input type="submit" name="submit" class="subtle" value="Kategorie löschen" />

</form>