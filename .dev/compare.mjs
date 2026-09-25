/**
 * Side-by-side full-page renders: the HTML template on the left, this theme on
 * the right, at the same width, so the two designs can be judged in one look
 * rather than property by property.
 *
 * Reads the raw captures that .dev/capture.mjs writes into
 * .dev/compare/source/ (the template) and .dev/compare/theme/ (a Playground),
 * and writes .dev/compare/<page>-<width>.jpg.
 *
 *   node .dev/capture.mjs https://preview.colorlib.com/theme/montana .dev/compare/source home=/index.html,...
 *   node .dev/capture.mjs http://127.0.0.1:9492 .dev/compare/theme home=/,...
 *   node .dev/compare.mjs home,about,services 1440,390
 *
 * @package Montana
 */

import sharp from 'sharp';
import { existsSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname( fileURLToPath( import.meta.url ) );
const dir = resolve( here, 'compare' );
const names = ( process.argv[ 2 ] || 'home' ).split( ',' );
const widths = ( process.argv[ 3 ] || '1440,390' ).split( ',' ).map( Number );

function label( text, width ) {
	const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${ width }" height="44">
		<rect width="100%" height="100%" fill="#222"/>
		<text x="14" y="29" font-family="Helvetica, Arial, sans-serif" font-size="20" fill="#fff">${ text }</text>
	</svg>`;
	return Buffer.from( svg );
}

for ( const name of names ) {
	for ( const width of widths ) {
		const left = join( dir, 'source', `${ name }-${ width }.png` );
		const right = join( dir, 'theme', `${ name }-${ width }.png` );
		if ( ! existsSync( left ) || ! existsSync( right ) ) {
			console.log( `skip ${ name }-${ width }: missing ${ existsSync( left ) ? right : left }` );
			continue;
		}
		// Desktop pages are halved so a whole page stays a reasonable file.
		const scale = width > 600 ? 0.5 : 1;
		const a = await sharp( left ).resize( Math.round( width * scale ) ).toBuffer( { resolveWithObject: true } );
		const b = await sharp( right ).resize( Math.round( width * scale ) ).toBuffer( { resolveWithObject: true } );
		const w = a.info.width;
		const gap = 24;
		const height = Math.max( a.info.height, b.info.height ) + 44;
		const out = join( dir, `${ name }-${ width }.jpg` );
		await sharp( { create: { width: w * 2 + gap, height, channels: 3, background: '#888' } } )
			.composite( [
				{ input: label( `template  ${ width }px`, w ), left: 0, top: 0 },
				{ input: label( `Montana theme  ${ width }px`, w ), left: w + gap, top: 0 },
				{ input: a.data, left: 0, top: 44 },
				{ input: b.data, left: w + gap, top: 44 },
			] )
			.jpeg( { quality: 72 } )
			.toFile( out );
		console.log( `${ out }  (${ a.info.height } vs ${ b.info.height } px tall)` );
	}
}
