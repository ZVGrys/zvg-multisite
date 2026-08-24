/**
 * The team section.
 *
 * @package ZVG_ACF
 */

(function () {
	'use strict';

	function zvgAcfInitTeam(team) {
		var dialog = team.querySelector('[data-member-dialog]');

		if (!dialog || 'function' !== typeof dialog.showModal) {
			return;
		}

		var nameSlot = dialog.querySelector('[data-member-name]');
		var roleSlot = dialog.querySelector('[data-member-role]');
		var bioSlot = dialog.querySelector('[data-member-bio]');
		var profileSlot = dialog.querySelector('[data-member-profile-slot]');
		var portraitSlot = dialog.querySelector('[data-member-portrait]');
		var linkSlot = dialog.querySelector('[data-member-link]');
		var toggles = team.querySelectorAll('[data-member-open]');
		var closers = dialog.querySelectorAll('[data-member-close]');
		var opener = null;
		var openerFromKeyboard = false;
		var i;

		if (!nameSlot || !roleSlot || !bioSlot || !profileSlot || !portraitSlot) {
			return;
		}

		function text(card, selector) {
			var node = card.querySelector(selector);

			return node ? node.textContent.trim() : '';
		}

		function fillPortrait(card) {
			var image = card.querySelector('.zvg-acf-member__portrait');

			if (!image) {
				portraitSlot.hidden = true;

				return;
			}

			portraitSlot.hidden = false;
			portraitSlot.src = image.src;
			portraitSlot.alt = image.alt;
			portraitSlot.srcset = image.srcset || '';
			portraitSlot.sizes = image.sizes || '';
			portraitSlot.width = image.naturalWidth || image.width;
			portraitSlot.height = image.naturalHeight || image.height;
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

			if (!linkSlot) {
				return;
			}

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

			nameSlot.textContent = text(card, '.zvg-acf-member__name');
			roleSlot.textContent = text(card, '.zvg-acf-member__role');
			bioSlot.textContent = text(card, '.zvg-acf-member__bio');

			fillPortrait(card);
			fillProfile(card);
			fillLink(button);

			dialog.showModal();
		}

		for (i = 0; i < toggles.length; i++) {
			toggles[i].hidden = false;
			toggles[i].addEventListener('click', function (event) {
				var button = event.currentTarget;
				var card = button.closest('.zvg-acf-member');

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

		if (linkSlot) {
			linkSlot.addEventListener('click', function () {
				var href = linkSlot.getAttribute('href') || '';

				if (0 === href.indexOf('#')) {
					dialog.close();
				}
			});
		}

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

	function zvgAcfInitTeams() {
		var teams = document.querySelectorAll('.zvg-acf-team');
		var i;

		for (i = 0; i < teams.length; i++) {
			zvgAcfInitTeam(teams[i]);
		}
	}

	if (document.readyState !== 'loading') {
		zvgAcfInitTeams();
	} else {
		document.addEventListener('DOMContentLoaded', zvgAcfInitTeams);
	}
})();
