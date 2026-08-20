/**
 * The three editors section.
 *
 * @package ZVG_ACF
 */

(function () {
	'use strict';

	/**
	 * Give one track a scrollbar of its own, clear of the cards.
	 *
	 * @param {HTMLElement} track The scrolling track.
	 */
	function zvgAcfInitTrack(track) {
		var bar = document.createElement('div');
		var thumb = document.createElement('div');
		var dragging = false;
		var dragOffset = 0;

		bar.className = 'zvg-acf-editors__scrollbar';
		bar.setAttribute('aria-hidden', 'true');
		thumb.className = 'zvg-acf-editors__thumb';
		bar.appendChild(thumb);
		track.parentNode.insertBefore(bar, track.nextSibling);

		track.classList.add('has-custom-scrollbar');

		/**
		 * How far the track can travel.
		 *
		 * @return {number} Scrollable distance in pixels.
		 */
		function maxScroll() {
			return track.scrollWidth - track.clientWidth;
		}

		/**
		 * Size and place the thumb for the current scroll position.
		 */
		function render() {
			var scrollable = maxScroll() > 0;
			var ratio;
			var width;
			var travel;

			bar.hidden = !scrollable;

			if (!scrollable) {
				return;
			}

			ratio = track.clientWidth / track.scrollWidth;
			width = Math.max(bar.clientWidth * ratio, 32);
			travel = bar.clientWidth - width;

			thumb.style.width = width + 'px';
			thumb.style.transform =
				'translateX(' + travel * (track.scrollLeft / maxScroll()) + 'px)';
		}

		/**
		 * Scroll the track to the position the pointer points at.
		 *
		 * @param {number} clientX Pointer position along the bar.
		 */
		function scrollTo(clientX) {
			var rail = bar.getBoundingClientRect();
			var travel = bar.clientWidth - thumb.offsetWidth;
			var position;

			if (travel <= 0) {
				return;
			}

			position = clientX - rail.left - dragOffset;

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
	function zvgAcfInitTracks() {
		var tracks = document.querySelectorAll('.zvg-acf-editors__track');

		Array.prototype.forEach.call(tracks, zvgAcfInitTrack);
	}

	if (document.readyState !== 'loading') {
		zvgAcfInitTracks();
	} else {
		document.addEventListener('DOMContentLoaded', zvgAcfInitTracks);
	}
})();
