#!/usr/bin/env python3
"""
Generates theme.json and every style variation from one table of colours.

Montana's brand blue, #009dff, is 2.89:1 on white: fine for a rule, a hover
fill or the price above a room photograph's dark gradient, and a failure for
body text, links and a 14px button label. So the palette keeps it as `accent`,
for decoration only, and derives `primary` (a deeper shade of the same hue,
5.09:1) for everything a reader has to read and every button that carries a
label.

Nothing here is eyeballed: audit() computes every pair the design actually
produces and refuses to write a palette that fails, including the dark-mode
palette that assets/css/scheme.css derives at runtime.

Usage:  python3 .dev/build_theme.py
"""

import collections
import json
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

# ---------------------------------------------------------------------------
# Palette
# ---------------------------------------------------------------------------
# Fourteen slugs, the same in every variation, so a pattern written against them
# works under all of them. `overlay` is separate from `base` on purpose: text on
# a dimmed photograph must stay near-white even when the palette is dark, and
# writing it as `base` is what turns a dark palette's covers black-on-black.
PALETTE = [
    ("Base",          "base",          "#ffffff"),
    ("Surface",       "surface",       "#f7f9fb"),   # the sidebar's search and the pale bands
    ("Contrast",      "contrast",      "#1f1f1f"),   # the template's heading colour
    ("Muted",         "muted",         "#4d4d4d"),   # the template's body copy, 8.45:1
    ("Primary",       "primary",       "#0072bb"),   # readable brand blue: links, buttons
    ("Primary deep",  "primary-deep",  "#005a94"),   # hover
    ("Accent",        "accent",        "#009dff"),   # the template's blue, decorative
    ("Dark",          "dark",          "#000000"),   # the footer and the scrolled header
    ("Divider",       "divider",       "#e4e7ec"),
    ("Overlay",       "overlay",       "#ffffff"),   # text on photographs and on `dark`
    # Body copy on the dark footer: the template's #bababa, 10.8:1 on black.
    ("On dark",       "on-dark",       "#bababa"),
    # The label on a primary fill. White in the light palettes; in the dark ones
    # `primary` is a light colour and the label has to be the dark one.
    ("On primary",    "on-primary",    "#ffffff"),
]

COLOR_SETS = {
    "colors-1-montana": ("Montana", {
        "base": "#ffffff", "surface": "#f7f9fb", "contrast": "#1f1f1f", "muted": "#4d4d4d",
        "primary": "#0072bb", "primary-deep": "#005a94", "accent": "#009dff",
        "dark": "#000000", "divider": "#e4e7ec",
        "overlay": "#ffffff", "on-dark": "#bababa", "on-primary": "#ffffff",
    }),
    "colors-2-pine": ("Pine", {
        "base": "#ffffff", "surface": "#f4f8f5", "contrast": "#16241c", "muted": "#4b5a51",
        "primary": "#1f6b47", "primary-deep": "#154f34", "accent": "#35a872",
        "dark": "#0b1510", "divider": "#dde8e1",
        "overlay": "#ffffff", "on-dark": "#bccbc2", "on-primary": "#ffffff",
    }),
    "colors-3-glacier": ("Glacier", {
        "base": "#ffffff", "surface": "#f2f8f8", "contrast": "#132426", "muted": "#48595b",
        "primary": "#0b6f73", "primary-deep": "#075356", "accent": "#18b8b0",
        "dark": "#071416", "divider": "#d9e8e8",
        "overlay": "#ffffff", "on-dark": "#b8cccc", "on-primary": "#ffffff",
    }),
    "colors-4-sunset": ("Sunset", {
        "base": "#ffffff", "surface": "#fbf6f2", "contrast": "#2a1a12", "muted": "#5e4d44",
        "primary": "#b1461a", "primary-deep": "#8a3512", "accent": "#f27a3d",
        "dark": "#1a0f09", "divider": "#efe2d8",
        "overlay": "#ffffff", "on-dark": "#d6c6bb", "on-primary": "#ffffff",
    }),
    "colors-5-heather": ("Heather", {
        "base": "#ffffff", "surface": "#f9f6fb", "contrast": "#241629", "muted": "#584c5e",
        "primary": "#7a3aa0", "primary-deep": "#5e2b7c", "accent": "#b36bdc",
        "dark": "#140b18", "divider": "#e9e0ee",
        "overlay": "#ffffff", "on-dark": "#cbbfd1", "on-primary": "#ffffff",
    }),
    "colors-6-brass": ("Brass", {
        "base": "#ffffff", "surface": "#f8f7f3", "contrast": "#222019", "muted": "#555249",
        "primary": "#80621a", "primary-deep": "#634b12", "accent": "#c9a24a",
        "dark": "#12110d", "divider": "#e8e4d9",
        "overlay": "#ffffff", "on-dark": "#c7c2b4", "on-primary": "#ffffff",
    }),
    # Dark palettes: `base` is the page, so it is dark here. A button is
    # bright-on-dark, which means its label is the DARK colour -- the opposite of
    # the usual rule, and the reason the audit measures instead of assuming.
    "colors-7-midnight": ("Midnight", {
        "base": "#0d131c", "surface": "#141c28", "contrast": "#eef2f7", "muted": "#a7b1bf",
        "primary": "#6cc2ff", "primary-deep": "#a3d8ff", "accent": "#009dff",
        "dark": "#05080c", "divider": "#243042",
        "overlay": "#ffffff", "on-dark": "#b3bcc8", "on-primary": "#0d131c",
    }),
    "colors-8-lodge": ("Lodge", {
        "base": "#17120e", "surface": "#211a14", "contrast": "#f3ece4", "muted": "#b7a99b",
        "primary": "#e8a86a", "primary-deep": "#f3c79a", "accent": "#c8763a",
        "dark": "#0b0806", "divider": "#352a20",
        "overlay": "#ffffff", "on-dark": "#c9bcae", "on-primary": "#17120e",
    }),
}
DEFAULT_COLORS = "colors-1-montana"

