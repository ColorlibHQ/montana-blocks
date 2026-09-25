#!/usr/bin/env python3
"""Generate Montana's patterns.

Run from the theme root:

    python3 .dev/build_patterns.py
    node .dev/normalize-blocks.mjs      # then let the editor re-serialise them
    node .dev/validate-blocks.mjs       # and refuse anything it calls invalid

Every pattern file is committed as generated. Edit this file, never
patterns/*.php.

The sections follow the Montana HTML template section by section: the
transparent header with the menu left, the mark centred and the booking
button right; the full-screen photograph slider; the about block with two
photographs at different heights; three offer cards; the video band; the
restaurant block, mirrored; the four featured rooms edge to edge; the
reservation line in its ruled box; the five-photograph strip; and the black
four-column footer. The copy is one mountain lake hotel's, start to finish.
"""

import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from patternlib import (  # noqa: E402
    attrs, button, buttons, column, columns, cover, group, heading, image,
    paragraph, shortcode, sp, spacer, theme_image,
)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PATTERNS = os.path.join(ROOT, "patterns")
WRITTEN = []

SECTIONS = ["montana-sections"]
PAGES = ["montana-pages"]

PHONE = "+1 (406) 555-0147"
PHONE_HREF = "tel:+14065550147"
EMAIL = "stay@montanaresort.com"
ADDRESS_1 = "1200 North Shore Road"
ADDRESS_2 = "Lake Arrow Valley"
# A public, embeddable film of a still mountain lake ("Peaceful Mountain Lake
# 4k", Peaceful Outdoor Vibes), checked with YouTube's oEmbed endpoint. The
# template's own link, vLnPwxZdW4Y, is a C++ course.
VIDEO = "https://www.youtube.com/watch?v=kmvObXDxKRA"


def write(slug, title, content, categories=None, keywords=None,
          description=None, inserter=True, block_types=None):
    header = ["Title: " + title, "Slug: montana/" + slug]
    if categories:
        header.append("Categories: " + ", ".join(categories))
    if keywords:
        header.append("Keywords: " + ", ".join(keywords))
    if block_types:
        header.append("Block Types: " + ", ".join(block_types))
    if description:
        header.append("Description: " + description)
    if not inserter:
        header.append("Inserter: no")

    body = (
        "<?php\n/**\n * " + "\n * ".join(header) + "\n *\n * @package Montana\n */\n\n"
        "defined( 'ABSPATH' ) || exit;\n?>\n" + content.strip() + "\n"
    )
    with open(os.path.join(PATTERNS, slug + ".php"), "w") as fh:
        fh.write(body)
    WRITTEN.append(slug)


def home(path="/"):
    """A link to one of the starter pages, resolved when the pattern loads.

    A literal `/contact/` breaks on a site installed in a subdirectory; `#`
    goes nowhere. The pattern is PHP, so it can ask WordPress, and the link is
    correct in the page activation builds from it.
    """
    return "<?php echo esc_url( home_url( '%s' ) ); ?>" % path


BOOK = "/rooms/#book"


def icon(name):
    """The class that draws a Tabler icon on the block that carries it.

    style.css draws the icon from the class with a mask filled in the text
    colour, so it follows the palette and dark mode, shows in the editor (an
    empty inline element would not), and changing it is an edit to Advanced ->
    Additional CSS class(es). Every name needs a `.montana-icon--<name>` rule;
    .dev/dead-selectors.py checks it."""
    return "montana-icon--%s" % name


def flex_row(inner, justify=None, gap=None, wrap="wrap", vertical="center", extra_class=None):
    """A horizontal group, written directly; normalize-blocks.mjs canonicalises it."""
    layout = {"type": "flex", "flexWrap": wrap}
    if justify:
        layout["justifyContent"] = justify
    if vertical:
        layout["verticalAlignment"] = vertical
    data = {}
    if extra_class:
        data["className"] = extra_class
    if gap is not None:
        data["style"] = {"spacing": {"blockGap": sp(gap) if gap != "0" else "0"}}
    data["layout"] = layout
    cls = "wp-block-group" + (" " + extra_class if extra_class else "")
    return '<!-- wp:group %s -->\n<div class="%s">\n%s\n</div>\n<!-- /wp:group -->' % (
        json.dumps(data, separators=(",", ":")), cls, inner
    )


def eyebrow(text, align=None, color=None):
    """The template's small blue line above a section title ("About Us")."""
    return paragraph(text, align=align, color=color, style="montana-eyebrow")


def section_title(kicker, title, align=None, color=None, kicker_color=None, level=2):
    """Eyebrow over a 46px regular-weight title, as `.section_title` has it."""
    return group("\n".join([
        eyebrow(kicker, align=align, color=kicker_color),
        heading(title, level=level, align=align, color=color),
    ]), layout="constrained", gap="30", extra_class="montana-title" + (" montana-title--center" if align == "center" else ""))


