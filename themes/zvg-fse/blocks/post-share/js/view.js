/**
 * Copy the post address to the clipboard, and say so on the button itself.
 *
 * @package ZVG_FSE
 */
(function () {
	'use strict';

	var RESET_AFTER = 2400;

	document.addEventListener('click', function (event) {
		var button = event.target.closest('.wp-block-zvg-fse-post-share__copy');

		if (!button) {
			return;
		}

		var url = button.getAttribute('data-share-copy');
		var label = button.querySelector('.wp-block-zvg-fse-post-share__copy-label');

		if (!url || !label || !navigator.clipboard) {
			return;
		}

		navigator.clipboard.writeText(url).then(function () {
			if (!button.dataset.shareIdle) {
				button.dataset.shareIdle = label.textContent;
			}

			label.textContent = button.getAttribute('data-share-done');
			button.classList.add('is-copied');

			window.clearTimeout(parseInt(button.dataset.shareTimer, 10));
			button.dataset.shareTimer = window.setTimeout(function () {
				label.textContent = button.dataset.shareIdle;
				button.classList.remove('is-copied');
			}, RESET_AFTER);
		});
	});
})();
