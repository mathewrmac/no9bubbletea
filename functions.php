<?php
/**
 * No.9 theme functions.
 *
 * @package no9
 */

require_once get_theme_file_path( 'inc/assets.php' );
require_once get_theme_file_path( 'inc/post-types.php' );
require_once get_theme_file_path( 'inc/taxonomies.php' );
require_once get_theme_file_path( 'inc/meta.php' );
require_once get_theme_file_path( 'inc/menu.php' );
require_once get_theme_file_path( 'inc/blocks.php' );
require_once get_theme_file_path( 'inc/activation.php' );
require_once get_theme_file_path( 'inc/settings.php' );
require_once get_theme_file_path( 'inc/contact-form-7.php' );



/**
 * No.9 signup form: both tickboxes are optional, but at least one must be ticked.
 * Add to your child theme's functions.php or a code-snippets plugin
 * (leave out the opening <?php line if you paste it into an existing file).
 */
add_filter( 'wpcf7_validate', 'no9_require_draw_or_marketing', 20, 2 );

function no9_require_draw_or_marketing( $result, $tags ) {
	$names = wp_list_pluck( $tags, 'name' );

	// Only run on the form that has both tickboxes.
	if ( ! in_array( 'draw', $names, true ) || ! in_array( 'consent', $names, true ) ) {
		return $result;
	}

	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return $result;
	}

	$draw    = $submission->get_posted_data( 'draw' );
	$consent = $submission->get_posted_data( 'consent' );

	if ( empty( $draw ) && empty( $consent ) ) {
		foreach ( $tags as $tag ) {
			if ( 'draw' === $tag->name ) {
				$result->invalidate( $tag, 'Tick at least one box: enter the draw, get our offers, or both.' );
				break;
			}
		}
	}

	return $result;
}


/**
 * No.9 signup form: send the right confirmation email (Mail 2)
 * from the theme's "emails" folder, depending on which boxes were ticked.
 *
 * Folder: wp-content/themes/no9/emails/
 *   marketing-confirmation.html           -> offers box only
 *   draw-confirmation.html                -> draw box only
 *   draw-and-marketing-confirmation.html  -> both boxes
 *
 * Your internal notification (Mail 1) is not touched: it uses whatever
 * is set in the form editor.
 *
 * Add to functions.php or a code-snippets plugin (leave out the opening
 * <?php line if you paste it into an existing file). This replaces the
 * earlier version of this snippet, so remove that one first.
 */
add_filter( 'wpcf7_mail_components', 'no9_load_email_templates', 10, 3 );

function no9_load_email_templates( $components, $contact_form, $mail ) {
	// Only change the confirmation email (Mail 2).
	if ( ! $mail instanceof WPCF7_Mail || 'mail_2' !== $mail->name() ) {
		return $components;
	}

	// Only run on the signup form (the one with the "draw" tickbox).
	if ( empty( $contact_form->scan_form_tags( array( 'name' => 'draw' ) ) ) ) {
		return $components;
	}

	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return $components;
	}

	$draw    = ! empty( $submission->get_posted_data( 'draw' ) );
	$consent = ! empty( $submission->get_posted_data( 'consent' ) );

	if ( $draw && $consent ) {
		$file    = 'draw-and-marketing-confirmation.html';
		$subject = "You're in 🧋";
	} elseif ( $draw ) {
		$file    = 'draw-confirmation.html';
		$subject = "You're in the draw 🧋";
	} else {
		$file    = 'marketing-confirmation.html';
		$subject = "You're in 🧋";
	}

	$path = get_stylesheet_directory() . '/emails/' . $file;
	if ( ! is_readable( $path ) ) {
		return $components; // File missing: fall back to the email set in the form editor.
	}

	// Fill in Contact Form 7 mail tags like [email] and [_site_url].
	$body = file_get_contents( $path );
	$body = wpcf7_mail_replace_tags( $body, array( 'html' => true, 'exclude_blank' => false ) );

	// Which month's draw this entry counts towards. Entries before the
	// first draw opens (1 November 2026) count towards November.
	$first_draw = new DateTimeImmutable( '2026-11-01 00:00:00', wp_timezone() );
	$now        = new DateTimeImmutable( 'now', wp_timezone() );
	$draw_month = ( $now < $first_draw )
		? wp_date( 'F', $first_draw->getTimestamp() )
		: wp_date( 'F' );

	$components['body']    = str_replace( '{{draw_month}}', $draw_month, $body );
	$components['subject'] = $subject;

	return $components;
}