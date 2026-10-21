<?php
/**
 * Feed block — front-end markup.
 *
 * @package no9
 *
 * @var array $attributes Block attributes.
 */

$images = array_filter(
	(array) $attributes['images'],
	function ( $image ) {
		return ! empty( $image['id'] ) && wp_attachment_is_image( (int) $image['id'] );
	}
);

if ( ! $images ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	foreach ( $images as $image ) :
		$id   = (int) $image['id'];
		$full = wp_get_attachment_image_url( $id, 'full' );
		$alt  = isset( $image['alt'] ) ? $image['alt'] : '';
		?>
		<a class="wp-block-no9-feed__item" href="<?php echo esc_url( $full ); ?>">
			<?php
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$id,
				'large',
				false,
				array(
					'alt'           => $alt,
					'loading'       => 'lazy',
					'data-original' => $full,
				)
			);
			?>
		</a>
	<?php endforeach; ?>
</div>
