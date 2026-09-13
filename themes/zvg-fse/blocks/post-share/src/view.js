/**
 * Copy the post address to the clipboard, and say so on the button itself.
 */

const RESET_AFTER = 2400;

document.addEventListener( 'click', ( event ) => {
	const button = event.target.closest( '.wp-block-zvg-fse-post-share__copy' );

	if ( ! button ) {
		return;
	}

	const url = button.getAttribute( 'data-share-copy' );
	const label = button.querySelector(
		'.wp-block-zvg-fse-post-share__copy-label'
	);

	if ( ! url || ! label || ! navigator.clipboard ) {
		return;
	}

	navigator.clipboard.writeText( url ).then( () => {
		if ( ! button.dataset.shareIdle ) {
			button.dataset.shareIdle = label.textContent;
		}

		label.textContent = button.getAttribute( 'data-share-done' );
		button.classList.add( 'is-copied' );

		window.clearTimeout( parseInt( button.dataset.shareTimer, 10 ) );
		button.dataset.shareTimer = window.setTimeout( () => {
			label.textContent = button.dataset.shareIdle;
			button.classList.remove( 'is-copied' );
		}, RESET_AFTER );
	} );
} );
