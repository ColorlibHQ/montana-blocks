<?php
/**
 * Give a fresh install the site it was shown in the screenshot.
 *
 * A block theme activated on an empty site shows the blog index, which looks
 * nothing like the demo and leaves the owner to assemble a home page from
 * patterns before they can tell whether they like it. This builds the pages
 * once, on first activation, and never touches them again.
 *
 * Patterns are **expanded into real post content** rather than referenced, so
 * every word is editable in the editor without hunting through theme files.
 * That expansion is also why the dynamic parts of the theme are shortcodes:
 * PHP inside stored post content never runs, so a pattern that rendered the
 * booking form inline would freeze its output into the page permanently.
 * `[montana_form]` survives the round trip because a shortcode is
 * expanded at render time, every time.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;

const MONTANA_SETUP_FLAG = 'montana_front_page_created';

/**
 * Pages to create, in order. Slug => [title, pattern, template].
 *
 * @return array<string, array<string, string>>
 */
function montana_starter_pages() {
	return array(
		'home'    => array(
			'title'    => __( 'Home', 'montana' ),
			'pattern'  => 'montana/page-home',
			'template' => 'page-no-title',
		),
		'rooms'   => array(
			'title'    => __( 'Rooms', 'montana' ),
			'pattern'  => 'montana/page-rooms',
			'template' => '',
		),
		'about'   => array(
			'title'    => __( 'About', 'montana' ),
			'pattern'  => 'montana/page-about',
			'template' => '',
		),
		'blog'    => array(
			'title'    => __( 'Blog', 'montana' ),
			'pattern'  => '',
			'template' => '',
		),
		'contact' => array(
			'title'    => __( 'Contact', 'montana' ),
			'pattern'  => 'montana/page-contact',
			'template' => '',
		),
	);
}



/**
 * Build the starter site, once.
 *
 * Guarded three ways: a one-shot option, a check that this is a genuinely
 * fresh site, and a per-page check that the slug is free. Activating, trying
 * another theme and coming back must not produce a second set of pages or
 * overwrite the first.
 */
