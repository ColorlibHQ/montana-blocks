<?php
/**
 * Title: Featured rooms: four photographs
 * Slug: montana/featured-rooms
 * Categories: montana-sections
 * Keywords: rooms, suites, accommodation
 * Description: Four rooms edge to edge, two by two, each a photograph with its price from and its name, and a book now link that slides in on hover.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-featured","style":{"spacing":{"padding":{"top":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull montana-featured" style="padding-top:var(--wp--preset--spacing--70)"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"montana-title montana-title\u002d\u002dcenter","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-title montana-title--center"><!-- wp:paragraph {"className":"is-style-montana-eyebrow","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center is-style-montana-eyebrow">Featured Rooms</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Choose a Better Room</h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"full","className":"montana-rooms","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
<div class="wp-block-columns alignfull montana-rooms"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/room-1.webp' ) ); ?>","dimRatio":50,"customGradient":"linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)","contentPosition":"bottom center","className":"montana-room","layout":{"type":"constrained"}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-center montana-room"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-1.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"montana-room__foot","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group montana-room__foot"><!-- wp:group {"className":"montana-room__words","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room__words"><!-- wp:paragraph {"className":"montana-room__price","textColor":"overlay"} -->
<p class="montana-room__price has-overlay-color has-text-color">From $190/night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"montana-room__name","textColor":"overlay","fontSize":"x-large"} -->
<h3 class="wp-block-heading montana-room__name has-overlay-color has-text-color has-x-large-font-size">Lake View Room</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"montana-room__link","textColor":"overlay"} -->
<p class="montana-room__link has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/room-2.webp' ) ); ?>","dimRatio":50,"customGradient":"linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)","contentPosition":"bottom center","className":"montana-room","layout":{"type":"constrained"}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-center montana-room"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-2.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"montana-room__foot","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group montana-room__foot"><!-- wp:group {"className":"montana-room__words","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room__words"><!-- wp:paragraph {"className":"montana-room__price","textColor":"overlay"} -->
<p class="montana-room__price has-overlay-color has-text-color">From $320/night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"montana-room__name","textColor":"overlay","fontSize":"x-large"} -->
<h3 class="wp-block-heading montana-room__name has-overlay-color has-text-color has-x-large-font-size">Mountain Suite</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"montana-room__link","textColor":"overlay"} -->
<p class="montana-room__link has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"full","className":"montana-rooms","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
<div class="wp-block-columns alignfull montana-rooms"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/room-3.webp' ) ); ?>","dimRatio":50,"customGradient":"linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)","contentPosition":"bottom center","className":"montana-room","layout":{"type":"constrained"}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-center montana-room"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-3.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"montana-room__foot","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group montana-room__foot"><!-- wp:group {"className":"montana-room__words","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room__words"><!-- wp:paragraph {"className":"montana-room__price","textColor":"overlay"} -->
<p class="montana-room__price has-overlay-color has-text-color">From $410/night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"montana-room__name","textColor":"overlay","fontSize":"x-large"} -->
<h3 class="wp-block-heading montana-room__name has-overlay-color has-text-color has-x-large-font-size">Family Chalet</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"montana-room__link","textColor":"overlay"} -->
<p class="montana-room__link has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/room-4.webp' ) ); ?>","dimRatio":50,"customGradient":"linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)","contentPosition":"bottom center","className":"montana-room","layout":{"type":"constrained"}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-center montana-room"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-4.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim wp-block-cover__gradient-background has-background-gradient" style="background:linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"montana-room__foot","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group montana-room__foot"><!-- wp:group {"className":"montana-room__words","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room__words"><!-- wp:paragraph {"className":"montana-room__price","textColor":"overlay"} -->
<p class="montana-room__price has-overlay-color has-text-color">From $260/night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"montana-room__name","textColor":"overlay","fontSize":"x-large"} -->
<h3 class="wp-block-heading montana-room__name has-overlay-color has-text-color has-x-large-font-size">A-Frame Cabin</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"montana-room__link","textColor":"overlay"} -->
<p class="montana-room__link has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">book now</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
