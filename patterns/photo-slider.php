<?php
/**
 * Title: Photo slider, set to the right
 * Slug: montana/photo-slider
 * Categories: montana-sections
 * Keywords: slider, gallery, photos
 * Description: Wide photographs that take turns, set in from the left as the template's about page has them, with arrows on a wide screen.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-carousel-frame montana-wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull montana-carousel-frame montana-wide"><!-- wp:group {"className":"montana-carousel montana-carousel\u002d\u002dwide","layout":{"type":"default"}} -->
<div class="wp-block-group montana-carousel montana-carousel--wide"><!-- wp:image {"aspectRatio":"1533/750","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-slide"} -->
<figure class="wp-block-image size-large montana-slide"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/wide-1.webp' ) ); ?>" alt="Wicker armchairs and potted pines on a long wooden terrace beside a hotel, clouds on the mountains" style="aspect-ratio:1533/750;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:image {"aspectRatio":"1533/750","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-slide"} -->
<figure class="wp-block-image size-large montana-slide"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/wide-2.webp' ) ); ?>" alt="A timber lodge on a stony point of a turquoise lake, framed by tall pines and a mountain" style="aspect-ratio:1533/750;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:image {"aspectRatio":"1533/750","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-slide"} -->
<figure class="wp-block-image size-large montana-slide"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/wide-3.webp' ) ); ?>" alt="Restaurant tables with checked cloths on a balcony, a jagged snowy peak and a range beyond" style="aspect-ratio:1533/750;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
