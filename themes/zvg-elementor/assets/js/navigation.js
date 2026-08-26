/**
 * Header menu.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	/**
	 * The width the menu collapses at, kept in the stylesheet so the value lives once.
	 *
	 * @return {string} A CSS length.
	 */
	function zvgElementorMenuBreakpoint() {
		var value = window
			.getComputedStyle(document.documentElement)
			.getPropertyValue('--zvg-elementor-menu-breakpoint')
			.trim();

		return value || '782px';
	}

	/**
	 * Toggle the fallback header menu on small screens. While open the panel covers
	 * the viewport, so focus is kept inside it.
	 */
	function zvgElementorInitNavigation() {
		var toggle = document.querySelector('.zvg-elementor-header__toggle');
		var nav = document.querySelector('.zvg-elementor-header__nav');
		var small = window.matchMedia('(max-width: ' + zvgElementorMenuBreakpoint() + ')');

		if (!toggle || !nav) {
			return;
		}

		function closeNav() {
			nav.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.documentElement.classList.remove('no-scroll');
		}

		/**
		 * The elements the open panel is allowed to hand focus to.
		 *
		 * @return {HTMLElement[]} Toggle plus the focusable descendants of the panel.
		 */
		function focusable() {
			return [ toggle ].concat(
				Array.prototype.slice.call(
					nav.querySelectorAll('a[href], button:not([disabled])')
				)
			);
		}

		/**
		 * Keep Tab inside the panel while it covers the page.
		 *
		 * @param {KeyboardEvent} event Key event.
		 */
		function trapFocus(event) {
			var items = focusable();
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

		toggle.addEventListener('click', function () {
			var isOpen = nav.classList.toggle('is-open');

			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			document.documentElement.classList.toggle('no-scroll', isOpen);
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
		zvgElementorInitNavigation();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorInitNavigation);
	}
})();
