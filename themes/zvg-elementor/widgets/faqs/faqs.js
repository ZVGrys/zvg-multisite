/**
 * FAQs widget.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	/**
	 * Open one answer at a time.
	 *
	 * @param {HTMLElement} widget The widget root.
	 */
	function zvgElementorInitFaqs(widget) {
		if (widget.dataset.zvgFaqsBound) {
			return;
		}

		widget.dataset.zvgFaqsBound = 'true';

		var triggers = widget.querySelectorAll('.zvg-elementor-faqs__trigger');

		Array.prototype.forEach.call(triggers, function (trigger) {
			trigger.addEventListener('click', function () {
				var isOpen = 'true' === trigger.getAttribute('aria-expanded');

				Array.prototype.forEach.call(triggers, function (other) {
					var answer = document.getElementById(
						other.getAttribute('aria-controls')
					);

					other.setAttribute('aria-expanded', 'false');

					if (answer) {
						answer.hidden = true;
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
	 * Hook into the Elementor front-end so the widget also works in the editor.
	 */
	function zvgElementorRegisterFaqs() {
		if (
			window.elementorFrontend &&
			window.elementorFrontend.hooks &&
			window.elementorFrontend.hooks.addAction
		) {
			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/zvg-elementor-faqs.default',
				function ($scope) {
					zvgElementorInitFaqs($scope[0] || $scope);
				}
			);

			return;
		}

		Array.prototype.forEach.call(
			document.querySelectorAll('.zvg-elementor-faqs'),
			zvgElementorInitFaqs
		);
	}

	window.addEventListener('elementor/frontend/init', zvgElementorRegisterFaqs);

	if (document.readyState !== 'loading') {
		zvgElementorRegisterFaqs();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorRegisterFaqs);
	}
})();
