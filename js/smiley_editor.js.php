<?php

header('Content-type: text/javascript');

require_once __DIR__ . '/../inc/functions/smileys.php';
require_once __DIR__ . '/../inc/functions/database.php';

$database = getDatabase();
$smileys = getDistinctSmileys();

?>


var CuteEdit_Emoticons = [
	{
		name: 'Smileys',
		items: [
			<?php foreach ($smileys as $smiley): ?>
			{
				name: '<?= $smiley['name'] ?>',
                text: ' <?= $smiley['code'] ?> ',
				url: 'img/smileys/<?= $smiley['image_filename'] ?>'
			},
			<?php endforeach; ?>
		]
	}
];