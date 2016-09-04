<h2>Datei löschen</h2>

<form action="?p=files&action=delete&id=<?= $file['id'] ?>" method="post">
	<p>Bist du sicher, dass du die Datei <strong><?= $file['name'] ?></strong> löschen möchtest? Das Löschen kann nicht
		rückgängig gemacht werden.</p>
	<a class="primary button" href="?p=files">Abbrechen</a>
	<input type="submit" name="submit" class="subtle" value="&quot;<?= $file['name'] ?>&quot; löschen" />
</form>