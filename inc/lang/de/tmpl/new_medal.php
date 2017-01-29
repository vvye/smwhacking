<h2>Medaille erstellen</h2>

<form action="?p=admin&s=new-medal&token=<?= $token ?>" method="post" enctype="multipart/form-data">
	<table class="form">
		<tr>
			<td><h3>Name:</h3></td>
			<td><label><input type="text" name="name" value="<?= $name ?>" /></label></td>
		</tr>
		<tr>
			<td><h3>Beschreibung:</h3></td>
			<td><label><input type="text" name="description" value="<?= $description ?>" /></label></td>
		</tr>
		<tr>
			<td><h3>Kategorie:</h3></td>
			<td>
				<label>
					<select name="category">
						<?php foreach ($categories as $category): ?>
							<option
									value="<?= $category['id'] ?>" <?= ($categoryId === $category['id']) ?
								'selected="selected"' : '' ?>><?= $category['name'] ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</td>
		</tr>
		<tr>
			<td><h3>Verleihung:</h3></td>
			<td>
				<label>
					<select name="award-condition">
						<?php foreach ($awardConditions as $name => $caption): ?>
							<option
									value="<?= $name ?>" <?= ($awardCondition === $name) ? 'selected="selected"' :
								'' ?>><?= $caption ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Wert:</h3>
				<p>Der Wert, ab dem die Medaille verliehen wird &mdash; nur nötig, wenn die Verleihung nicht manuell
					erfolgt.</p>
				<p>Die Registrierungszeit in Sekunden angeben!</p>
			</td>
			<td>
				<label><input type="number" name="value" value="<?= $value ?>" /></label>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Bild:</h3>
				<p>Das Bild, das für diese Medaille verwendet werden soll. Wenn du ein neues Bild hochlädst, wird ein
					Bild mit demselben Dateinamen gegebenenfalls überschrieben.</p>
			</td>
			<td>
				<label>
					<select name="image-filename">
						<?php foreach ($imageFilenames as $filename): ?>
							<option
									value="<?= $filename ?>" <?= ($filename === $imageFilename) ?
								'selected="selected"' : '' ?>><?= $filename ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<div class="custom-checkbox-group">
					<input class="custom-checkbox" type="checkbox" name="upload-image" id="upload-image" />
					<label class="custom-checkbox-label" for="upload-image"> neues Bild hochladen</label>
				</div>
				<div id="image-upload">
					<input type="file" class="file-input" name="medal-image" />
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Geheim:</h3>
				<p>Geheime Medaillen werden nur den Nutzern angezeigt, die sie bereits haben.</p>
			</td>
			<td>
				<label>
					<div class="custom-checkbox-group">
						<input class="custom-checkbox" type="checkbox" name="secret" id="secret" <?= $secret ?
							'checked="checked"' : '' ?> />
						<label class="custom-checkbox-label" for="secret"> geheim</label>
					</div>
				</label>
			</td>
		</tr>
	</table>
	<button class="primary" type="submit" name="submit">Medaille erstellen</button>
</form>

<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript">

    fileInput('file-input', {
        buttonText: 'Durchsuchen&hellip;',
        noFileText: 'Keine Datei ausgewählt.'
    });

    var uploadImageCheckbox = document.getElementById('upload-image');
    (uploadImageCheckbox.onclick = function () {
        document.getElementById('image-upload').style.display = uploadImageCheckbox.checked ? '' : 'none';
    })();

</script>