def line_link(text, url, color=None):
    """The template's `.line-button`: words over a 1px rule, no box.

    A link in a paragraph, not a core/button: it reads as a link, and a button
    block would promise a button's boundary (WCAG 1.4.11) that a line under
    some words does not have."""
    return paragraph('<a href="%s">%s</a>' % (url, text), color=color, extra_class="montana-more")


def social_links(style="montana-plain", justify="right"):
    services = ["facebook", "x", "instagram"]
    data = {"size": "has-small-icon-size", "className": "is-style-" + style,
            "layout": {"type": "flex", "justifyContent": justify, "flexWrap": "nowrap"}}
    return (
        '<!-- wp:social-links %s -->\n'
        '<ul class="wp-block-social-links has-small-icon-size is-style-%s">%s</ul>\n'
        '<!-- /wp:social-links -->' % (
            json.dumps(data, separators=(",", ":")), style,
            "".join('<!-- wp:social-link {"url":"#","service":"%s"} /-->' % s for s in services))
    )


def dots_list(items):
    return ('<!-- wp:list {"className":"is-style-montana-dots"} -->\n'
            '<ul class="wp-block-list is-style-montana-dots">%s</ul>\n<!-- /wp:list -->'
            % "".join("<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->" % i for i in items))


def section(inner, background=None, padding="70", anchor=None, extra_class=None, text=None):
    return group(inner, align="full", background=background, padding_y=padding, text=text,
                 layout="constrained", anchor=anchor, extra_class=extra_class)


def zero_gap_columns(cols, extra_class=None, align="full", stack=True):
    data = {}
    if align:
        data["align"] = align
    if not stack:
        data["isStackedOnMobile"] = False
    if extra_class:
        data["className"] = extra_class
    data["style"] = {"spacing": {"blockGap": {"top": "0", "left": "0"}}}
    cls = ["wp-block-columns"]
    if align:
        cls.append("align" + align)
    if not stack:
        cls.append("is-not-stacked-on-mobile")
    if extra_class:
        cls.append(extra_class)
    return '<!-- wp:columns %s -->\n<div class="%s">\n%s\n</div>\n<!-- /wp:columns -->' % (
        json.dumps(data, separators=(",", ":")), " ".join(cls), "\n".join(cols))


# ---------------------------------------------------------------------------
# Photographs: slug -> alt text, written from each picture at full size.
# ---------------------------------------------------------------------------
ALT = {}


def alt(slug):
    if slug not in ALT:
        raise SystemExit("no alt text for %s" % slug)
    return ALT[slug]


# ---------------------------------------------------------------------------
# Parts
# ---------------------------------------------------------------------------
def build_header():
    # The switch needs text inside it: an empty core/button renders nothing at
    # all. The label is for screen readers; inc/scheme.php adds the pressed state.
    toggle = button('<span class="screen-reader-text">Switch between light and dark mode</span>', "#",
                    extra_class="montana-scheme-toggle")
    nav = group(
        '<!-- wp:navigation {"overlayMenu":"mobile","className":"montana-nav","layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap"}} /-->',
        layout="flex", extra_class="montana-header__nav")
    # The template's mark: an initial in a ruled square over the name, with a
    # spaced word beneath. The initial and the word are ordinary paragraphs, so
    # renaming the hotel is an edit in place. An uploaded logo replaces all three.
    brand = group("\n".join([
        '<!-- wp:site-logo {"width":112} /-->',
        paragraph("M", align="center", extra_class="montana-logo__mark", placeholder=" "),
        '<!-- wp:site-title {"level":0,"textAlign":"center"} /-->',
        paragraph("Resort", align="center", extra_class="montana-logo__word", placeholder=" "),
    ]), layout="constrained", extra_class="montana-header__brand")
    end = flex_row("\n".join([
        social_links("montana-plain"),
        buttons([toggle, button("Book A Room", home(BOOK), extra_class="montana-header__book")],
                nowrap=True, gap="40"),
    ]), justify="right", gap="40", wrap="nowrap", extra_class="montana-header__end")
    row = flex_row("\n".join([nav, brand, end]), justify="space-between", gap="40", wrap="nowrap",
                   extra_class="montana-header__row")
    write("header", "Header",
          group(row, align="full", layout="default", extra_class="montana-header"),
          keywords=["header", "navigation"],
          description="The template's header, laid over the first photograph: the menu on the left, the mark in "
                      "the middle, social links and the booking button on the right. It turns black once the "
                      "page scrolls.",
          block_types=["core/template-part/header"])


def footer_title(text):
    return heading(text, level=2, size="large", color="overlay", extra_class="montana-footer__title")


