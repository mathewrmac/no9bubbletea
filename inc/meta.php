<?php
/**
 * Custom fields for Menu Items.
 *
 * @package no9
 */

/**
 * Register the Price meta field for Menu Items.
 */
function no9_register_menu_item_meta() {
	register_post_meta(
		'menu_item',
		'price',
		array(
			'type'              => 'number',
			'description'       => __( 'Price of the menu item.', 'no9' ),
			'single'            => true,
			'sanitize_callback' => 'no9_sanitize_decimal',
			'default'           => 0,
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'no9_register_menu_item_meta' );

/**
 * Add the Price meta box to the Menu Item edit screen.
 */
function no9_add_menu_item_meta_box() {
	add_meta_box(
		'no9_menu_item_price',
		__( 'Price', 'no9' ),
		'no9_render_menu_item_price_field',
		'menu_item',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'no9_add_menu_item_meta_box' );

/**
 * Render the Price input.
 *
 * @param WP_Post $post Current post object.
 */
function no9_render_menu_item_price_field( $post ) {
	wp_nonce_field( 'no9_save_menu_item_price', 'no9_menu_item_price_nonce' );

	printf(
		'<label for="no9_menu_item_price" class="screen-reader-text">%s</label><input name="no9_menu_item_price" type="number" id="no9_menu_item_price" value="%s" step="any" inputmode="decimal" class="widefat" />',
		esc_html__( 'Price', 'no9' ),
		esc_attr( get_post_meta( $post->ID, 'price', true ) )
	);
}

/**
 * Save the Price meta field.
 *
 * @param int $post_id Post ID.
 */
function no9_save_menu_item_price( $post_id ) {
	if ( ! isset( $_POST['no9_menu_item_price_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['no9_menu_item_price_nonce'] ) ), 'no9_save_menu_item_price' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['no9_menu_item_price'] ) ) {
		return;
	}

	update_post_meta( $post_id, 'price', no9_sanitize_decimal( wp_unslash( $_POST['no9_menu_item_price'] ) ) );
}
add_action( 'save_post_menu_item', 'no9_save_menu_item_price' );
