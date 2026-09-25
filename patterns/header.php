<?php
/**
 * Title: Header
 * Slug: montana/header
 * Keywords: header, navigation
 * Block Types: core/template-part/header
 * Description: The template's header, laid over the first photograph: the menu on the left, the mark in the middle, social links and the booking button on the right. It turns black once the page scrolls.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-header","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull montana-header"><!-- wp:group {"className":"montana-header__row","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group montana-header__row"><!-- wp:group {"className":"montana-header__nav is-layout-flex","layout":{"type":"flex"}} -->
<div class="wp-block-group montana-header__nav is-layout-flex"><!-- wp:navigation {"className":"montana-nav","layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap"}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"montana-header__brand","layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-header__brand"><!-- wp:site-logo {"width":112} /-->

<!-- wp:paragraph {"placeholder":" ","className":"montana-logo__mark","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center montana-logo__mark">M</p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"level":0,"style":{"typography":{"textAlign":"center"}}} /-->

<!-- wp:paragraph {"placeholder":" ","className":"montana-logo__word","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center montana-logo__word">Resort</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"montana-header__end","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
<div class="wp-block-group montana-header__end"><!-- wp:social-links {"size":"has-small-icon-size","className":"is-style-montana-plain","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
<ul class="wp-block-social-links has-small-icon-size is-style-montana-plain"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"montana-scheme-toggle"} -->
<div class="wp-block-button montana-scheme-toggle"><a class="wp-block-button__link wp-element-button" href="#"><span class="screen-reader-text">Switch between light and dark mode</span></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"montana-header__book"} -->
<div class="wp-block-button montana-header__book"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">Book A Room</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
