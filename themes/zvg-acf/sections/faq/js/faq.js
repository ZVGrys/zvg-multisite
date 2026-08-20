/**
 * The FAQ section.
 *
 * @package ZVG_ACF
 */

(function () {
	'use strict';

	/**
	 * Open one answer at a time.
	 *
	 * @param {HTMLElement} section The section root.
	 */
	function zvgAcfInitFaq(section) {
		var triggers = section.querySelectorAll('.zvg-acf-faq__trigger');

		Array.prototype.forEach.call(triggers, function (trigger) {
			var answer = document.getElementById(
				trigger.getAttribute('aria-controls')
			);

			trigger.setAttribute('aria-expanded', 'false');

			if (answer) {
				answer.hidden = true;
			}

			trigger.addEventListener('click', function () {
				var isOpen = 'true' === trigger.getAttribute('aria-expanded');

				Array.prototype.forEach.call(triggers, function (other) {
					var otherAnswer = document.getElementById(
						other.getAttribute('aria-controls')
					);

					other.setAttribute('aria-expanded', 'false');

					if (otherAnswer) {
						otherAnswer.hidden = true;
					}
				});

				if (isOpen) {
					return;
				}

				var current = document.getElementById(
					trigger.getAttribute('aria-controls')
				);

				trigger.setAttribute('aria-expanded', 'true');

				if (current) {
					current.hidden = false;
				}
			});
		});
	}

	/**
	 * Bind every FAQ section on the page.
	 */
	function zvgAcfInitFaqs() {
		Array.prototype.forEach.call(
			document.querySelectorAll('.zvg-acf-faq'),
			zvgAcfInitFaq
		);
	}

	if (document.readyState !== 'loading') {
		zvgAcfInitFaqs();
	} else {
		document.addEventListener('DOMContentLoaded', zvgAcfInitFaqs);
	}
})();
