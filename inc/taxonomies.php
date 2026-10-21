<?php
/**
 * Custom taxonomies.
 *
 * @package no9
 */

/**
 * Register the Menu Category taxonomy for Menu Items.
 */
function no9_register_menu_category_taxonomy() {
	register_taxonomy(
		'menu_category',
		'menu_item',
		array(
			'labels'            => array(
				'name'              => __( 'Menu Categories', 'no9' ),
				'singular_name'     => __( 'Menu Category', 'no9' ),
				'search_items'      => __( 'Search Menu Categories', 'no9' ),
				'all_items'         => __( 'All Menu Categories', 'no9' ),
				'parent_item'       => __( 'Parent Menu Category', 'no9' ),
				'parent_item_colon' => __( 'Parent Menu Category:', 'no9' ),
				'edit_item'         => __( 'Edit Menu Category', 'no9' ),
				'view_item'         => __( 'View Menu Category', 'no9' ),
				'update_item'       => __( 'Update Menu Category', 'no9' ),
				'add_new_item'      => __( 'Add New Menu Category', 'no9' ),
				'new_item_name'     => __( 'New Menu Category Name', 'no9' ),
				'not_found'         => __( 'No menu categories found.', 'no9' ),
				'back_to_items'     => __( '&larr; Back to Menu Categories', 'no9' ),
				'menu_name'         => __( 'Categories', 'no9' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'no9_register_menu_category_taxonomy' );

/**
 * Register the Menu Tag taxonomy for Menu Items.
 */
function no9_register_menu_tag_taxonomy() {
	register_taxonomy(
		'menu_tag',
		'menu_item',
		array(
			'labels'            => array(
				'name'                       => __( 'Menu Tags', 'no9' ),
				'singular_name'              => __( 'Menu Tag', 'no9' ),
				'search_items'               => __( 'Search Menu Tags', 'no9' ),
				'popular_items'              => __( 'Popular Menu Tags', 'no9' ),
				'all_items'                  => __( 'All Menu Tags', 'no9' ),
				'edit_item'                  => __( 'Edit Menu Tag', 'no9' ),
				'view_item'                  => __( 'View Menu Tag', 'no9' ),
				'update_item'                => __( 'Update Menu Tag', 'no9' ),
				'add_new_item'               => __( 'Add New Menu Tag', 'no9' ),
				'new_item_name'              => __( 'New Menu Tag Name', 'no9' ),
				'separate_items_with_commas' => __( 'Separate menu tags with commas', 'no9' ),
				'add_or_remove_items'        => __( 'Add or remove menu tags', 'no9' ),
				'choose_from_most_used'      => __( 'Choose from the most used menu tags', 'no9' ),
				'not_found'                  => __( 'No menu tags found.', 'no9' ),
				'back_to_items'              => __( '&larr; Back to Menu Tags', 'no9' ),
				'menu_name'                  => __( 'Tags', 'no9' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'no9_register_menu_tag_taxonomy' );
