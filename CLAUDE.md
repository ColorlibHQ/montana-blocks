# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Montana 1.0.1** is a Colorlib **WordPress block theme** (full site editing) for
hotels, lodges and mountain resorts. Text domain and slug `montana`. 31
patterns, 10 templates, 3 parts, 8 colour palettes × 5 type pairings, 5
starter pages built on activation, visitor dark mode, WooCommerce styling, and
a room booking request form, a contact form and a newsletter sign-up that need
no plugin.

It reproduces the design of the Montana HTML template
(`preview.colorlib.com/theme/montana/`, Bootstrap 4 + jQuery): the header laid
over the first photograph, the 100px hero slider, the about block with two
photographs at different heights, three offer cards, the video band, the
restaurant block mirrored, four rooms edge to edge, the ruled reservation line,
the five-photograph strip and the black footer. It was matched with
side-by-side full-page renders (`.dev/compare/`), not property by property.
The Elementor edition of the same design is `colorlibhub.com/montana/`
(theme `ColorlibHQ/montana` + the Montana Companion plugin for its widgets).

Decisions made and not to be revisited: **block theme only, no companion
plugin, no page builder, no jQuery**; **self-hosted, not wp.org** (`Update
URI: https://updates.colorlib.com/theme/montana.json`). It is not a static HTML
template: the HTML upgrade phases and R2 preview flow in the global
instructions do not apply.

Provenance: the toolchain and `inc/` were ported from
`~/Fresh Projects/horseclub-blocks` (checkers with its four fixes, the booking
form) and `~/Fresh Projects/dreamrs-blocks` (directory-independent updater,
`build_templates.py`, `capture.mjs`/`compare.mjs`, the `-` decoding in
dead-selectors, the publish renderer). **Every leftover word from a source
theme is a silent bug**: `grep -riE "horseclub|dreamrs|unioncorp|pato|daren"`
must find nothing outside this paragraph.

## The story (demo content)

One niche: Montana Resort, a lakeside hotel in the mountains -- thirty-two
rooms, a restaurant (the Boathouse), a small spa, a jetty with boats. Every
photograph is from Pexels, chosen as one timber-and-lake series; none of the
HTML template's photographs ships (they told a tropical sea-resort story, and
several could not be traced to a free source -- the trace is in the build
report). `.dev/photos.py` holds each photograph's source and the alt text,
written from the picture. Theme Check therefore reports no photo-site finding.

## Commands

```bash
python3 .dev/build_theme.py        # theme.json + styles/** (audits contrast first, refuses a failing palette)
python3 .dev/build_patterns.py     # patterns/*.php (reads .dev/photos.py for alt text and focal points)
python3 .dev/build_templates.py    # templates/*.html + parts/*.html
node    .dev/build-fonts.mjs       # assets/fonts: Raleway + Playfair Display, variable, latin + latin-ext
python3 .dev/photos.py             # the photograph credits, as readme.txt lists them

# A throwaway WordPress on this theme's port (9492). --login signs every visitor in.
npx -y @wp-playground/cli@3.1.54 server --port=9492 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/montana" \
  --blueprint=.dev/blueprint-demo.json --login
# .dev/blueprint.json is the same without the demo content.

export WP_URL=http://127.0.0.1:9492
node .dev/normalize-blocks.mjs     # ALWAYS after build_patterns.py; a second run must rewrite 0
node .dev/validate-blocks.mjs      # templates, parts, patterns, stored pages, posts and menus
node .dev/editor-check.mjs         # every pattern opened in the editor
bash .dev/check-rendered.sh        # contrast (8 palettes × light/dark, second slides too), buttons,
                                   # overflow, alignment, dead selectors
node .dev/capture.mjs http://127.0.0.1:9492 .dev/compare/theme home=/,rooms=/rooms/ 1440,390
node .dev/capture.mjs https://preview.colorlib.com/theme/montana .dev/compare/source home=/index.html,rooms=/rooms.html 1440,390
node .dev/compare.mjs home,rooms 1440,390
bash .dev/build-zip.sh             # /tmp/montana-build/montana.zip, without .dev/CLAUDE.md/node_modules
node .dev/publish/shoot.mjs        # product-page screenshots (demo content imported)
```

Pages are **copies** made at activation. After changing a pattern, restart the
Playground on a fresh instance before believing a fix did or did not work.

## Where things live

