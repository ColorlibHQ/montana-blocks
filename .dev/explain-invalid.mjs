/**
 * For one pattern file, print what the editor expected for each invalid block
 * next to what the file has — the diff the generator needs to close.
 *
 *   node .dev/explain-invalid.mjs patterns/intro-video.php
 */
import { chromium } from 'playwright';
import { readFileSync } from 'node:fs';

const url = process.env.WP_URL || 'http://127.0.0.1:9492';
const file = process.argv[ 2 ];
const raw = readFileSync( file, 'utf8' );
const body = raw.slice( raw.indexOf( '?>\n' ) + 3 ).replace( /<\?php[\s\S]*?\?>/g, 'MTPHP' );

const browser = await chromium.launch();
const page = await ( await browser.newContext() ).newPage();
await page.goto( url + '/wp-login.php', { waitUntil: 'commit' } );
await page.fill( '#user_login', process.env.WP_USER || 'admin' );
await page.fill( '#user_pass', process.env.WP_PASS || 'password' );
await page.click( '#wp-submit' );
await page.waitForFunction( () => 'complete' === document.readyState );
await page.goto( url + '/wp-admin/site-editor.php', { waitUntil: 'commit', timeout: 60000 } );
await page.waitForSelector( '.edit-site, #site-editor', { timeout: 60000 } );
await page.waitForTimeout( 5000 );

const out = await page.evaluate( ( content ) => {
	const res = [];
	const walk = ( list ) => {
		for ( const b of list ) {
			if ( b.name && false === b.isValid ) {
				res.push( { name: b.name, attrs: JSON.stringify( b.attributes ), expected: window.wp.blocks.getSaveContent( b.name, b.attributes, b.innerBlocks ), actual: b.originalContent } );
			}
			if ( b.innerBlocks?.length ) {
				walk( b.innerBlocks );
			}
		}
	};
	walk( window.wp.blocks.parse( content ) );
	return res;
}, body );
await browser.close();
for ( const o of out ) {
	console.log( '== ' + o.name + '\nATTRS    ' + o.attrs + '\nEXPECTED ' + o.expected.slice( 0, 700 ) + '\nACTUAL   ' + o.actual.slice( 0, 700 ) + '\n' );
}
console.log( out.length + ' invalid' );
