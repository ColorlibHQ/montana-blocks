/**
 * The colorlib.com product page's screenshots, from a Playground with the demo
 * content imported (.dev/blueprint-demo.json runs .dev/demo/import.php).
 *
 * Every section shot is one viewport (1440x1000, scale 1) scaled with Lanczos
 * to exactly 1140x792, progressive JPEG q82. The card is 1200x800 and the
 * listing card 1200x900, both the home hero alone. The palettes image is the
 * booking request section under each of the eight palettes, applied
 * client-side exactly as a style variation does (.dev/palette.mjs), in two
 * rows of four with 12px gutters and a label under each, 1140px wide.
 *
 *   WP_URL=http://127.0.0.1:9492 node .dev/publish/shoot.mjs [out-dir]
 *
 * @package Montana
 */

import { chromium } from 'playwright';
import sharp from 'sharp';
import { mkdirSync } from 'node:fs';
import { join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { applyPalette } from '../palette.mjs';

const base = ( process.env.WP_URL || 'http://127.0.0.1:9492' ).replace( /\/$/, '' );
// fileURLToPath, not .pathname: the repo path has a space.
const out = process.argv[ 2 ] || fileURLToPath( new URL( './images/', import.meta.url ) );
mkdirSync( out, { recursive: true } );

const browser = await chromium.launch();

async function open( path, width = 1440, height = 1000 ) {
	const context = await browser.newContext( { viewport: { width, height }, deviceScaleFactor: 1, reducedMotion: 'reduce' } );
	const page = await context.newPage();
	await page.goto( base + path, { waitUntil: 'networkidle', timeout: 90000 } );
	// --login signs every visitor in: no admin bar in a product shot.
	await page.addStyleTag( { content: '#wpadminbar{display:none!important}html{margin-top:0!important}:root{--wp-admin--admin-bar--height:0px!important}' } );
	// Load every image, fire every reveal, then settle back at the top.
	await page.evaluate( async () => {
		document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
			img.loading = 'eager';
		} );
		for ( let y = 0; y < document.body.scrollHeight; y += 400 ) {
			window.scrollTo( 0, y );
			await new Promise( ( r ) => setTimeout( r, 50 ) );
		}
		await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
		window.scrollTo( 0, 0 );
	} );
	await page.waitForTimeout( 900 );
	return { context, page };
}

// Put an element `pad` px below the top of the screen. Below the hero the
// header is the stuck black bar, 68px tall, so every pad allows for it.
async function scrollTo( page, selector, pad = 0 ) {
	await page.evaluate( ( [ sel, gap ] ) => {
		const el = document.querySelector( sel );
		window.scrollTo( 0, el.getBoundingClientRect().top + window.scrollY - gap );
	}, [ selector, pad ] );
	await page.waitForTimeout( 1200 );
}

// Text the frame cuts in half reads as a mistake. Nudge the scroll, a few
// pixels at a time, until no line of text straddles the bottom edge or the
// stuck header's lower edge.
async function clearEdges( page ) {
	for ( let step = 0; step < 16; step++ ) {
		const cut = await page.evaluate( () => {
			const header = document.querySelector( '.montana-header.is-stuck' );
			const top = header ? header.getBoundingClientRect().bottom : 0;
			const bottom = window.innerHeight;
			let hits = 0;
			const walker = document.createTreeWalker( document.body, NodeFilter.SHOW_TEXT );
			while ( walker.nextNode() ) {
				const node = walker.currentNode;
				if ( ! node.textContent.trim() || node.parentElement.closest( 'header, .screen-reader-text' ) ) {
					continue;
				}
				const range = document.createRange();
				range.selectNodeContents( node );
				for ( const r of range.getClientRects() ) {
					if ( r.height && ( ( r.top < bottom && r.bottom > bottom ) || ( r.top < top && r.bottom > top ) ) ) {
						hits++;
					}
				}
			}
			return hits;
		} );
		if ( ! cut ) {
			return;
		}
		await page.evaluate( () => window.scrollBy( 0, -12 ) );
		await page.waitForTimeout( 150 );
	}
	console.log( '  warning: text still cut at an edge' );
}