# Typography. Raleway is the template's only face. Playfair Display is the
# serif a hotel reaches for (menus, a letterpress card on the pillow), and the
# system stack is the zero-download option. Two families, five pairings.
TYPE_SETS = {
    "type-1-raleway": ("Raleway throughout", "raleway", "raleway", None),
    "type-2-playfair-raleway": ("Playfair Display headings, Raleway text", "playfair", "raleway", None),
    "type-3-raleway-system": ("Raleway headings, system text", "raleway", "system", "400"),
    "type-4-playfair-system": ("Playfair Display headings, system text", "playfair", "system", "400"),
    "type-5-system": ("System fonts", "system", "system", "400"),
}

LATIN = ("U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, "
         "U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD")
LATIN_EXT = ("U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, "
             "U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, "
             "U+2C60-2C7F, U+A720-A7FF")

# name, CSS stack, [(weight, file stem)] — each stem ships as -latin and -latin-ext.
FAMILIES = collections.OrderedDict([
    ("raleway", ("Raleway", "Raleway, system-ui, -apple-system, 'Segoe UI', sans-serif",
                 [("300 700", "raleway-%s-wght-normal")])),
    ("playfair", ("Playfair Display", "'Playfair Display', Georgia, 'Times New Roman', serif",
                  [("400 900", "playfair-display-%s-wght-normal")])),
    ("system", ("System", "system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif", [])),
])


def fluid(minimum, maximum):
    return collections.OrderedDict([("min", minimum), ("max", maximum)])


# The template's scale, restated: 14px eyebrows, prices and buttons, 16px copy,
# 22px card and footer titles, 30px room names and the reservation line, 46px
# section titles, 60px page banners and 100px on the hero. Only the big three
# are fluid, stepping down to the template's own phone sizes (30px, 30px, 33px).
FONT_SIZES = [
    ("X Small",  "x-small",  "0.75rem",   None),
    ("Small",    "small",    "0.875rem",  None),
    ("Medium",   "medium",   "1rem",      None),
    ("Large",    "large",    "1.375rem",  None),
    ("X Large",  "x-large",  "1.875rem",  fluid("1.25rem", "1.875rem")),
    ("Heading",  "heading",  "2.875rem",  fluid("1.875rem", "2.875rem")),
    ("Display",  "display",  "3.75rem",   fluid("1.875rem", "3.75rem")),
    ("Colossal", "colossal", "6.25rem",   fluid("2.0625rem", "6.25rem")),
]

