/**
 * Editor track widget.
 *
 * @package ZVG_Elementor
 */

(function () {
	'use strict';

	/**
	 * Give one track its own scrollbar, so the native one does not sit under the cards.
	 *
	 * @param {HTMLElement} track Track element.
	 */
	function zvgElementorInitTrack(track) {
		if (track.dataset.zvgTrackBound) {
			return;
		}

		track.dataset.zvgTrackBound = 'true';

		var bar = document.createElement('div');
		var thumb = document.createElement('div');
		var dragging = false;
		var dragOffset = 0;

		bar.className = 'zvg-elementor-editor-track__scrollbar';
		bar.setAttribute('aria-hidden', 'true');
		thumb.className = 'zvg-elementor-editor-track__thumb';
		bar.appendChild(thumb);
		track.parentNode.insertBefore(bar, track.nextSibling);

		track.classList.add('has-custom-scrollbar');

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
	}

	/**
	 * Wire every track on the page.
	 */
	function zvgElementorInitTracks() {
		var tracks = document.querySelectorAll('.zvg-elementor-editor-track');
		var index;

		for (index = 0; index < tracks.length; index++) {
			zvgElementorInitTrack(tracks[index]);
		}
	}

	if (document.readyState !== 'loading') {
		zvgElementorInitTracks();
	} else {
		document.addEventListener('DOMContentLoaded', zvgElementorInitTracks);
	}

	window.addEventListener('elementor/frontend/init', zvgElementorInitTracks);
})();
