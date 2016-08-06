<?php if ($numPms === 0): ?>
	<em>Dein Posteingang ist leer.</em>
<?php else: ?>
	<table class="pms">
		<thead>
		<tr>
			<th class="status"></th>
			<th class="subject">Betreff</th>
			<th class="author">Absender</th>
			<th class="send-time">gesendet am</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($pms as $pm): ?>
			<?php if ($pm['unread']): ?>
				<tr class="unread">
			<?php else: ?>
				</tr>
			<?php endif; ?>
			<td class="status">
				<?php if ($pm['unread']): ?>
					<span class="new"><?= MSG_NEW ?></span>
				<?php endif; ?>
			</td>
			<td class="subject"><a href="?p=pm&id=<?= $pm['id'] ?>"><?= $pm['subject'] ?></a></td>
			<td class="author"><a href="?p=user&id=<?= $pm['author_id'] ?>"><?= $pm['author_name'] ?></a></td>
			<td class="send-time"><?= date(DEFAULT_DATE_FORMAT, $pm['send_time']) ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
