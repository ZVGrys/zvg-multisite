/**
 * ZVG Member Dialog — front-end behaviour.
 *
 * Compiled by gulp to view.min.js; edit this file, never the .min.js.
 *
 * @package ZVG_FSE
 */

(function () {
	'use strict';

	function zvgFseInitMemberDialog(dialog) {
		var nameSlot = dialog.querySelector('[data-member-name]');
		var roleSlot = dialog.querySelector('[data-member-role]');
		var bioSlot = dialog.querySelector('[data-member-bio]');
		var profileSlot = dialog.querySelector('[data-member-profile-slot]');
		var portraitSlot = dialog.querySelector('[data-member-portrait]');
		var linkSlot = dialog.querySelector('[data-member-link]');
		var toggles = document.querySelectorAll('[data-member-open]');
		var closers = dialog.querySelectorAll('[data-member-close]');
		var opener = null;
		var openerFromKeyboard = false;
		var i;

		if (
			!nameSlot ||
			!roleSlot ||
			!bioSlot ||
			!profileSlot ||
			!portraitSlot ||
			!linkSlot
		) {
			return;
		}

		function text(card, selector) {
			var node = card.querySelector(selector);

			return node ? node.textContent.trim() : '';
		}

		function fillPortrait(card) {
			var image = card.querySelector('.zvg-fse-member__portrait img');

			if (!image) {
				portraitSlot.hidden = true;

				return;
			}

			portraitSlot.hidden = false;
			portraitSlot.src = image.src;
			portraitSlot.alt = image.alt;
			portraitSlot.srcset = image.srcset || '';
			portraitSlot.sizes = image.sizes || '';
		}

		function fillProfile(card) {
			var profile = card.querySelector('[data-member-profile]');
			var clone;

			profileSlot.textContent = '';

			if (!profile) {
				return;
			}

			clone = profile.cloneNode(true);
			clone.removeAttribute('data-member-profile');
			clone.removeAttribute('hidden');
			profileSlot.appendChild(clone);
		}

		function fillLink(button) {
			var url = button.getAttribute('data-member-link') || '';
			var external;

			if (!url) {
				linkSlot.href = '';
				linkSlot.hidden = true;
				linkSlot.removeAttribute('target');
				linkSlot.removeAttribute('rel');

				return;
			}

			linkSlot.href = url;
			linkSlot.hidden = false;

			if (0 === url.indexOf('#')) {
				linkSlot.removeAttribute('target');
				linkSlot.removeAttribute('rel');

				return;
			}

			external = true;

			try {
				external = new URL(url, window.location.href).origin !== window.location.origin;
			} catch (e) {
				external = true;
			}

			if (external) {
				linkSlot.target = '_blank';
				linkSlot.rel = 'noopener noreferrer';
			} else {
				linkSlot.removeAttribute('target');
				linkSlot.removeAttribute('rel');
			}
		}

		function open(card, button, fromKeyboard) {
			opener = button;
			openerFromKeyboard = fromKeyboard;

			nameSlot.textContent = text(card, '.zvg-fse-member__name');
			roleSlot.textContent = text(card, '.zvg-fse-member__role');
			bioSlot.textContent = text(card, '.zvg-fse-member__bio');

			fillPortrait(card);
			fillProfile(card);
			fillLink(button);

			dialog.showModal();
		}

		for (i = 0; i < toggles.length; i++) {
			toggles[i].hidden = false;
			toggles[i].addEventListener('click', function (event) {
				var button = event.currentTarget;
				var card = button.closest('.wp-block-post');

				if (card) {
					open(card, button, 0 === event.detail);
				}
			});
		}

		for (i = 0; i < closers.length; i++) {
			closers[i].addEventListener('click', function () {
				dialog.close();
			});
		}

		linkSlot.addEventListener('click', function () {
			var href = linkSlot.getAttribute('href') || '';

			if (0 === href.indexOf('#')) {
				dialog.close();
			}
		});

		dialog.addEventListener('click', function (event) {
			if (event.target === dialog) {
				dialog.close();
			}
		});

		dialog.addEventListener('close', function () {
			if (opener) {
				opener.focus({ focusVisible: openerFromKeyboard });
				opener = null;
			}

			document.documentElement.style.pointerEvents = 'none';
			requestAnimationFrame(function () {
				document.documentElement.style.pointerEvents = '';
			});
		});
	}

	function zvgFseInit() {
		var dialogs = document.querySelectorAll('[data-member-dialog]');
		var i;

		for (i = 0; i < dialogs.length; i++) {
			if ('function' === typeof dialogs[i].showModal) {
				zvgFseInitMemberDialog(dialogs[i]);
			}
		}
	}

	if (document.readyState !== 'loading') {
		zvgFseInit();
	} else {
		document.addEventListener('DOMContentLoaded', zvgFseInit);
	}
})();