async function save( buffer, name, width = 1140, height = 792 ) {
	const file = join( out, name + '.jpg' );
	await sharp( buffer )
		.resize( width, height, { fit: 'cover', position: 'top', kernel: 'lanczos3' } )
		.jpeg( { quality: 82, progressive: true, mozjpeg: true } )
		.toFile( file );
	const meta = await sharp( file ).metadata();
	console.log( `${ name }.jpg ${ meta.width }x${ meta.height }` );
}

// name, path, section to put near the top (null: the top of the page), pad, options.
const SHOTS = [
	[ 'montana-block-theme-home', '/', null, 0 ],
	[ 'montana-block-theme-featured-rooms', '/', '.montana-rooms', 68 ],
	[ 'montana-block-theme-rooms-page', '/rooms/', '.montana-room-card', 100 ],
	[ 'montana-block-theme-booking-form', '/rooms/', '.montana-booking', 0 ],
	[ 'montana-block-theme-dark-mode', '/', '.montana-offers .montana-title', 100, { dark: true } ],
	[ 'montana-block-theme-blog', '/blog/', 'main', 68 ],
];

for ( const [ name, path, selector, pad, opts = {} ] of SHOTS ) {
	const { context, page } = await open( path );
	if ( opts.dark ) {
		await page.evaluate( () => document.documentElement.classList.add( 'montana-dark' ) );
		await page.waitForTimeout( 500 );
	}
	if ( selector ) {
		await scrollTo( page, selector, pad );
		await clearEdges( page );
	}
	await save( await page.screenshot(), name );
	await context.close();
}

// The card (3:2) and the listing card (4:3): the home hero alone.
for ( const [ name, h, oh ] of [ [ 'montana-free-hotel-wordpress-theme', 960, 800 ], [ 'montana-free-hotel-wordpress-theme-card', 1080, 900 ] ] ) {
	const { context, page } = await open( '/', 1440, h );
	await save( await page.screenshot(), name, 1200, oh );
	await context.close();
}

// Palettes: the booking request under each palette -- the full-width button,
// the box, the labels and the stuck header's button all take the palette.
const PALETTES = [
	[ 'colors-1-montana', 'Montana' ],
	[ 'colors-2-pine', 'Pine' ],
	[ 'colors-3-glacier', 'Glacier' ],
	[ 'colors-4-sunset', 'Sunset' ],
	[ 'colors-5-heather', 'Heather' ],
	[ 'colors-6-brass', 'Brass' ],
	[ 'colors-7-midnight', 'Midnight' ],
	[ 'colors-8-lodge', 'Lodge' ],
];
const GAP = 12;
const TILE_W = ( 1140 - 3 * GAP ) / 4;
const TILE_H = Math.round( TILE_W * 1000 / 1440 );
const LABEL = 34;
const tiles = [];
{
	const { context, page } = await open( '/rooms/' );
	await scrollTo( page, '.montana-booking', 0 );
	for ( const [ slug, label ] of PALETTES ) {
		await applyPalette( page, slug, false );
		await page.waitForTimeout( 300 );
		const png = await sharp( await page.screenshot() ).resize( TILE_W, TILE_H, { fit: 'cover', position: 'top', kernel: 'lanczos3' } ).png().toBuffer();
		tiles.push( { png, label } );
	}
	await context.close();
}
const rows = 2;
const H = rows * ( TILE_H + LABEL ) + ( rows - 1 ) * GAP;
const composites = [];
tiles.forEach( ( t, i ) => {
	const x = ( i % 4 ) * ( TILE_W + GAP );
	const y = Math.floor( i / 4 ) * ( TILE_H + LABEL + GAP );
	composites.push( { input: t.png, left: x, top: y } );
	const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${ TILE_W }" height="${ LABEL }"><text x="0" y="24" font-family="Helvetica, Arial, sans-serif" font-size="17" font-weight="600" fill="#1f1f1f">${ t.label }</text></svg>`;
	composites.push( { input: Buffer.from( svg ), left: x, top: y + TILE_H } );
} );
const grid = await sharp( { create: { width: 1140, height: H, channels: 3, background: '#ffffff' } } ).composite( composites ).png().toBuffer();
await sharp( grid ).jpeg( { quality: 82, progressive: true, mozjpeg: true } ).toFile( join( out, 'montana-block-theme-colour-palettes.jpg' ) );
console.log( `montana-block-theme-colour-palettes.jpg 1140x${ H }` );

await browser.close();
