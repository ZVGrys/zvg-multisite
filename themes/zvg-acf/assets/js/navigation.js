/**
 * Header menu.
 *
 * @package ZVG_ACF
 */

(function () {
	'use strict';

	/**
	 * Toggle the header menu on small screens.
	 */
	function zvgAcfInitNavigation() {
		var toggle = document.querySelector('.zvg-acf-header__toggle');
		var close = document.querySelector('.zvg-acf-header__close');
		var nav = document.querySelector('.zvg-acf-header__nav');
		var small = window.matchMedia('(max-width: 782px)');

		if (!toggle || !nav) {
			return;
		}

		function focusable() {
			return nav.querySelectorAll('a[href], button:not([disabled])');
		}

		function openNav() {
			nav.classList.add('is-open');
			toggle.setAttribute('aria-expanded', 'true');
			document.documentElement.classList.add('no-scroll');

			if (close) {
				close.focus();
			}
		}

		function closeNav() {
			nav.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.documentElement.classList.remove('no-scroll');
		}

		function trapFocus(event) {
			var items = focusable();

			if (!items.length) {
				return;
			}

			var first = items[0];
			var last = items[items.length - 1];

			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();

				return;
			}

			if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		}

		toggle.addEventListener('click', openNav);

		if (close) {
			close.addEventListener('click', function () {
				closeNav();
				toggle.focus();
			});
		}

		nav.addEventListener('click', function (event) {
			if (event.target.closest('a')) {
				closeNav();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (!nav.classList.contains('is-open')) {
				return;
			}

			if ('Escape' === event.key) {
				closeNav();
				toggle.focus();

				return;
			}

			if ('Tab' === event.key) {
				trapFocus(event);
			}
		});

		small.addEventListener('change', function (event) {
			if (!event.matches) {
				closeNav();
			}
		});
	}

	if (document.readyState !== 'loading') {
		zvgAcfInitNavigation();
	} else {
		document.addEventListener('DOMContentLoaded', zvgAcfInitNavigation);
	}
})();
