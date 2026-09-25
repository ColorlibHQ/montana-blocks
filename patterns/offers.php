<?php
/**
 * Title: Offers: three cards
 * Slug: montana/offers
 * Categories: montana-sections
 * Keywords: offers, packages, deals
 * Description: Three offers, each a photograph that zooms on hover, a title, three facts and a book now button.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-offers","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"offers"} -->
<div class="wp-block-group alignfull montana-offers" id="offers" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"className":"montana-title montana-title\u002d\u002dcenter","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-title montana-title--center"><!-- wp:paragraph {"className":"is-style-montana-eyebrow","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center is-style-montana-eyebrow">Our Offers</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Ongoing Offers</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"montana-offer","layout":{"type":"default"}} -->
<div class="wp-block-group montana-offer"><!-- wp:image {"aspectRatio":"362/350","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-offer__photo"} -->
<figure class="wp-block-image size-large montana-offer__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/offer-1.webp' ) ); ?>" alt="Two round wicker armchairs on a covered balcony, looking over misty mountains" style="aspect-ratio:362/350;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"className":"montana-offer__title"} -->
<h3 class="wp-block-heading montana-offer__title">Stay three nights, <br>pay for two</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>Any room or suite</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Breakfast for two every morning</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sunday to Thursday arrivals</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline","style":{"dimensions":{"width":"100%"}}} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"montana-offer","layout":{"type":"default"}} -->
<div class="wp-block-group montana-offer"><!-- wp:image {"aspectRatio":"362/350","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-offer__photo"} -->
<figure class="wp-block-image size-large montana-offer__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/offer-2.webp' ) ); ?>" alt="A barrel sauna and a wooden hot tub beside an old timber hut in a green meadow below the forest" style="aspect-ratio:362/350;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"className":"montana-offer__title"} -->
<h3 class="wp-block-heading montana-offer__title">A spa weekend <br>for two</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>Two nights in a Lake View Room</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Two sixty-minute massages</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sauna and hot tub every evening</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline","style":{"dimensions":{"width":"100%"}}} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"montana-offer","layout":{"type":"default"}} -->
<div class="wp-block-group montana-offer"><!-- wp:image {"aspectRatio":"362/350","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-offer__photo"} -->
<figure class="wp-block-image size-large montana-offer__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/offer-3.webp' ) ); ?>" alt="Wooden rowing boats tied along a jetty on a green mountain lake, grey peaks above" style="aspect-ratio:362/350;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"className":"montana-offer__title"} -->
<h3 class="wp-block-heading montana-offer__title">The family <br>summer week</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>Seven nights in the Family Chalet</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Children under twelve eat free</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Kayaks and bikes included</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline","style":{"dimensions":{"width":"100%"}}} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
