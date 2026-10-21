/**
 * Menu Categories block — editor.
 *
 * Plain ES5-style script using WordPress globals, so it runs without a JS build step.
 * The editor preview is server-rendered (render.php), so the list of products and
 * their size variants only needs to be built once, in PHP.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var InnerBlocks = wp.blockEditor.InnerBlocks;
	var PanelBody = wp.components.PanelBody;
	var CheckboxControl = wp.components.CheckboxControl;
	var Spinner = wp.components.Spinner;
	var ServerSideRender = wp.serverSideRender;
	var useSelect = wp.data.useSelect;

	function Edit( props ) {
		var a = props.attributes;
		var set = props.setAttributes;
		var blockProps = useBlockProps();

		var terms = useSelect( function ( select ) {
			return select( 'core' ).getEntityRecords( 'taxonomy', 'menu_category', {
				per_page: -1,
				orderby: 'name',
				order: 'asc',
			} );
		}, [] );

		function toggleTerm( termId, checked ) {
			var ids = a.categoryIds.slice();
			if ( checked ) {
				if ( ids.indexOf( termId ) === -1 ) {
					ids.push( termId );
				}
			} else {
				ids = ids.filter( function ( id ) {
					return id !== termId;
				} );
			}
			set( { categoryIds: ids } );
		}

		var termControls;
		if ( ! terms ) {
			termControls = el( Spinner, null );
		} else if ( terms.length === 0 ) {
			termControls = el( 'p', null, __( 'No menu categories found.', 'no9' ) );
		} else {
			termControls = terms.map( function ( term ) {
				return el( CheckboxControl, {
					key: term.id,
					label: term.name,
					checked: a.categoryIds.indexOf( term.id ) !== -1,
					onChange: function ( checked ) {
						toggleTerm( term.id, checked );
					},
					__nextHasNoMarginBottom: true,
				} );
			} );
		}

		var inspector = el(
			InspectorControls,
			null,
			el( PanelBody, { title: __( 'Menu categories', 'no9' ) }, termControls )
		);

		var preview = a.categoryIds.length
			? el( ServerSideRender, { block: 'no9/menu-categories', attributes: a } )
			: el( 'p', null, __( 'Select one or more menu categories in the sidebar.', 'no9' ) );

		var innerBlocks = el( InnerBlocks, {
			renderAppender: InnerBlocks.ButtonBlockAppender,
		} );

		return el( wp.element.Fragment, null, inspector, el( 'div', blockProps, preview, innerBlocks ) );
	}

	wp.blocks.registerBlockType( 'no9/menu-categories', {
		edit: Edit,
		save: function () {
			return el( InnerBlocks.Content );
		},
	} );
} )( window.wp );
