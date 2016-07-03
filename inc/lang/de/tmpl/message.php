<?php if ($type === 'success'): ?>
<div class="message success">
	<?php elseif ($type === 'error'): ?>
	<div class="message error">
		<?php else: ?>
		<div class="message">
			<?php endif; ?>
			<?= $message ?>
		</div>
