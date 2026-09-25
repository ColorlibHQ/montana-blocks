/**
 * Measure text contrast on a real rendered page, under every colour palette.
 *
 * theme.json's own audit checks the palette's numbers. It cannot catch a
 * pattern that puts the wrong slug on the wrong ground — which is how every
 * cover headline and the whole footer came out black-on-black in the two dark
 * palettes: they asked for `base`, and `base` is the page background, so in a
 * dark palette it is nearly black.
 *
 * This walks the rendered DOM, resolves each text node's effective background
 * by climbing its ancestors, and reports anything under 4.5:1. It is the check
 * that would have caught that bug, so it runs over all eight palettes.
 *
 * Text on a photograph is measured too, by its pixels (photo-ground.mjs). This
 * check used to skip it: climbing ancestors from a cover headline walks past
 * the photograph and its dimming layer, which are siblings, and finds the page.
 * Skipping meant the hero, the page banners, the statistics and the "Why us"
 * section were never measured at all — and the primary-coloured eyebrow on the
 * darkened "Why us" photograph shipped at under 3:1 without a single warning.
 * An element taken out of flow (absolute or fixed) is measured the same way,
 * since what is behind it is not in its ancestry either.
 *
 * Text inside a scroll strip that sits outside the strip's visible box (the
 * reviews slider's other slides) is not on screen and is skipped: photographed
 * where it sits, it would be measured against whatever happens to be beside
 * the strip.
 *
 *   WP_URL=... MONTANA_PATHS=/,/about/ node .dev/contrast-rendered.mjs
 *   MONTANA_DARK=1 WP_URL=... node .dev/contrast-rendered.mjs
 *
 * @package Montana
 */

import { chromium } from 'playwright';
import { contrast, paletteCss, parseColor, sampleBehind, worstTenth } from './photo-ground.mjs';

const site = ( process.env.WP_URL || 'http://127.0.0.1:9492' ).replace( /\/$/, '' );
const paths = ( process.env.MONTANA_PATHS || process.env.MONTANA_URL || '/,/rooms/,/about/,/contact/,/blog/,/five-walks-from-the-front-door/,/category/outdoors/,/?s=lake,/no-such-page/' ).split( ',' ).filter( Boolean );

const browser = await chromium.launch();
// Reduced motion, so no scroll reveal is half-way through its fade while the
// pixels behind it are photographed. Scale 1, so a screenshot pixel is a CSS pixel.
const page = await ( await browser.newContext( {
	viewport: { width: 1400, height: 1000 },
	deviceScaleFactor: 1,
	reducedMotion: 'reduce',
} ) ).newPage();

let failedPages = 0;
let measuredTotal = 0;

