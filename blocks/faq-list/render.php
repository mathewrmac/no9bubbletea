<?php
/**
 * FAQ List block — front-end markup.
 *
 * @package no9
 *
 * @var array $attributes Block attributes.
 */

$entries = isset( $attributes['entries'] ) && is_array( $attributes['entries'] ) ? $attributes['entries'] : array();

$entries = array_filter(
	$entries,
	static function ( $entry ) {
		return ! empty( $entry['question'] ) && '' !== trim( (string) $entry['question'] );
	}
);

if ( empty( $entries ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'faq-list' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="faq-list-items">
		<?php foreach ( $entries as $entry ) : ?>
			<?php
			$answer = isset( $entry['answer'] ) ? trim( (string) $entry['answer'] ) : '';
			if ( '' !== $answer && ! preg_match( '/^<(table|ul|ol|div|p|figure)[\s>]/i', $answer ) ) {
				$answer = '<p>' . $answer . '</p>';
			}
			?>
			<details>
				<summary><?php echo esc_html( $entry['question'] ); ?></summary>
				<?php echo wp_kses_post( $answer ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</details>
		<?php endforeach; ?>
	</div>
</div>
