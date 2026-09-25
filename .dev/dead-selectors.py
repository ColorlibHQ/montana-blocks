#!/usr/bin/env python3
"""
Fail on any `montana-*` CSS selector that nothing in the theme emits.

A selector that matches nothing is not an error anywhere: the browser ignores
it, the build passes, Theme Check passes, and every rendered check passes too,
because the element it was meant to style still renders — just unstyled.

That is how a ported form ships broken: the stylesheet keeps the source
theme's word for the form while the PHP emits the new one, so the wrapper, the
grid, the notices and the honeypot's hiding rule all match nothing. The fields
still stack and look plausible, which is what hides it, and the honeypot renders
in full view — "Leave this field empty", above the form, for every visitor.

Class names built at runtime count as emitted. PHP such as
`'montana-field--' . esc_attr( $name )` or JavaScript such as
`'montana-' + name` registers its literal prefix, and any selector starting
with that prefix is treated as live. Without that, `.montana-field--message`
would be reported, although inc/booking.php builds it for the message field.

    python3 .dev/dead-selectors.py      # exits 1 and lists them, or 0
"""

import glob
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

CSS = sorted(glob.glob("assets/css/*.css"))
EMITTERS = sorted(
    glob.glob("inc/*.php") + glob.glob("functions.php") + glob.glob("patterns/*.php")
    + glob.glob("templates/*.html") + glob.glob("parts/*.html") + glob.glob("assets/js/*.js")
    + glob.glob("theme.json") + glob.glob("styles/**/*.json", recursive=True)
)

TOKEN = re.compile(r"montana-[a-z0-9_-]+")
SELECTOR = re.compile(r"\.(montana-[a-z0-9_-]+)")
# A runtime prefix is the montana- token that ENDS a string literal and is then
# concatenated with a variable. It must not be required to START the literal:
#
#   class="montana-field montana-field--' . esc_attr( $name ) . '"
#
# begins at `class="`, so the first version of this pattern — which wanted a
# quote directly before the prefix — missed `montana-field--` and reported the
# live `.montana-field--message` as dead. Its own first run caught that.
PHP_PREFIX = re.compile(r"""(montana-[a-z0-9_-]*)['"]\s*\.\s*(?:[a-z_]+\s*\(\s*)?\$""")
# 'montana-' + name   /   `montana-${name}`
JS_PREFIX = re.compile(r"""(montana-[a-z0-9_-]*)(?:['"]\s*\+|\$\{)""")


def strip_comments(css):
    return re.sub(r"/\*.*?\*/", "", css, flags=re.S)


REGISTERED = re.compile(r"""array\(\s*'(montana-[a-z0-9-]+)'\s*,\s*__\(""")
IS_STYLE = re.compile(r"\.(is-style-[a-z0-9-]+)")


def main():
    failed = False
    # style.css is the theme's main stylesheet and was missing from the first
    # version of this list, so nothing checked it at all.
    css_text = {path: strip_comments(open(path, encoding="utf-8").read()) for path in ["style.css"] + CSS}

    selectors = {}
    for path, text in css_text.items():
        for name in SELECTOR.findall(text):
            selectors.setdefault(name, set()).add(path)

    literal, prefixes = set(), set()
    for path in EMITTERS:
        # Block comment attributes are JSON, and the block serialiser writes
        # `--` as `\u002d\u002d`: a class that exists only in a self-closing
        # block's comment (a post-comments-count with an icon) reads as
        # `montana-icon\u002d\u002dmessage` until it is decoded.
        text = open(path, encoding="utf-8").read().replace("\\u002d", "-")
        literal.update(TOKEN.findall(text))
        prefixes.update(p for p in PHP_PREFIX.findall(text) + JS_PREFIX.findall(text) if p)

    # A bare `montana-` prefix (an id built as 'montana-' . $form) would make
    # every selector count as live and the whole check pass vacuously.
    prefixes = {p for p in prefixes if p.rstrip("-") != "montana"}

    def live(name):
        return name in literal or any(name.startswith(p) for p in prefixes)

    dead = sorted(name for name in selectors if not live(name))

    print("%d montana-* selectors, %d emitted literally, dynamic prefixes: %s"
          % (len(selectors), len(literal), ", ".join(sorted(prefixes)) or "none"))
    if dead:
        failed = True
        print("\n%d selector(s) match nothing the theme emits:" % len(dead))
        for name in dead:
            print("  .%-40s %s" % (name, ", ".join(sorted(selectors[name]))))

    # The other direction, for the one kind of class that is a promise: a block
    # style registered in functions.php appears in the editor's Styles panel by
    # name. If no stylesheet styles it, choosing it does nothing, and nothing
    # else in the toolchain notices.
    registered = sorted(set(REGISTERED.findall(open("functions.php", encoding="utf-8").read())))
    all_css = "\n".join(css_text.values())
    unstyled = [name for name in registered if (".is-style-" + name) not in all_css]
    print("%d registered block styles: %s" % (len(registered), ", ".join(registered) or "none"))
    if unstyled:
        failed = True
        print("\n%d registered block style(s) that no stylesheet styles:" % len(unstyled))
        for name in unstyled:
            print("  is-style-%s" % name)

    # And an is-style-* rule for a style nothing registers or emits is a rule
    # for another theme's vocabulary, carried over in a port.
    styled = {}
    for path, text in css_text.items():
        for cls in IS_STYLE.findall(text):
            styled.setdefault(cls, set()).add(path)
    emitted_is_style = set()
    for path in EMITTERS:
        emitted_is_style.update(re.findall(r"is-style-[a-z0-9-]+", open(path, encoding="utf-8").read()))
    core_styles = {"is-style-wide", "is-style-dots", "is-style-rounded", "is-style-outline", "is-style-fill",
                   "is-style-default", "is-style-stripes", "is-style-large", "is-style-plain", "is-style-logos-only",
                   "is-style-pill-shape", "is-style-squared"}
    orphan = sorted(c for c in styled
                    if c not in emitted_is_style and c[len("is-style-"):] not in registered and c not in core_styles)
    if orphan:
        failed = True
        print("\n%d is-style-* rule(s) for a style nothing registers or emits:" % len(orphan))
        for cls in orphan:
            print("  .%-40s %s" % (cls, ", ".join(sorted(styled[cls]))))

    if failed:
        sys.exit(1)
    print("every selector is live and every registered block style is styled")


if __name__ == "__main__":
    main()
