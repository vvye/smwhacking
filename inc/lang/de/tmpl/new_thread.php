<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a>
</p>

<h2>Thema erstellen</h2>

<form method="post" action="?p=new-thread&forum=<?= $forumId ?>">
	<input type="text" name="thread-title" placeholder="Titel" value="<?=  $threadTitle ?>" />
	<textarea class="post-text" name="post-text" placeholder="Beitrags-Text"><?=  $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Thema erstellen</button>
</form>