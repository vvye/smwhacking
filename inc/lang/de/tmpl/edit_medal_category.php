<h2>Medaillen-Kategorie bearbeiten</h2>

<form action="?p=admin&s=edit-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>" method="post">

	Name: <input type="text" name="name" value="<?= $category['name'] ?>" /><br /><br />
	<input type="submit" name="submit" class="primary" value="Kategorie bearbeiten" />

</form>