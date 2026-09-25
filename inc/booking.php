<?php
/**
 * The theme's own forms: room booking request, contact and newsletter.
 *
 * A hotel's site exists to fill its rooms, so Montana ships the booking
 * request form rather than requiring a plugin for the one thing a visitor came
 * to do. It is a REQUEST: it checks the dates make sense and emails them to
 * the front desk. It does not know what is free and takes no payment, and its
 * wording says so. One shortcode draws all three forms:
 *
 *     [montana_form type="booking" button="Send request"]
 *     [montana_form type="contact"]
 *     [montana_form type="newsletter"]
 *
 * It is a **shortcode**, not inline PHP in a pattern. That is not a style
 * preference: inc/front-page-setup.php expands patterns into real post content
 * so the copy stays editable, and PHP inside stored post content never runs.
 * A pattern that rendered the form inline would freeze whatever it produced at
 * activation into the page forever.
 *
 * A theme must not create database tables or register a post type, so Montana
 * stores nothing. It validates, then hands the submission to whoever wants it:
 *
 *   - `montana_form_handlers` — return true from any handler to say the
 *     submission has been dealt with, and the built-in email is skipped. This
 *     is where a booking system, a mailing list or a webhook hooks in.
 *   - `montana_form_email_to` / `_subject` / `_body` — adjust the email the
 *     theme sends when nothing else claims the submission.
 *   - `montana_form_fields` — add, remove or relabel fields, per form type.
 *
 * The forms work with JavaScript off: each is a plain POST to the same URL,
 * answered with a redirect carrying the result.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;

const MONTANA_FORM_ACTION = 'montana_form';

/**
 * The form types and their fields.
 *
 * @param string $type booking, contact or newsletter.
 * @return array<string, array<string, mixed>>
 */
function montana_form_fields( $type ) {
	$name  = array(
		'label'        => __( 'Your name', 'montana' ),
		'type'         => 'text',
		'autocomplete' => 'name',
		'required'     => true,
	);
	$email = array(
		'label'        => __( 'Email address', 'montana' ),
		'type'         => 'email',
		'autocomplete' => 'email',
		'required'     => true,
	);

	$today = current_time( 'Y-m-d' );

	$sets = array(
		'booking'    => array(
			'checkin'  => array(
				'label'    => __( 'Check-in', 'montana' ),
				'type'     => 'date',
				'required' => true,
				'min'      => $today,
			),
			'checkout' => array(
				'label'    => __( 'Check-out', 'montana' ),
				'type'     => 'date',
				'required' => true,
				'min'      => gmdate( 'Y-m-d', strtotime( $today . ' +1 day' ) ),
			),
			'guests'   => array(
				'label'    => __( 'Guests', 'montana' ),
				'type'     => 'select',
				'required' => true,
				'options'  => montana_guest_options(),
			),
			'room'     => array(
				'label'    => __( 'Room', 'montana' ),
				'type'     => 'select',
				'required' => false,
				'options'  => montana_room_options(),
			),
			'name'     => $name,
			'email'    => $email,
			'message'  => array(
				'label'    => __( 'Anything we should know (arrival time, a cot, dietary needs)', 'montana' ),
				'type'     => 'textarea',
				'required' => false,
			),
		),
		'contact'    => array(
			'message' => array(
				'label'    => __( 'Message', 'montana' ),
				'type'     => 'textarea',
				'required' => true,
			),
			'name'    => $name,
			'email'   => $email,
			'subject' => array(
				'label'    => __( 'Subject', 'montana' ),
				'type'     => 'text',
				'required' => false,
			),
		),
		'newsletter' => array(
			'email' => $email,
		),
	);

	$fields = isset( $sets[ $type ] ) ? $sets[ $type ] : array();

	/**
	 * Filters the fields of one of the theme's forms.
	 *
	 * @param array  $fields Field definitions keyed by name.
	 * @param string $type   booking, contact or newsletter.
	 */
	return apply_filters( 'montana_form_fields', $fields, $type );
}

/**
 * The known form types.
 *
 * @return string[]
 */
