<?php
/**
 * Title: Restaurant: two photographs and words
 * Slug: montana/dining
 * Categories: montana-sections
 * Keywords: restaurant, food, dining
 * Description: Two food photographs at different heights beside the restaurant's story.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-about","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull montana-about" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"58.33%"} -->
<div class="wp-block-column" style="flex-basis:58.33%"><!-- wp:group {"className":"montana-pair montana-pair\u002d\u002dstart","layout":{"type":"default"}} -->
<div class="wp-block-group montana-pair montana-pair--start"><!-- wp:columns {"isStackedOnMobile":false,"className":"montana-pair__cols","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|20"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile montana-pair__cols"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"aspectRatio":"284/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dining-1.webp' ) ); ?>" alt="A plated main course with a golden fillet, glazed potatoes, mushrooms and a dark sauce" style="aspect-ratio:284/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"montana-pair__low"} -->
<div class="wp-block-column montana-pair__low"><!-- wp:image {"aspectRatio":"294/400","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dining-2.webp' ) ); ?>" alt="A couple eating dinner at a candlelit wooden table outside, a snow-capped ridge behind them" style="aspect-ratio:294/400;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"41.66%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.66%"><!-- wp:group {"className":"montana-about__text","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-about__text"><!-- wp:group {"className":"montana-title","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-title"><!-- wp:paragraph {"className":"is-style-montana-eyebrow"} -->
<p class="is-style-montana-eyebrow">Delicious Food</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">We Serve Fresh and <br>Delicious Food</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>The Boathouse kitchen buys from four farms in the valley and one fisherman on the lake. Breakfast is on the terrace until eleven, dinner is by the window until the last table wants to leave, and a picnic basket for the trail can be ready by seven in the morning.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"montana-more"} -->
<p class="montana-more"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">See the menu</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