for ( const path of paths ) {
// `domcontentloaded` plus a settle, not `networkidle`: a production site with
// analytics or a chat widget may never reach network idle at all, and the
// check then fails as a timeout rather than a contrast result.
await page.goto( site + path, { waitUntil: 'domcontentloaded', timeout: 90000 } );
await page.waitForTimeout( 2500 );
if ( process.env.MONTANA_PALETTE ) {
	await page.addStyleTag( { content: await paletteCss( process.env.MONTANA_PALETTE ) } );
	await page.waitForTimeout( 200 );
}

// A featured room's "book now" is hidden until the room is hovered: shown here
// in its hovered state, or it would never be measured. The script's header
// state and the reveal are settled too.
await page.addStyleTag( { content: '.montana-room__link{opacity:1!important;transform:none!important;transition:none!important}' } );

// MONTANA_SLIDE=2 puts each slider's second slide first, where it is on
// screen: the other slides sit outside the strip and are skipped otherwise.
if ( '2' === process.env.MONTANA_SLIDE ) {
	await page.addStyleTag( { content: '.montana-carousel>.montana-slide:first-child{order:2}' } );
	await page.evaluate( () => document.querySelectorAll( '.montana-carousel' ).forEach( ( c ) => c.scrollTo( 0, 0 ) ) );
	await page.waitForTimeout( 300 );
}

// MONTANA_DARK=1 measures the same page with dark mode on, which is where the
// palette is lifted rather than replaced and is the likeliest place for a
// pairing to fall under AA.
if ( process.env.MONTANA_DARK ) {
	await page.evaluate( () => {
		document.documentElement.classList.add( 'montana-dark' );
	} );
	await page.waitForTimeout( 400 );
}

const candidates = await page.evaluate( () => {
	// rgb()/rgba(), or color(srgb r g b / a), which color-mix() produces.
	const alphaOf = ( value ) => {
		if ( ! value || 'transparent' === value ) {
			return 0;
		}
		const m = value.match( /\/\s*([\d.]+)\s*\)$/ ) || value.match( /^rgba\([^,]+,[^,]+,[^,]+,\s*([\d.]+)\s*\)$/ );
		return m ? Number( m[ 1 ] ) : 1;
	};

	const out = [];

	document.querySelectorAll( 'h1,h2,h3,h4,h5,h6,p,li,a,dt,dd,summary,label,button,span' ).forEach( ( el, i ) => {
		const own = [ ...el.childNodes ].some( ( n ) => 3 === n.nodeType && n.textContent.trim() );
		if ( ! own ) {
			return;
		}

		// Too small to be read, or parked off the side of the page on purpose, as
		// the booking form's honeypot is: nobody sees either.
		const box = el.getBoundingClientRect();
		if ( box.width < 6 || box.height < 6 || box.right <= 0 || box.left >= document.documentElement.clientWidth ) {
			return;
		}

		const style = getComputedStyle( el );
		if ( 'hidden' === style.visibility || '0' === style.opacity || 'none' === style.display ) {
			return;
		}

		// Scrolled out of a clipping ancestor (a slider's other slides): not on screen.
		for ( let node = el.parentElement; node && node !== document.body; node = node.parentElement ) {
			const ns = getComputedStyle( node );
			if ( 'visible' !== ns.overflowX || 'visible' !== ns.overflowY ) {
				const nb = node.getBoundingClientRect();
				if ( box.right <= nb.left + 1 || box.left >= nb.right - 1 || box.bottom <= nb.top + 1 || box.top >= nb.bottom - 1 ) {
					return;
				}
			}
		}

		// Climb to whichever comes first: an opaque background colour, which is
		// the ground; or a photograph, or an element out of flow, where the
		// ground is pixels. A translucent background — a tinted panel laid on a
		// photograph — is not a ground on its own, so the climb carries on past it.
		let ground = null;
		let pixels = false;
		for ( let node = el; node; node = node.parentElement ) {
			const s = getComputedStyle( node );
			if ( node.classList.contains( 'wp-block-cover' ) || ( s.backgroundImage && 'none' !== s.backgroundImage ) ) {
				pixels = true;
				break;
			}
			const alpha = alphaOf( s.backgroundColor );
			if ( alpha >= 0.99 ) {
				ground = s.backgroundColor;
				break;
			}
			if ( alpha > 0 || 'absolute' === s.position || 'fixed' === s.position ) {
				pixels = true;
				break;
			}
		}

		// WCAG's large text — 24px, or 18.66px at bold — needs 3:1, not 4.5:1.
		// The pricing numerals (36px, light, on the brand gradient) are the
		// case: the template's own treatment, and AA at 3.6:1.
		const px = parseFloat( style.fontSize );
		const large = px >= 24 || ( px >= 18.66 && Number( style.fontWeight ) >= 700 );

		el.setAttribute( 'data-mt-cr', String( i ) );
		out.push( {
			i,
			need: large ? 3 : 4.5,
			text: el.textContent.trim().replace( /\s+/g, ' ' ).slice( 0, 40 ),
			colour: style.color,
			// Nothing painted anywhere up the tree is the browser's white canvas.
			ground: pixels ? null : ( ground || 'rgb(255, 255, 255)' ),
		} );
	} );

	return out;
} );

const findings = [];
let onPhotos = 0;

for ( const c of candidates ) {
	const colour = parseColor( c.colour );
	if ( ! colour ) {
		continue;
	}

	if ( null !== c.ground ) {
		const ground = parseColor( c.ground );
		const r = ground ? contrast( colour, ground ) : null;
		if ( null !== r && r < c.need ) {
			findings.push( { ratio: r, text: c.text, colour: c.colour, background: c.ground } );
		}
		continue;
	}

	onPhotos++;
	const handle = await page.$( `[data-mt-cr="${ c.i }"]` );
	const sampled = handle ? await sampleBehind( page, handle, 0, { text: true } ) : null;
	const r = sampled ? worstTenth( colour, sampled.interior ) : null;
	if ( null === r ) {
		findings.push( { ratio: 0, text: c.text, colour: c.colour, background: 'a photograph it could not measure' } );
	} else if ( r < c.need ) {
		findings.push( { ratio: r, text: c.text, colour: c.colour, background: 'the photograph behind it (worst tenth of its pixels)' } );
	}
}

measuredTotal += candidates.length;
if ( findings.length ) {
	failedPages++;
	console.error( `${ findings.length } text nodes under AA on ${ path } (${ onPhotos } measured on photographs)` );
	for ( const f of findings.slice( 0, 12 ) ) {
		console.error( `  ${ f.ratio.toFixed( 2 ) }  "${ f.text }"  ${ f.colour } on ${ f.background }` );
	}
} else {
	console.log( `every text node on ${ path } meets AA — 4.5:1, or 3:1 for large text (${ candidates.length } measured, ${ onPhotos } on photographs)` );
}
}

await browser.close();

if ( failedPages ) {
	process.exit( 1 );
}
console.log( `\n${ paths.length } pages, ${ measuredTotal } text nodes, all at AA${ process.env.MONTANA_DARK ? ' (dark mode)' : '' }` );
