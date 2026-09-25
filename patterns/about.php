<?php
/**
 * Title: About: words and two photographs
 * Slug: montana/about
 * Categories: montana-sections
 * Keywords: about, story, intro
 * Description: The hotel's story beside two photographs set at different heights, with a Learn More link.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-about montana-about\u002d\u002dfirst","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull montana-about montana-about--first" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"41.66%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.66%"><!-- wp:group {"className":"montana-about__text","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-about__text"><!-- wp:group {"className":"montana-title","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-title"><!-- wp:paragraph {"className":"is-style-montana-eyebrow"} -->
<p class="is-style-montana-eyebrow">About Us</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">A Lakeside Hotel <br>in the Mountains</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>Montana Resort sits on the quiet north shore of Lake Arrow, forty minutes from the nearest town and five from the first trailhead. Thirty-two rooms and suites, a restaurant that cooks with what the valley grows, a small spa, and a jetty where the day starts with coffee and ends with the light going pink on the peaks.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-more"} -->
<p class="montana-more"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Learn More</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"58.33%"} -->
<div class="wp-block-column" style="flex-basis:58.33%"><!-- wp:group {"className":"montana-pair montana-pair\u002d\u002dend","layout":{"type":"default"}} -->
<div class="wp-block-group montana-pair montana-pair--end"><!-- wp:columns {"isStackedOnMobile":false,"className":"montana-pair__cols","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile montana-pair__cols"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"aspectRatio":"284/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/about-1.webp' ) ); ?>" alt="A dark timber chalet with a long balcony, on a meadow of daisies below the hills" style="aspect-ratio:284/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"montana-pair__low"} -->
<div class="wp-block-column montana-pair__low"><!-- wp:image {"aspectRatio":"294/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/about-2.webp' ) ); ?>" alt="A hotel lounge with pale armchairs round a low table, bookshelves and framed pictures on timber walls" style="aspect-ratio:294/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
