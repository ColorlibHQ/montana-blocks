#!/usr/bin/env python3
"""Generate templates/*.html and parts/*.html.

Templates are small, but every one of them repeats the same header, footer,
main wrapper and sidebar columns, and a hand-edited copy is where a stray
attribute or a forgotten class creeps in. validate-blocks.mjs parses the output.

    python3 .dev/build_templates.py
"""

import os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

HEADER = '<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->\n\n'
FOOTER = '\n\n<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->\n'


def pat(slug, indent="\t\t\t"):
    return indent + '<!-- wp:pattern {"slug":"montana/%s"} /-->' % slug


def main_padded(inner):
    # 70 top and bottom: the template's 130px blog area at desktop, fluid down.
    return (
        '<!-- wp:group {"tagName":"main","className":"montana-main","style":{"spacing":{"padding":'
        '{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->\n'
        '<main class="wp-block-group montana-main" style="padding-top:var(--wp--preset--spacing--70);'
        'padding-bottom:var(--wp--preset--spacing--70)">\n%s\n</main>\n<!-- /wp:group -->' % inner
    )


def main_flush(inner):
    return (
        '<!-- wp:group {"tagName":"main","className":"montana-main","layout":{"type":"constrained"}} -->\n'
        '<main class="wp-block-group montana-main">\n%s\n</main>\n<!-- /wp:group -->' % inner
    )


def with_sidebar(content):
    """Content two-thirds, sidebar one third: the template's 750 + 30 + 360."""
    return (
        '\t<!-- wp:columns {"className":"montana-with-sidebar","style":{"spacing":{"blockGap":'
        '{"top":"var:preset|spacing|60","left":"var:preset|spacing|40"}}}} -->\n'
        '\t<div class="wp-block-columns montana-with-sidebar">\n'
        '\t\t<!-- wp:column {"width":"66.66%"} -->\n'
        '\t\t<div class="wp-block-column" style="flex-basis:66.66%">\n' + content + '\n\t\t</div>\n'
        '\t\t<!-- /wp:column -->\n'
        '\t\t<!-- wp:column {"width":"33.33%"} -->\n'
        '\t\t<div class="wp-block-column" style="flex-basis:33.33%">\n'
        '\t\t\t<!-- wp:template-part {"slug":"sidebar"} /-->\n'
        '\t\t</div>\n'
        '\t\t<!-- /wp:column -->\n'
        '\t</div>\n'
        '\t<!-- /wp:columns -->'
    )


PAGE_CONTENT = ('\t<!-- wp:post-content {"align":"full","className":"montana-page-content",'
                '"layout":{"type":"constrained"}} /-->')


def single_body(indent):
    lines = [
        '<!-- wp:post-featured-image {"aspectRatio":"2/1"} /-->',
        '<!-- wp:pattern {"slug":"montana/hidden-post-meta"} /-->',
        '<!-- wp:post-content {"className":"montana-entry","layout":{"type":"constrained"}} /-->',
        '<!-- wp:post-terms {"term":"post_tag","className":"montana-tags","fontSize":"small"} /-->',
        '<!-- wp:group {"className":"montana-post-nav","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->',
        '<div class="wp-block-group montana-post-nav">',
        '\t<!-- wp:post-navigation-link {"type":"previous","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->',
        '\t<!-- wp:post-navigation-link {"showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->',
        '</div>',
        '<!-- /wp:group -->',
        '<!-- wp:pattern {"slug":"montana/hidden-author"} /-->',
        '<!-- wp:pattern {"slug":"montana/hidden-comments"} /-->',
    ]
    return "\n".join(indent + line for line in lines)


def templates():
    listing = with_sidebar(pat("hidden-posts-list"))
    out = {
        "index": HEADER + pat("hidden-blog-banner", "") + "\n\n" + main_padded(listing) + FOOTER,
        "home": HEADER + pat("hidden-blog-banner", "") + "\n\n" + main_padded(listing) + FOOTER,
        "archive": HEADER + pat("hidden-archive-banner", "") + "\n\n" + main_padded(listing) + FOOTER,
        "search": HEADER + pat("hidden-search-banner", "") + "\n\n" + main_padded(listing) + FOOTER,
        "404": HEADER + pat("hidden-404-banner", "") + "\n\n" + main_flush(pat("hidden-404", "\t")) + FOOTER,
        "page": HEADER + pat("hidden-page-banner", "") + "\n\n" + main_flush(PAGE_CONTENT) + FOOTER,
        "page-no-title": HEADER + main_flush(PAGE_CONTENT) + FOOTER,
        "page-with-sidebar": HEADER + pat("hidden-page-banner", "") + "\n\n" + main_padded(with_sidebar(
            '\t\t\t<!-- wp:post-content {"layout":{"type":"constrained"}} /-->')) + FOOTER,
        "single": HEADER + pat("hidden-post-banner", "") + "\n\n" + main_padded(with_sidebar(single_body("\t\t\t"))) + FOOTER,
        "single-no-sidebar": HEADER + pat("hidden-post-banner", "") + "\n\n" + main_padded(
            '\t<!-- wp:group {"layout":{"type":"constrained","contentSize":"780px"}} -->\n'
            '\t<div class="wp-block-group">\n' + single_body("\t\t") + '\n\t</div>\n\t<!-- /wp:group -->') + FOOTER,
    }
    return out


def main():
    os.makedirs("templates", exist_ok=True)
    os.makedirs("parts", exist_ok=True)
    for name in os.listdir("templates"):
        os.remove(os.path.join("templates", name))
    for name, body in templates().items():
        with open("templates/%s.html" % name, "w") as fh:
            fh.write(body)
        print("  templates/%s.html" % name)
    # Each part is one pattern reference, so the words inside it live in a PHP
    # file and can be translated.
    for part in ("header", "footer", "sidebar"):
        with open("parts/%s.html" % part, "w") as fh:
            fh.write('<!-- wp:pattern {"slug":"montana/%s"} /-->\n' % part)
        print("  parts/%s.html" % part)


if __name__ == "__main__":
    main()
