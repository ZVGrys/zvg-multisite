/**
 * ZVG Member Dialog — front-end behaviour.
 */

/**
 * Wire one dialog to every card toggle on the page.
 *
 * @param {HTMLDialogElement} dialog The team dialog.
 *
 * @return {void}
 */
function initMemberDialog( dialog ) {
	const nameSlot = dialog.querySelector( '[data-member-name]' );
	const roleSlot = dialog.querySelector( '[data-member-role]' );
	const bioSlot = dialog.querySelector( '[data-member-bio]' );
	const profileSlot = dialog.querySelector( '[data-member-profile-slot]' );
	const portraitSlot = dialog.querySelector( '[data-member-portrait]' );
	const linkSlot = dialog.querySelector( '[data-member-link]' );
	let opener = null;
	let openerFromKeyboard = false;

	if (
		! nameSlot ||
		! roleSlot ||
		! bioSlot ||
		! profileSlot ||
		! portraitSlot ||
		! linkSlot
	) {
		return;
	}

	const toggles = document.querySelectorAll( '[data-member-open]' );
	const closers = dialog.querySelectorAll( '[data-member-close]' );

	const text = ( card, selector ) => {
		const node = card.querySelector( selector );

		return node ? node.textContent.trim() : '';
	};

	const fillPortrait = ( card ) => {
		const image = card.querySelector( '.zvg-fse-member__portrait img' );

		if ( ! image ) {
			portraitSlot.hidden = true;

			return;
		}

		portraitSlot.hidden = false;
		portraitSlot.src = image.src;
		portraitSlot.alt = image.alt;
		portraitSlot.srcset = image.srcset || '';
		portraitSlot.sizes = image.sizes || '';
		portraitSlot.width = image.naturalWidth || image.width;
		portraitSlot.height = image.naturalHeight || image.height;
	};

	const fillProfile = ( card ) => {
		const profile = card.querySelector( '[data-member-profile]' );

		profileSlot.textContent = '';

		if ( ! profile ) {
			return;
		}

		const clone = profile.cloneNode( true );
		clone.removeAttribute( 'data-member-profile' );
		clone.removeAttribute( 'hidden' );
		profileSlot.appendChild( clone );
	};

	const fillLink = ( button ) => {
		const url = button.getAttribute( 'data-member-link' ) || '';

		if ( ! url ) {
			linkSlot.href = '';
			linkSlot.hidden = true;
			linkSlot.removeAttribute( 'target' );
			linkSlot.removeAttribute( 'rel' );

			return;
		}

		linkSlot.href = url;
		linkSlot.hidden = false;

		if ( 0 === url.indexOf( '#' ) ) {
			linkSlot.removeAttribute( 'target' );
			linkSlot.removeAttribute( 'rel' );

			return;
		}

		let external = true;

		try {
			external =
				new URL( url, window.location.href ).origin !==
				window.location.origin;
		} catch {
			external = true;
		}

		if ( external ) {
			linkSlot.target = '_blank';
			linkSlot.rel = 'noopener noreferrer';
		} else {
			linkSlot.removeAttribute( 'target' );
			linkSlot.removeAttribute( 'rel' );
		}
	};

	const open = ( card, button, fromKeyboard ) => {
		opener = button;
		openerFromKeyboard = fromKeyboard;

		nameSlot.textContent = text( card, '.zvg-fse-member__name' );
		roleSlot.textContent = text( card, '.zvg-fse-member__role' );
		bioSlot.textContent = text( card, '.zvg-fse-member__bio' );

		fillPortrait( card );
		fillProfile( card );
		fillLink( button );

		dialog.showModal();
	};

	toggles.forEach( ( toggle ) => {
		toggle.hidden = false;
		toggle.addEventListener( 'click', ( event ) => {
			const button = event.currentTarget;
			const card = button.closest( '.wp-block-post' );

			if ( card ) {
				open( card, button, 0 === event.detail );
			}
		} );
	} );

	closers.forEach( ( closer ) => {
		closer.addEventListener( 'click', () => dialog.close() );
	} );

	linkSlot.addEventListener( 'click', () => {
		const href = linkSlot.getAttribute( 'href' ) || '';

		if ( 0 === href.indexOf( '#' ) ) {
			dialog.close();
		}
	} );

	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target === dialog ) {
			dialog.close();
		}
	} );

	/**
	 * Swallow the click that closed the dialog.
	 *
	 * A click on the backdrop closes the dialog during the mousedown/mouseup pair,
	 * so the trailing click lands on whatever the backdrop was covering — on this
	 * page that is another member card, which re-opens the dialog immediately.
	 * Killing pointer events for a single frame lets that stray click expire.
	 *
	 * @return {void}
	 */
	const swallowClosingClick = () => {
		document.documentElement.style.pointerEvents = 'none';
		window.requestAnimationFrame( () => {
			document.documentElement.style.pointerEvents = '';
		} );
	};

	dialog.addEventListener( 'close', () => {
		if ( opener ) {
			opener.focus( { focusVisible: openerFromKeyboard } );
			opener = null;
		}

		swallowClosingClick();
	} );
}

document.querySelectorAll( '[data-member-dialog]' ).forEach( ( dialog ) => {
	if ( 'function' === typeof dialog.showModal ) {
		initMemberDialog( dialog );
	}
} );
