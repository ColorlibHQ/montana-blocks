<?php
/**
 * Title: Contact: map, form and details
 * Slug: montana/contact
 * Categories: montana-sections
 * Keywords: contact, form, map
 * Description: A map, then the message form beside the address, phone and email.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"contact"} -->
<div class="wp-block-group alignfull montana-contact" id="contact" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:html -->
<iframe class="montana-map" title="Map of the lake shore around the hotel" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=-114.4300%2C48.3800%2C-114.2900%2C48.4700&amp;layer=mapnik" style="width:100%;height:480px;border:0"></iframe>
<!-- /wp:html -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"className":"montana-contact__title"} -->
<h2 class="wp-block-heading montana-contact__title">Get in Touch</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"montana-contact__cols","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|70","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns montana-contact__cols"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:shortcode -->
[montana_form type="contact"]
<!-- /wp:shortcode --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"montana-contact__item montana-icon\u002d\u002dhome","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group montana-contact__item montana-icon--home"><!-- wp:paragraph {"className":"montana-contact__main"} -->
<p class="montana-contact__main">1200 North Shore Road, Lake Arrow Valley</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-contact__sub"} -->
<p class="montana-contact__sub">Montana, United States</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"montana-contact__item montana-icon\u002d\u002dphone","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group montana-contact__item montana-icon--phone"><!-- wp:paragraph {"className":"montana-contact__main"} -->
<p class="montana-contact__main"><a href="tel:+14065550147">+1 (406) 555-0147</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-contact__sub"} -->
<p class="montana-contact__sub">Front desk, 7am to 11pm</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"montana-contact__item montana-icon\u002d\u002dmail","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group montana-contact__item montana-icon--mail"><!-- wp:paragraph {"className":"montana-contact__main"} -->
<p class="montana-contact__main"><a href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-contact__sub"} -->
<p class="montana-contact__sub">Send us your question any time</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
