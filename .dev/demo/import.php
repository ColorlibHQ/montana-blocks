<?php
/**
 * The Montana demo: everything colorlibhub.com/montana-blocks/ shows that
 * the theme itself does not build.
 *
 * The theme's own activation builds the five starter pages and the menu
 * (inc/front-page-setup.php). This adds what a real hotel's site would have
 * on top: the site title and tagline, an author with a biography, six journal
 * posts with categories, tags, featured photographs and comments, and it
 * removes WordPress's "Hello world!" and "Sample Page".
 *
 * Run it with the theme active, from WP-CLI, as the user PHP runs as:
 *
 *     sudo -u www-data wp --path=/var/www/colorlibhub.com/public \
 *       --url=https://colorlibhub.com/montana-blocks/ \
 *       eval "require '/path/to/demo/import.php';"
 *
 * `wp eval` + `require`, not `wp eval-file`: eval-file runs the file inside a
 * function, where a top-level variable is not a global. Everything below lives
 * in functions anyway, so either works, but require is the tested path.
 *
 * Safe to run twice. Posts are found by slug with get_posts() — never
 * get_page_by_path(), which also matches attachments — and a photograph is
 * uploaded only once, found again by the `_montana_demo_file` meta it is
 * given. The photographs sit in media/ next to this file and are sideloaded from there;
 * none of them ships in the theme zip.
 *
 * The Playground blueprint (.dev/blueprint.json) runs this same file.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

if ( ! function_exists( 'montana_demo_log' ) ) {

	/**
	 * Print a line, through WP-CLI when there is one.
	 *
	 * @param string $line Message.
	 */
	function montana_demo_log( $line ) {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::log( $line );
		} else {
			echo esc_html( $line ) . "\n";
		}
	}

	/**
	 * The posts. Plain lines: `## ` is a heading, `> words — who` a quote.
	 *
	 * @return array[]
	 */
	function montana_demo_posts() {
		return array(
			array(
				'slug'     => 'five-walks-from-the-front-door',
				'title'    => 'Five walks that start at our front door',
				'category' => 'Outdoors',
				'tags'     => array( 'hiking', 'trails', 'summer' ),
				'image'    => 'post-trail.jpg',
				'alt'      => 'A line of walkers on a grassy path up a green valley, pines and a rocky ridge above them',
				'days'     => 3,
				'excerpt'  => 'From a forty-minute loop round the point to a full day up to the ridge lakes: the routes the front desk hands out most often.',
				'body'     => array(
					'Forty miles of marked trail start at the hotel gate, and the question we hear most at the front desk is simply where to begin. These are the five walks we send guests on most, from a stroll before breakfast to a proper day out.',
					'## The point loop, forty minutes',
					'Out along the shore path to the rocky point, back through the pines. Flat, shaded and good in any weather. Take it at seven and you will have the jetty to yourself on the way home.',
					'## Arrow Falls, two hours',
					'A steady climb beside the creek to a waterfall that is at its loudest in June. The last ten minutes are steep; the bench at the top is worth it.',
					'> We tell every guest the same thing: start early, carry more water than you think, and turn round when the weather does. — Clara, front desk',
					'## The ridge lakes, a full day',
					'Three small lakes strung along the ridge, six hours there and back. Snow can lie on the upper section into July, so ask us before you set out, and borrow a pair of poles from the rack by the door.',
				),
				'comment'  => array( 'Tom Reilly', 'We did the ridge lakes on our last day. Hard work, and the best thing we did all week.' ),
			),
			array(
				'slug'     => 'what-the-kitchen-does-with-a-lake-trout',
				'title'    => 'What the kitchen does with a lake trout',
				'category' => 'Kitchen',
				'tags'     => array( 'restaurant', 'local food' ),
				'image'    => 'post-kitchen.jpg',
				'alt'      => 'A whole roasted trout on a slate board with lettuce, a slice of tomato and lemon wedges',
				'days'     => 7,
				'excerpt'  => 'One fisherman, two boats and a menu that changes with his catch. Our chef on cooking the fish that swims past the dining room.',
				'body'     => array(
					'Most mornings between May and October a small boat ties up at our jetty at half past six, and the kitchen finds out what it is cooking that night. The trout on the menu has usually been in the lake the day before.',
					'## Simple, and quickly',
					'Our chef cooks it one of two ways: whole over the grill with brown butter and wild thyme, or filleted and pan-fried skin-side down until it crackles. Anything more, she says, gets in the way.',
					'> A fish this fresh does not need a sauce. It needs a hot pan and someone who is paying attention. — Maria, head chef',
					'On the side there is whatever the valley farms sent up that week: new potatoes in June, green beans in July, and in August a spoonful of huckleberry relish that has become the thing guests ask about most.',
				),
			),
			array(
				'slug'     => 'the-jetty-opens-for-summer',
				'title'    => 'The jetty is open for the summer',
				'category' => 'News',
				'tags'     => array( 'lake', 'kayaks', 'summer' ),
				'image'    => 'post-jetty.jpg',
				'alt'      => 'Red canoes stacked on a wooden jetty over a still turquoise lake, a mountain reflected at sunset',
				'days'     => 12,
				'excerpt'  => 'Kayaks, canoes and two rowing boats, free to every guest from eight until sunset. Here is how the boat shed works this year.',
				'body'     => array(
					'The ice went out in the first week of May, the boards have had their spring coat of oil, and the boat shed is open. Every guest can take a kayak, a canoe or one of the two rowing boats out from eight in the morning until sunset, at no charge.',
					'## Before you paddle',
					'Life jackets are in the shed and are not optional. Sign the board by the door with where you are going and when you expect to be back; the front desk checks it at sunset.',
					'The water stays cold well into July. If you swim from the jetty, do it in the afternoon, and keep the ladder in sight.',
				),
				'comment'  => array( 'Grace Okafor', 'The early morning paddle to the point with mist on the water was magic. The kids still talk about it.' ),
			),
			array(
				'slug'     => 'a-winter-weekend-by-the-stove',
				'title'    => 'A winter weekend by the stove',
				'category' => 'Seasons',
				'tags'     => array( 'winter', 'snowshoes' ),
				'image'    => 'post-winter.jpg',
				'alt'      => 'A snow-covered log cabin in a wood, a snowy split-rail fence in front of it',
				'days'     => 20,
				'excerpt'  => 'From December the lake freezes, the trails go quiet and the fires are lit at four. Why winter is our favourite season.',
				'body'     => array(
					'When the last autumn guests leave, the valley goes quiet in a way that summer visitors never see. The lake freezes from the edges in, the trails are packed by snowshoes rather than boots, and the fires in the lounge are lit at four.',
					'## Out in the snow',
					'We lend snowshoes and poles to every guest, and the point loop becomes a gentle first outing. On clear nights the front desk keeps a flask of hot chocolate by the door for anyone heading out to look at the stars.',
					'## In by the fire',
					'The spa runs longer hours from December, the Mountain Suite and the A-Frame Cabin have their own stoves, and dinner moves to the long table by the window.',
				),
			),
			array(
				'slug'     => 'stars-over-the-north-shore',
				'title'    => 'Watching the stars from the north shore',
				'category' => 'Outdoors',
				'tags'     => array( 'night sky', 'lake' ),
				'image'    => 'post-stars.jpg',
				'alt'      => 'The Milky Way over a still mountain lake at night, snowy peaks reflected in the water',
				'days'     => 27,
				'excerpt'  => 'Forty minutes from the nearest town, there is no glow on the horizon. Where to stand, when to look, and what to bring.',
				'body'     => array(
					'Forty minutes from the nearest streetlight, the sky over the lake on a clear night is darker than most guests have ever seen. The Milky Way rises over the ridge in late summer, and from October the lake is still enough to hold its reflection.',
					'## Where to stand',
					'The end of the jetty, facing east, with the lodge lights behind you. Give your eyes twenty minutes; the stars keep arriving.',
					'Blankets are in the chest by the boat shed, and on the new moon in August we set out reclining chairs and a telescope on the lawn.',
				),
			),
			array(
				'slug'     => 'huckleberry-season',
				'title'    => 'It is huckleberry season',
				'category' => 'Kitchen',
				'tags'     => array( 'local food', 'summer' ),
				'image'    => 'post-berries.jpg',
				'alt'      => 'A wooden bowl full of dark blueberries on a pale table, green leaves beside it',
				'days'     => 34,
				'excerpt'  => 'Three weeks in August when the hillsides turn purple and every dish on the menu finds a way to use them.',
				'body'     => array(
					'For about three weeks every August the slopes above the lake turn purple, and the kitchen stops pretending to plan a menu. Huckleberries go into the pancakes at breakfast, the relish with the trout and the tart that closes dinner.',
					'Guests are welcome to pick along the lower trail; take a tub from the kitchen door and leave the bushes by the path for the bears. Whatever you bring back, the pastry kitchen will turn into something by the evening.',
				),
			),
		);
	}

	/**
	 * Turn the plain lines into block markup.
	 *
	 * @param string[] $lines Paragraphs, `## ` headings and `> ` quotes.
	 * @return string
	 */
	function montana_demo_blocks( $lines ) {
		$out = array();
		foreach ( $lines as $line ) {
			if ( 0 === strpos( $line, '## ' ) ) {
				$out[] = "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( substr( $line, 3 ) ) . "</h2>\n<!-- /wp:heading -->";
			} elseif ( 0 === strpos( $line, '> ' ) ) {
				list( $words, $who ) = array_map( 'trim', explode( '—', substr( $line, 2 ) ) );
				$out[]               = "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( $words ) . "</p>\n<!-- /wp:paragraph --><cite>" . esc_html( $who ) . "</cite></blockquote>\n<!-- /wp:quote -->";
			} else {
				$out[] = "<!-- wp:paragraph -->\n<p>" . esc_html( $line ) . "</p>\n<!-- /wp:paragraph -->";
			}
		}
		return implode( "\n\n", $out );
	}

	/**
	 * The ID of a post of this type with this slug, in any status, or 0.
	 *
	 * @param string $slug Slug.
	 * @param string $type Post type.
	 * @return int
	 */
	function montana_demo_find( $slug, $type ) {
		$ids = get_posts(
			array(
				'name'             => $slug,
				'post_type'        => $type,
				'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'suppress_filters' => true,
			)
		);
		return $ids ? (int) $ids[0] : 0;
	}

	/**
	 * An attachment for one of the photographs next to this file, uploaded once.
	 *
	 * @param string $file    File name in this directory.
	 * @param int    $post_id Post to attach a new upload to.
	 * @param string $title   Attachment title.
	 * @param string $alt     Alt text.
	 * @return int Attachment ID, or 0.
	 */
	function montana_demo_media( $file, $post_id, $title, $alt ) {
		$found = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'meta_key'       => '_montana_demo_file', // phpcs:ignore WordPress.DB.SlowDBQuery -- a one-off import.
				'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $found ) {
			update_post_meta( $found[0], '_wp_attachment_image_alt', $alt );
			return (int) $found[0];
		}

		$source = __DIR__ . '/media/' . $file;
		if ( ! is_readable( $source ) ) {
			montana_demo_log( "  missing photograph: $file" );
			return 0;
		}

		// media_handle_sideload() moves the file it is given, so hand it a copy.
		$tmp = wp_tempnam( $file );
		copy( $source, $tmp );
		$id = media_handle_sideload(
			array(
				'name'     => $file,
				'tmp_name' => $tmp,
			),
			$post_id,
			$title
		);
		if ( is_wp_error( $id ) ) {
			montana_demo_log( '  upload failed: ' . $file . ' — ' . $id->get_error_message() );
			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
			return 0;
		}

		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		update_post_meta( $id, '_montana_demo_file', $file );
		return (int) $id;
	}

	/**
	 * The posts' author: the hotel's own byline, created once.
	 *
	 * The single-post template prints the author's name, photograph and
	 * biography under every post, so the demo needs a person with a biography
	 * -- and never user 1, which on colorlibhub is the network's super admin.
	 * An Author on this site only; on a multisite the user is added to the site.
	 *
	 * @return int User ID.
	 */
	function montana_demo_author() {
		$user = get_user_by( 'login', 'montana-frontdesk' );
		if ( $user ) {
			$id = (int) $user->ID;
			if ( is_multisite() && ! is_user_member_of_blog( $id ) ) {
				add_user_to_blog( get_current_blog_id(), $id, 'author' );
			}
			return $id;
		}

		$id = wp_insert_user(
			wp_slash(
				array(
					'user_login'   => 'montana-frontdesk',
					'user_pass'    => wp_generate_password( 32, true, true ),
					'user_email'   => 'montana-frontdesk@example.com',
					'display_name' => 'Clara Holt',
					'first_name'   => 'Clara',
					'last_name'    => 'Holt',
					'description'  => 'Clara runs the front desk at Montana, which means she knows which trails are still under snow, which table has the best view and where the kayaks are. She writes the journal between check-ins.',
					'role'         => 'author',
				)
			)
		);
		if ( is_wp_error( $id ) ) {
			montana_demo_log( 'could not create the author: ' . $id->get_error_message() );
			$admins = get_users(
				array(
					'role'    => 'administrator',
					'number'  => 1,
					'orderby' => 'ID',
					'fields'  => 'ids',
				)
			);
			return $admins ? (int) $admins[0] : 1;
		}
		montana_demo_log( 'created author Clara Holt' );
		return (int) $id;
	}

	/**
	 * A category by name, created when missing.
	 *
	 * @param string $name Category name.
	 * @return int Term ID, or 0.
	 */
	function montana_demo_category( $name ) {
		$term = term_exists( $name, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'category' );
		}
		return is_array( $term ) ? (int) $term['term_id'] : 0;
	}

	/**
	 * Run the import.
	 */
	function montana_demo_import() {
		if ( ! function_exists( 'montana_create_front_page' ) ) {
			montana_demo_log( 'Montana is not the active theme on ' . home_url( '/' ) . ' — activate it first. Nothing imported.' );
			return;
		}

		// Site identity. The header prints the site title inside the mark.
		update_option( 'blogname', 'Montana' );
		update_option( 'blogdescription', 'A lakeside hotel in the mountains' );

		// The starter pages and menu are the theme's own job. Its function is
		// one-shot and checks every slug, so calling it again only fills in what
		// activation did not get to (for example when it ran before patterns
		// were registered).
		montana_create_front_page();
		montana_demo_log( 'starter pages: ' . get_option( MONTANA_SETUP_FLAG ) );

		// WordPress's own sample content.
		foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $sample ) {
			$id = montana_demo_find( $sample[0], $sample[1] );
			if ( $id ) {
				wp_delete_post( $id, true );
				montana_demo_log( "removed {$sample[1]} {$sample[0]}" );
			}
		}

		$author = montana_demo_author();

		// Activation from WP-CLI runs with no user, so the starter pages are
		// saved with author 0. Give them the same author as the posts — in the
		// table, not through wp_update_post(): that re-saves the content through
		// kses, which strips the contact page's map <iframe> when there is no user.
		global $wpdb;
		foreach ( array_keys( montana_starter_pages() ) as $slug ) {
			$page = montana_demo_find( $slug, 'page' );
			if ( $page && ! (int) get_post_field( 'post_author', $page ) ) {
				$wpdb->update( $wpdb->posts, array( 'post_author' => $author ), array( 'ID' => $page ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				clean_post_cache( $page );
			}
		}

		foreach ( montana_demo_posts() as $post ) {
			$id = montana_demo_find( $post['slug'], 'post' );

			if ( ! $id ) {
				$id = wp_insert_post(
					wp_slash(
						array(
							'post_title'    => $post['title'],
							'post_name'     => $post['slug'],
							'post_excerpt'  => $post['excerpt'],
							'post_content'  => montana_demo_blocks( $post['body'] ),
							'post_status'   => 'publish',
							'post_author'   => $author,
							'post_date'     => wp_date( 'Y-m-d H:i:s', time() - $post['days'] * DAY_IN_SECONDS ),
							'post_category' => array( montana_demo_category( $post['category'] ) ),
							'tags_input'    => $post['tags'],
						)
					),
					true
				);
				if ( is_wp_error( $id ) ) {
					montana_demo_log( 'post failed: ' . $post['slug'] . ' — ' . $id->get_error_message() );
					continue;
				}
				montana_demo_log( 'created post ' . $post['slug'] );
			} else {
				montana_demo_log( 'kept post ' . $post['slug'] );
			}

			if ( ! has_post_thumbnail( $id ) ) {
				$media = montana_demo_media( $post['image'], $id, $post['title'], $post['alt'] );
				if ( $media ) {
					set_post_thumbnail( $id, $media );
				}
			}

			if ( ! empty( $post['comment'] ) ) {
				list( $who, $words ) = $post['comment'];
				$has                 = get_comments(
					array(
						'post_id'      => $id,
						'search'       => $words,
						'count'        => true,
					)
				);
				if ( ! $has ) {
					wp_insert_comment(
						wp_slash(
							array(
								'comment_post_ID'  => $id,
								'comment_author'   => $who,
								'comment_content'  => $words,
								'comment_approved' => 1,
								'comment_date'     => wp_date( 'Y-m-d H:i:s', time() - ( $post['days'] - 1 ) * DAY_IN_SECONDS ),
							)
						)
					);
				}
			}
		}

		$count = wp_count_posts( 'post' );
		montana_demo_log( sprintf( 'done: %d published posts, %d pages', $count->publish, wp_count_posts( 'page' )->publish ) );
	}
}

montana_demo_import();