# The template breathes: 100px under a section title, 100px between most
# sections and 200px above the first one after the hero. On a phone all of it
# comes down to 40px, as the template's own media queries do.
SPACING = [
    ("20", "0.5rem"),
    ("30", "1rem"),
    ("40", "1.5rem"),
    ("50", "clamp(1.75rem, 3vw, 2.5rem)"),
    ("60", "clamp(2.5rem, 5vw, 4rem)"),
    ("70", "clamp(2.5rem, 7vw, 6.25rem)"),
    ("80", "clamp(2.5rem, 11vw, 12.5rem)"),
]

# Every foreground/background pair the design actually puts together, at the
# ratio it needs: 4.5 for text, 3 for large numerals and graphics.
CONTRAST_CHECKS = [
    ("contrast", "base", 4.5), ("contrast", "surface", 4.5),
    ("muted", "base", 4.5), ("muted", "surface", 4.5),
    ("primary", "base", 4.5), ("primary", "surface", 4.5),
    ("primary-deep", "base", 4.5),
    # The footer and the scrolled header: headings, copy and links on `dark`.
    ("overlay", "dark", 4.5),
    ("on-dark", "dark", 4.5),
    ("accent", "dark", 4.5),
    # Every button label on every fill it can have: resting and hovered.
    ("on-primary", "primary", 4.5),
    ("on-primary", "primary-deep", 4.5),
    # A filled button against the page: WCAG 1.4.11.
    ("primary", "base", 3.0),
]


# ---------------------------------------------------------------------------
# Contrast
# ---------------------------------------------------------------------------
def _channel(value):
    value = value / 255
    return value / 12.92 if value <= 0.04045 else ((value + 0.055) / 1.055) ** 2.4


def luminance(hex_colour):
    r, g, b = (int(hex_colour[i:i + 2], 16) for i in (1, 3, 5))
    return 0.2126 * _channel(r) + 0.7152 * _channel(g) + 0.0722 * _channel(b)


def contrast_ratio(a, b):
    la, lb = luminance(a), luminance(b)
    lighter, darker = max(la, lb), min(la, lb)
    return (lighter + 0.05) / (darker + 0.05)


# ---------------------------------------------------------------------------
# theme.json
# ---------------------------------------------------------------------------
def od(*pairs):
    return collections.OrderedDict(pairs)


def var(slug):
    return "var(--wp--preset--color--%s)" % slug


def fs(slug):
    return "var(--wp--preset--font-size--%s)" % slug


def ff(slug):
    return "var(--wp--preset--font-family--%s)" % slug


def sp(slug):
    valid = {s for s, _ in SPACING}
    if slug not in valid:
        raise SystemExit("spacing %r is not on the scale %s" % (slug, sorted(valid)))
    return "var(--wp--preset--spacing--%s)" % slug


def palette(colors):
    missing = [slug for _, slug, _ in PALETTE if slug not in colors]
    if missing:
        raise SystemExit("palette is missing %s" % ", ".join(missing))
    return [od(("name", name), ("slug", slug), ("color", colors[slug])) for name, slug, _ in PALETTE]


def font_families():
    out = []
    for key, (name, stack, faces) in FAMILIES.items():
        entry = od(("name", name), ("slug", key), ("fontFamily", stack))
        if faces:
            entry["fontFace"] = []
            for weight, stem in faces:
                for subset, rng in (("latin", LATIN), ("latin-ext", LATIN_EXT)):
                    entry["fontFace"].append(od(
                        ("fontFamily", name), ("fontStyle", "normal"), ("fontWeight", weight),
                        ("fontDisplay", "swap"),
                        ("src", ["file:./assets/fonts/%s.woff2" % (stem % subset)]),
                        ("unicodeRange", rng),
                    ))
        out.append(entry)
    return out


def font_files():
    files = []
    for _, (_, _, faces) in FAMILIES.items():
        for _, stem in faces:
            files += ["%s.woff2" % (stem % s) for s in ("latin", "latin-ext")]
    return files


GRADIENTS = [
    # The wash over a featured room's photograph: the template's
    # linear-gradient(to bottom, #fff 0%, #000 77%) at half opacity, which
    # leaves the top of the picture bright and puts the room's name and price on
    # a dark foot. Neutral on purpose: it is the same under every palette.
    ("Room shade", "room-shade", "linear-gradient(180deg, rgb(255, 255, 255) 0%, rgb(0, 0, 0) 77%)"),
]


