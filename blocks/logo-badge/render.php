<?php
/**
 * Logo Badge block — front-end markup.
 *
 * @package no9
 *
 * @var array $attributes Block attributes.
 */

$path_id  = wp_unique_id( 'no9-badge-path-' );
$size     = max( 120, (int) $attributes['size'] );
$duration = max( 5, (int) $attributes['duration'] );
$alt      = $attributes['alt'];

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'style' => sprintf( '--no9-badge-size:%dpx;--no9-badge-spin:%ds', $size, $duration ),
	)
);

if ( ! empty( $attributes['mediaId'] ) ) {
	$image = wp_get_attachment_image(
		$attributes['mediaId'],
		'medium_large',
		false,
		array(
			'alt'           => $alt,
			'fetchpriority' => 'high',
			'loading'       => false,
		)
	);
} else {
	$image = sprintf(
		'<img src="%s" alt="%s" width="720" height="720" fetchpriority="high">',
		esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ),
		esc_attr( $alt )
	);
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<svg class="wp-block-no9-logo-badge__ring" viewBox="0 0 300 300" aria-hidden="true" focusable="false">
		<defs><path id="<?php echo esc_attr( $path_id ); ?>" d="M150,150 m-128,0 a128,128 0 1,1 256,0 a128,128 0 1,1 -256,0" /></defs>
		<text><textPath href="#<?php echo esc_attr( $path_id ); ?>" textLength="800" lengthAdjust="spacing"><?php echo esc_html( $attributes['ringText'] ); ?></textPath></text>
	</svg>
	<div class="wp-block-no9-logo-badge__disc">
		<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
