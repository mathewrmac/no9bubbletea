<?php
/**
 * Contact Form 7 tweaks.
 *
 * @package no9
 */

/**
 * Stop CF7 wrapping form markup in <p> and <br> tags; forms supply their own markup.
 * Mail bodies keep the default auto-paragraphs.
 *
 * @param bool  $autop   Whether to apply autop.
 * @param array $options Context, e.g. array( 'for' => 'form' ).
 * @return bool
 */
function no9_wpcf7_disable_form_autop( $autop, $options ) {
	if ( isset( $options['for'] ) && 'form' === $options['for'] ) {
		return false;
	}

	return $autop;
}
add_filter( 'wpcf7_autop_or_not', 'no9_wpcf7_disable_form_autop', 10, 2 );