def build_settings():
    return od(
        ("appearanceTools", True),
        ("useRootPaddingAwareAlignments", True),
        # The template is a Bootstrap 4 layout: a 1140px container with 15px
        # gutters, so 1110px of content. Wide is the blog's article + sidebar.
        ("layout", od(("contentSize", "1110px"), ("wideSize", "1290px"))),
        ("color", od(("custom", True), ("defaultPalette", False), ("defaultGradients", False),
                     ("defaultDuotone", False),
                     ("palette", palette(COLOR_SETS[DEFAULT_COLORS][1])),
                     ("gradients", [od(("name", n), ("slug", s), ("gradient", g)) for n, s, g in GRADIENTS]))),
        ("typography", od(
            ("fluid", True), ("customFontSize", True), ("defaultFontSizes", False),
            ("fontFamilies", font_families()),
            # A size with no fluid range is fixed: left to WordPress's default
            # fluid rule, 15px body copy shrinks to 14px on a phone and the 18px
            # site title to 14px.
            ("fontSizes", [od(("name", name), ("slug", slug), ("size", size), ("fluid", f if f else False))
                           for name, slug, size, f in FONT_SIZES]),
        )),
        ("spacing", od(("units", ["px", "em", "rem", "vh", "vw", "%"]),
                       ("padding", True), ("margin", True), ("blockGap", True),
                       ("defaultSpacingSizes", False),
                       ("spacingSizes", [od(("name", name), ("slug", name), ("size", size))
                                         for name, size in SPACING]))),
        ("border", od(("color", True), ("radius", True), ("style", True), ("width", True))),
        ("shadow", od(("defaultPresets", False), ("presets", [
            # The template's own card hover, a long soft shadow down and left.
            # The scrolled header's shadow, as the template's sticky bar.
            od(("name", "Header"), ("slug", "header"), ("shadow", "0 3px 16px 0 rgba(0, 0, 0, 0.1)")),
            od(("name", "Card"), ("slug", "card"), ("shadow", "0 10px 30px 0 rgba(0, 0, 0, 0.08)")),
        ]))),
    )


def build_styles():
    return od(
        ("color", od(("background", var("base")), ("text", var("muted")))),
        # The template's copy: Raleway 16px at weight 300 on a 28px line.
        ("typography", od(("fontFamily", ff("raleway")), ("fontSize", fs("medium")),
                          ("fontWeight", "300"), ("lineHeight", "1.75"))),
        ("spacing", od(("blockGap", sp("40")),
                       ("padding", od(("top", "0px"), ("bottom", "0px"),
                                      ("left", sp("40")), ("right", sp("40")))))),
        ("elements", od(
            # Regular weight, not bold: the template's headings are all 400.
            ("heading", od(("typography", od(("fontFamily", ff("raleway")), ("fontWeight", "400"),
                                             ("lineHeight", "1.22"))),
                           ("color", od(("text", var("contrast")))))),
            ("h1", od(("typography", od(("fontSize", fs("display")))))),
            ("h2", od(("typography", od(("fontSize", fs("heading")))))),
            ("h3", od(("typography", od(("fontSize", fs("large")))))),
            ("h4", od(("typography", od(("fontSize", fs("large")))))),
            ("h5", od(("typography", od(("fontSize", "1.125rem"))))),
            ("h6", od(("typography", od(("fontSize", fs("medium")))))),
            ("link", od(("color", od(("text", var("primary")))),
                        (":hover", od(("color", od(("text", var("primary-deep")))))))),
            ("button", od(
                # The template's "Book A Room": a square blue fill, 14px, regular
                # weight. The label is `on-primary`, which the audit holds against
                # the fill and the hover fill in every palette and in dark mode.
                ("color", od(("background", var("primary")), ("text", var("on-primary")))),
                ("typography", od(("fontFamily", "inherit"), ("fontWeight", "500"),
                                  ("fontSize", fs("small")), ("lineHeight", "1.5"))),
                ("border", od(("radius", "0px"), ("width", "1px"), ("style", "solid"),
                              ("color", "transparent"))),
                ("spacing", od(("padding", od(("top", "0.75rem"), ("bottom", "0.75rem"),
                                              ("left", "1.625rem"), ("right", "1.625rem"))))),
                (":hover", od(("color", od(("background", var("primary-deep")), ("text", var("on-primary")))))),
            )),
            ("caption", od(("typography", od(("fontSize", fs("small")))))),
        )),
        ("blocks", od(
            ("core/separator", od(("color", od(("text", var("divider")))))),
            ("core/site-title", od(("typography", od(("fontWeight", "400"), ("fontSize", "2.5rem"),
                                                     ("lineHeight", "1"))),
                                   ("elements", od(("link", od(("color", od(("text", var("overlay")))),
                                                               ("typography", od(("textDecoration", "none"))))))))),
            ("core/navigation", od(("typography", od(("fontSize", fs("medium")), ("fontWeight", "600"))))),
            ("core/post-title", od(("elements", od(("link", od(("color", od(("text", var("contrast")))),
                                                               ("typography", od(("textDecoration", "none"))))))))),
            ("core/quote", od(("border", od(("left", od(("color", var("accent")), ("style", "solid"),
                                                        ("width", "2px"))))),
                              ("color", od(("background", var("surface")))),
                              ("spacing", od(("padding", od(("top", sp("50")), ("bottom", sp("50")),
                                                            ("left", sp("50")), ("right", sp("50")))))),
                              ("typography", od(("fontStyle", "normal")))),
             ),
            ("core/pullquote", od(("border", od(("top", od(("color", var("accent")), ("style", "solid"), ("width", "2px"))),
                                                ("bottom", od(("color", var("accent")), ("style", "solid"), ("width", "2px"))))))),
        )),
    )


