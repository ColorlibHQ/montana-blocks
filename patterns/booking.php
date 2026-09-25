<?php
/**
 * Title: Booking request form
 * Slug: montana/booking
 * Categories: montana-sections
 * Keywords: booking, reservation, form, availability
 * Description: The template's booking box over a darkened photograph: check-in and check-out dates, guests, room, name, email and a message. It sends a request; nothing is booked.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/booking.webp' ) ); ?>","dimRatio":60,"overlayColor":"dark","isUserOverlayColor":true,"align":"full","className":"montana-booking","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"book"} -->
<div class="wp-block-cover alignfull montana-booking" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)" id="book"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/booking.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-60 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"montana-booking__box","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-booking__box has-base-background-color has-background"><!-- wp:heading {"className":"montana-booking__title","style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<h2 class="wp-block-heading has-text-align-center montana-booking__title has-large-font-size">Request a Stay</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"montana-booking__note","style":{"typography":{"textAlign":"center"}},"fontSize":"small"} -->
<p class="has-text-align-center montana-booking__note has-small-font-size">Tell us your dates and we will reply within a day with what is free and a price. Nothing is booked or charged until you confirm.</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[montana_form type="booking"]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
