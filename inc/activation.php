<?php
/**
 * Theme activation hooks.
 *
 * @package no9
 */

/**
 * Flush rewrite rules on theme activation so Menu Item permalinks work.
 */
function no9_flush_rewrite_rules() {
	no9_register_menu_item_post_type();
	no9_register_menu_category_taxonomy();
	no9_register_menu_tag_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'no9_flush_rewrite_rules' );
