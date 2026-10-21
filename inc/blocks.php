<?php
/**
 * Custom blocks.
 *
 * @package no9
 */

/**
 * Register theme blocks.
 */
function no9_register_blocks() {
	// Viewer.js (vendored in assets/vendor/viewerjs) — lightbox for the Feed block.
	wp_register_script( 'no9-viewerjs', get_theme_file_uri( 'assets/vendor/viewerjs/viewer.min.js' ), array(), '1.15.0', true );
	wp_register_style( 'no9-viewerjs', get_theme_file_uri( 'assets/vendor/viewerjs/viewer.min.css' ), array(), '1.15.0' );

	register_block_type( get_theme_file_path( 'blocks/logo-badge' ) );
	register_block_type( get_theme_file_path( 'blocks/menu-categories' ) );
	register_block_type( get_theme_file_path( 'blocks/faq-list' ) );
	register_block_type( get_theme_file_path( 'blocks/feed' ) );
	register_block_type( get_theme_file_path( 'blocks/icon-button' ) );

	wp_add_inline_script(
		'no9-logo-badge-editor-script',
		'window.no9LogoBadge = ' . wp_json_encode( array( 'defaultLogo' => get_theme_file_uri( 'assets/images/logo.png' ) ) ) . ';',
		'before'
	);
}
add_action( 'init', 'no9_register_blocks' );
