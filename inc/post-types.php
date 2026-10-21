<?php
/**
 * Custom post types.
 *
 * @package no9
 */

/**
 * Register the Menu Item post type.
 */
function no9_register_menu_item_post_type() {
	register_post_type(
		'menu_item',
		array(
			'labels'       => array(
				'name'                  => __( 'Menu Items', 'no9' ),
				'singular_name'         => __( 'Menu Item', 'no9' ),
				'add_new'               => __( 'Add New', 'no9' ),
				'add_new_item'          => __( 'Add New Menu Item', 'no9' ),
				'edit_item'             => __( 'Edit Menu Item', 'no9' ),
				'new_item'              => __( 'New Menu Item', 'no9' ),
				'view_item'             => __( 'View Menu Item', 'no9' ),
				'view_items'            => __( 'View Menu Items', 'no9' ),
				'search_items'          => __( 'Search Menu Items', 'no9' ),
				'not_found'             => __( 'No menu items found.', 'no9' ),
				'not_found_in_trash'    => __( 'No menu items found in Trash.', 'no9' ),
				'all_items'             => __( 'All Menu Items', 'no9' ),
				'archives'              => __( 'Menu Item Archives', 'no9' ),
				'featured_image'        => __( 'Item Image', 'no9' ),
				'set_featured_image'    => __( 'Set item image', 'no9' ),
				'remove_featured_image' => __( 'Remove item image', 'no9' ),
				'use_featured_image'    => __( 'Use as item image', 'no9' ),
				'menu_name'             => __( 'Menu Items', 'no9' ),
			),
			'public'       => false,
			'hierarchical' => true,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-coffee',
			'supports'     => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'      => false,
		)
	);
}
add_action( 'init', 'no9_register_menu_item_post_type' );
