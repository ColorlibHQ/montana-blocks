<?php
/**
 * Title: Footer
 * Slug: montana/footer
 * Keywords: footer, newsletter
 * Block Types: core/template-part/footer
 * Description: Four columns on black: the address, reservations, a few links and the newsletter sign-up, with the copyright line and social links under a hairline.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-footer","backgroundColor":"dark","textColor":"on-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull montana-footer has-on-dark-color has-dark-background-color has-text-color has-background"><!-- wp:columns {"className":"montana-footer__cols","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns montana-footer__cols"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"className":"montana-footer__title","textColor":"overlay","fontSize":"large"} -->
<h2 class="wp-block-heading montana-footer__title has-overlay-color has-text-color has-large-font-size">Address</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"on-dark"} -->
<p class="has-on-dark-color has-text-color">1200 North Shore Road,<br>Lake Arrow Valley, Montana</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-more","textColor":"on-dark"} -->
<p class="montana-more has-on-dark-color has-text-color"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get directions</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:heading {"className":"montana-footer__title","textColor":"overlay","fontSize":"large"} -->
<h2 class="wp-block-heading montana-footer__title has-overlay-color has-text-color has-large-font-size">Reservation</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"montana-footer__lines","textColor":"on-dark"} -->
<p class="montana-footer__lines has-on-dark-color has-text-color"><a href="tel:+14065550147">+1 (406) 555-0147</a><br><a href="mailto:stay@montanaresort.com">stay@montanaresort.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"16.66%"} -->
<div class="wp-block-column" style="flex-basis:16.66%"><!-- wp:heading {"className":"montana-footer__title","textColor":"overlay","fontSize":"large"} -->
<h2 class="wp-block-heading montana-footer__title has-overlay-color has-text-color has-large-font-size">Navigation</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"montana-footer__links"} -->
<ul class="wp-block-list montana-footer__links"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/rooms/' ) ); ?>">Rooms</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">News</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading {"className":"montana-footer__title","textColor":"overlay","fontSize":"large"} -->
<h2 class="wp-block-heading montana-footer__title has-overlay-color has-text-color has-large-font-size">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[montana_form type="newsletter"]
<!-- /wp:shortcode -->

<!-- wp:paragraph {"className":"montana-footer__note","textColor":"on-dark"} -->
<p class="montana-footer__note has-on-dark-color has-text-color">Seasonal offers and news from the lake, four times a year.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide montana-footer__rule"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide montana-footer__rule"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"montana-footer__bottom","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group montana-footer__bottom"><!-- wp:paragraph {"className":"montana-footer__legal","textColor":"on-dark"} -->
<p class="montana-footer__legal has-on-dark-color has-text-color">© Montana Resort. All rights reserved. Theme by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-small-icon-size","className":"is-style-montana-plain","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
<ul class="wp-block-social-links has-small-icon-size is-style-montana-plain"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
