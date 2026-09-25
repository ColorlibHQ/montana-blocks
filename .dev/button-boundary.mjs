/**
 * Every button must look like a button: its fill (or border) against what is
 * behind it at 3:1, and its label at 4.5:1.
 *
 * That is deliberately stricter than WCAG. 1.4.11 (non-text contrast) does not
 * require a boundary on a button whose readable label already identifies it,
 * so strictly a transparent button with a legible label passes. This theme
 * holds itself to the stricter reading because the failure it exists to catch —
 * every button rendering as bare text — would pass the letter of the rule while
 * leaving a visitor with nothing that reads as a control.
 *
 * contrast-rendered.mjs cannot see this, because it measures TEXT. A button
 * whose fill has gone transparent still has a readable label, so every text
 * check passes while every button on the site has stopped looking like one.
 * That shipped in dark mode: scheme.css lifted `primary` with a colour-mix on a
 * slug the palette did not define, the variable went invalid, and every button
 * rendered as bare text.
 *
 * On a plain ground the check climbs ancestors to the first opaque background.
 * On a photograph — a cover, or any background image — it measures the pixels
 * instead (photo-ground.mjs), against the worst tenth of them. Climbing there
 * finds the page, not the photograph, and reported a white outline button on a
 * darkened hero as white-on-white.
 *
 *   WP_URL=https://colorlibhub.com/montana node .dev/button-boundary.mjs
 *   MONTANA_DARK=1 WP_URL=… node .dev/button-boundary.mjs
 *   MONTANA_PATHS=/,/about/ WP_URL=… node .dev/button-boundary.mjs
 *
 * Exits non-zero on any failure.
 */

import { chromium } from 'playwright';
import { contrast, paletteCss, parseColor, sampleBehind, worstTenth } from './photo-ground.mjs';

const base = ( process.env.WP_URL || 'http://127.0.0.1:9492' ).replace( /\/$/, '' );
const dark = process.env.MONTANA_DARK === '1';
const paths = ( process.env.MONTANA_PATHS || '/,/rooms/,/about/,/contact/,/blog/,/five-walks-from-the-front-door/,/category/outdoors/,/?s=lake,/no-such-page/' ).split( ',' );

const browser = await chromium.launch();
const failures = [];
let checked = 0;
let onPhotos = 0;

