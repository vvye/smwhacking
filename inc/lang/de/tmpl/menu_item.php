<?php if ($active): ?>
<li class="active">
	<?php else: ?>
<li>
	<?php endif; ?>
	<a href="<?= $link ?>"><?= $caption ?></a>
</li>