function montana_form_types() {
	return array( 'booking', 'contact', 'newsletter' );
}

/**
 * The rooms a guest can ask for, value => label.
 *
 * The four rooms the starter pages describe. A site with other rooms changes
 * them here, through `montana_room_options`, and the Rooms page's words in the
 * editor; the two are not linked.
 *
 * @return array<string, string>
 */
function montana_room_options() {
	$rooms = array(
		''               => __( 'Any room', 'montana' ),
		'lake-view-room' => __( 'Lake View Room', 'montana' ),
		'mountain-suite' => __( 'Mountain Suite', 'montana' ),
		'family-chalet'  => __( 'Family Chalet', 'montana' ),
		'a-frame-cabin'  => __( 'A-Frame Cabin', 'montana' ),
	);

	/**
	 * Filters the rooms offered in the booking request form.
	 *
	 * @param array<string, string> $rooms Value => label. An empty value means no preference.
	 */
	return apply_filters( 'montana_room_options', $rooms );
}

/**
 * How many guests a request can be for, value => label.
 *
 * @return array<string, string>
 */
function montana_guest_options() {
	$guests = array();
	for ( $n = 1; $n <= 6; $n++ ) {
		/* translators: %d: number of guests. */
		$guests[ (string) $n ] = sprintf( _n( '%d guest', '%d guests', $n, 'montana' ), $n );
	}

	/**
	 * Filters the guest counts offered in the booking request form.
	 *
	 * @param array<string, string> $guests Value => label.
	 */
	return apply_filters( 'montana_guest_options', $guests );
}

/**
 * Render one field, label included.
 *
 * Every field has a real <label for>. The compact and inline layouts hide it
 * visually and show the same words as the placeholder, as the design does —
 * the label is still what a screen reader announces.
 *
 * @param string $form  The form's id prefix (`montana-booking`, `montana-newsletter-2`).
 * @param string $name  Field name.
 * @param array  $field Field definition.
 * @param bool   $quiet Whether the label is visually hidden.
 * @return string
 */
function montana_form_field( $form, $name, $field, $quiet ) {
	$id       = $form . '-' . $name;
	$required = ! empty( $field['required'] );

	$attributes = array(
		'id'    => $id,
		'name'  => $name,
		'class' => 'montana-field__control',
	);

	if ( $required ) {
		$attributes['required'] = 'required';
	}
	if ( ! empty( $field['autocomplete'] ) ) {
		$attributes['autocomplete'] = $field['autocomplete'];
	}
	if ( isset( $field['min'] ) ) {
		$attributes['min'] = $field['min'];
	}
	// Dates and choices keep a visible label: a date input has no room for a
	// placeholder, and a select shows its first option instead of its name.
	$visible = in_array( $field['type'], array( 'date', 'select' ), true );
	if ( $quiet && ! $visible ) {
		$attributes['placeholder'] = $field['label'] . ( $required ? ' *' : '' );
	}

	$label_class = 'montana-field__label' . ( $quiet && ! $visible ? ' screen-reader-text' : '' );

	$out  = '<p class="montana-field montana-field--' . esc_attr( $name ) . '">';
	$out .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] );
	if ( $required ) {
		$out .= ' <span class="montana-field__required" aria-hidden="true">*</span>';
	}
	$out .= '</label>';

	if ( 'textarea' === $field['type'] ) {
		$attributes['rows'] = 4;
		$out               .= '<textarea' . montana_attributes( $attributes ) . '></textarea>';
	} elseif ( 'select' === $field['type'] ) {
		unset( $attributes['placeholder'] );
		$out .= '<select' . montana_attributes( $attributes ) . '>';
		foreach ( $field['options'] as $value => $text ) {
			$out .= '<option value="' . esc_attr( $value ) . '">' . esc_html( $text ) . '</option>';
		}
		$out .= '</select>';
	} else {
		$attributes['type'] = $field['type'];
		$out               .= '<input' . montana_attributes( $attributes ) . '>';
	}

	return $out . '</p>';
}

/**
 * Build an attribute string from a map, escaping every value.
 *
 * @param array $attributes Attribute map.
 * @return string
 */
