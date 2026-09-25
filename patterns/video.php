<?php
/**
 * Title: Video band
 * Slug: montana/video
 * Categories: montana-sections
 * Keywords: video, film, banner
 * Description: A photograph across the width, dimmed, with a line, a heading and a round play button that opens the film.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/video.webp' ) ); ?>","dimRatio":50,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":740,"align":"full","className":"montana-video-band","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull montana-video-band" style="min-height:740px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/video.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"is-style-montana-eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<p class="has-text-align-center is-style-montana-eyebrow has-overlay-color has-text-color">Montana Lake View</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<h2 class="wp-block-heading has-text-align-center has-overlay-color has-text-color">Relax and Enjoy <br>Your Holiday</h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"montana-video montana-play"} -->
<div class="wp-block-button montana-video montana-play"><a class="wp-block-button__link wp-element-button" href="https://www.youtube.com/watch?v=kmvObXDxKRA"><span class="screen-reader-text">Play the film: a still mountain lake</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
