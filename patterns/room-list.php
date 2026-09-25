<?php
/**
 * Title: Rooms: four rooms with their facts
 * Slug: montana/room-list
 * Categories: montana-sections
 * Keywords: rooms, suites, prices, facts
 * Description: Each room with a photograph, its nightly price from, size, beds, guests and view, a line about it and a button that goes to the booking request.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"montana-room-list","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"rooms"} -->
<div class="wp-block-group alignfull montana-room-list" id="rooms" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"className":"montana-title montana-title\u002d\u002dcenter","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-title montana-title--center"><!-- wp:paragraph {"className":"is-style-montana-eyebrow","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center is-style-montana-eyebrow">Rooms &amp; Suites</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center">Every Room Looks at Something</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|70"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:columns {"verticalAlignment":"center","className":"montana-room-card","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center montana-room-card"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:image {"aspectRatio":"960/600","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-room-card__photo"} -->
<figure class="wp-block-image size-large montana-room-card__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-1.webp' ) ); ?>" alt="A small table and two wooden chairs in a corner window over wide, still water and distant hills" style="aspect-ratio:960/600;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"className":"montana-room-card__words","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room-card__words"><!-- wp:paragraph {"className":"montana-room-card__price"} -->
<p class="montana-room-card__price">From $190 / night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size">Lake View Room</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>28 m²</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>King or twin beds</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sleeps 2</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Lake view</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Our most booked room: a corner window over the water with a table for two, a rain shower, and linen from a mill up the valley.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline"} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">Request this room</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"montana-room-card montana-room-card\u002d\u002dflip","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center montana-room-card montana-room-card--flip"><!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"className":"montana-room-card__words","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room-card__words"><!-- wp:paragraph {"className":"montana-room-card__price"} -->
<p class="montana-room-card__price">From $320 / night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size">Mountain Suite</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>46 m²</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>King bed and sofa bed</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sleeps 3</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Mountain view</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>A separate sitting room with a wood stove, a deep bath under the eaves and windows on two sides facing the ridge.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline"} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">Request this room</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:image {"aspectRatio":"960/600","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-room-card__photo"} -->
<figure class="wp-block-image size-large montana-room-card__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-2.webp' ) ); ?>" alt="A timber-framed sitting room with a stone fireplace, armchairs, a red rug and tall windows onto the hills" style="aspect-ratio:960/600;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"montana-room-card","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center montana-room-card"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:image {"aspectRatio":"960/600","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-room-card__photo"} -->
<figure class="wp-block-image size-large montana-room-card__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-3.webp' ) ); ?>" alt="A wooden deck with high stools and a dining table under a timber roof, looking over forested hills" style="aspect-ratio:960/600;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"className":"montana-room-card__words","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room-card__words"><!-- wp:paragraph {"className":"montana-room-card__price"} -->
<p class="montana-room-card__price">From $410 / night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size">Family Chalet</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>64 m²</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Two bedrooms</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sleeps 5</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Garden and lake</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>A timber chalet in the garden with two bedrooms, a small kitchen and its own porch, a short walk from the jetty and the kayaks.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline"} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">Request this room</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"verticalAlignment":"center","className":"montana-room-card montana-room-card\u002d\u002dflip","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center montana-room-card montana-room-card--flip"><!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"className":"montana-room-card__words","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group montana-room-card__words"><!-- wp:paragraph {"className":"montana-room-card__price"} -->
<p class="montana-room-card__price">From $260 / night</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size">A-Frame Cabin</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-montana-dots"} -->
<ul class="wp-block-list is-style-montana-dots"><!-- wp:list-item -->
<li>34 m²</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>King bed</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Sleeps 2</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Forest and hills</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>A cabin for two at the edge of the pines: the bed faces a wall of glass, and there is a wood stove inside and a cedar hot tub on the deck.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-montana-outline"} -->
<div class="wp-block-button is-style-montana-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/rooms/#book' ) ); ?>">Request this room</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:image {"aspectRatio":"960/600","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"montana-room-card__photo"} -->
<figure class="wp-block-image size-large montana-room-card__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/room-4.webp' ) ); ?>" alt="A wide bed facing the glass end wall of an A-frame cabin, hills beyond the balcony" style="aspect-ratio:960/600;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
