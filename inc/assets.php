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
 * Enqueue the UTM capture script, which fills hidden utm_* form fields from the URL.
 * Loaded on every page so UTMs are captured wherever a visitor lands.
 */
function no9_enqueue_utm_script() {
	$path = get_theme_file_path( 'assets/js/utm.js' );

	wp_enqueue_script(
		'no9-utm',
		get_theme_file_uri( 'assets/js/utm.js' ),
		array(),
		file_exists( $path ) ? filemtime( $path ) : null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'no9_enqueue_utm_script' );

/**
 * Load compiled theme styles in the block editor.
 */
function no9_editor_styles() {
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'after_setup_theme', 'no9_editor_styles' );
