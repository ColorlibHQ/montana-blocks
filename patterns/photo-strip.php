<?php
/**
 * Title: Photo strip: five photographs edge to edge
 * Slug: montana/photo-strip
 * Categories: montana-sections
 * Keywords: gallery, photos, instagram
 * Description: Five square photographs across the full width, no gaps, enlarging on click.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:gallery {"columns":5,"linkTo":"none","align":"full","className":"montana-strip","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
<figure class="wp-block-gallery alignfull has-nested-images columns-5 is-cropped montana-strip"><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-1.webp' ) ); ?>" alt="The red bow of a kayak on a clear green lake, jagged snow-streaked mountains ahead"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-2.webp' ) ); ?>" alt="Two rowing boats on a still lake in the mist, the forested mountainside mirrored in the water"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-3.webp' ) ); ?>" alt="A walker in a yellow jacket and a sun hat crossing bare rock with a rucksack, pines behind"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-4.webp' ) ); ?>" alt="Two armchairs with sheepskins and a small round table in the sunlit glass end of an A-frame cabin"/></figure>
<!-- /wp:image -->

<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-5.webp' ) ); ?>" alt="A table for two on a timber terrace under the trees, a green valley falling away below"/></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery -->
