/**
 * Menu widget.
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
	 * Wire one menu widget: the toggle opens the panel, Escape and a second click close it.
	 * While open the panel covers the viewport, so focus is kept inside it.
	 *
	 * @param {HTMLElement} nav Widget root.
	 */
	function zvgElementorInitMenu(nav) {
		var toggle = nav.querySelector('.zvg-elementor-nav__toggle');
		var panel = nav.querySelector('.zvg-elementor-nav__panel');
		var small = window.matchMedia('(max-width: ' + zvgElementorMenuBreakpoint() + ')');

		if (!toggle || !panel || nav.dataset.zvgMenuBound) {
			return;
		}

		nav.dataset.zvgMenuBound = 'true';

		function setOpen(isOpen) {
			panel.classList.toggle('is-open', isOpen);
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			document.documentElement.classList.toggle('no-scroll', isOpen);
		}

		/**
		 * The elements the open panel is allowed to hand focus to.
		 *
		 * @return {NodeList} Focusable descendants of the widget root.
		 */
		function focusable() {
			return nav.querySelectorAll('a[href], button:not([disabled])');
		}

		/**
		 * Keep Tab inside the panel while it covers the page.
		 *
		 * @param {KeyboardEvent} event Key event.
		 */
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

		toggle.addEventListener('click', function () {
			setOpen(!panel.classList.contains('is-open'));
		});

		document.addEventListener('keydown', function (event) {
			if (!panel.classList.contains('is-open')) {
				return;
			}

			if ('Escape' === event.key) {
				setOpen(false);
				toggle.focus();

				return;
			}

			if ('Tab' === event.key) {
				trapFocus(event);
			}
		});

		small.addEventListener('change', function (event) {
			if (!event.matches && panel.classList.contains('is-open')) {
				setOpen(false);
			}
		});
	}

	/**
	 * Wire every menu widget on the page.
	 */
	function zvgElementorInitMenus() {
		var navs = document.querySelectorAll('.zvg-elementor-nav');
		var index;

		for (index = 0; index < navs.length; index++) {
			zvgElementorInitMenu(navs[index]);
		}
	}

	if (document.readyState !== 'loading') {
		zvgElementorInitMenus();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorInitMenus);
	}

	window.addEventListener('elementor/frontend/init', zvgElementorInitMenus);
})();
