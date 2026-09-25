=== Montana ===

Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, entertainment, full-site-editing, block-patterns, block-styles, template-editing, wide-blocks, accessibility-ready, translation-ready, custom-colors, custom-menu, custom-logo, featured-images, threaded-comments, one-column, two-columns, right-sidebar, rtl-language-support, sticky-post, theme-options

A block theme for hotels, lodges and mountain resorts, with a room booking request form that needs no plugin.

== Description ==

Montana is a full site editing theme for hotels, lodges, guest houses and
mountain resorts: a full-screen photograph slider, the hotel's story beside two
photographs, three offers, a video band, the restaurant, four featured rooms
edge to edge, a rooms page with each room's nightly price, size, beds, guests
and view, a contact page with a map, a newsletter sign-up and a journal.

The booking form is the heart of it: check-in and check-out dates, guests, the
room, name, email and a message. It sends a REQUEST to the front desk -- the
wording on the page says so -- and does not show availability or take payment.
The dates are checked on the server as well as in the browser: check-in no
earlier than today, check-out at least a night later. The booking, contact and
newsletter forms need no plugin.

It is built from the Montana HTML template: the header laid over the first
photograph with the mark in the middle, the 100px hero, the offer cards, the
rooms two by two with their prices, the ruled reservation line, the strip of
five photographs and the black footer are all there, as blocks you can edit.

Activate it on a new site and it builds the pages for you -- Home, Rooms,
About, Blog and Contact -- with a menu to match. On a site that already has
pages it leaves them alone.

Eight colour palettes and five type pairings, each checked for contrast before
release rather than by eye. Visitor-facing dark mode that follows the reader's
system setting until they choose for themselves. WooCommerce is styled if you
install it and loads nothing if you do not.

== Installation ==

1. In WordPress, go to Appearance -> Themes -> Add New Theme -> Upload Theme.
2. Choose montana.zip and click Install Now, then Activate.
3. Appearance -> Editor is where the header, footer, colours and templates live.

== Frequently Asked Questions ==

= Do I need a plugin for the booking form? =

No. The booking request, contact and newsletter forms are part of the theme
and send with WordPress's own wp_mail() to the site's admin address. If your
host cannot send mail, install any SMTP plugin -- whatever fixes a lost
password-reset email fixes the forms too.

= Can the booking form go to my reservation system instead? =

Yes. Each form has its own filter -- `montana_booking_handlers`,
`montana_contact_handlers`, `montana_newsletter_handlers` -- and
`montana_form_handlers` sees all three. Return true from a handler and the
theme sends no email of its own. The rooms offered in the form come from the
`montana_room_options` filter, and the guest counts from
`montana_guest_options`.

= Does the form show which rooms are free? =

No, and it does not say it does. It is a request: the front desk replies with
what is free and a price, and nothing is booked or charged until the guest
confirms. A site that needs live availability and payment wants a booking
plugin; its shortcode or block can replace the form on the Rooms page.

= How do I change the colours? =

