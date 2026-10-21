<?php
/**
 * Menu display helpers, shared by the Menu Categories block.
 *
 * @package no9
 */

/**
 * Sort priority for known size/temperature variant labels, so "Medium"/"Hot"
 * always display before "Large"/"Iced" regardless of creation order.
 */
function no9_menu_variant_sort_priority() {
	return array(
		'Medium' => 0,
		'Hot'    => 0,
		'Large'  => 1,
		'Iced'   => 1,
	);
}

/**
 * Abbreviate size variant labels for the price row, e.g. "Medium" -> "M",
 * "Large" -> "L". Other variant labels (e.g. Hot/Iced) are left as-is.
 *
 * @param string $label Variant post title.
 */
function no9_menu_variant_label( $label ) {
	$abbreviations = array(
		'Medium' => 'M',
		'Large'  => 'L',
	);

	return isset( $abbreviations[ $label ] ) ? $abbreviations[ $label ] : $label;
}

/**
 * Format a price as it appears on the printed menu: "£5.8", "£7.5", "£5".
 *
 * @param mixed $price Raw price meta value.
 */
function no9_format_price( $price ) {
	$formatted = number_format( (float) $price, 2 );
	$formatted = rtrim( $formatted, '0' );
	$formatted = rtrim( $formatted, '.' );

	return '£' . $formatted;
}

/**
 * Render a single `.cat` card for one Menu Category term, aggregating each
 * product's size/temperature variants into its price row.
 *
 * @param int $term_id menu_category term ID.
 */
function no9_render_menu_category( $term_id ) {
	$term = get_term( $term_id, 'menu_category' );

	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}

	$products = get_posts(
		array(
			'post_type'      => 'menu_item',
			'post_status'    => 'publish',
			'post_parent'    => 0,
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'menu_category',
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		)
	);

	if ( empty( $products ) ) {
		return '';
	}

	$priority = no9_menu_variant_sort_priority();

	ob_start();
	?>
	<section class="cat" id="<?php echo esc_attr( $term->slug ); ?>">
		<h3 class="pill"><?php echo esc_html( $term->name ); ?></h3>
		<?php if ( $term->description ) : ?>
			<p class="blurb"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
		<ul>
			<?php foreach ( $products as $product ) : ?>
				<?php
				$variants = get_posts(
					array(
						'post_type'      => 'menu_item',
						'post_status'    => 'publish',
						'post_parent'    => $product->ID,
						'posts_per_page' => -1,
					)
				);

				usort(
					$variants,
					function ( $a, $b ) use ( $priority ) {
						$a_priority = isset( $priority[ $a->post_title ] ) ? $priority[ $a->post_title ] : 99;
						$b_priority = isset( $priority[ $b->post_title ] ) ? $priority[ $b->post_title ] : 99;

						if ( $a_priority === $b_priority ) {
							return $a->ID <=> $b->ID;
						}

						return $a_priority <=> $b_priority;
					}
				);
				?>
				<li>
					<span class="thumb">
						<?php echo get_the_post_thumbnail( $product->ID, 'thumbnail', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<div class="nm">
						<?php echo esc_html( $product->post_title ); ?>
						<?php if ( trim( $product->post_excerpt ) ) : ?>
							<span class="meta"><?php echo esc_html( trim( $product->post_excerpt ) ); ?></span>
						<?php endif; ?>
					</div>
					<div class="pr">
						<?php if ( $variants ) : ?>
							<?php foreach ( $variants as $variant ) : ?>
								<span class="p">
									<small><?php echo esc_html( no9_menu_variant_label( $variant->post_title ) ); ?></small><?php echo esc_html( no9_format_price( get_post_meta( $variant->ID, 'price', true ) ) ); ?>
								</span>
							<?php endforeach; ?>
						<?php else : ?>
							<span class="p"><?php echo esc_html( no9_format_price( get_post_meta( $product->ID, 'price', true ) ) ); ?></span>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
	return ob_get_clean();
}
