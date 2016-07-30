<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadTitle ?></strong>
</p>

<h2>Beitrag bearbeiten</h2>

<form method="post" action="?p=edit-post&id=<?= $postId ?>&token=<?= $token ?>">
	<?php if ($isThread): ?>
		<input type="text" class="thread-title" name="thread-title" placeholder="Titel" value="<?= $threadTitle ?>" />
	<?php endif; ?>
	<textarea class="post-text" name="post-text" id="post-text" placeholder="Beitrags-Text"><?= $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Beitrag bearbeiten</button>
	<button type="submit" name="preview">Vorschau</button>
</form>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript">

	var editor = new CuteEdit('post-text');

</script>