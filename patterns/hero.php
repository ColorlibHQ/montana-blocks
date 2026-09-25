<?php
/**
 * Title: Hero: photograph slider
 * Slug: montana/hero
 * Categories: montana-sections
 * Keywords: hero, slider, banner
 * Description: Two full-screen photographs that take turns, each with the hotel's name and one line, and arrows on a wide screen.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-carousel-frame montana-hero-frame","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull montana-carousel-frame montana-hero-frame"><!-- wp:group {"className":"montana-carousel montana-carousel\u002d\u002dhero","layout":{"type":"default"}} -->
<div class="wp-block-group montana-carousel montana-carousel--hero"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-1.webp' ) ); ?>","dimRatio":30,"overlayColor":"dark","isUserOverlayColor":true,"focalPoint":{"x":0.62,"y":0.5},"minHeight":100,"minHeightUnit":"vh","className":"montana-hero montana-slide","layout":{"type":"constrained"}} -->
<div class="wp-block-cover montana-hero montana-slide" style="min-height:100vh"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-1.webp' ) ); ?>" style="object-position:62% 50%" data-object-fit="cover" data-object-position="62% 50%"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"className":"montana-hero__title","style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"colossal"} -->
<h1 class="wp-block-heading has-text-align-center montana-hero__title has-overlay-color has-text-color has-colossal-font-size">Montana Resort</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"montana-hero__line","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<p class="has-text-align-center montana-hero__line has-overlay-color has-text-color">A lakeside hotel below the peaks</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-2.webp' ) ); ?>","dimRatio":30,"overlayColor":"dark","isUserOverlayColor":true,"focalPoint":{"x":0.62,"y":0.55},"minHeight":100,"minHeightUnit":"vh","className":"montana-hero montana-slide","layout":{"type":"constrained"}} -->
<div class="wp-block-cover montana-hero montana-slide" style="min-height:100vh"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-2.webp' ) ); ?>" style="object-position:62% 55%" data-object-fit="cover" data-object-position="62% 55%"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"className":"montana-hero__title","style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"colossal"} -->
<h2 class="wp-block-heading has-text-align-center montana-hero__title has-overlay-color has-text-color has-colossal-font-size">Life is Beautiful</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"montana-hero__line","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<p class="has-text-align-center montana-hero__line has-overlay-color has-text-color">Slow mornings, clear water, long days outside</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
