/**
 * Full-page captures of any site, at desktop and phone width.
 *
 * Used for both halves of a side-by-side: the HTML template on
 * preview.colorlib.com and this theme in a Playground. Scrolls the page first
 * so lazy images load and scroll reveals have fired, then captures the whole
 * height.
 *
 *   node .dev/capture.mjs <base-url> <out-dir> <name=path,...> [widths]
 *   node .dev/capture.mjs https://preview.colorlib.com/theme/montana .dev/compare/source \
 *     home=/index.html,about=/about.html 1440,390
 *
 * @package Montana
 */

import { chromium } from 'playwright';
import { mkdirSync } from 'node:fs';
import { join } from 'node:path';

const [ base, out, list, widthList ] = process.argv.slice( 2 );
if ( ! base || ! out || ! list ) {
	console.error( 'Usage: node .dev/capture.mjs <base-url> <out-dir> <name=path,...> [widths]' );
	process.exit( 1 );
}
const widths = ( widthList || '1440,390' ).split( ',' ).map( Number );
const pages = list.split( ',' ).map( ( pair ) => pair.split( '=' ) );
mkdirSync( out, { recursive: true } );

const browser = await chromium.launch();
for ( const width of widths ) {
	const context = await browser.newContext( {
		viewport: { width, height: width > 600 ? 900 : 844 },
		deviceScaleFactor: 1,
		isMobile: width <= 480,
		hasTouch: width <= 480,
	} );
	const page = await context.newPage();
	for ( const [ name, path ] of pages ) {
		await page.goto( base.replace( /\/$/, '' ) + path, { waitUntil: 'domcontentloaded', timeout: 90000 } );
		// A Playground started with --login signs every visitor in, and the
		// admin bar would sit over the header in every capture.
		await page.addStyleTag( { content: '#wpadminbar{display:none!important}html{margin-top:0!important}.admin-bar .wp-site-blocks>header.wp-block-template-part{top:0!important}' } );
		await page.waitForTimeout( 1500 );
		// Walk down the page so lazy images load and reveals fire, then back up.
		await page.evaluate( async () => {
			const step = window.innerHeight * 0.8;
			for ( let y = 0; y < document.body.scrollHeight; y += step ) {
				window.scrollTo( 0, y );
				await new Promise( ( r ) => setTimeout( r, 120 ) );
			}
			window.scrollTo( 0, 0 );
			document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
				img.loading = 'eager';
			} );
			await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
		} );
		await page.waitForTimeout( 1200 );
		const file = join( out, `${ name }-${ width }.png` );
		await page.screenshot( { path: file, fullPage: true } );
		const h = await page.evaluate( () => document.documentElement.scrollHeight );
		console.log( `${ file }  ${ width }x${ h }` );
	}
	await context.close();
}
await browser.close();
