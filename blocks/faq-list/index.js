/**
 * FAQ List block — editor.
 *
 * Plain ES5-style script using WordPress globals, so it runs without a JS build step.
 * Questions are edited from the sidebar (not inline in the canvas), so the canvas
 * just mirrors the front-end markup built in render.php.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var BLOCK_TAG_RE = /^<(table|ul|ol|div|p|figure)[\s>]/i;

	function wrapAnswer( answer ) {
		var trimmed = ( answer || '' ).trim();
		if ( '' === trimmed || BLOCK_TAG_RE.test( trimmed ) ) {
			return trimmed;
		}
		return '<p>' + trimmed + '</p>';
	}

	var __ = wp.i18n.__;
	var Fragment = wp.element.Fragment;
	var RawHTML = wp.element.RawHTML;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Button = wp.components.Button;

	function Edit( props ) {
		var a = props.attributes;
		var set = props.setAttributes;
		var blockProps = useBlockProps( { className: 'faq-list' } );
		var entries = a.entries || [];

		function updateEntry( index, field, value ) {
			var next = entries.slice();
			var entry = Object.assign( {}, next[ index ] );
			entry[ field ] = value;
			next[ index ] = entry;
			set( { entries: next } );
		}

		function removeEntry( index ) {
			var next = entries.slice();
			next.splice( index, 1 );
			set( { entries: next } );
		}

		function addEntry() {
			set( { entries: entries.concat( [ { question: '', answer: '' } ] ) } );
		}

		function moveEntry( index, direction ) {
			var target = index + direction;
			if ( target < 0 || target >= entries.length ) {
				return;
			}
			var next = entries.slice();
			var moved = next.splice( index, 1 )[ 0 ];
			next.splice( target, 0, moved );
			set( { entries: next } );
		}

		var rows = entries.map( function ( entry, index ) {
			return el(
				'div',
				{ key: index, style: { marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #ddd' } },
				el( TextControl, {
					label: __( 'Question', 'no9' ) + ' ' + ( index + 1 ),
					value: entry.question,
					onChange: function ( v ) { updateEntry( index, 'question', v ); },
					__nextHasNoMarginBottom: true,
				} ),
				el( TextareaControl, {
					label: __( 'Answer', 'no9' ),
					help: __( 'Basic HTML (links, etc.) is allowed.', 'no9' ),
					value: entry.answer,
					rows: 3,
					onChange: function ( v ) { updateEntry( index, 'answer', v ); },
					__nextHasNoMarginBottom: true,
				} ),
				el(
					'div',
					{ style: { display: 'flex', gap: '8px', marginTop: '8px' } },
					el( Button, {
						variant: 'secondary',
						size: 'small',
						disabled: index === 0,
						onClick: function () { moveEntry( index, -1 ); },
					}, __( 'Move up', 'no9' ) ),
					el( Button, {
						variant: 'secondary',
						size: 'small',
						disabled: index === entries.length - 1,
						onClick: function () { moveEntry( index, 1 ); },
					}, __( 'Move down', 'no9' ) ),
					el( Button, {
						variant: 'secondary',
						isDestructive: true,
						size: 'small',
						onClick: function () { removeEntry( index ); },
					}, __( 'Remove', 'no9' ) )
				)
			);
		} );

		var inspector = el(
			InspectorControls,
			null,
			el(
				PanelBody,
				{ title: __( 'Questions', 'no9' ), initialOpen: true },
				rows,
				el( Button, { variant: 'primary', onClick: addEntry }, __( 'Add question', 'no9' ) )
			)
		);

		var list = el(
			'div',
			{ className: 'faq-list-items' },
			entries.map( function ( entry, index ) {
				return el(
					'details',
					{ key: index },
					el( 'summary', null, entry.question || __( '(Empty question)', 'no9' ) ),
					el( RawHTML, null, wrapAnswer( entry.answer ) )
				);
			} )
		);

		return el( Fragment, null, inspector, el( 'div', blockProps, list ) );
	}

	wp.blocks.registerBlockType( 'no9/faq-list', {
		edit: Edit,
		save: function () {
			return null;
		},
	} );
} )( window.wp );
