<h2>Neue private Nachricht</h2>

<form class="pm-form post-form" method="post" action="?p=new-pm&token=<?= $token ?>">
	<table>
		<tr>
			<td>Empfänger:</td>
			<td><label><input type="text" name="recipient" id="recipient" value="<?= $recipientName ?>" /></label></td>
		</tr>
		<tr>
			<td>Betreff:</td>
			<td><label><input type="text" name="subject" id="subject" value="<?= $subject ?>" /></label></td>
		</tr>
	</table>
	<textarea class="post-text" name="pm-text" id="pm-text" placeholder="Nachrichten-Text"><?= $pmText ?></textarea>
	<button class="primary" type="submit" name="submit">Nachricht absenden</button>
	<button type="submit" name="preview">Vorschau</button>
</form>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript">

	var editor = new CuteEdit('pm-text');

</script>