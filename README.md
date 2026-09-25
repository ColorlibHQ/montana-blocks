# Montana

A block theme for hotels, lodges and mountain resorts, with a room booking request form that needs no plugin.

A free WordPress block theme by [Colorlib](https://colorlib.com/). Full site editing,
no page builder and no plugins required.

- **Theme page:** https://colorlib.com/wp/themes/montana/
- **Live demo:** https://colorlibhub.com/montana-blocks/
- **Download:** https://updates.colorlib.com/download/theme/montana.zip (or the zip attached to the [latest release](../../releases/latest))

## Description

Montana is a full site editing theme for hotels, lodges, guest houses and mountain resorts: a full-screen photograph slider, the hotel's story beside two photographs, three offers, a video band, the restaurant, four featured rooms edge to edge, a rooms page with each room's nightly price, size, beds, guests and view, a contact page with a map, a newsletter sign-up and a journal.

The booking form is the heart of it: check-in and check-out dates, guests, the room, name, email and a message. It sends a REQUEST to the front desk -- the wording on the page says so -- and does not show availability or take payment. The dates are checked on the server as well as in the browser: check-in no earlier than today, check-out at least a night later. The booking, contact and newsletter forms need no plugin.

It is built from the Montana HTML template: the header laid over the first photograph with the mark in the middle, the 100px hero, the offer cards, the rooms two by two with their prices, the ruled reservation line, the strip of five photographs and the black footer are all there, as blocks you can edit.

Activate it on a new site and it builds the pages for you -- Home, Rooms, About, Blog and Contact -- with a menu to match. On a site that already has pages it leaves them alone.

Eight colour palettes and five type pairings, each checked for contrast before release rather than by eye. Visitor-facing dark mode that follows the reader's system setting until they choose for themselves. WooCommerce is styled if you install it and loads nothing if you do not.

## Two versions

This repository is the **block theme**. The same design also exists as an
**Elementor edition** for sites built with Elementor: [live demo](https://colorlibhub.com/montana/),
[source](https://github.com/ColorlibHQ/montana). It needs the free Elementor plugin. It also needs the [Montana Companion](https://github.com/ColorlibHQ/montana-companion) plugin. For a new site
the block theme is the one to use.

## Installation

1. In WordPress, go to Appearance -> Themes -> Add New Theme -> Upload Theme.
2. Choose montana.zip and click Install Now, then Activate.
3. Appearance -> Editor is where the header, footer, colours and templates live.

The theme updates itself from colorlib.com: it is distributed outside the
WordPress.org directory, so it checks `updates.colorlib.com` for new versions.

## Development

The files in `.dev/` generate and check the theme (palettes, patterns, block
validation, rendered contrast, overflow and alignment checks) and build the zip.
They are not part of the distributed theme. See `.dev/README.md` where present,
and `CLAUDE.md` for the conventions.

## Licence

GNU General Public License v2 or later. Photographs and fonts carry their own
licences, listed in `readme.txt`.