def build_footer():
    col_address = column("\n".join([
        footer_title("Address"),
        paragraph("%s,<br>%s, Montana" % (ADDRESS_1, ADDRESS_2), color="on-dark"),
        line_link("Get directions", home("/contact/"), color="on-dark"),
    ]), width="25%")
    col_reserve = column("\n".join([
        footer_title("Reservation"),
        paragraph('<a href="%s">%s</a><br><a href="mailto:%s">%s</a>' % (PHONE_HREF, PHONE, EMAIL, EMAIL),
                  color="on-dark", extra_class="montana-footer__lines"),
    ]), width="25%")
    links = "".join('<li><a href="%s">%s</a></li>' % (home(p), t)
                    for p, t in (("/", "Home"), ("/rooms/", "Rooms"), ("/about/", "About"), ("/blog/", "News")))
    col_nav = column("\n".join([
        footer_title("Navigation"),
        '<!-- wp:list {"className":"montana-footer__links"} -->\n<ul class="wp-block-list montana-footer__links">%s</ul>\n<!-- /wp:list -->'
        % "".join("<!-- wp:list-item -->\n%s\n<!-- /wp:list-item -->" % li for li in
                  ['<li><a href="%s">%s</a></li>' % (home(p), t)
                   for p, t in (("/", "Home"), ("/rooms/", "Rooms"), ("/about/", "About"), ("/blog/", "News"))]),
    ]), width="16.66%")
    del links
    col_news = column("\n".join([
        footer_title("Newsletter"),
        shortcode('[montana_form type="newsletter"]'),
        paragraph("Seasonal offers and news from the lake, four times a year.", color="on-dark",
                  extra_class="montana-footer__note"),
    ]), width="33.33%")
    body = columns([col_address, col_reserve, col_nav, col_news], gap="50", extra_class="montana-footer__cols")
    rule = ('<!-- wp:separator {"className":"is-style-wide montana-footer__rule"} -->\n'
            '<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide montana-footer__rule"/>\n'
            '<!-- /wp:separator -->')
    legal = flex_row("\n".join([
        paragraph('&copy; Montana Resort. All rights reserved. Theme by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a>.',
                  color="on-dark", extra_class="montana-footer__legal"),
        social_links("montana-plain"),
    ]), justify="space-between", gap="40", extra_class="montana-footer__bottom")
    write("footer", "Footer",
          group("\n".join([body, rule, legal]), align="full", background="dark", text="on-dark",
                layout="constrained", extra_class="montana-footer"),
          keywords=["footer", "newsletter"],
          description="Four columns on black: the address, reservations, a few links and the newsletter "
                      "sign-up, with the copyright line and social links under a hairline.",
          block_types=["core/template-part/footer"])


def box(title, inner):
    """One of the sidebar's pale panels, its title over a hairline."""
    parts = []
    if title:
        parts.append(heading(title, level=2, extra_class="montana-widget-title"))
    parts.append(inner)
    return group("\n".join(parts), layout="constrained", gap="40", style="montana-box")


def build_sidebar():
    inner = "\n".join([
        box(None, '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /-->'),
        box("Category", '<!-- wp:categories {"showPostCounts":true,"className":"montana-counts"} /-->'),
        box("Recent Post", '<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageSizeSlug":"thumbnail","featuredImageAlign":"left","featuredImageSizeWidth":80,"featuredImageSizeHeight":80,"className":"montana-recent"} /-->'),
        box("Tag Clouds", '<!-- wp:tag-cloud {"smallestFontSize":"0.875rem","largestFontSize":"0.875rem","className":"montana-tags"} /-->'),
        box("Newsletter", shortcode('[montana_form type="newsletter" layout="block" button="Subscribe"]')),
    ])
    write("sidebar", "Sidebar", group(inner, layout="constrained", gap="40", extra_class="montana-sidebar"),
          keywords=["sidebar"], inserter=False,
          description="Search, categories, recent posts, tags and a newsletter sign-up, each on a pale panel.")


# ---------------------------------------------------------------------------
# Sections
# ---------------------------------------------------------------------------
HERO_SLIDES = [
    ("hero-1", "Montana Resort", "A lakeside hotel below the peaks", 1),
    ("hero-2", "Life is Beautiful", "Slow mornings, clear water, long days outside", 2),
]
FOCAL = {}


def build_hero():
    slides = []
    for slug, title, line, level in HERO_SLIDES:
        inner = group("\n".join([
            heading(title, level=level, align="center", color="overlay", size="colossal",
                    extra_class="montana-hero__title"),
            paragraph(line, align="center", color="overlay", extra_class="montana-hero__line"),
        ]), layout="constrained", gap="20")
        slides.append(cover(inner, slug, dim=30, min_height=100, min_height_unit="vh", align=None,
                            extra_class="montana-hero montana-slide", focal=FOCAL.get(slug)))
    # The frame holds the arrows the script adds; the strip inside it scrolls.
    carousel = group(group("\n".join(slides), layout="default", extra_class="montana-carousel montana-carousel--hero"),
                     align="full", layout="default", extra_class="montana-carousel-frame montana-hero-frame")
    write("hero", "Hero: photograph slider", carousel,
          categories=SECTIONS, keywords=["hero", "slider", "banner"],
          description="Two full-screen photographs that take turns, each with the hotel's name and one line, "
                      "and arrows on a wide screen.")


