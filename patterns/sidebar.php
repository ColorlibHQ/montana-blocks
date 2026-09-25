<?php
/**
 * Title: Sidebar
 * Slug: montana/sidebar
 * Keywords: sidebar
 * Description: Search, categories, recent posts, tags and a newsletter sign-up, each on a pale panel.
 * Inserter: no
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"montana-sidebar","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-sidebar"><!-- wp:group {"className":"is-style-montana-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-montana-box"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-montana-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-montana-box"><!-- wp:heading {"className":"montana-widget-title"} -->
<h2 class="wp-block-heading montana-widget-title">Category</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showPostCounts":true,"className":"montana-counts"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-montana-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-montana-box"><!-- wp:heading {"className":"montana-widget-title"} -->
<h2 class="wp-block-heading montana-widget-title">Recent Post</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":80,"featuredImageSizeHeight":80,"className":"montana-recent"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-montana-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-montana-box"><!-- wp:heading {"className":"montana-widget-title"} -->
<h2 class="wp-block-heading montana-widget-title">Tag Clouds</h2>
<!-- /wp:heading -->

<!-- wp:tag-cloud {"smallestFontSize":"0.875rem","largestFontSize":"0.875rem","className":"montana-tags"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-montana-box","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-montana-box"><!-- wp:heading {"className":"montana-widget-title"} -->
<h2 class="wp-block-heading montana-widget-title">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[montana_form type="newsletter" layout="block" button="Subscribe"]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
