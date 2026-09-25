<?php
/**
 * Title: Comments
 * Slug: montana/hidden-comments
 * Description: The comments and the reply form for a single post.
 * Inserter: no
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:comments {"className":"montana-comments"} -->
<div class="wp-block-comments montana-comments"><!-- wp:comments-title {"fontSize":"large"} /-->

<!-- wp:comment-template -->
<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile"><!-- wp:column {"width":"70px"} -->
<div class="wp-block-column" style="flex-basis:70px"><!-- wp:avatar {"size":70} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-content /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group"><!-- wp:comment-author-name {"fontSize":"medium"} /-->

<!-- wp:comment-date {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:comment-reply-link {"className":"montana-reply","fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-numbers /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->
