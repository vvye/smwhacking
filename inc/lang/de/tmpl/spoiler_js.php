<script type="text/javascript">

	var spoilerButtons = document.querySelectorAll('.spoiler .head .button');
	for (var i = 0; i < spoilerButtons.length; i++) {
		(function (i) {
			spoilerButtons[i].onclick = function () {
				var spoilerButton = spoilerButtons[i];
				var spoilerBox = spoilerButton.parentNode.parentNode.getElementsByTagName('div')[0];
				if (window.getComputedStyle(spoilerBox).getPropertyValue('display') === 'none') {
					spoilerBox.style.display = 'block';
					spoilerButton.innerHTML = 'verstecken';
				} else {
					spoilerBox.style.display = 'none';
					spoilerButton.innerHTML = 'anzeigen';
				}
			};
		})(i);
	}

</script>