function montana_create_front_page() {
	if ( get_option( MONTANA_SETUP_FLAG ) ) {
		return;
	}

	// Only on a site that has not been built yet: someone activating Montana
	// over an existing site wants their pages left alone. WordPress's own two
	// pages do not count as "built" — a brand new install has them.
	$existing = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 5,
			'fields'         => 'ids',
			'exclude'        => array_filter(
				array(
					(int) get_option( 'wp_page_for_privacy_policy' ),
					montana_page_exists( 'sample-page' ),
				)
			),
		)
	);

	if ( count( $existing ) > 1 ) {
		update_option( MONTANA_SETUP_FLAG, 'skipped: site already had pages', false );
		return;
	}

	$created = array();

	// The contact page's map is an <iframe>, which kses strips from content
	// saved by anyone without `unfiltered_html` — a multisite site admin, or
	// no user at all when activation runs from WP-CLI or a Playground
	// blueprint. The content here is the theme's own patterns, not anyone's
	// input, so the filters are lifted for these inserts and put back after.
	$kses = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses ) {
		kses_remove_filters();
	}

	foreach ( montana_starter_pages() as $slug => $page ) {
		if ( montana_page_exists( $slug ) ) {
			continue;
		}

		$content = '';
		if ( $page['pattern'] ) {
			$content = montana_pattern_content( $page['pattern'] );
			if ( '' === $content ) {
				continue;
			}
		}

		// wp_insert_post() unslashes its input. Pattern markup carries JSON escapes
		// such as \u002d in block attributes; without wp_slash() they lose their
		// backslash and every spacer opens as "unexpected or invalid content".
		$id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => wp_slash( $content ),
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);

		if ( ! is_wp_error( $id ) && $id ) {
			$created[ $slug ] = $id;
			if ( $page['template'] ) {
				update_post_meta( $id, '_wp_page_template', $page['template'] );
			}
		}
	}

	if ( $kses ) {
		kses_init_filters();
	}

	if ( isset( $created['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $created['home'] );
	}
	if ( isset( $created['blog'] ) ) {
		update_option( 'page_for_posts', $created['blog'] );
	}

	montana_create_primary_menu( $created );

	// Claimed only now, and only if something was actually built. Firing before
	// the pattern registry is ready is a real possibility — the content comes
	// back empty and every page is skipped — and a flag set up front would make
	// that one bad moment permanent. Left unset, the admin_init retry below
	// finishes the job on the next page load.
	if ( $created ) {
		update_option( MONTANA_SETUP_FLAG, gmdate( 'c' ), false );
	}
}
add_action( 'after_switch_theme', 'montana_create_front_page' );

/**
 * The ID of a page with this slug, or 0.
 *
 * Not get_page_by_path(): it searches attachments as well as pages, whatever
 * post type it is given, so an image titled "About" would count as the About
 * page and the page would never be built.
 *
 * @param string $slug Page slug.
 * @return int
 */
function montana_page_exists( $slug ) {
	$ids = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/**
 * Second chance.
 *
 * after_switch_theme can fire before the block pattern registry is populated,
 * in which case every page resolves to empty content and nothing is built. This
 * runs once more on the first admin request, by which time patterns are
 * certainly registered, and does nothing at all once the flag is set.
 */
function montana_create_front_page_retry() {
	if ( get_option( MONTANA_SETUP_FLAG ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	montana_create_front_page();
}
add_action( 'admin_init', 'montana_create_front_page_retry' );

/**
 * The markup of a registered pattern, with nested pattern references expanded.
 *
 * The page patterns are built out of `<!-- wp:pattern {"slug":"..."} /-->`
 * references. Stored in a post those still *render* — WordPress resolves them
 * on output — but they are not editable: the editor shows one opaque block per
 * section, and changing a word means finding the pattern file in the theme.
 * The whole point of building the starter site as real content is that the
 * owner can rewrite it, so the references are resolved here, recursively.
 *
 * `$seen` guards against a pattern that references itself, directly or through
 * a chain. Without it that is an infinite loop and a white screen, at
 * activation, on someone else's site.
 *
 * @param string   $name Pattern name, e.g. `montana/page-home`.
 * @param string[] $seen Names already being expanded on this branch.
 * @return string Pattern content, or '' when it is not registered.
 */
function montana_pattern_content( $name, $seen = array() ) {
	if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
		return '';
	}

	$registry = WP_Block_Patterns_Registry::get_instance();
	if ( ! $registry->is_registered( $name ) ) {
		return '';
	}

	$pattern = $registry->get_registered( $name );
	$content = isset( $pattern['content'] ) ? $pattern['content'] : '';

	if ( '' === $content || in_array( $name, $seen, true ) ) {
		return $content;
	}

	$seen[] = $name;

	return (string) preg_replace_callback(
		'#<!--\s*wp:pattern\s+(\{.*?\})\s*/-->#s',
		static function ( $matches ) use ( $seen ) {
			$attributes = json_decode( $matches[1], true );

			if ( ! is_array( $attributes ) || empty( $attributes['slug'] ) ) {
				return $matches[0];
			}

			$nested = montana_pattern_content( $attributes['slug'], $seen );

			// A reference we cannot resolve is left as it was: it still
			// renders, which is better than deleting the section.
			return '' === $nested ? $matches[0] : $nested;
		},
		$content
	);
}

/**
 * A navigation menu pointing at the pages just created.
 *
 * Block themes use a `wp_navigation` post rather than a nav menu, and the
 * header pattern falls back to a page list when there is none — so this is an
 * improvement on the fallback, not a requirement for the header to work.
 *
 * @param array<string, int> $pages Slug => page ID.
 */
function montana_create_primary_menu( $pages ) {
	if ( ! $pages ) {
		return;
	}

	$order = array( 'home', 'rooms', 'about', 'blog', 'contact' );
	$items = '';

	foreach ( $order as $slug ) {
		if ( ! isset( $pages[ $slug ] ) ) {
			continue;
		}

		$id    = $pages[ $slug ];
		$title = get_the_title( $id );

		// core/home-link rather than a custom link for the front page: only
		// home-link is given `current-menu-item`, so a custom link would never
		// highlight while someone is actually on the home page.
		if ( 'home' === $slug ) {
			$items .= '<!-- wp:home-link {"label":"' . esc_attr( $title ) . '"} /-->';
			continue;
		}

		$items .= sprintf(
			'<!-- wp:navigation-link {"label":"%s","type":"page","id":%d,"url":"%s","kind":"post-type"} /-->',
			esc_attr( $title ),
			$id,
			esc_url( get_permalink( $id ) )
		);
	}

	if ( '' === $items ) {
		return;
	}

	wp_insert_post(
		array(
			'post_title'   => __( 'Primary', 'montana' ),
			'post_name'    => 'primary',
			'post_content' => wp_slash( $items ),
			'post_status'  => 'publish',
			'post_type'    => 'wp_navigation',
		)
	);
}
