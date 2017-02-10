<?php if (!isset($inlineFunction)): ?>
	<script type="text/javascript">
        (
			<?php endif ?>

            function initSpoilerButtons() {

                var toggleButtons = document.querySelectorAll('.spoiler .head .button');

                for (var i = 0; i < toggleButtons.length; i++) {
                    (function (i) {
                        toggleButtons[i].onclick = function () {
                            var spoilerButton = toggleButtons[i];
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

            }

			<?php if (!isset($inlineFunction)): ?>
        )();
	</script>
<?php endif ?>