def build_theme():
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3),
        ("settings", build_settings()),
        ("styles", build_styles()),
        ("customTemplates", [
            od(("name", "page-no-title"), ("title", "Page without banner"), ("postTypes", ["page"])),
            od(("name", "page-with-sidebar"), ("title", "Page with sidebar"), ("postTypes", ["page"])),
            od(("name", "single-no-sidebar"), ("title", "Post without sidebar"), ("postTypes", ["post"])),
        ]),
        ("templateParts", [
            od(("name", "header"), ("title", "Header"), ("area", "header")),
            od(("name", "footer"), ("title", "Footer"), ("area", "footer")),
            od(("name", "sidebar"), ("title", "Sidebar"), ("area", "uncategorized")),
        ]),
    )


def build_color_variation(slug, name, colors):
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("settings", od(("color", od(("palette", palette(colors)))))),
    )


def build_type_variation(slug, name, heading, body, weight):
    typography = od(("fontFamily", ff(body)))
    if weight:
        # A light weight is Poppins' character; in a system face it just reads thin.
        typography["fontWeight"] = weight
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("styles", od(
            ("typography", typography),
            ("elements", od(
                ("heading", od(("typography", od(("fontFamily", ff(heading)))))),
                ("button", od(("typography", od(("fontFamily", ff(body)))))),
            )),
            ("blocks", od(("core/site-title", od(("typography", od(("fontFamily", ff(heading)))))))),
        )),
    )


def write(path, data):
    os.makedirs(os.path.dirname(path) or ".", exist_ok=True)
    with open(path, "w", encoding="utf-8") as handle:
        json.dump(data, handle, indent="\t", ensure_ascii=False)
        handle.write("\n")
    return path


def _mix_with_white(hex_colour, percent):
    """CSS `color-mix(in srgb, <colour> <percent>%, white)`, per channel."""
    channels = [int(hex_colour[i:i + 2], 16) for i in (1, 3, 5)]
    share = percent / 100
    return "#" + "".join("%02x" % round(c * share + 255 * (1 - share)) for c in channels)


