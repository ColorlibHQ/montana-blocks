<?php
/**
 * Title: Post banner
 * Slug: montana/hidden-post-banner
 * Description: The photograph banner a post title sits on.
 * Inserter: no
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-2.webp' ) ); ?>","dimRatio":40,"overlayColor":"dark","isUserOverlayColor":true,"align":"full","className":"montana-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull montana-banner"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-2.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-40 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":1,"className":"montana-banner__post","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
