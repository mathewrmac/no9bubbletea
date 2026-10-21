/**
 * Logo Badge block — editor.
 *
 * Plain ES5-style script using WordPress globals, so it runs without a JS build step.
 * Markup mirrors render.php.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var RangeControl = wp.components.RangeControl;
	var Button = wp.components.Button;
	var useInstanceId = wp.compose.useInstanceId;

	var defaultLogo = ( window.no9LogoBadge || {} ).defaultLogo;

	function Edit( props ) {
		var a = props.attributes;
		var set = props.setAttributes;
		var pathId = 'no9-badge-path-editor-' + useInstanceId( Edit );

		var blockProps = useBlockProps( {
			style: {
				'--no9-badge-size': a.size + 'px',
				'--no9-badge-spin': a.duration + 's',
			},
		} );

		var inspector = el(
			InspectorControls,
			null,
			el(
				PanelBody,
				{ title: __( 'Badge', 'no9' ) },
				el( TextControl, {
					label: __( 'Ring text', 'no9' ),
					help: __( 'Repeat the phrase so it wraps the full circle.', 'no9' ),
					value: a.ringText,
					onChange: function ( v ) { set( { ringText: v } ); },
					__nextHasNoMarginBottom: true,
				} ),
				el( TextControl, {
					label: __( 'Logo alt text', 'no9' ),
					value: a.alt,
					onChange: function ( v ) { set( { alt: v } ); },
					__nextHasNoMarginBottom: true,
				} ),
				el( RangeControl, {
					label: __( 'Max width (px)', 'no9' ),
					value: a.size,
					min: 120,
					max: 600,
					step: 10,
					onChange: function ( v ) { set( { size: v } ); },
					__nextHasNoMarginBottom: true,
				} ),
				el( RangeControl, {
					label: __( 'Seconds per rotation', 'no9' ),
					value: a.duration,
					min: 5,
					max: 120,
					onChange: function ( v ) { set( { duration: v } ); },
					__nextHasNoMarginBottom: true,
				} )
			),
			el(
				PanelBody,
				{ title: __( 'Logo image', 'no9' ) },
				el(
					MediaUploadCheck,
					null,
					el( MediaUpload, {
						allowedTypes: [ 'image' ],
						value: a.mediaId,
						onSelect: function ( media ) {
							set( { mediaId: media.id, mediaUrl: media.url } );
						},
						render: function ( obj ) {
							return el(
								Button,
								{ variant: 'secondary', onClick: obj.open },
								a.mediaId ? __( 'Replace logo', 'no9' ) : __( 'Choose logo', 'no9' )
							);
						},
					} )
				),
				a.mediaId &&
					el(
						Button,
						{
							variant: 'link',
							isDestructive: true,
							onClick: function () { set( { mediaId: undefined, mediaUrl: undefined } ); },
						},
						__( 'Use theme logo', 'no9' )
					)
			)
		);

		return el(
			wp.element.Fragment,
			null,
			inspector,
			el(
				'div',
				blockProps,
				el(
					'svg',
					{ className: 'wp-block-no9-logo-badge__ring', viewBox: '0 0 300 300', 'aria-hidden': true },
					el( 'defs', null, el( 'path', { id: pathId, d: 'M150,150 m-128,0 a128,128 0 1,1 256,0 a128,128 0 1,1 -256,0' } ) ),
					el( 'text', null, el( 'textPath', { href: '#' + pathId, textLength: 800, lengthAdjust: 'spacing' }, a.ringText ) )
				),
				el(
					'div',
					{ className: 'wp-block-no9-logo-badge__disc' },
					el( 'img', { src: a.mediaUrl || defaultLogo, alt: a.alt } )
				)
			)
		);
	}

	wp.blocks.registerBlockType( 'no9/logo-badge', {
		edit: Edit,
		save: function () { return null; },
	} );
} )( window.wp );
