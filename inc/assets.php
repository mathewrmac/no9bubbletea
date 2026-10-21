<?php
/**
 * Front-end and editor styles.
 *
 * @package no9
 */

/**
 * Enqueue compiled theme styles on the front end.
 */
function no9_enqueue_styles() {
	$path = get_theme_file_path( 'assets/css/main.css' );

	wp_enqueue_style(
		'no9-main',
		get_theme_file_uri( 'assets/css/main.css' ),
		array(),
		file_exists( $path ) ? filemtime( $path ) : null
	);
}
add_action( 'wp_enqueue_scripts', 'no9_enqueue_styles' );

/**
 * Load compiled theme styles in the block editor.
 */
function no9_editor_styles() {
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'after_setup_theme', 'no9_editor_styles' );