def photo_pair(first, second, extra_class):
    """Two portrait photographs side by side, the second set 40px lower."""
    return group(columns([
        column(image(first, alt(first), ratio="284/400")),
        column(image(second, alt(second), ratio="294/400"), extra_class="montana-pair__low"),
    ], gap="20", stack_on_mobile=False, extra_class="montana-pair__cols"),
        layout="default", extra_class="montana-pair " + extra_class)


def build_about():
    text = "\n".join([
        section_title("About Us", "A Lakeside Hotel <br>in the Mountains"),
        paragraph("Montana Resort sits on the quiet north shore of Lake Arrow, forty minutes from the nearest "
                  "town and five from the first trailhead. Thirty-two rooms and suites, a restaurant that cooks "
                  "with what the valley grows, a small spa, and a jetty where the day starts with coffee and ends "
                  "with the light going pink on the peaks."),
        line_link("Learn More", home("/about/")),
    ])
    write("about", "About: words and two photographs",
          section(columns([
              column(group(text, layout="constrained", gap="40", extra_class="montana-about__text"),
                     width="41.66%", vertical="center"),
              column(photo_pair("about-1", "about-2", "montana-pair--end"), width="58.33%"),
          ], gap="50", vertical="center"), extra_class="montana-about montana-about--first"),
          categories=SECTIONS, keywords=["about", "story", "intro"],
          description="The hotel's story beside two photographs set at different heights, with a Learn More link.")


OFFERS = [
    ("offer-1", "Stay three nights, <br>pay for two",
     ["Any room or suite", "Breakfast for two every morning", "Sunday to Thursday arrivals"]),
    ("offer-2", "A spa weekend <br>for two",
     ["Two nights in a Lake View Room", "Two sixty-minute massages", "Sauna and hot tub every evening"]),
    ("offer-3", "The family <br>summer week",
     ["Seven nights in the Family Chalet", "Children under twelve eat free", "Kayaks and bikes included"]),
]


def build_offers(first=False):
    cards = []
    for slug, title, items in OFFERS:
        cards.append(column(group("\n".join([
            image(slug, alt(slug), ratio="362/350", extra_class="montana-offer__photo"),
            heading(title, level=3, extra_class="montana-offer__title"),
            dots_list(items),
            buttons([button("book now", home(BOOK), style="montana-outline", width=100)]),
        ]), layout="default", extra_class="montana-offer")))
    inner = section_title("Our Offers", "Ongoing Offers", align="center") + "\n" + columns(cards, gap="40")
    slug = "offers"
    write(slug, "Offers: three cards",
          section(inner, extra_class="montana-offers", anchor="offers"),
          categories=SECTIONS, keywords=["offers", "packages", "deals"],
          description="Three offers, each a photograph that zooms on hover, a title, three facts and a book "
                      "now button.")


def build_video():
    inner = group("\n".join([
        eyebrow("Montana Lake View", align="center", color="overlay"),
        heading("Relax and Enjoy <br>Your Holiday", level=2, align="center", color="overlay"),
        # montana-video: assets/js/interactions.js plays the film in a popup.
        # Without JavaScript it is an ordinary link to the video.
        buttons([button('<span class="screen-reader-text">Play the film: a still mountain lake</span>', VIDEO,
                        extra_class="montana-video montana-play")], align="center"),
    ]), layout="constrained", gap="30")
    write("video", "Video band",
          cover(inner, "video", dim=50, min_height=740, extra_class="montana-video-band"),
          categories=SECTIONS, keywords=["video", "film", "banner"],
          description="A photograph across the width, dimmed, with a line, a heading and a round play button "
                      "that opens the film.")


def build_dining():
    text = "\n".join([
        section_title("Delicious Food", "We Serve Fresh and <br>Delicious Food"),
        paragraph("The Boathouse kitchen buys from four farms in the valley and one fisherman on the lake. "
                  "Breakfast is on the terrace until eleven, dinner is by the window until the last table "
                  "wants to leave, and a picnic basket for the trail can be ready by seven in the morning."),
        line_link("See the menu", home("/about/")),
    ])
    write("dining", "Restaurant: two photographs and words",
          section(columns([
              column(photo_pair("dining-1", "dining-2", "montana-pair--start"), width="58.33%"),
              column(group(text, layout="constrained", gap="40", extra_class="montana-about__text"),
                     width="41.66%", vertical="center"),
          ], gap="50", vertical="center"), extra_class="montana-about"),
          categories=SECTIONS, keywords=["restaurant", "food", "dining"],
          description="Two food photographs at different heights beside the restaurant's story.")


