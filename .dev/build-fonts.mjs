/**
 * Downloads the theme's webfonts into assets/fonts/, latin and latin-ext.
 *
 * theme.json declares each face with a `file:./assets/fonts/...` src and a
 * unicode-range, so WordPress serves them from the theme, no page contacts
 * Google, and a page in English never downloads the latin-ext file. The files
 * here are exactly the ones theme.json names — an undeclared file is dead
 * weight in the zip, and a declared file that is missing is a 404 on every page.
 *
 * Usage:  node .dev/build-fonts.mjs
 */

import { writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );
const out = join( root, 'assets/fonts' );

const SUBSETS = [ 'latin', 'latin-ext' ];
const FILES = [];

// Raleway, the template's only face: one variable file per subset covers the
// 300-700 the design uses (light body copy, regular headings, semibold menu).
for ( const subset of SUBSETS ) {
	FILES.push( `@fontsource-variable/raleway@5.3.0/files/raleway-${ subset }-wght-normal.woff2` );
}

// Playfair Display: one variable file per subset covers 400–900.
for ( const subset of SUBSETS ) {
	FILES.push( `@fontsource-variable/playfair-display@5.3.0/files/playfair-display-${ subset }-wght-normal.woff2` );
}

mkdirSync( out, { recursive: true } );
let bytes = 0;

for ( const path of FILES ) {
	const url = `https://cdn.jsdelivr.net/npm/${ path }`;
	const res = await fetch( url );
	if ( ! res.ok ) {
		throw new Error( `${ res.status } ${ url }` );
	}
	const buf = Buffer.from( await res.arrayBuffer() );
	const name = path.split( '/' ).pop();
	writeFileSync( join( out, name ), buf );
	bytes += buf.length;
	console.log( `  ${ name.padEnd( 44 ) } ${ ( buf.length / 1024 ).toFixed( 1 ) }KB` );
}

console.log( `\n${ ( bytes / 1024 ).toFixed( 0 ) }KB in assets/fonts/` );