def _dark_scheme():
    """What assets/css/scheme.css does to the palette, read from the file itself.

    Read rather than restated: the percentages live in the CSS, and a second
    copy here would drift from it. It also refuses any colour slug the palette
    does not define — a colour-mix on an undefined variable makes the whole
    declaration invalid, `primary` stops resolving and every button renders as
    bare text, while every text check still passes.
    """
    css = open("assets/css/scheme.css", encoding="utf-8").read()
    defined = {slug for _, slug, _ in PALETTE}
    referenced = set(re.findall(r"var\(--wp--preset--color--([a-z0-9-]+)\)", css))
    unknown = sorted(referenced - defined)
    if unknown:
        raise SystemExit("scheme.css reads colour slugs the palette does not define: %s"
                         % ", ".join(unknown))

    def mix(slug):
        m = re.search(r"--wp--preset--color--%s:\s*color-mix\(in srgb,\s*"
                      r"var\(--wp--preset--color--([a-z0-9-]+)\)\s*(\d+)%%,\s*white\)"
                      % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s` mix" % slug)
        return m.group(1), int(m.group(2))

    def fixed(slug):
        m = re.search(r"--wp--preset--color--%s:\s*(#[0-9a-fA-F]{6})\s*;" % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s`" % slug)
        return m.group(1).lower()

    return {
        "primary": mix("primary"), "primary-deep": mix("primary-deep"),
        "on-primary": fixed("on-primary"), "base": fixed("base"), "surface": fixed("surface"),
        "contrast": fixed("contrast"), "muted": fixed("muted"),
    }


def audit():
    problems = []

    print("  light      label/primary  /deep  fill/page")
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        for fg, bg, need in CONTRAST_CHECKS:
            ratio = contrast_ratio(colors[fg], colors[bg])
            if ratio < need:
                problems.append("%s: %s on %s is %.2f, needs %.1f" % (name, fg, bg, ratio, need))
        print("  %-10s %13.2f  %5.2f  %9.2f" % (
            name, contrast_ratio(colors["on-primary"], colors["primary"]),
            contrast_ratio(colors["on-primary"], colors["primary-deep"]),
            contrast_ratio(colors["primary"], colors["base"])))

    dark = _dark_scheme()
    print("\n  dark mode, as scheme.css applies it: primary = %s %d%%, deep = %s %d%% (+ white)"
          % (dark["primary"] + dark["primary-deep"]))
    print("  dark       text/base  text/surf  link-hover  label  label-hover  boundary")
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        fill = _mix_with_white(colors[dark["primary"][0]], dark["primary"][1])
        deep = _mix_with_white(colors[dark["primary-deep"][0]], dark["primary-deep"][1])
        measured = (
            ("primary text on base", contrast_ratio(fill, dark["base"]), 4.5),
            ("primary text on surface", contrast_ratio(fill, dark["surface"]), 4.5),
            ("hovered link on base", contrast_ratio(deep, dark["base"]), 4.5),
            ("button label on its fill", contrast_ratio(dark["on-primary"], fill), 4.5),
            ("button label on the hover fill", contrast_ratio(dark["on-primary"], deep), 4.5),
            # WCAG 1.4.11. A button has to be visible as a button, not merely
            # carry a readable label.
            ("button fill against the page",
             min(contrast_ratio(fill, dark["base"]), contrast_ratio(fill, dark["surface"])), 3.0),
        )
        for label, value, need in measured:
            if value < need:
                problems.append("%s (dark): %s is %.2f, needs %.1f" % (name, label, value, need))
        print("  %-10s %9.2f  %9.2f  %10.2f  %5.2f  %11.2f  %8.2f"
              % ((name,) + tuple(v for _, v, _ in measured)))
    for fg, bg in (("contrast", "base"), ("contrast", "surface"), ("muted", "base"), ("muted", "surface"),
                   ("base", "contrast")):
        ratio = contrast_ratio(dark[fg], dark[bg])
        if ratio < 4.5:
            problems.append("dark: %s on %s is %.2f" % (fg, bg, ratio))

    print("\n  for the record: the template's #009dff is %.2f:1 on white and its #919191 grey %.2f:1 --"
          % (contrast_ratio("#009dff", "#ffffff"), contrast_ratio("#919191", "#ffffff")))
    print("  the blue ships as `accent`, for decoration, never as text on white or a label.")
    if problems:
        raise SystemExit("\nContrast failures:\n  " + "\n  ".join(problems))


def check_fonts():
    missing = [f for f in font_files() if not os.path.exists(os.path.join("assets/fonts", f))]
    if missing:
        print("\n  warning: fonts not downloaded yet (node .dev/build-fonts.mjs): %s" % ", ".join(missing))


def main():
    audit()
    written = [write("theme.json", build_theme())]
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        written.append(write("styles/colors/%s.json" % slug, build_color_variation(slug, name, colors)))
    for slug, (name, heading, body, weight) in sorted(TYPE_SETS.items()):
        written.append(write("styles/typography/%s.json" % slug,
                             build_type_variation(slug, name, heading, body, weight)))
    check_fonts()
    print("\n  %d files written" % len(written))
    for path in written:
        print("    " + path)


if __name__ == "__main__":
    main()