ROOMS = [
    # slug, name, price, size, beds, sleeps, view, text
    ("room-1", "Lake View Room", "190", "28 m²", "King or twin beds", "Sleeps 2", "Lake view",
     "Our most booked room: a corner window over the water with a table for two, a rain shower, "
     "and linen from a mill up the valley."),
    ("room-2", "Mountain Suite", "320", "46 m²", "King bed and sofa bed", "Sleeps 3", "Mountain view",
     "A separate sitting room with a wood stove, a deep bath under the eaves and windows on two sides "
     "facing the ridge."),
    ("room-3", "Family Chalet", "410", "64 m²", "Two bedrooms", "Sleeps 5", "Garden and lake",
     "A timber chalet in the garden with two bedrooms, a small kitchen and its own porch, a short walk "
     "from the jetty and the kayaks."),
    ("room-4", "A-Frame Cabin", "260", "34 m²", "King bed", "Sleeps 2", "Forest and hills",
     "A cabin for two at the edge of the pines: the bed faces a wall of glass, and there is a wood "
     "stove inside and a cedar hot tub on the deck."),
]


def build_featured_rooms():
    tiles = []
    for slug, name, price, *_ in ROOMS:
        words = group("\n".join([
            paragraph("From $%s/night" % price, color="overlay", extra_class="montana-room__price"),
            heading(name, level=3, color="overlay", size="x-large", extra_class="montana-room__name"),
        ]), layout="constrained", gap="20", extra_class="montana-room__words")
        link = paragraph('<a href="%s">book now</a>' % home(BOOK), color="overlay", extra_class="montana-room__link")
        row = flex_row(words + "\n" + link, justify="space-between", gap="40", wrap="nowrap",
                       vertical="bottom", extra_class="montana-room__foot")
        tiles.append(cover(row, slug, dim=50, align=None, gradient="linear-gradient(180deg,rgb(255,255,255) 0%,rgb(0,0,0) 77%)",
                           content_position="bottom center", extra_class="montana-room", min_height=None))
    grid = zero_gap_columns([column(t) for t in tiles[:2]], extra_class="montana-rooms") + "\n" + \
        zero_gap_columns([column(t) for t in tiles[2:]], extra_class="montana-rooms")
    head = section_title("Featured Rooms", "Choose a Better Room", align="center")
    write("featured-rooms", "Featured rooms: four photographs",
          group(group(head, layout="constrained") + "\n" + grid, align="full", layout="constrained",
                extra_class="montana-featured", padding={"top": "70"}),
          categories=SECTIONS, keywords=["rooms", "suites", "accommodation"],
          description="Four rooms edge to edge, two by two, each a photograph with its price from and its name, "
                      "and a book now link that slides in on hover.")


def build_room_list():
    rows = []
    for n, (slug, name, price, size, beds, sleeps, view, text) in enumerate(ROOMS):
        photo = column(image(slug, alt(slug), ratio="960/600", extra_class="montana-room-card__photo"),
                       width="55%", vertical="center")
        words = column(group("\n".join([
            paragraph("From $%s / night" % price, extra_class="montana-room-card__price"),
            heading(name, level=3, size="x-large"),
            dots_list([size, beds, sleeps, view]),
            paragraph(text),
            buttons([button("Request this room", home(BOOK), style="montana-outline")]),
        ]), layout="constrained", gap="30", extra_class="montana-room-card__words"), width="45%", vertical="center")
        pair = [photo, words] if n % 2 == 0 else [words, photo]
        rows.append(columns(pair, gap="60", vertical="center",
                            extra_class="montana-room-card" + (" montana-room-card--flip" if n % 2 else "")))
    head = section_title("Rooms &amp; Suites", "Every Room Looks at Something", align="center")
    write("room-list", "Rooms: four rooms with their facts",
          section(head + "\n" + group("\n".join(rows), layout="constrained", gap="70"), anchor="rooms",
                  extra_class="montana-room-list"),
          categories=SECTIONS, keywords=["rooms", "suites", "prices", "facts"],
          description="Each room with a photograph, its nightly price from, size, beds, guests and view, a line "
                      "about it and a button that goes to the booking request.")


