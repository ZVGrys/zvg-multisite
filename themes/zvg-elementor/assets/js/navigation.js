/**
 * Header menu.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	/**
	 * Toggle the fallback header menu on small screens.
	 */
	function zvgElementorInitNavigation() {
		var toggle = document.querySelector('.zvg-elementor-header__toggle');
		var nav = document.querySelector('.zvg-elementor-header__nav');
		var small = window.matchMedia('(max-width: 782px)');

		if (!toggle || !nav) {
			return;
		}

		function closeNav() {
			nav.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.documentElement.classList.remove('no-scroll');
		}

		toggle.addEventListener('click', function () {
			var isOpen = nav.classList.toggle('is-open');

			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			document.documentElement.classList.toggle('no-scroll', isOpen);
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' !== event.key || !nav.classList.contains('is-open')) {
				return;
			}

			closeNav();
			toggle.focus();
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
