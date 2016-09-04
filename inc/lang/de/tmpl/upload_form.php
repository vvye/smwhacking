<h2>Datei hochladen</h2>
<p>Bitte lade nur Dinge hoch, die du selbst gemacht hast, es sei denn, du hast die Erlaubnis des Autors. ROMs sind hier
	nicht erwünscht &mdash; wenn du Hacks hochladen willst, dann als Patch.</p>

<form method="post" action="?p=upload" enctype="multipart/form-data">
	<table class="upload form">
		<tr>
			<td>
				<h3>Datei</h3>
				<p>Die Datei, die du hochladen möchtest (das Dateiformat ist egal).</p>
			</td>
			<td><input class="file-input" type="file" name="uploaded-file" /></td>
		</tr>
		<tr>
			<td>
				<h3>Name</h3>
				<p>Der Name der Datei, der in der Liste erscheint.</p>
			</td>
			<td><input type="text" name="title" maxlength="50" placeholder="maximal 50 Zeichen" value="<?= $name ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<h3>Kurze Beschreibung</h3>
			</td>
			<td>
				<input type="text" name="short-description" maxlength="50" placeholder="maximal 50 Zeichen"
				       value="<?= $shortDescription ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<h3>Lange Beschreibung</h3>
			</td>
			<td>
				<textarea name="long-description" maxlength="1000" placeholder="maximal 1000 Zeichen"
				><?= $longDescription ?></textarea>
			</td>
		</tr>
	</table>
	<button class="primary" type="submit" name="submit">Datei hochladen</button>
</form>

<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript">

	fileInput('file-input', {
		buttonText: 'Durchsuchen&hellip;',
		noFileText: 'Keine Datei ausgewählt.'
	});

</script>