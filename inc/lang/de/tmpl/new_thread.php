<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a>
</p>

<h2>Thema erstellen</h2>

<form method="post" action="?p=new-thread&forum=<?= $forumId ?>">
	<input type="text" class="thread-title" name="thread-title" placeholder="Titel" value="<?=  $threadTitle ?>" />
	<textarea class="post-text" name="post-text" id="post-text" placeholder="Beitrags-Text"><?= $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Thema erstellen</button>
</form>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript">

    var editor = new CuteEdit('post-text');

</script>