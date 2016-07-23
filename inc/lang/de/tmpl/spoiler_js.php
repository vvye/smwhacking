<script type="text/javascript">

	var spoilerButtons = document.querySelectorAll('.spoiler .head .button');
	for (var i = 0; i < spoilerButtons.length; i++) {
		(function (i) {
			spoilerButtons[i].onclick = function () {
				var spoilerButton = spoilerButtons[i];
				var spoilerBox = spoilerButton.parentNode.parentNode.getElementsByTagName('div')[0];
				if (window.getComputedStyle(spoilerBox).getPropertyValue('display') === 'none') {
					spoilerBox.style.display = 'block';
					spoilerButton.innerHTML = '<?= BBCODE_SPOILER_HIDE ?>';
				} else {
					spoilerBox.style.display = 'none';
					spoilerButton.innerHTML = '<?= BBCODE_SPOILER_SHOW ?>';
				}
			};
		})(i);
	}

</script>