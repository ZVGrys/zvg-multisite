/**
 * Build chooser widget.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	var BUILDS = ['fse', 'elementor', 'acf'];

	/**
	 * Walk one chooser through its questions and hand out a verdict.
	 *
	 * @param {HTMLElement} form Chooser form.
	 */
	function zvgElementorInitChooser(form) {
		if (form.dataset.zvgChooserBound) {
			return;
		}

		form.dataset.zvgChooserBound = 'true';

		var steps = form.querySelectorAll('[data-chooser-step]');
		var actions = form.querySelector('[data-chooser-actions]');
		var back = form.querySelector('[data-chooser-back]');
		var next = form.querySelector('[data-chooser-next]');
		var result = form.querySelector('[data-chooser-result]');
		var winnerSlot = form.querySelector('[data-chooser-winner]');
		var othersTitle = form.querySelector('[data-chooser-others-title]');
		var othersSlot = form.querySelector('[data-chooser-others]');
		var pool = form.querySelector('[data-chooser-pool]');
		var restart = form.querySelector('[data-chooser-restart]');
		var labels = JSON.parse(form.getAttribute('data-chooser-labels') || '{}');
		var current = 0;

		if (!steps.length || !next) {
			return;
		}

		form.setAttribute('data-chooser-ready', '');

		function answered(index) {
			return !!steps[index].querySelector('input:checked');
		}

		function render() {
			var i;

			for (i = 0; i < steps.length; i++) {
				steps[i].hidden = i !== current;
			}

			if (back) {
				back.hidden = current === 0;
			}

			next.textContent = current === steps.length - 1 ? labels.see : labels.next;
			next.disabled = !answered(current);
			actions.hidden = false;
			result.hidden = true;

			if (restart) {
				restart.hidden = true;
			}

			if (othersTitle) {
				othersTitle.hidden = true;
			}
		}

		/**
		 * Sums the weights of the chosen answers and returns the top build.
		 *
		 * @return {string} Build key.
		 */
		function winner() {
			var scores = { fse: 0, elementor: 0, acf: 0 };
			var chosen = form.querySelectorAll('input:checked');
			var i;
			var b;

			for (i = 0; i < chosen.length; i++) {
				for (b = 0; b < BUILDS.length; b++) {
					scores[BUILDS[b]] += parseInt(chosen[i].getAttribute('data-' + BUILDS[b]) || '0', 10);
				}
			}

			return BUILDS.slice().sort(function (a, b) {
				return scores[b] - scores[a];
			})[0];
		}

		function show() {
			var top = winner();
			var verdicts = form.querySelectorAll('[data-build]');
			var i;
			var verdict;

			for (i = 0; i < steps.length; i++) {
				steps[i].hidden = true;
			}

			actions.hidden = true;
			result.hidden = false;

			if (restart) {
				restart.hidden = false;
			}

			if (othersTitle) {
				othersTitle.hidden = false;
			}

			for (i = 0; i < verdicts.length; i++) {
				verdict = verdicts[i];

				if (verdict.getAttribute('data-build') === top) {
					verdict.setAttribute('data-role', 'winner');
					winnerSlot.appendChild(verdict);
				} else {
					verdict.setAttribute('data-role', 'other');
					othersSlot.appendChild(verdict);
				}
			}
		}

		form.addEventListener('change', function (event) {
			if (event.target.type === 'radio') {
				next.disabled = !answered(current);
			}
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!answered(current)) {
				return;
			}

			if (current < steps.length - 1) {
				current++;
				render();
				return;
			}

			show();
		});

		if (back) {
			back.addEventListener('click', function () {
				if (current > 0) {
					current--;
					render();
				}
			});
		}

		if (restart) {
			restart.addEventListener('click', function () {
				var verdicts = form.querySelectorAll('[data-build]');
				var i;
				var j;

				form.reset();

				for (i = 0; i < BUILDS.length; i++) {
					for (j = 0; j < verdicts.length; j++) {
						if (verdicts[j].getAttribute('data-build') === BUILDS[i]) {
							verdicts[j].removeAttribute('data-role');
							pool.appendChild(verdicts[j]);
						}
					}
				}

				current = 0;
				render();
				form.scrollIntoView({ block: 'nearest' });
			});
		}

		render();
	}

	/**
	 * Wire every chooser on the page.
	 */
	function zvgElementorInitChoosers() {
		var forms = document.querySelectorAll('[data-chooser]');
		var index;

		for (index = 0; index < forms.length; index++) {
			zvgElementorInitChooser(forms[index]);
		}
	}

	if (document.readyState !== 'loading') {
		zvgElementorInitChoosers();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorInitChoosers);
	}

	window.addEventListener('elementor/frontend/init', function () {
		if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
			zvgElementorInitChoosers();
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/zvg-elementor-build-chooser.default',
			function (scope) {
				var root = scope && scope.length ? scope[0] : scope;
				var form = root && root.querySelector ? root.querySelector('[data-chooser]') : null;

				if (form) {
					zvgElementorInitChooser(form);
				}
			}
		);
	});
})();
