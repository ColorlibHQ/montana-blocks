<?php
/**
 * Title: Post meta
 * Slug: montana/hidden-post-meta
 * Description: Categories, date and comment count for a single post.
 * Inserter: no
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"montana-post__meta","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group montana-post__meta"><!-- wp:post-terms {"term":"category","className":"montana-meta montana-icon\u002d\u002dfolder","fontSize":"small"} /-->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"montana-meta montana-icon\u002d\u002dcalendar","fontSize":"small"} /-->

<!-- wp:post-comments-count {"className":"montana-meta montana-icon\u002d\u002dmessage","fontSize":"small"} /--></div>
<!-- /wp:group -->