def build_booking():
    box = group("\n".join([
        heading("Request a Stay", level=2, align="center", size="large", extra_class="montana-booking__title"),
        paragraph("Tell us your dates and we will reply within a day with what is free and a price. "
                  "Nothing is booked or charged until you confirm.", align="center", size="small",
                  extra_class="montana-booking__note"),
        shortcode('[montana_form type="booking"]'),
    ]), layout="constrained", background="base", gap="30", extra_class="montana-booking__box")
    write("booking", "Booking request form",
          cover(box, "booking", dim=60, extra_class="montana-booking", padding_y="70", anchor="book"),
          categories=SECTIONS, keywords=["booking", "reservation", "form", "availability"],
          description="The template's booking box over a darkened photograph: check-in and check-out dates, "
                      "guests, room, name, email and a message. It sends a request; nothing is booked.")


def build_query():
    inner = group(flex_row("\n".join([
        paragraph("For a reservation or a question?", extra_class="montana-query__text"),
        buttons([button(PHONE, PHONE_HREF, style="montana-pill")]),
    ]), justify="space-between", gap="40", extra_class="montana-query__row"),
        layout="constrained", extra_class="montana-query")
    write("reservation-line", "Reservation line",
          section(group(inner, layout="constrained", content_size="930px"), padding="80",
                  extra_class="montana-query-band"),
          categories=SECTIONS, keywords=["reservation", "phone", "call to action"],
          description="One line in a ruled box, and the reservation number as a round button.")


GALLERY = ["gallery-1", "gallery-2", "gallery-3", "gallery-4", "gallery-5"]


def build_gallery():
    items = "\n".join(
        '<!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"large","linkDestination":"none"} -->\n'
        '<figure class="wp-block-image size-large"><img src="%s" alt="%s"/></figure>\n'
        '<!-- /wp:image -->' % (theme_image(s), alt(s)) for s in GALLERY)
    data = {"columns": 5, "imageCrop": True, "linkTo": "none", "sizeSlug": "large", "align": "full",
            "className": "montana-strip",
            "style": {"spacing": {"blockGap": {"top": "0", "left": "0"}}}}
    gallery = ('<!-- wp:gallery %s -->\n'
               '<figure class="wp-block-gallery alignfull has-nested-images columns-5 is-cropped montana-strip">\n'
               '%s\n</figure>\n<!-- /wp:gallery -->' % (json.dumps(data, separators=(",", ":")), items))
    write("photo-strip", "Photo strip: five photographs edge to edge", gallery,
          categories=SECTIONS, keywords=["gallery", "photos", "instagram"],
          description="Five square photographs across the full width, no gaps, enlarging on click.")


ABOUT_WIDE = ["wide-1", "wide-2", "wide-3"]


def build_about_wide():
    slides = "\n".join(
        image(s, alt(s), ratio="1533/750", extra_class="montana-slide") for s in ABOUT_WIDE)
    write("photo-slider", "Photo slider, set to the right",
          group(group(slides, layout="default", extra_class="montana-carousel montana-carousel--wide"),
                align="full", layout="default", extra_class="montana-carousel-frame montana-wide"),
          categories=SECTIONS, keywords=["slider", "gallery", "photos"],
          description="Wide photographs that take turns, set in from the left as the template's about page has "
                      "them, with arrows on a wide screen.")


def build_notes():
    notes = [
        ("Rooms That Look <br>at Something",
         "Every room faces the lake, the ridge or the pines, and none faces the car park. Beds are made "
         "with linen from a mill in the valley, and the windows open."),
        ("A Kitchen That Knows <br>Its Farmers",
         "The menu changes with the week's deliveries: trout from the lake, lamb from the Reyes ranch, "
         "huckleberries in August, and bread baked in the kitchen before dawn."),
        ("Out of the Door, <br>Onto the Trail",
         "Forty miles of marked trails start at the gate. The front desk lends maps, poles and bear spray, "
         "and knows which routes are still under snow."),
        ("Quiet by Design",
         "No televisions in the suites, no music on the terrace, and a spa that takes six guests at a time. "
         "Children are welcome everywhere except the sauna."),
    ]
    cols = [column(group("\n".join([
        heading(t, level=3, extra_class="montana-note__title"),
        paragraph(x),
    ]), layout="constrained", gap="30", extra_class="montana-note")) for t, x in notes]
    rows = columns(cols[:2], gap="60") + "\n" + columns(cols[2:], gap="60")
    write("about-notes", "About: four short notes",
          section(group(rows, layout="constrained", gap="50"), extra_class="montana-notes"),
          categories=SECTIONS, keywords=["about", "features", "text"],
          description="Four short paragraphs under their headings, two by two.")


