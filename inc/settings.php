<?php
/**
 * Site settings.
 *
 * @package no9
 */

/**
 * Register the Drink Price Difference setting.
 */
function no9_register_settings() {
	register_setting(
		'general',
		'no9_drink_price_difference',
		array(
			'type'              => 'number',
			'description'       => __( 'Price difference between drink sizes.', 'no9' ),
			'sanitize_callback' => 'no9_sanitize_decimal',
			'default'           => 0,
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'no9_register_settings' );

/**
 * Add the Drink Price Difference field to Settings > General.
 */
function no9_add_settings_fields() {
	add_settings_field(
		'no9_drink_price_difference',
		__( 'Drink Price Difference', 'no9' ),
		'no9_render_drink_price_difference_field',
		'general',
		'default',
		array( 'label_for' => 'no9_drink_price_difference' )
	);
}
add_action( 'admin_init', 'no9_add_settings_fields' );

/**
 * Render the Drink Price Difference input.
 */
function no9_render_drink_price_difference_field() {
	printf(
		'<input name="no9_drink_price_difference" type="number" id="no9_drink_price_difference" value="%s" step="any" inputmode="decimal" class="small-text" />',
		esc_attr( get_option( 'no9_drink_price_difference', 0 ) )
	);
}

/**
 * Sanitize a decimal number.
 *
 * @param mixed $value Raw value.
 * @return float
 */
function no9_sanitize_decimal( $value ) {
	return is_numeric( $value ) ? (float) $value : 0.0;
}
