/**
 * Theme front-end behaviour.
 *
 * @package ZVG_FSE
 */

(function () {
	'use strict';

	/**
	 * Close the responsive navigation overlay a link was activated from.
	 *
	 * @param {HTMLElement} link The activated link.
	 *
	 * @return {void}
	 */
	function zvgFseCloseNavigationOverlay(link) {
		var overlay = link.closest(
			'.wp-block-navigation__responsive-container.is-menu-open'
		);

		if (!overlay) {
			return;
		}

		var close = overlay.querySelector(
			'.wp-block-navigation__responsive-container-close'
		);

		if (close) {
			close.click();
		}
	}

	/**
	 * Scroll to anchors that land on this page instead of navigating to them.
	 */
	function zvgFseInitAnchorScroll() {
		document.addEventListener('click', function (event) {
			if (
				event.defaultPrevented ||
				0 !== event.button ||
				event.metaKey ||
				event.ctrlKey ||
				event.shiftKey ||
				event.altKey
			) {
				return;
			}

			var link = event.target.closest('a[href*="#"]');

			if (!link || '_blank' === link.target) {
				return;
			}

			var url;

			try {
				url = new URL(link.href, window.location.href);
			} catch (error) {
				return;
			}

			if (
				url.origin !== window.location.origin ||
				url.pathname !== window.location.pathname ||
				(url.search && url.search !== window.location.search) ||
				url.hash.length < 2
			) {
				return;
			}

			var id = url.hash.slice(1);

			try {
				id = decodeURIComponent(id);
			} catch (error) {
				id = url.hash.slice(1);
			}

			var target = document.getElementById(id);

			if (!target) {
				return;
			}

			var prefersReducedMotion = window.matchMedia(
				'(prefers-reduced-motion: reduce)'
			).matches;

			event.preventDefault();
			zvgFseCloseNavigationOverlay(link);
			target.scrollIntoView({
				behavior: prefersReducedMotion ? 'auto' : 'smooth',
				block: 'start',
			});

			window.history.pushState(null, '', url.hash);
			target.setAttribute('tabindex', '-1');
			target.focus({ preventScroll: true });
		});
	}

	if (document.readyState !== 'loading') {
		zvgFseInitAnchorScroll();
	} else {
		document.addEventListener('DOMContentLoaded', zvgFseInitAnchorScroll);
	}
})();
