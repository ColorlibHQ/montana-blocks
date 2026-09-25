<?php
/**
 * Title: Posts list
 * Slug: montana/hidden-posts-list
 * Description: The post list used by the blog and every archive: photograph with its date, title, excerpt, categories and comments.
 * Inserter: no
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:query {"queryId":0,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true},"className":"montana-posts","layout":{"type":"default"}} -->
<div class="wp-block-query montana-posts"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
<!-- wp:group {"className":"montana-post__media","layout":{"type":"default"}} -->
<div class="wp-block-group montana-post__media"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->

<!-- wp:group {"className":"montana-date","layout":{"type":"default"}} -->
<div class="wp-block-group montana-date"><!-- wp:post-date {"format":"d","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"montana-date__day"} /-->

<!-- wp:post-date {"format":"M","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"montana-date__month"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"montana-post__body","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-post__body"><!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->

<!-- wp:post-excerpt {"excerptLength":36} /-->

<!-- wp:group {"className":"montana-post__meta","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group montana-post__meta"><!-- wp:post-terms {"term":"category","className":"montana-meta montana-icon\u002d\u002dfolder","fontSize":"small"} /-->

<!-- wp:post-comments-count {"className":"montana-meta montana-icon\u002d\u002dmessage","fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>Nothing here yet. Try a search, or start again from the home page.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous {"label":" "} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":" "} /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query -->
