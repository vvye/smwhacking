<table class="forum thread-list">
	<thead>
	<tr>
		<th class="thread" colspan="3"><?= MSG_THREAD ?></th>
		<th class="num-replies"><?= MSG_REPLIES ?></th>
		<th class="num-views"><?= MSG_VIEWS ?></th>
		<th class="last-post"><?= MSG_LAST_POST ?></th>
	</tr>
	</thead>
	<tbody>

	<?php if ($numTotalThreads === 0): ?>
		<tr>
			<td colspan="5" style="text-align: center;">
				<em><?= MSG_NO_THREADS ?></em>
			</td>
		</tr>
	<?php endif; ?>

	<?php foreach ($threads as $thread): ?>

		<?php if ($thread['lastSticky']): ?>
			<tr class="last sticky">
		<?php elseif ($thread['sticky']): ?>
			<tr class="sticky">
		<?php else: ?>
			<tr>
		<?php endif; ?>
		<td class="status">
			<?php if ($thread['closed']): ?>
				<span class="closed"><?= MSG_OFF ?></span><br />
			<?php endif; ?>
			<?php if ($thread['unread']): ?>
				<span class="new"><?= MSG_NEW ?></span><br />
			<?php endif; ?>
		</td>
		<td class="thread">
			<h3 id="thread-<?= $thread['id'] ?>">
				<?php if ($thread['sticky']): ?>
					<?= MSG_STICKY ?>
				<?php endif; ?>
				<a href="?p=thread&id=<?= $thread['id'] ?>"><?= $thread['name'] ?></a>
			</h3>
			<p><?= MSG_CREATED_BY ?> <a href="?p=user&id=<?= $thread['authorId'] ?>"><?= $thread['authorName'] ?></a>
				<?= MSG_AT ?> <?= $thread['creationTime'] ?></p>
		</td>
		<td class="page-selection">
			<?php if ($thread['numPages'] > 1): ?>
				<label>
					<select class="small" onchange="window.location.href = this.value;">
						<option value="?p=thread&id=<?= $thread['id'] ?>">Seite&hellip;</option>
						<?php for ($page = 1; $page <= $thread['numPages']; $page++): ?>
							<option value="?p=thread&id=<?= $thread['id'] ?>&page=<?= $page ?>"><?= $page ?></option>
						<?php endfor ?>
					</select>
				</label>
			<?php endif ?>
		</td>
		<td class="num-replies"><?= $thread['numReplies'] ?></td>
		<td class="num-views"><?= $thread['numViews'] ?></td>
		<td class="last-post">
			<?php if ($thread['lastPost'] === null): ?>
				<em><?= MSG_NONE ?></em>
			<?php else: ?>
				<?= MSG_BY ?> <a href="?p=user&id=<?= $thread['lastPost']['author_id'] ?>">
					<?= $thread['lastPost']['author_name'] ?>
				</a>
				<a href="?p=thread&id=<?= $thread['lastPost']['thread_id'] ?>&page=<?= $thread['lastPostPage'] ?>#post-<?= $thread['lastPost']['id'] ?>"
				   title="Letzten Post anzeigen">
					<i class="fa fa-arrow-right"></i>
				</a>
				<p><?= date(DEFAULT_DATE_FORMAT, $thread['lastPost']['post_time']) ?></p>
			<?php endif; ?>
		</td>
		</tr>

	<?php endforeach; ?>

	</tbody>
</table>


<script type="text/javascript" src="js/nanoajax.min.js"></script>
<script type="text/javascript">

    var threadIds = [
		<?php foreach ($threads as $thread): ?>
		<?= $thread['id'] ?>,
		<?php endforeach; ?>
    ];

    for (var i = 0; i < threadIds.length; i++) {
        var threadId = threadIds[i];
        addFirstUnreadPostLink(threadId);
    }

    function addFirstUnreadPostLink(threadId) {

        nanoajax.ajax({
            url: 'inc/ajax/thread.php?action=first_unread_post&thread=' + threadId
        }, function (status, response) {

            if (status !== 200) {
                return;
            }

            var post = JSON.parse(response);
            if (!post.id) {
                return;
            }

            document.getElementById('thread-' + threadId).innerHTML +=
                '<a href="?p=thread&id=' + threadId + '&page=' + post.page + '#post-' + post.id + '"'
                + ' title="Ersten ungelesen Post anzeigen">'
                + '<i class="fa fa-arrow-right"></i>'
                + '</a>';

        });
    }


</script>