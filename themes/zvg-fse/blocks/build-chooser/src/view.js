/**
 * ZVG Build Chooser — front-end behaviour.
 */

const BUILDS = [ 'fse', 'elementor', 'acf' ];

/**
 * Turn the plain form into a one-question-at-a-time quiz.
 *
 * @param {HTMLFormElement} form The chooser form.
 *
 * @return {void}
 */
function initChooser( form ) {
	const steps = form.querySelectorAll( '[data-chooser-step]' );
	const actions = form.querySelector( '[data-chooser-actions]' );
	const back = form.querySelector( '[data-chooser-back]' );
	const next = form.querySelector( '[data-chooser-next]' );
	const result = form.querySelector( '[data-chooser-result]' );
	const winnerSlot = form.querySelector( '[data-chooser-winner]' );
	const othersTitle = form.querySelector( '[data-chooser-others-title]' );
	const othersSlot = form.querySelector( '[data-chooser-others]' );
	const pool = form.querySelector( '[data-chooser-pool]' );
	const restart = form.querySelector( '[data-chooser-restart]' );
	const labels = JSON.parse(
		form.getAttribute( 'data-chooser-labels' ) || '{}'
	);
	let current = 0;

	form.setAttribute( 'data-chooser-ready', '' );

	steps.forEach( ( step ) => step.setAttribute( 'tabindex', '-1' ) );

	result.setAttribute( 'tabindex', '-1' );

	const answered = ( index ) =>
		!! steps[ index ].querySelector( 'input:checked' );

	/**
	 * Show one step and hide the rest.
	 *
	 * @param {boolean} moveFocus Whether to send focus to the step now on screen. The
	 *                            step that replaces it is a different question, so
	 *                            leaving focus on the button that caused the swap
	 *                            gives a screen reader nothing to announce.
	 *
	 * @return {void}
	 */
	const render = ( moveFocus ) => {
		steps.forEach( ( step, i ) => {
			step.hidden = i !== current;
		} );

		back.hidden = current === 0;
		next.textContent =
			current === steps.length - 1 ? labels.see : labels.next;
		next.disabled = ! answered( current );
		result.hidden = true;
		actions.hidden = false;
		restart.hidden = true;
		othersTitle.hidden = true;

		if ( moveFocus ) {
			steps[ current ].focus();
		}
	};

	/**
	 * Sums the weights of the chosen answers and returns the top build.
	 *
	 * @return {string} Build key.
	 */
	const winner = () => {
		const scores = { fse: 0, elementor: 0, acf: 0 };

		form.querySelectorAll( 'input:checked' ).forEach( ( chosen ) => {
			BUILDS.forEach( ( build ) => {
				scores[ build ] += parseInt(
					chosen.getAttribute( 'data-' + build ) || '0',
					10
				);
			} );
		} );

		return BUILDS.slice().sort(
			( a, b ) => scores[ b ] - scores[ a ]
		)[ 0 ];
	};

	const show = () => {
		const top = winner();

		steps.forEach( ( step ) => {
			step.hidden = true;
		} );

		actions.hidden = true;
		result.hidden = false;
		restart.hidden = false;
		othersTitle.hidden = false;

		form.querySelectorAll( '[data-build]' ).forEach( ( verdict ) => {
			if ( verdict.getAttribute( 'data-build' ) === top ) {
				verdict.setAttribute( 'data-role', 'winner' );
				winnerSlot.appendChild( verdict );
			} else {
				verdict.setAttribute( 'data-role', 'other' );
				othersSlot.appendChild( verdict );
			}
		} );

		result.focus();
	};

	form.addEventListener( 'change', ( event ) => {
		if ( event.target.type === 'radio' ) {
			next.disabled = ! answered( current );
		}
	} );

	form.addEventListener( 'submit', ( event ) => {
		event.preventDefault();

		if ( ! answered( current ) ) {
			return;
		}

		if ( current < steps.length - 1 ) {
			current++;
			render( true );
			return;
		}

		show();
	} );

	back.addEventListener( 'click', () => {
		if ( current > 0 ) {
			current--;
			render( true );
		}
	} );

	restart.addEventListener( 'click', () => {
		const verdicts = form.querySelectorAll( '[data-build]' );

		form.reset();

		BUILDS.forEach( ( build ) => {
			verdicts.forEach( ( verdict ) => {
				if ( verdict.getAttribute( 'data-build' ) === build ) {
					verdict.removeAttribute( 'data-role' );
					pool.appendChild( verdict );
				}
			} );
		} );

		current = 0;
		render( true );
		form.scrollIntoView( { block: 'nearest' } );
	} );

	render();
}

document.querySelectorAll( '[data-chooser]' ).forEach( initChooser );