def build_contact():
    def item(name, first, second):
        return group("\n".join([
            paragraph(first, extra_class="montana-contact__main"),
            paragraph(second, extra_class="montana-contact__sub"),
        # Flow, not constrained: a constrained group takes the root padding in
        # the editor, which overrode the indent the icon sits in.
        ]), layout="default", gap="20", extra_class="montana-contact__item " + icon(name))

    details = group("\n".join([
        item("home", ADDRESS_1 + ", " + ADDRESS_2, "Montana, United States"),
        item("phone", '<a href="%s">%s</a>' % (PHONE_HREF, PHONE), "Front desk, 7am to 11pm"),
        item("mail", '<a href="mailto:%s">%s</a>' % (EMAIL, EMAIL), "Send us your question any time"),
    ]), layout="constrained", gap="40")
    form = shortcode('[montana_form type="contact"]')
    map_block = (
        '<!-- wp:html -->\n'
        '<iframe class="montana-map" title="Map of the lake shore around the hotel" loading="lazy" '
        'src="https://www.openstreetmap.org/export/embed.html?bbox=-114.4300%2C48.3800%2C-114.2900%2C48.4700&amp;layer=mapnik" '
        'style="width:100%;height:480px;border:0"></iframe>\n'
        '<!-- /wp:html -->'
    )
    inner = "\n".join([
        map_block,
        spacer("60"),
        heading("Get in Touch", level=2, extra_class="montana-contact__title"),
        columns([column(form, width="66.66%"), column(details, width="25%")], gap="70",
                extra_class="montana-contact__cols"),
    ])
    write("contact", "Contact: map, form and details",
          section(inner, anchor="contact", extra_class="montana-contact"),
          categories=SECTIONS, keywords=["contact", "form", "map"],
          description="A map, then the message form beside the address, phone and email.")


# ---------------------------------------------------------------------------
# Hidden patterns: the pieces templates are built from.
# ---------------------------------------------------------------------------
def banner(slug, title, description, title_block, photo="banner-1"):
    write(slug, title, cover(group(title_block, layout="constrained"), photo, dim=40, min_height=None,
                             extra_class="montana-banner", focal=FOCAL.get(photo)),
          inserter=False, description=description)


def build_hidden():
    banner("hidden-page-banner", "Page banner", "The photograph banner a page title sits on.",
           '<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay"} /-->')
    banner("hidden-post-banner", "Post banner", "The photograph banner a post title sits on.",
           '<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay","className":"montana-banner__post"} /-->',
           photo="banner-2")
    banner("hidden-blog-banner", "Blog banner", "The heading for the posts page.",
           heading("Blog", level=1, align="center", color="overlay"), photo="banner-2")
    banner("hidden-archive-banner", "Archive banner", "The banner an archive title sits on.",
           '<!-- wp:query-title {"type":"archive","textAlign":"center","textColor":"overlay","showPrefix":false} /-->',
           photo="banner-2")
    banner("hidden-search-banner", "Search banner", "The banner search results sit under.",
           '<!-- wp:query-title {"type":"search","textAlign":"center","textColor":"overlay"} /-->')
    banner("hidden-404-banner", "Not found banner", "The banner of the page that is not there.",
           heading("Page Not Found", level=1, align="center", color="overlay"))

    date_badge = group("\n".join([
        '<!-- wp:post-date {"format":"d","className":"montana-date__day"} /-->',
        '<!-- wp:post-date {"format":"M","className":"montana-date__month"} /-->',
    ]), layout="default", extra_class="montana-date")
    posts = (
        '<!-- wp:query {"queryId":0,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":true},"className":"montana-posts","layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query montana-posts"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->\n'
        + group('<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->\n' + date_badge,
                layout="default", extra_class="montana-post__media") + '\n'
        + group("\n".join([
            '<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"large"} /-->',
            '<!-- wp:post-excerpt {"excerptLength":36} /-->',
            flex_row("\n".join([
                '<!-- wp:post-terms {"term":"category","className":"montana-meta %s","fontSize":"small"} /-->' % icon("folder"),
                '<!-- wp:post-comments-count {"className":"montana-meta %s","fontSize":"small"} /-->' % icon("message"),
            ]), gap="30", extra_class="montana-post__meta"),
        ]), layout="constrained", gap="30", extra_class="montana-post__body") + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("Nothing here yet. Try a search, or start again from the home page.") + '\n'
        '<!-- /wp:query-no-results -->\n'
        '<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->\n'
        '<!-- wp:query-pagination-previous {"label":" "} /-->\n'
        '<!-- wp:query-pagination-numbers /-->\n'
        '<!-- wp:query-pagination-next {"label":" "} /-->\n'
        '<!-- /wp:query-pagination --></div>\n'
        '<!-- /wp:query -->'
    )
    write("hidden-posts-list", "Posts list", posts, inserter=False,
          description="The post list used by the blog and every archive: photograph with its date, title, "
                      "excerpt, categories and comments.")

    meta = flex_row("\n".join([
        '<!-- wp:post-terms {"term":"category","className":"montana-meta %s","fontSize":"small"} /-->' % icon("folder"),
        '<!-- wp:post-date {"className":"montana-meta %s","fontSize":"small"} /-->' % icon("calendar"),
        '<!-- wp:post-comments-count {"className":"montana-meta %s","fontSize":"small"} /-->' % icon("message"),
    ]), gap="30", extra_class="montana-post__meta")
    write("hidden-post-meta", "Post meta", meta, inserter=False,
          description="Categories, date and comment count for a single post.")

    author = group('<!-- wp:post-author {"avatarSize":90,"showBio":true,"className":"montana-author"} /-->',
                   layout="constrained", extra_class="montana-author-box")
    write("hidden-author", "Author box", author, inserter=False,
          description="The author's photograph, name and biography under a post.")

    comments = (
        '<!-- wp:comments {"className":"montana-comments"} -->\n'
        '<div class="wp-block-comments montana-comments">\n'
        '<!-- wp:comments-title {"level":2,"fontSize":"large"} /-->\n'
        '<!-- wp:comment-template -->\n'
        '<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->\n'
        '<div class="wp-block-columns is-not-stacked-on-mobile">'
        '<!-- wp:column {"width":"70px"} -->\n<div class="wp-block-column" style="flex-basis:70px">'
        '<!-- wp:avatar {"size":70} /--></div>\n<!-- /wp:column -->\n'
        '<!-- wp:column -->\n<div class="wp-block-column">'
        '<!-- wp:comment-content /-->\n'
        '<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->\n'
        '<div class="wp-block-group">'
        '<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->\n<div class="wp-block-group">'
        '<!-- wp:comment-author-name {"fontSize":"medium"} /-->\n'
        '<!-- wp:comment-date {"fontSize":"small"} /--></div>\n<!-- /wp:group -->\n'
        '<!-- wp:comment-reply-link {"className":"montana-reply","fontSize":"small"} /--></div>\n'
        '<!-- /wp:group --></div>\n<!-- /wp:column --></div>\n'
        '<!-- /wp:columns -->\n'
        '<!-- /wp:comment-template -->\n'
        '<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"left"}} -->\n'
        '<!-- wp:comments-pagination-previous /-->\n'
        '<!-- wp:comments-pagination-numbers /-->\n'
        '<!-- wp:comments-pagination-next /-->\n'
        '<!-- /wp:comments-pagination -->\n'
        '<!-- wp:post-comments-form /-->\n'
        '</div>\n'
        '<!-- /wp:comments -->'
    )
    write("hidden-comments", "Comments", comments, inserter=False,
          description="The comments and the reply form for a single post.")

    notfound = "\n".join([
        heading("That Page Has Gone for a Walk", level=2, align="center"),
        paragraph("The address may be old, or the page may have been renamed. "
                  "Try a search, or start again from the home page.", align="center"),
        '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search the site","buttonText":"Search","align":"center"} /-->',
        buttons([button("Back to the home page", home("/"))], align="center"),
    ])
    write("hidden-404", "404 content",
          section(group(notfound, layout="constrained", content_size="620px", gap="40")),
          inserter=False, description="What a visitor sees when nothing is there.")


