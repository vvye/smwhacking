<p class="column breadcrumbs">
	<a href="?p=forums">Foren-Übersicht</a> &rarr;
	<a href="?p=forum&id=<?= $forumId ?>"><?= $forumName ?></a> &rarr;
	<strong><?= $threadName ?></strong>
</p>

<h2>Beitrag schreiben</h2>

<form method="post" action="?p=new-post&thread=<?= $threadId ?>">
	<textarea class="post-text" name="post-text" id="post-text" placeholder="Beitrags-Text"><?= $postText ?></textarea>
	<button class="primary" type="submit" name="submit">Beitrag absenden</button>
</form>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript">

	var editor = new CuteEdit('post-text');

</script>