| Concern | File(s) |
| --- | --- |
| Palette, type scale, spacing, fonts, element and block styles | `.dev/build_theme.py` → `theme.json`, `styles/**` (never edit the JSON) |
| Sections, pages, hidden template pieces | `.dev/build_patterns.py` (+ `patternlib.py`, `photos.py`) → `patterns/*.php` |
| Templates and parts | `.dev/build_templates.py` → `templates/*.html`, `parts/*.html` |
| Components, block-style CSS, icons, header, sliders | `style.css` (also the editor stylesheet) |
| Booking request, contact, newsletter `[montana_form type=…]` | `inc/booking.php` + `assets/css/forms.css` |
| Form-plugin styling | `inc/forms.php` + `assets/css/forms.css` |
| Starter pages + menu on activation | `inc/front-page-setup.php` |
| Visitor dark mode | `inc/scheme.php` + `assets/css/scheme.css` + `assets/js/scheme-toggle.js` |
| Header, sliders, reveals, video popup, booking dates | `assets/js/interactions.js` |
| Self-hosted updates + install count | `inc/updates.php` |
| WooCommerce | `inc/woocommerce.php` + `assets/css/woocommerce.css` |
| Demo content for colorlibhub + Playground (not shipped) | `.dev/demo/import.php`, `.dev/demo/media/` |

## Conventions that matter

- **Generated, then normalised.** `build_patterns.py` writes markup that merely
  parses; `normalize-blocks.mjs` re-serialises it through the real block
  serialiser. A custom-gradient cover still writes its dim classes before the
  two gradient classes (`patternlib.cover`).
- **Palette slugs name jobs.** `overlay` is text on a photograph or `dark`;
  `on-dark` is the footer's grey; `on-primary` is a label on a `primary` fill.
  `accent` is the template's #009dff (2.9:1 on white): decoration and the
  footer's link hovers only. `build_theme.py` refuses a palette that fails any
  pair in `CONTRAST_CHECKS`, and a scheme.css that names an undefined slug.
- **The header lies over the first photograph** (absolute on
  `.wp-site-blocks > header`) and the script adds `is-stuck` past 200px: a black
  bar fixed to the top. A page that opens with neither `.montana-hero` nor
  `.montana-banner` gets the black bar from the start (`:has()` in style.css),
  and so does the editor. Heroes and banners carry a top shade (`::before`) so
  the white menu reads on a bright sky.
- **Sliders** (`.montana-carousel` inside a `.montana-carousel-frame`) are
  scroll-snap strips that work without JavaScript; the script adds the arrows
  and autoplay (none under reduced motion or with the animations filter off,
  paused on hover/focus, stopped once an arrow is used). contrast-rendered
  measures the second slides with `MONTANA_SLIDE=2`.
- **The booking form is a request.** Dates are native inputs; the server
  checks real dates, check-in not before today (site timezone), check-out at
  least a night later, and select values from the offered lists only. Each
  form has `montana_{booking,contact,newsletter}_handlers`, and
  `montana_form_handlers` sees all three. Rooms: `montana_room_options`.
- **A form can appear twice on a page** (the blog's sidebar and footer
  newsletters): ids are numbered per type, and the redirect carries the
  instance (`montana-form-at`) so only the form that was sent shows its notice.
- **Icons are classes on the block** (`montana-icon--<name>` sets
  `--montana-icon`, a `::before` masks a Tabler SVG). dead-selectors checks every
  name, decoding the serialiser's `--`.
- **Activation** removes kses around its own inserts (the map iframe),
  slashes content, claims its flag only after creating something, retries on
  `admin_init`, and never uses get_page_by_path().
- **Nothing depends on the directory name.** The stylesheet is loaded by
  template path, the update check matches the `Update URI`, and colorlibhub
  installs it as `themes/montana-blocks`.

## Theme Check

Run on the **built** zip. Expected: two REQUIRED (`add_shortcode` in
inc/booking.php, `Update URI`), no warnings. readme.txt explains both.

## Release checklist

1. `php -l` every PHP file, `node --check` the JS, validate the JSON.
2. Regenerate: build_theme → build_patterns → normalize (twice: 0 rewritten) → build_templates.
3. Fresh Playground: validate-blocks, editor-check, check-rendered.sh, forms end to end, side-by-sides.
4. One Playground run mounted as `themes/montana-blocks`.
5. Theme Check on the built zip; regenerate `languages/montana.pot`.
6. Bump `Version:` (style.css), `MONTANA_VERSION`, `Stable tag:`, changelog.