function montana_attributes( $attributes ) {
	$out = '';
	foreach ( $attributes as $key => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	return $out;
}

/**
 * The form shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function montana_form( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'type'   => 'booking',
			'layout' => '',
			'button' => '',
		),
		$atts,
		'montana_form'
	);

	$type = in_array( $atts['type'], montana_form_types(), true ) ? $atts['type'] : 'booking';

	$defaults = array(
		'booking'    => array( 'compact', __( 'Send request', 'montana' ) ),
		'contact'    => array( 'split', __( 'Send message', 'montana' ) ),
		'newsletter' => array( 'inline', __( 'Sign up', 'montana' ) ),
	);

	$layout = in_array( $atts['layout'], array( 'compact', 'split', 'inline', 'block', 'stacked' ), true ) ? $atts['layout'] : $defaults[ $type ][0];
	$label  = '' !== $atts['button'] ? $atts['button'] : $defaults[ $type ][1];
	$quiet  = 'stacked' !== $layout;

	// A page can carry the same form twice -- the blog's sidebar and footer
	// both have a newsletter sign-up -- and ids must not repeat. The first
	// keeps the plain id; later ones are numbered.
	static $count = array();
	$count[ $type ] = isset( $count[ $type ] ) ? $count[ $type ] + 1 : 1;
	$prefix = 'montana-' . $type . ( $count[ $type ] > 1 ? '-' . $count[ $type ] : '' );
	$anchor = 'montana-form-' . $type . ( $count[ $type ] > 1 ? '-' . $count[ $type ] : '' );

	$out  = '<form class="montana-form montana-form--' . esc_attr( $layout ) . ' montana-form--' . esc_attr( $type ) . '" method="post" action="' . esc_url( montana_current_url() ) . '#' . esc_attr( $anchor ) . '">';
	$out .= '<span id="' . esc_attr( $anchor ) . '" class="montana-form__anchor"></span>';
	$out .= montana_form_notice( $type, $anchor );
	// Written out rather than with wp_nonce_field(), which gives the field an
	// id: two forms on one page would repeat it.
	$out .= '<input type="hidden" name="montana_form_nonce" value="' . esc_attr( wp_create_nonce( MONTANA_FORM_ACTION ) ) . '">';
	$out .= '<input type="hidden" name="action" value="' . esc_attr( MONTANA_FORM_ACTION ) . '">';
	$out .= '<input type="hidden" name="montana_form_type" value="' . esc_attr( $type ) . '">';
	$out .= '<input type="hidden" name="montana_form_anchor" value="' . esc_attr( $anchor ) . '">';

	// The page to come back to, carried explicitly.
	//
	// wp_get_referer() cannot do this job: it returns false whenever the
	// referer matches the current request URI, which is always the case for a
	// form that posts to its own page, and the visitor would land on the front
	// page with no form in sight. Validated with wp_validate_redirect() on the
	// way back out, so a crafted value cannot send anyone off-site.
	$out .= '<input type="hidden" name="montana_redirect" value="' . esc_url( montana_current_url() ) . '">';

	// A field no visitor sees and no visitor fills in. Bots fill everything.
	$out .= '<p class="montana-form__trap" aria-hidden="true">';
	$out .= '<label for="' . esc_attr( $prefix ) . '-website">' . esc_html__( 'Leave this field empty', 'montana' ) . '</label>';
	$out .= '<input id="' . esc_attr( $prefix ) . '-website" type="text" name="montana_website" tabindex="-1" autocomplete="off">';
	$out .= '</p>';

	$out .= '<div class="montana-form__grid">';
	foreach ( montana_form_fields( $type ) as $name => $field ) {
		$out .= montana_form_field( $prefix, $name, $field, $quiet );
	}

	if ( 'inline' === $layout ) {
		// The template's footer sign-up: the button sits inside the field's
		// right-hand end.
		$out .= '<button type="submit" class="montana-form__inline-button wp-element-button">' . esc_html( $label ) . '</button>';
		$out .= '</div>';
	} else {
		$out .= '</div>';
		$out .= '<p class="montana-form__actions">';
		$out .= '<button type="submit" class="wp-block-button__link wp-element-button">' . esc_html( $label ) . '</button>';
		$out .= '</p>';
	}

	$out .= '</form>';

	return $out;
}
add_shortcode( 'montana_form', 'montana_form' );

/**
 * The current URL, without any previous result parameters.
 *
 * @return string
 */
function montana_current_url() {
	$url = is_singular() ? get_permalink() : '';
	if ( ! $url ) {
		$url = home_url( add_query_arg( array() ) );
	}
	return remove_query_arg( array( 'montana-form', 'montana-form-type', 'montana-form-at' ), $url );
}

/**
 * The message shown after a submission, on the form that was submitted.
 *
 * @param string $type Form type.
 * @return string
 */
function montana_form_notice( $type, $anchor = '' ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display only.
	$result = isset( $_GET['montana-form'] ) ? sanitize_key( wp_unslash( $_GET['montana-form'] ) ) : '';
	$which  = isset( $_GET['montana-form-type'] ) ? sanitize_key( wp_unslash( $_GET['montana-form-type'] ) ) : '';
	$where  = isset( $_GET['montana-form-at'] ) ? sanitize_key( wp_unslash( $_GET['montana-form-at'] ) ) : '';
	// phpcs:enable

	// Only on the form that was sent: of two newsletter sign-ups on one page,
	// the one in the footer should not thank a visitor who used the sidebar's.
	if ( $which !== $type || ( '' !== $where && '' !== $anchor && $where !== $anchor ) ) {
		return '';
	}

	$sent = array(
		'booking'    => __( 'Thank you: your request is with the front desk. We will reply within a day with what is free and a price. Nothing is booked until you confirm.', 'montana' ),
		'contact'    => __( 'Thank you: your message is with us. We will reply by email shortly.', 'montana' ),
		'newsletter' => __( 'Thank you: you are on the list for the next newsletter.', 'montana' ),
	);

	$messages = array(
		'sent'    => array( 'ok', $sent[ $type ] ),
		'invalid' => array( 'error', __( 'Please check the form: every field marked * needs an answer.', 'montana' ) ),
		'email'   => array( 'error', __( 'That email address does not look right.', 'montana' ) ),
		'dates'   => array( 'error', __( 'Please check the dates: check-out has to be at least one night after check-in.', 'montana' ) ),
		'past'    => array( 'error', __( 'Please check the dates: check-in cannot be earlier than today.', 'montana' ) ),
		'baddate' => array( 'error', __( 'Please check the dates: one of them is not a date on the calendar.', 'montana' ) ),
		'choice'  => array( 'error', __( 'Please choose the guests and the room from the lists.', 'montana' ) ),
		'failed'  => array( 'error', __( 'Sorry, that could not be sent. Please call or email the front desk instead.', 'montana' ) ),
		'expired' => array( 'error', __( 'That form had been open a while and expired. Please send it again.', 'montana' ) ),
	);

	if ( ! isset( $messages[ $result ] ) ) {
		return '';
	}

	list( $kind, $text ) = $messages[ $result ];

	return '<p class="montana-form__notice is-' . esc_attr( $kind ) . '" role="status">' . esc_html( $text ) . '</p>';
}

/**
 * Handle a submitted form.
 *
 * Runs on `template_redirect` so it can redirect before anything is sent —
 * the POST/redirect/GET that stops a refresh from sending it twice.
 */
function montana_handle_form() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked immediately below.
	if ( ! isset( $_POST['action'] ) || MONTANA_FORM_ACTION !== $_POST['action'] ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce checked below; these only select the form and where to go.
	$type     = isset( $_POST['montana_form_type'] ) ? sanitize_key( wp_unslash( $_POST['montana_form_type'] ) ) : '';
	$posted   = isset( $_POST['montana_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['montana_redirect'] ) ) : '';
	$anchor   = isset( $_POST['montana_form_anchor'] ) ? sanitize_key( wp_unslash( $_POST['montana_form_anchor'] ) ) : '';
	// phpcs:enable
	$type     = in_array( $type, montana_form_types(), true ) ? $type : 'booking';
	$redirect = wp_validate_redirect( $posted, home_url( '/' ) );
	$redirect = remove_query_arg( array( 'montana-form', 'montana-form-type', 'montana-form-at' ), $redirect );
	// Only an anchor this form could have drawn; anything else falls back.
	if ( ! preg_match( '/^montana-form-' . $type . '(-[0-9]+)?$/', $anchor ) ) {
		$anchor = 'montana-form-' . $type;
	}
	$GLOBALS['montana_form_anchor'] = $anchor;

	$nonce = isset( $_POST['montana_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['montana_form_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, MONTANA_FORM_ACTION ) ) {
		montana_form_redirect( $redirect, $type, 'expired' );
	}

	// Silently accept and discard anything that filled the honeypot: telling a
	// bot it failed only teaches it to try again differently.
	if ( ! empty( $_POST['montana_website'] ) ) {
		montana_form_redirect( $redirect, $type, 'sent' );
	}

	$submission = array();
	foreach ( montana_form_fields( $type ) as $name => $field ) {
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised by type below.
		$raw = is_string( $raw ) ? $raw : '';

		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( 'email' === $field['type'] ) {
			$value = sanitize_email( $raw );
		} elseif ( 'select' === $field['type'] ) {
			// Only one of the offered values. A value that is not on the list
			// is someone posting around the form.
			$value = sanitize_text_field( $raw );
			if ( ! array_key_exists( $value, $field['options'] ) ) {
				montana_form_redirect( $redirect, $type, 'choice' );
			}
		} else {
			$value = sanitize_text_field( $raw );
		}

		// Something typed that is not an address gets the email message, not
		// "every field marked * needs an answer": sanitize_email() empties it.
		if ( 'email' === $field['type'] && '' !== trim( $raw ) && ! is_email( $value ) ) {
			montana_form_redirect( $redirect, $type, 'email' );
		}

		if ( ! empty( $field['required'] ) && '' === $value ) {
			montana_form_redirect( $redirect, $type, 'invalid' );
		}

		$submission[ $name ] = $value;
	}

	if ( ! empty( $submission['email'] ) && ! is_email( $submission['email'] ) ) {
		montana_form_redirect( $redirect, $type, 'email' );
	}

	if ( 'booking' === $type ) {
		$problem = montana_booking_dates_problem( $submission );
		if ( $problem ) {
			montana_form_redirect( $redirect, $type, $problem );
		}
	}

	/**
	 * Filters whether a submission of one form has already been handled:
	 * `montana_booking_handlers`, `montana_contact_handlers` or
	 * `montana_newsletter_handlers`.
	 *
	 * Return true from any handler and Montana will not send its own email --
	 * which is how a reservation system, a mailing list or a webhook takes over,
	 * and how a demo site swallows mail.
	 *
	 * @param bool  $handled    Whether something has dealt with it.
	 * @param array $submission The sanitised submission.
	 */
	$handled = apply_filters( 'montana_' . $type . '_handlers', false, $submission );

	/**
	 * Filters whether any of the theme's form submissions has been handled.
	 *
	 * The same as the per-form filters above, for code that wants all three.
	 *
	 * @param bool   $handled    Whether something has dealt with it.
	 * @param array  $submission The sanitised submission.
	 * @param string $type       booking, contact or newsletter.
	 */
	$handled = apply_filters( 'montana_form_handlers', $handled, $submission, $type );

	if ( ! $handled ) {
		$handled = montana_form_email( $submission, $type );
	}

	montana_form_redirect( $redirect, $type, $handled ? 'sent' : 'failed' );
}
add_action( 'template_redirect', 'montana_handle_form' );

/**
 * Redirect back to the form with a result, and stop.
 *
 * @param string $url    Where to go.
 * @param string $type   Form type.
 * @param string $result Result key.
 */
function montana_form_redirect( $url, $type, $result ) {
	$anchor = isset( $GLOBALS['montana_form_anchor'] ) ? $GLOBALS['montana_form_anchor'] : 'montana-form-' . $type;
	$url    = add_query_arg(
		array(
			'montana-form'      => $result,
			'montana-form-type' => $type,
			'montana-form-at'   => $anchor,
		),
		$url
	);
	wp_safe_redirect( $url . '#' . $anchor, 303 );
	exit;
}

/**
 * What is wrong with a booking request's dates, if anything.
 *
 * The date inputs already refuse most mistakes in the browser, but a request
 * can arrive without the browser's help, so the server checks for itself:
 * both dates real calendar dates, check-in no earlier than today in the site's
 * timezone, and check-out at least one night later.
 *
 * @param array $submission Sanitised submission.
 * @return string '' when the dates are fine, or a notice key.
 */
function montana_booking_dates_problem( $submission ) {
	$in  = montana_parse_date( isset( $submission['checkin'] ) ? $submission['checkin'] : '' );
	$out = montana_parse_date( isset( $submission['checkout'] ) ? $submission['checkout'] : '' );

	if ( ! $in || ! $out ) {
		return 'baddate';
	}
	if ( $in->format( 'Y-m-d' ) < current_time( 'Y-m-d' ) ) {
		return 'past';
	}
	if ( $out <= $in ) {
		return 'dates';
	}
	return '';
}

/**
 * A Y-m-d date as a DateTimeImmutable, or null when it is not a real date.
 *
 * `2026-02-30` parses in PHP as 2 March; comparing the formatted result with
 * the input is what rejects it.
 *
 * @param string $value Date string.
 * @return DateTimeImmutable|null
 */
function montana_parse_date( $value ) {
	$date = DateTimeImmutable::createFromFormat( '!Y-m-d', (string) $value );
	return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $date : null;
}

/**
 * Email the submission to the site's admin address.
 *
 * From: is the site's own address, never the visitor's. Putting the visitor
 * there fails SPF and DMARC — the mail is sent by this server, not by their
 * provider — and it is the classic route to header injection. Reply-To carries
 * them instead, and wp_mail() rejects a header containing a newline.
 *
 * @param array  $submission Sanitised submission.
 * @param string $type       Form type.
 * @return bool
 */
function montana_form_email( $submission, $type ) {
	$to = apply_filters( 'montana_form_email_to', get_option( 'admin_email' ), $type );

	if ( ! $to || ! is_email( $to ) ) {
		return false;
	}

	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$what = array(
		/* translators: %s: site name. */
		'booking'    => __( '[%s] Room booking request', 'montana' ),
		/* translators: %s: site name. */
		'contact'    => __( '[%s] Website message', 'montana' ),
		/* translators: %s: site name. */
		'newsletter' => __( '[%s] Newsletter sign-up', 'montana' ),
	);
	$subject = sprintf( $what[ $type ], $site );
	$subject = apply_filters( 'montana_form_email_subject', $subject, $submission, $type );

	$lines  = array();
	$fields = montana_form_fields( $type );
	foreach ( $submission as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$label = isset( $fields[ $name ]['label'] ) ? $fields[ $name ]['label'] : $name;
		// A choice is sent as its label (Mountain Suite), not its value.
		if ( isset( $fields[ $name ]['options'][ $value ] ) ) {
			$value = $fields[ $name ]['options'][ $value ];
		}
		$lines[] = $label . ': ' . $value;
	}

	if ( 'booking' === $type ) {
		$in  = montana_parse_date( $submission['checkin'] );
		$out = montana_parse_date( $submission['checkout'] );
		if ( $in && $out ) {
			$nights = (int) $in->diff( $out )->days;
			/* translators: %d: number of nights. */
			$lines[] = sprintf( _n( '%d night', '%d nights', $nights, 'montana' ), $nights );
		}
	}

	$body = implode( "\n", $lines );
	$body = apply_filters( 'montana_form_email_body', $body, $submission, $type );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $submission['email'] ) && is_email( $submission['email'] ) ) {
		$headers[] = 'Reply-To: ' . $submission['email'];
	}

	return (bool) wp_mail( $to, $subject, $body, $headers );
}
