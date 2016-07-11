<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadName ?></strong>
</p>

<h2>Beitrag bearbeiten</h2>

<form method="post" action="?p=edit-reply&id=<?= $postId ?>">
	<textarea class="post-text" name="post-text" placeholder="Beitrags-Text"><?= $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Beitrag bearbeiten</button>
</form>