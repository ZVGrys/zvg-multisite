/**
 * Team widget.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	/**
	 * Fill one dialog from the card that opened it — the copy stays in the
	 * markup rather than in this script.
	 *
	 * @param {HTMLElement} team Widget root.
	 */
	function zvgElementorInitTeam(team) {
		if (team.dataset.zvgTeamBound) {
			return;
		}

		var dialog = team.querySelector('[data-member-dialog]');

		if (!dialog || 'function' !== typeof dialog.showModal) {
			return;
		}

		team.dataset.zvgTeamBound = 'true';

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

		function text(card, selector) {
			var node = card.querySelector(selector);

			return node ? node.textContent.trim() : '';
		}

		function fillPortrait(card) {
			if (!portraitSlot) {
				return;
			}

			var image = card.querySelector('.zvg-elementor-team__portrait');

			if (!image) {
				portraitSlot.hidden = true;

				return;
			}

			portraitSlot.hidden = false;
			portraitSlot.src = image.currentSrc || image.src;
			portraitSlot.alt = image.alt;
		}

		function fillProfile(card) {
			var profile = card.querySelector('[data-member-profile]');

			profileSlot.textContent = '';

			if (!profile) {
				return;
			}

			var clone = profile.cloneNode(true);

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

			nameSlot.textContent = text(card, '.zvg-elementor-team__name');
			roleSlot.textContent = text(card, '.zvg-elementor-team__role');
			bioSlot.textContent = text(card, '.zvg-elementor-team__bio');

			fillPortrait(card);
			fillProfile(card);
			fillLink(button);

			dialog.showModal();
		}

		for (i = 0; i < toggles.length; i++) {
			toggles[i].hidden = false;
			toggles[i].addEventListener('click', function (event) {
				var button = event.currentTarget;
				var card = button.closest('.zvg-elementor-team__member');

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
		});
	}

	function zvgElementorInitTeams() {
		var teams = document.querySelectorAll('.zvg-elementor-team');
		var i;

		for (i = 0; i < teams.length; i++) {
			zvgElementorInitTeam(teams[i]);
		}
	}

	if (document.readyState !== 'loading') {
		zvgElementorInitTeams();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorInitTeams);
	}

	window.addEventListener('elementor/frontend/init', function () {
		if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
			zvgElementorInitTeams();
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/zvg-elementor-team.default',
			function (scope) {
				var root = scope && scope.length ? scope[0] : scope;
				var team = root && root.querySelector ? root.querySelector('.zvg-elementor-team') : null;

				if (team) {
					zvgElementorInitTeam(team);
				}
			}
		);
	});
})();