for ( const path of paths ) {
	// Reduced motion so nothing is mid-reveal while it is being measured.
	const context = await browser.newContext( {
		viewport: { width: 1440, height: 900 },
		deviceScaleFactor: 1,
		colorScheme: dark ? 'dark' : 'light',
		reducedMotion: 'reduce',
	} );
	const page = await context.newPage();
	await page.goto( base + path, { waitUntil: 'networkidle', timeout: 60000 } );
	await page.waitForTimeout( 500 );
if ( process.env.MONTANA_PALETTE ) {
	await page.addStyleTag( { content: await paletteCss( process.env.MONTANA_PALETTE ) } );
	await page.waitForTimeout( 200 );
}

	const buttons = await page.evaluate( () => {
		return [ ...document.querySelectorAll( '.wp-block-button__link, .wp-element-button' ) ]
			// The scheme toggle is an icon-only control by design; its boundary
			// is its 44px hit area, not a fill.
			.filter( ( el ) => el.offsetWidth > 0 && ! el.closest( '.montana-scheme-toggle' ) )
			.map( ( el, i ) => {
				el.setAttribute( 'data-mt-bb', String( i ) );
				const cs = getComputedStyle( el );
				let onPhoto = false;
				let surface = null;
				for ( let node = el.parentElement; node; node = node.parentElement ) {
					const s = getComputedStyle( node );
					if ( node.classList.contains( 'wp-block-cover' ) || ( s.backgroundImage && 'none' !== s.backgroundImage ) ) {
						onPhoto = true;
						break;
					}
					if ( ! surface && s.backgroundColor && 'rgba(0, 0, 0, 0)' !== s.backgroundColor && 'transparent' !== s.backgroundColor ) {
						surface = s.backgroundColor;
					}
				}
				// The words a sighted visitor sees, without screen-reader text. A
				// button with none is an icon button: its "label" is a graphic,
				// which WCAG 1.4.11 holds to 3:1 rather than 4.5:1.
				const clone = el.cloneNode( true );
				clone.querySelectorAll( '.screen-reader-text' ).forEach( ( n ) => n.remove() );
				const visible = ( 'INPUT' === el.tagName ? el.value : clone.textContent ).trim();
				return {
					i,
					text: ( el.innerText.trim() || el.value || '[' + String( el.className ).split( ' ' ).slice( 0, 2 ).join( '.' ) + ']' ).slice( 0, 40 ),
					iconOnly: '' === visible,
					// A gradient fill is the element's background-image; its colour
					// stops are the fill, and the worst of them is what counts.
					stops: ( cs.backgroundImage.match( /rgba?\([^)]+\)|color\(srgb[^)]+\)/g ) || [] ),
					fill: cs.backgroundColor,
					border: cs.borderTopColor,
					borderWidth: parseFloat( cs.borderTopWidth ) || 0,
					label: cs.color,
					onPhoto,
					surface: surface || 'rgb(255, 255, 255)',
				};
			} );
	} );

	for ( const b of buttons ) {
		checked++;
		const flat = parseColor( b.fill );
		const stops = b.stops.map( parseColor ).filter( ( c ) => c && c.a > 0.5 );
		const fills = stops.length ? stops : ( flat && flat.a > 0.5 ? [ flat ] : [] );
		const border = parseColor( b.border );
		const label = parseColor( b.label );
		const fillOpaque = fills.length > 0;
		const hasBorder = !! border && border.a > 0.5 && b.borderWidth > 0;
		const worst = ( fn ) => Math.min( ...fills.map( fn ) );
		const need = b.iconOnly ? 3 : 4.5;

		let boundary = 0;
		let labelRatio = 0;
		let where = '';

		if ( b.onPhoto ) {
			onPhotos++;
			const handle = await page.$( `[data-mt-bb="${ b.i }"]` );
			const ground = handle ? await sampleBehind( page, handle ) : null;
			if ( ! ground ) {
				failures.push( `${ path } "${ b.text }": could not be measured on its photograph` );
				continue;
			}
			boundary = Math.max( fillOpaque ? worst( ( f ) => worstTenth( f, ground.ring ) ) : 0, hasBorder ? worstTenth( border, ground.ring ) : 0 );
			labelRatio = fillOpaque ? worst( ( f ) => contrast( label, f ) ) : worstTenth( label, ground.interior );
			where = ' (on a photograph, worst tenth of its pixels)';
		} else {
			const surface = parseColor( b.surface );
			boundary = Math.max( fillOpaque ? worst( ( f ) => contrast( f, surface ) ) : 0, hasBorder ? contrast( border, surface ) : 0 );
			labelRatio = fillOpaque ? worst( ( f ) => contrast( label, f ) ) : contrast( label, surface );
		}

		const problems = [];
		if ( boundary < 3 ) {
			problems.push( `boundary ${ boundary.toFixed( 2 ) }:1${ fillOpaque || hasBorder ? '' : ' (no fill, no border)' }` );
		}
		if ( labelRatio < need ) {
			problems.push( `${ b.iconOnly ? 'icon' : 'label' } ${ labelRatio.toFixed( 2 ) }:1` );
		}
		if ( problems.length ) {
			failures.push( `${ path } "${ b.text }": ${ problems.join( ', ' ) }${ where }` );
		}
	}

	await context.close();
}

await browser.close();

const scheme = dark ? 'dark' : 'light';
if ( failures.length ) {
	console.error( `${ failures.length } of ${ checked } buttons fail in ${ scheme } mode (${ onPhotos } measured on photographs):` );
	failures.forEach( ( f ) => console.error( '  ' + f ) );
	process.exit( 1 );
}
console.log( `All ${ checked } buttons are visible as buttons in ${ scheme } mode (boundary >= 3:1, label >= 4.5:1; ${ onPhotos } measured on photographs).` );