Appearance -> Editor -> Styles -> Browse styles. Eight palettes are included --
Montana (the template's own blue), Pine, Glacier, Sunset, Heather, Brass, and
the dark Midnight and Lodge -- and each restyles every section.

= Why is the template's blue not used for links? =

The template's blue, #009dff, is 2.9:1 against white, which fails WCAG AA for
text and for a button label. It ships as the decorative accent (the footer's
link hovers, the Colorlib credit), and a deeper shade of the same blue carries
links, buttons and anything else you have to read.

= How do I change the map on the contact page? =

The map is a Custom HTML block holding an OpenStreetMap embed. Open
openstreetmap.org, find your hotel, choose Share -> HTML, and paste the new
address into the block's src.

= Can I turn dark mode off? =

Yes: add add_filter( 'montana_enable_dark_mode', '__return_false' ); to a
child theme or a small plugin. The switch in the header disappears with it.

= Can I turn the scroll animations off? =

Yes: add add_filter( 'montana_enable_scroll_animations', '__return_false' );
to a child theme or a small plugin. That also stops the sliders turning on
their own; their arrows and the video popup keep working. Visitors who have
asked their system for reduced motion see neither.

= What does the update check send? =

Montana is distributed from colorlib.com, not the WordPress.org directory, so
it asks updates.colorlib.com for new versions, twice a day at most. It sends
the theme's version, the WordPress and PHP versions, the locale, whether the
site is a multisite, and a one-way hash of the site address -- no site name,
no email address, nothing personal. add_filter( 'montana_check_for_updates',
'__return_false' ); stops it entirely.

== Theme Check ==

Theme Check reports two REQUIRED findings and no warnings. Both are deliberate,
and each is the price of something the theme does on purpose.

1. **add_shortcode() in inc/booking.php.** The forms have to keep working after
   a pattern is expanded into a page's content, where PHP never runs. A
   shortcode is the only mechanism WordPress offers for that. Moving it to a
   plugin would mean the booking form stops working the moment the plugin is
   disabled, on a page the theme built.
2. **Update URI in style.css.** This theme is distributed outside the
   WordPress.org directory and checks colorlib.com for its own updates. A theme
   inside the directory must not carry this header.

== Copyright ==

Montana WordPress Theme, (C) 2026 Colorlib.
Montana is distributed under the terms of the GNU GPL v2 or later.

Raleway and Playfair Display
License: SIL Open Font License 1.1
Source: https://fontsource.org/

Tabler Icons
License: MIT, https://github.com/tabler/tabler-icons/blob/main/LICENSE
Source: https://tabler.io/icons

Photographs
All from Pexels, under the Pexels License (https://www.pexels.com/license/),
which is not the GPL: replace them with your own if you redistribute the
theme under the GPL alone.

* about-1.webp -- Denner Trindade, Pexels,
  https://www.pexels.com/photo/a-wooden-house-in-mountains-17821266/
* about-2.webp -- Sarah O'Shea, Pexels,
  https://www.pexels.com/photo/grotto-library-at-palma-lobby-bar-in-santa-monica-proper-hotel-santa-monica-california-usa-12003496/
* banner-1.webp -- James Wheeler, Pexels,
  https://www.pexels.com/photo/wooden-dock-at-the-lake-during-day-1619319/
* banner-2.webp -- Marko Mocilac, Pexels,
  https://www.pexels.com/photo/wooden-pier-at-the-shore-of-lake-bohinj-in-gorenjska-slovenia-17164966/
* booking.webp -- Amanda Brady, Pexels,
  https://www.pexels.com/photo/scenic-lakefront-log-cabin-in-colorado-mountains-29158145/
* dining-1.webp -- Filipp Romanovski, Pexels,
  https://www.pexels.com/photo/gourmet-fine-dining-plate-with-sauce-and-herbs-28705621/
* dining-2.webp -- ArtHouse Studio, Pexels,
  https://www.pexels.com/photo/man-and-woman-eating-dinner-on-patio-4640904/
* gallery-1.webp -- Chris, Pexels,
  https://www.pexels.com/photo/kayaking-on-a-lake-with-a-beautiful-view-6611017/
* gallery-2.webp -- eberhard grossgasteiger, Pexels,
  https://www.pexels.com/photo/boats-at-calm-body-of-water-by-mountain-slip-2437293/
* gallery-3.webp -- Mark A Jenkins, Pexels,
  https://www.pexels.com/photo/backpacker-in-mountains-19263508/
* gallery-4.webp -- Jonathan Borba, Pexels,
  https://www.pexels.com/photo/chairs-and-a-coffee-table-by-the-window-with-a-mountain-view-19737831/
* gallery-5.webp -- Fausto Hernández, Pexels,
  https://www.pexels.com/photo/brown-wooden-table-and-chairs-12276515/
* hero-1.webp -- Marlon Martinez, Pexels,
  https://www.pexels.com/photo/body-of-water-near-house-1450208/
* hero-2.webp -- Alexandre Moreira, Pexels,
  https://www.pexels.com/photo/scenic-lake-cabin-in-italian-alpine-forest-33191386/
* offer-1.webp -- Quang Nguyen Vinh, Pexels,
  https://www.pexels.com/photo/modern-armchairs-on-terrace-with-fence-6130068/
* offer-2.webp -- Jean-Paul Wettstein, Pexels,
  https://www.pexels.com/photo/rustic-midsummer-bar-in-swiss-countryside-32767465/
* offer-3.webp -- Nuwan chamara, Pexels,
  https://www.pexels.com/photo/people-riding-boats-3087240/
* room-1.webp -- Luis Quintero, Pexels,
  https://www.pexels.com/photo/wooden-lounge-overlooking-calm-sea-5212392/
* room-2.webp -- Clay Elliot, Pexels,
  https://www.pexels.com/photo/white-and-red-floral-area-rug-5784432/
* room-3.webp -- Amar Preciado, Pexels,
  https://www.pexels.com/photo/cozy-mountain-chalet-with-scenic-view-30070557/
* room-4.webp -- Jonathan Borba, Pexels,
  https://www.pexels.com/photo/wide-bed-in-a-luxury-wooden-cabin-with-a-scenic-mountain-view-17399353/
* video.webp -- Donovan Kelly, Pexels,
  https://www.pexels.com/photo/grey-blue-27623535/
* wide-1.webp -- Nadin Romanova, Pexels,
  https://www.pexels.com/photo/modern-hotel-terrace-with-mountain-view-in-georgia-31665649/
* wide-2.webp -- Jack Borno, Pexels,
  https://www.pexels.com/photo/charming-lodge-by-emerald-lake-in-british-columbia-38893778/
* wide-3.webp -- Heart Rules, Pexels,
  https://www.pexels.com/photo/restaurant-near-mountains-covered-with-snow-3709821/

== Changelog ==

= 1.0.1 =
* The sample email address in the header, footer and contact patterns is now hello@yourdomain.com. The 1.0.0 address used a domain that belongs to someone else.

= 1.0.0 =
* Initial release.