# ---------------------------------------------------------------------------
# Whole pages
# ---------------------------------------------------------------------------
def ref(slug):
    return '<!-- wp:pattern {"slug":"montana/%s"} /-->' % slug


def build_pages():
    pages = {
        "page-home": ("Page: home", ["hero", "about", "offers", "video", "dining", "featured-rooms",
                                     "reservation-line", "photo-strip"]),
        "page-about": ("Page: about", ["about", "photo-slider", "about-notes", "reservation-line", "photo-strip"]),
        "page-rooms": ("Page: rooms", ["offers", "room-list", "booking", "reservation-line", "photo-strip"]),
        "page-contact": ("Page: contact", ["contact"]),
    }
    for slug, (title, refs) in pages.items():
        write(slug, title, "\n".join(ref(r) for r in refs), categories=PAGES,
              description="A complete %s page, built from the theme's sections, as the template lays it out."
              % title.split(": ")[1])


def main():
    from photos import ALT as PHOTO_ALT, FOCAL as PHOTO_FOCAL
    ALT.update(PHOTO_ALT)
    FOCAL.update(PHOTO_FOCAL)
    os.makedirs(PATTERNS, exist_ok=True)
    for name in os.listdir(PATTERNS):
        if name.endswith(".php"):
            os.remove(os.path.join(PATTERNS, name))
    build_header()
    build_footer()
    build_sidebar()
    build_hidden()
    build_hero()
    build_about()
    build_offers()
    build_video()
    build_dining()
    build_featured_rooms()
    build_room_list()
    build_booking()
    build_query()
    build_gallery()
    build_about_wide()
    build_notes()
    build_contact()
    build_pages()

    for slug in sorted(WRITTEN):
        print("  patterns/%s.php" % slug)
    print("\n%d patterns" % len(WRITTEN))


if __name__ == "__main__":
    main()
