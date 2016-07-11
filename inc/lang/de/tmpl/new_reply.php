<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadName ?></strong>
</p>

<h2>Beitrag schreiben</h2>

<form method="post" action="?p=new-reply&thread=<?= $threadId ?>">
	<textarea class="post-text" name="post-text" placeholder="Beitrags-Text"><?= $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Beitrag absenden</button>
</form>

