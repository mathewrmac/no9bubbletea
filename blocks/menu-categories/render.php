<?php
/**
 * Menu Categories block — front-end markup.
 *
 * Renders one `.cat` card per selected Menu Category term. Within each card,
 * every top-level Menu Item (post_parent = 0) is listed once, with its
 * size/temperature variants (child Menu Items, e.g. Medium/Large, Hot/Iced)
 * aggregated into a single price row. Any inner blocks added in the editor
 * are rendered last, within the column flow.
 *
 * @package no9
 *
 * @var array  $attributes Block attributes.
 * @var string $content    Rendered inner blocks markup.
 */

$category_ids = array_filter( array_map( 'absint', (array) $attributes['categoryIds'] ) );

if ( empty( $category_ids ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();

$terms = array_filter(
	array_map(
		static function ( $term_id ) {
			$term = get_term( $term_id, 'menu_category' );
			return ( $term && ! is_wp_error( $term ) ) ? $term : null;
		},
		$category_ids
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( count( $terms ) > 1 ) : ?>
		<nav class="jump" aria-label="Menu sections">
			<?php foreach ( $terms as $term ) : ?>
				<a href="#<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>
	<?php foreach ( $category_ids as $term_id ) : ?>
		<?php echo no9_render_menu_category( $term_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endforeach; ?>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
