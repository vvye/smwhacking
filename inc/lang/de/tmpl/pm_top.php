<?php if ($inbox): ?>
	<h2>Private Nachrichten: Posteingang</h2>
<?php else: ?>
	<h2>Private Nachrichten: Postausgang</h2>
<?php endif; ?>

<p></p>
<a class="primary button" href="?p=new-pm"><i class="fa fa-envelope"></i> Nachricht schreiben</a>
<?php if ($inbox): ?>
	<a class="button" href="?p=pm&outbox"><i class="fa fa-send"></i> Postausgang ansehen</a>
<?php else: ?>
	<a class="button" href="?p=pm"><i class="fa fa-inbox"></i> Posteingang ansehen</a>
<?php endif; ?>
