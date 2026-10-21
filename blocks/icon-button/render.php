<?php
/**
 * Icon Button block — front-end markup.
 *
 * Mirrors core/button's markup (a `.wp-block-button` wrapper around a
 * `.wp-block-button__link` anchor) so core's Button styles and the Fill/Outline
 * block styles apply, with an image icon that swaps to a second image on hover.
 *
 * @package no9
 *
 * @var array $attributes Block attributes.
 */

$text     = isset( $attributes['text'] ) ? trim( (string) $attributes['text'] ) : '';
$icon_url = isset( $attributes['iconUrl'] ) ? (string) $attributes['iconUrl'] : '';

if ( '' === $text && '' === $icon_url ) {
	return;
}

$hover_url     = isset( $attributes['hoverIconUrl'] ) ? (string) $attributes['hoverIconUrl'] : '';
$icon_position = isset( $attributes['iconPosition'] ) ? $attributes['iconPosition'] : 'before';
$icon_size     = isset( $attributes['iconSize'] ) ? max( 1, (int) $attributes['iconSize'] ) : 24;
$icon_alt      = isset( $attributes['iconAlt'] ) ? (string) $attributes['iconAlt'] : '';
$width         = isset( $attributes['width'] ) ? (int) $attributes['width'] : 0;

// Outer `.wp-block-button` wrapper — layout and width only. Kept in sync with index.js.
$wrapper_classes = array( 'wp-block-button', 'no9-icon-button' );

if ( $width ) {
	$wrapper_classes[] = 'has-custom-width';
	$wrapper_classes[] = 'wp-block-button__width-' . $width;
}

// The anchor carries the block supports, as core/button's anchor does.
$link_classes = array( 'wp-block-button__link', 'wp-element-button', 'no9-icon-button__link' );

if ( '' !== $icon_url ) {
	$link_classes[] = 'has-icon';
	$link_classes[] = 'after' === $icon_position ? 'has-icon-after' : 'has-icon-before';

	if ( '' !== $hover_url ) {
		$link_classes[] = 'has-hover-icon';
	}
}

$link_attributes = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $link_classes ),
		'style' => sprintf( '--no9-icon-button-icon-size:%dpx', $icon_size ),
	)
);

foreach ( array(
	'href'   => empty( $attributes['url'] ) ? '' : esc_url( $attributes['url'] ),
	'target' => empty( $attributes['linkTarget'] ) ? '' : esc_attr( $attributes['linkTarget'] ),
	'rel'    => empty( $attributes['rel'] ) ? '' : esc_attr( $attributes['rel'] ),
	'title'  => empty( $attributes['title'] ) ? '' : esc_attr( $attributes['title'] ),
) as $name => $value ) {
	if ( '' !== $value ) {
		$link_attributes .= sprintf( ' %s="%s"', $name, $value );
	}
}

/**
 * Render one icon image, preferring the attachment (for srcset) over a bare URL.
 *
 * A closure rather than a named function: render.php runs once per block instance.
 */
$render_icon = static function ( $id, $url, $class, $alt, $decorative ) {
	$attr = array(
		'class'    => $class,
		'alt'      => $alt,
		'decoding' => 'async',
	);

	if ( $decorative ) {
		$attr['aria-hidden'] = 'true';
	}

	if ( $id ) {
		$image = wp_get_attachment_image( (int) $id, 'medium', false, $attr );

		if ( $image ) {
			return $image;
		}
	}

	return sprintf(
		'<img class="%1$s" src="%2$s" alt="%3$s" decoding="async"%4$s>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_attr( $alt ),
		$decorative ? ' aria-hidden="true"' : ''
	);
};

$icon = '';

if ( '' !== $icon_url ) {
	$icon = $render_icon(
		isset( $attributes['iconId'] ) ? $attributes['iconId'] : 0,
		$icon_url,
		'no9-icon-button__icon-img is-default',
		$icon_alt,
		false
	);

	if ( '' !== $hover_url ) {
		// Both images sit in the markup and cross-fade in CSS, so the hover
		// image is already loaded by the time the pointer arrives.
		$icon .= $render_icon(
			isset( $attributes['hoverIconId'] ) ? $attributes['hoverIconId'] : 0,
			$hover_url,
			'no9-icon-button__icon-img is-hover',
			'',
			true
		);
	}

	$icon = '<span class="no9-icon-button__icon">' . $icon . '</span>';
}

$label = '' === $text ? '' : '<span class="no9-icon-button__label">' . wp_kses_post( $text ) . '</span>';

printf(
	'<div class="%1$s"><a %2$s>%3$s%4$s</a></div>',
	esc_attr( implode( ' ', $wrapper_classes ) ),
	$link_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$label // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
