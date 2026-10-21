/**
 * Feed block — front end. Opens the grid in a Viewer.js lightbox.
 * Requires the `no9-viewerjs` script (window.Viewer).
 */
( function () {
	if ( typeof window.Viewer === 'undefined' ) {
		return;
	}

	document.querySelectorAll( '.wp-block-no9-feed' ).forEach( function ( feed ) {
		new window.Viewer( feed, {
			url: 'data-original',
			title: function ( image ) { return image.alt; },
			toolbar: { zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1, prev: 1, next: 1, rotateLeft: 1, rotateRight: 1 },
			navbar: false,
			loop: true,
		} );

		// Images sit inside links to the full file; keep the link as a no-JS fallback.
		feed.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.wp-block-no9-feed__item' ) ) {
				event.preventDefault();
			}
		} );
	} );
} )();
