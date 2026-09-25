#!/usr/bin/env bash
#
# Every rendered check, over every page, palette and scheme, against a running
# Playground (see README.md). Prints one line per run and a total; exits 1 if
# anything failed.
#
#   WP_URL=http://127.0.0.1:9492 bash .dev/check-rendered.sh
#   PALETTES="colors-1-montana" bash .dev/check-rendered.sh     # a quicker pass
#   SKIP_LAYOUT=1 PALETTES="…" bash .dev/check-rendered.sh     # contrast and buttons only,
#                                                              # to split palettes across runs
#   ONLY_LAYOUT=1 bash .dev/check-rendered.sh                  # overflow, alignment, selectors
#
set -u

here="$( cd "$( dirname "$0" )/.." && pwd )"
cd "$here"

export WP_URL="${WP_URL:-http://127.0.0.1:9492}"
PAGES="${PAGES:-/ /rooms/ /about/ /blog/ /contact/ /five-walks-from-the-front-door/ /category/outdoors/ /?s=lake /no-such-page/}"
PALETTES="${PALETTES:-colors-1-montana colors-2-pine colors-3-glacier colors-4-sunset colors-5-heather colors-6-brass colors-7-midnight colors-8-lodge}"
failed=0
runs=0

note() {
	runs=$(( runs + 1 ))
	if [ "$1" -ne 0 ]; then
		failed=$(( failed + 1 ))
		echo "FAIL  $2"
		echo "$3" | sed 's/^/      /' | head -8
	else
		echo "ok    $2  $( echo "$3" | tail -1 )"
	fi
}

# ONLY_LAYOUT=1 skips the palette loop: overflow, alignment and dead selectors.
[ -n "${ONLY_LAYOUT:-}" ] && PALETTES=""

for palette in $PALETTES; do
	for scheme in light dark; do
		dark=""
		[ "$scheme" = "dark" ] && dark=1
		for page in $PAGES; do
			out=$( MONTANA_PALETTE="$palette" MONTANA_DARK="$dark" MONTANA_URL="$page" node .dev/contrast-rendered.mjs 2>&1 )
			note $? "contrast $palette $scheme $page" "$out"
		done
		# The sliders' second photographs (home hero, about page).
		out=$( MONTANA_SLIDE=2 MONTANA_PALETTE="$palette" MONTANA_DARK="$dark" MONTANA_PATHS="/,/about/" node .dev/contrast-rendered.mjs 2>&1 )
		note $? "contrast $palette $scheme second slides" "$out"
		paths=$( echo $PAGES | tr ' ' ',' )
		out=$( MONTANA_PALETTE="$palette" MONTANA_DARK="$( [ -n "$dark" ] && echo 1 || echo 0 )" MONTANA_PATHS="$paths" node .dev/button-boundary.mjs 2>&1 )
		note $? "buttons $palette $scheme" "$out"
	done
done

if [ -n "${SKIP_LAYOUT:-}" ]; then
	echo
	echo "$runs runs, $failed failed"
	[ "$failed" -eq 0 ]
	exit
fi

paths=$( echo $PAGES | tr ' ' ',' )
out=$( MONTANA_PATHS="$paths" node .dev/overflow-check.mjs 2>&1 )
note $? "overflow (1400, 1024, 768, 390)" "$( echo "$out" | grep -v ' clean$' ; echo "$out" | grep -c ' clean$' ) clean page-widths"
out=$( MONTANA_PATHS="$paths" node .dev/alignment-check.mjs 2>&1 )
note $? "alignment" "$out"
out=$( python3 .dev/dead-selectors.py 2>&1 )
note $? "dead selectors" "$out"

echo
echo "$runs runs, $failed failed"
[ "$failed" -eq 0 ]
