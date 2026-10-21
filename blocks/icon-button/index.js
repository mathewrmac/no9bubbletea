/**
 * Icon Button block — editor.
 *
 * Plain ES5-style script using WordPress globals, so it runs without a JS build step.
 * Markup mirrors render.php: a `.wp-block-button` wrapper around a
 * `.wp-block-button__link` anchor, so core's Button styles (including the
 * Fill/Outline block styles) apply as they do to core/button.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var __ = wp.i18n.__;

	var blockEditor = wp.blockEditor;
	var components = wp.components;

	var useBlockProps = blockEditor.useBlockProps;
	var RichText = blockEditor.RichText;
	var InspectorControls = blockEditor.InspectorControls;
	var BlockControls = blockEditor.BlockControls;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var LinkControl = blockEditor.__experimentalLinkControl;

	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var ToggleControl = components.ToggleControl;
	var RangeControl = components.RangeControl;
	var ButtonGroup = components.ButtonGroup;
	var Button = components.Button;
	var Dropdown = components.Dropdown;
	var ToolbarGroup = components.ToolbarGroup;
	var ToolbarButton = components.ToolbarButton;

	var NEW_TAB_TARGET = '_blank';
	var NEW_TAB_REL = 'noreferrer noopener';
	var WIDTHS = [ 25, 50, 75, 100 ];

	/**
	 * Classes for the outer `.wp-block-button` wrapper. Kept in sync with render.php.
	 */
	function wrapperClasses( attributes ) {
		var classes = [ 'wp-block-button', 'no9-icon-button' ];

		if ( attributes.width ) {
			classes.push( 'has-custom-width' );
			classes.push( 'wp-block-button__width-' + attributes.width );
		}

		return classes.join( ' ' );
	}

	/**
	 * Classes for the anchor itself. Kept in sync with render.php.
	 */
	function linkClasses( attributes ) {
		var classes = [ 'wp-block-button__link', 'wp-element-button', 'no9-icon-button__link' ];

		if ( attributes.iconUrl ) {
			classes.push( 'has-icon' );
			classes.push( 'after' === attributes.iconPosition ? 'has-icon-after' : 'has-icon-before' );
		}

		if ( attributes.iconUrl && attributes.hoverIconUrl ) {
			classes.push( 'has-hover-icon' );
		}

		return classes.join( ' ' );
	}

	/**
	 * Media picker rows for one icon slot.
	 */
	function iconPicker( label, mediaId, onSelect, onClear, clearLabel ) {
		return el(
			MediaUploadCheck,
			null,
			el(
				'div',
				{ style: { marginBottom: '16px' } },
				el( 'p', { style: { margin: '0 0 8px', fontWeight: 500 } }, label ),
				el( MediaUpload, {
					allowedTypes: [ 'image' ],
					value: mediaId,
					onSelect: onSelect,
					render: function ( obj ) {
						return el(
							Button,
							{ variant: 'secondary', onClick: obj.open },
							mediaId ? __( 'Replace image', 'no9' ) : __( 'Choose image', 'no9' )
						);
					},
				} ),
				mediaId &&
					el(
						Button,
						{ variant: 'link', isDestructive: true, onClick: onClear, style: { marginLeft: '8px' } },
						clearLabel
					)
			)
		);
	}

	function Edit( props ) {
		var a = props.attributes;
		var set = props.setAttributes;
		var opensInNewTab = NEW_TAB_TARGET === a.linkTarget;

		var blockProps = useBlockProps( {
			className: linkClasses( a ),
			style: { '--no9-icon-button-icon-size': ( a.iconSize || 24 ) + 'px' },
		} );

		function setNewTab( value ) {
			set( {
				linkTarget: value ? NEW_TAB_TARGET : undefined,
				rel: value ? a.rel || NEW_TAB_REL : NEW_TAB_REL === a.rel ? undefined : a.rel,
			} );
		}

		function onLinkChange( value ) {
			set( { url: value.url } );

			if ( opensInNewTab !== !! value.opensInNewTab ) {
				setNewTab( !! value.opensInNewTab );
			}
		}

		function removeLink() {
			set( { url: undefined, linkTarget: undefined, rel: undefined } );
		}

		var linkPopoverContent = LinkControl
			? el( LinkControl, {
					value: { url: a.url, opensInNewTab: opensInNewTab },
					onChange: onLinkChange,
					onRemove: removeLink,
					settings: [ { id: 'opensInNewTab', title: __( 'Open in new tab', 'no9' ) } ],
			  } )
			: el(
					'div',
					{ style: { padding: '16px', minWidth: '260px' } },
					el( TextControl, {
						label: __( 'URL', 'no9' ),
						type: 'url',
						value: a.url || '',
						onChange: function ( v ) { set( { url: v } ); },
						__nextHasNoMarginBottom: true,
					} )
			  );

		var toolbar = el(
			BlockControls,
			{ group: 'block' },
			el(
				ToolbarGroup,
				null,
				el( Dropdown, {
					contentClassName: 'no9-icon-button__link-popover',
					popoverProps: { placement: 'bottom start' },
					renderToggle: function ( obj ) {
						return el( ToolbarButton, {
							name: 'link',
							icon: 'admin-links',
							label: __( 'Link', 'no9' ),
							onClick: obj.onToggle,
							isPressed: obj.isOpen || !! a.url,
						} );
					},
					renderContent: function () { return linkPopoverContent; },
				} ),
				!! a.url &&
					el( ToolbarButton, {
						name: 'unlink',
						icon: 'editor-unlink',
						label: __( 'Unlink', 'no9' ),
						onClick: removeLink,
					} )
			)
		);

		var iconPanel = el(
			PanelBody,
			{ title: __( 'Icon', 'no9' ), initialOpen: true },
			iconPicker(
				__( 'Icon image', 'no9' ),
				a.iconId,
				function ( media ) { set( { iconId: media.id, iconUrl: media.url } ); },
				function () { set( { iconId: undefined, iconUrl: undefined } ); },
				__( 'Remove icon', 'no9' )
			),
			iconPicker(
				__( 'Hover image', 'no9' ),
				a.hoverIconId,
				function ( media ) { set( { hoverIconId: media.id, hoverIconUrl: media.url } ); },
				function () { set( { hoverIconId: undefined, hoverIconUrl: undefined } ); },
				__( 'Remove hover image', 'no9' )
			),
			el( TextControl, {
				label: __( 'Icon alt text', 'no9' ),
				help: __( 'Leave empty when the icon just decorates the button text.', 'no9' ),
				value: a.iconAlt || '',
				onChange: function ( v ) { set( { iconAlt: v } ); },
				__nextHasNoMarginBottom: true,
			} ),
			el( RangeControl, {
				label: __( 'Icon size (px)', 'no9' ),
				value: a.iconSize,
				min: 8,
				max: 96,
				onChange: function ( v ) { set( { iconSize: v || 24 } ); },
				__nextHasNoMarginBottom: true,
			} ),
			el(
				'div',
				null,
				el( 'p', { style: { margin: '0 0 8px', fontWeight: 500 } }, __( 'Icon position', 'no9' ) ),
				el(
					ButtonGroup,
					null,
					el(
						Button,
						{
							variant: 'secondary',
							isPressed: 'after' !== a.iconPosition,
							onClick: function () { set( { iconPosition: 'before' } ); },
						},
						__( 'Before text', 'no9' )
					),
					el(
						Button,
						{
							variant: 'secondary',
							isPressed: 'after' === a.iconPosition,
							onClick: function () { set( { iconPosition: 'after' } ); },
						},
						__( 'After text', 'no9' )
					)
				)
			)
		);

		var widthPanel = el(
			PanelBody,
			{ title: __( 'Width settings', 'no9' ), initialOpen: false },
			el(
				ButtonGroup,
				null,
				WIDTHS.map( function ( width ) {
					return el(
						Button,
						{
							key: width,
							variant: 'secondary',
							isPressed: a.width === width,
							onClick: function () { set( { width: a.width === width ? undefined : width } ); },
						},
						width + '%'
					);
				} )
			)
		);

		var linkPanel = el(
			PanelBody,
			{ title: __( 'Link', 'no9' ), initialOpen: false },
			el( TextControl, {
				label: __( 'URL', 'no9' ),
				type: 'url',
				value: a.url || '',
				onChange: function ( v ) { set( { url: v } ); },
				__nextHasNoMarginBottom: true,
			} ),
			el( ToggleControl, {
				label: __( 'Open in new tab', 'no9' ),
				checked: opensInNewTab,
				onChange: setNewTab,
				__nextHasNoMarginBottom: true,
			} ),
			el( TextControl, {
				label: __( 'Link rel', 'no9' ),
				value: a.rel || '',
				onChange: function ( v ) { set( { rel: v } ); },
				__nextHasNoMarginBottom: true,
			} ),
			el( TextControl, {
				label: __( 'Link title', 'no9' ),
				value: a.title || '',
				onChange: function ( v ) { set( { title: v } ); },
				__nextHasNoMarginBottom: true,
			} )
		);

		var icon = a.iconUrl
			? el(
					'span',
					{ className: 'no9-icon-button__icon' },
					el( 'img', {
						className: 'no9-icon-button__icon-img is-default',
						src: a.iconUrl,
						alt: a.iconAlt || '',
					} ),
					a.hoverIconUrl &&
						el( 'img', {
							className: 'no9-icon-button__icon-img is-hover',
							src: a.hoverIconUrl,
							alt: '',
							'aria-hidden': true,
						} )
			  )
			: null;

		var label = el( RichText, {
			identifier: 'text',
			tagName: 'span',
			className: 'no9-icon-button__label',
			value: a.text,
			onChange: function ( v ) { set( { text: v } ); },
			placeholder: __( 'Add text…', 'no9' ),
			withoutInteractiveFormatting: true,
		} );

		return el(
			Fragment,
			null,
			toolbar,
			el( InspectorControls, null, iconPanel, widthPanel, linkPanel ),
			// The anchor carries useBlockProps (and so the block supports) exactly as
			// render.php gives it get_block_wrapper_attributes(). No href here, so
			// clicking it in the canvas can't navigate the editor away.
			el( 'div', { className: wrapperClasses( a ) }, el( 'a', blockProps, icon, label ) )
		);
	}

	wp.blocks.registerBlockType( 'no9/icon-button', {
		edit: Edit,
		save: function () { return null; },
	} );

	/**
	 * Let the block sit inside core's Buttons block, which otherwise only allows core/button.
	 */
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'no9/icon-button/allow-in-buttons',
		function ( settings, name ) {
			if ( 'core/buttons' !== name ) {
				return settings;
			}

			var allowed = settings.allowedBlocks || [ 'core/button' ];

			if ( -1 !== allowed.indexOf( 'no9/icon-button' ) ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				allowedBlocks: allowed.concat( [ 'no9/icon-button' ] ),
			} );
		}
	);
} )( window.wp );
