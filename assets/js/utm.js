/**
 * UTM capture. Reads utm_* parameters from the URL and copies them into
 * matching hidden fields (e.g. <input type="hidden" name="utm_source">) in forms.
 * Values are kept in sessionStorage so they survive navigating to another page
 * before the form is submitted; a new visit with fresh UTMs replaces them.
 */
( function () {
	var KEYS = [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ];
	var STORAGE_KEY = 'no9_utm';

	function read() {
		try {
			return JSON.parse( window.sessionStorage.getItem( STORAGE_KEY ) ) || {};
		} catch ( e ) {
			return {};
		}
	}

	function save( data ) {
		try {
			window.sessionStorage.setItem( STORAGE_KEY, JSON.stringify( data ) );
		} catch ( e ) {}
	}

	var params = new URLSearchParams( window.location.search );
	var fromUrl = {};

	KEYS.forEach( function ( key ) {
		var value = params.get( key );
		if ( value ) {
			fromUrl[ key ] = value.trim().slice( 0, 200 );
		}
	} );

	// Treat the URL's UTMs as one set: if any are present, they replace what was stored.
	var utm = Object.keys( fromUrl ).length ? fromUrl : read();
	save( utm );

	KEYS.forEach( function ( key ) {
		if ( ! utm[ key ] ) {
			return;
		}

		document.querySelectorAll( 'form input[type="hidden"][name="' + key + '"]' ).forEach( function ( input ) {
			input.value = utm[ key ];
		} );
	} );
} )();
