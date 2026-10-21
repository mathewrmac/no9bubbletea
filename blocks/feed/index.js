/**
 * Feed block — editor.
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
	var TextareaControl = wp.components.TextareaControl;
	var Button = wp.components.Button;
	var Placeholder = wp.components.Placeholder;

	function toImage( media ) {
		var sizes = media.sizes || {};
		var thumb = sizes.large || sizes.medium_large || sizes.medium;
		return {
			id: media.id,
			thumb: thumb ? thumb.url : media.url,
			alt: media.alt || '',
		};
	}

	function GalleryButton( props ) {
		return el( MediaUpload, {
			allowedTypes: [ 'image' ],
			multiple: 'add',
			gallery: true,
			value: props.images.map( function ( i ) { return i.id; } ),
			onSelect: function ( media ) {
				props.onSelect( media.map( toImage ) );
			},
			render: function ( obj ) {
				return el(
					Button,
					{ variant: props.primary ? 'primary' : 'secondary', onClick: obj.open },
					props.label
				);
			},
		} );
	}

	function Edit( props ) {
		var images = props.attributes.images;
		var set = props.setAttributes;
		var blockProps = useBlockProps();

		function updateAlt( index, alt ) {
			set( {
				images: images.map( function ( image, i ) {
					return i === index ? Object.assign( {}, image, { alt: alt } ) : image;
				} ),
			} );
		}

		function remove( index ) {
			set( { images: images.filter( function ( image, i ) { return i !== index; } ) } );
		}

		function move( index, dir ) {
			var next = images.slice();
			var target = index + dir;
			var tmp = next[ index ];
			next[ index ] = next[ target ];
			next[ target ] = tmp;
			set( { images: next } );
		}

		function replaceSelection( list ) {
			// Keep alt text already edited for images that stay in the selection.
			set( {
				images: list.map( function ( n ) {
					var old = images.filter( function ( o ) { return o.id === n.id; } )[ 0 ];
					return old ? Object.assign( {}, n, { alt: old.alt } ) : n;
				} ),
			} );
		}

		if ( ! images.length ) {
			return el(
				'div',
				blockProps,
				el(
					Placeholder,
					{ icon: 'format-gallery', label: __( 'Feed', 'no9' ), instructions: __( 'Choose photos from the media library.', 'no9' ) },
					el(
						MediaUploadCheck,
						null,
						el( GalleryButton, { images: images, primary: true, label: __( 'Choose images', 'no9' ), onSelect: replaceSelection } )
					)
				)
			);
		}

		var inspector = el(
			InspectorControls,
			null,
			el(
				PanelBody,
				{ title: __( 'Images', 'no9' ) },
				el(
					MediaUploadCheck,
					null,
					el( GalleryButton, { images: images, label: __( 'Edit selection', 'no9' ), onSelect: replaceSelection } )
				),
				images.map( function ( image, index ) {
					return el(
						'div',
						{ key: image.id, style: { marginTop: '16px' } },
						el( 'img', { src: image.thumb, alt: '', style: { width: '64px', height: '64px', objectFit: 'cover', borderRadius: '4px' } } ),
						el( TextareaControl, {
							label: __( 'Alt text', 'no9' ) + ' #' + ( index + 1 ),
							value: image.alt,
							rows: 2,
							onChange: function ( v ) { updateAlt( index, v ); },
							__nextHasNoMarginBottom: true,
						} ),
						el( Button, { size: 'small', disabled: index === 0, onClick: function () { move( index, -1 ); } }, '↑' ),
						el( Button, { size: 'small', disabled: index === images.length - 1, onClick: function () { move( index, 1 ); } }, '↓' ),
						el( Button, { size: 'small', isDestructive: true, onClick: function () { remove( index ); } }, __( 'Remove', 'no9' ) )
					);
				} )
			)
		);

		return el(
			wp.element.Fragment,
			null,
			inspector,
			el(
				'div',
				blockProps,
				images.map( function ( image ) {
					return el(
						'span',
						{ key: image.id, className: 'wp-block-no9-feed__item' },
						el( 'img', { src: image.thumb, alt: image.alt } )
					);
				} )
			)
		);
	}

	wp.blocks.registerBlockType( 'no9/feed', {
		edit: Edit,
		save: function () { return null; },
	} );
} )( window.wp );
