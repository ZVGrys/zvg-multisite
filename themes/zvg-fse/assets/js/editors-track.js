/**
 * Landing page behaviour.
 *
 * @package ZVG_FSE
 */

(function () {
	'use strict';

	/**
	 * Scrollbar for the editor track.
	 */
	function zvgFseInitTrackScrollbar() {
		var tracks = document.querySelectorAll('.zvg-fse-editors__track');

		Array.prototype.forEach.call(tracks, function (track) {
			var bar = document.createElement('div');
			var thumb = document.createElement('div');

			bar.className = 'zvg-fse-editors__scrollbar';
			bar.setAttribute('aria-hidden', 'true');
			thumb.className = 'zvg-fse-editors__thumb';
			bar.appendChild(thumb);
			track.parentNode.insertBefore(bar, track.nextSibling);

			track.classList.add('has-custom-scrollbar');

			var dragging = false;
			var dragOffset = 0;

			function maxScroll() {
				return track.scrollWidth - track.clientWidth;
			}

			function render() {
				var scrollable = maxScroll() > 0;

				bar.hidden = !scrollable;

				if (!scrollable) {
					return;
				}

				var ratio = track.clientWidth / track.scrollWidth;
				var width = Math.max(bar.clientWidth * ratio, 32);
				var travel = bar.clientWidth - width;

				thumb.style.width = width + 'px';
				thumb.style.transform =
					'translateX(' + travel * (track.scrollLeft / maxScroll()) + 'px)';
			}

			function scrollTo(clientX) {
				var rail = bar.getBoundingClientRect();
				var travel = bar.clientWidth - thumb.offsetWidth;

				if (travel <= 0) {
					return;
				}

				var position = clientX - rail.left - dragOffset;

				track.scrollLeft =
					(Math.min(Math.max(position, 0), travel) / travel) * maxScroll();
			}

			thumb.addEventListener('pointerdown', function (event) {
				dragging = true;
				dragOffset = event.clientX - thumb.getBoundingClientRect().left;
				thumb.setPointerCapture(event.pointerId);
				event.preventDefault();
			});

			thumb.addEventListener('pointermove', function (event) {
				if (dragging) {
					scrollTo(event.clientX);
				}
			});

			thumb.addEventListener('pointerup', function () {
				dragging = false;
			});

			bar.addEventListener('pointerdown', function (event) {
				if (event.target === thumb) {
					return;
				}

				dragOffset = thumb.offsetWidth / 2;
				scrollTo(event.clientX);
			});

			track.addEventListener('scroll', render, { passive: true });

			if (window.ResizeObserver) {
				new window.ResizeObserver(render).observe(track);
			} else {
				window.addEventListener('resize', render);
			}

			render();
		});
	}

	if (document.readyState !== 'loading') {
		zvgFseInitTrackScrollbar();
	} else {
		document.addEventListener('DOMContentLoaded', zvgFseInitTrackScrollbar);
	}
})();
