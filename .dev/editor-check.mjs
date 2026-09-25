/**
 * Open every pattern in the block editor and look at it there.
 *
 * validate-blocks.mjs proves the markup parses. It cannot see what an editor
 * sees: an icon drawn from an empty inline element renders on the site and not
 * in the editor, an empty paragraph shows "Type / to choose a block", and a
 * block that recovers with a warning still parses. So each pattern is put into
 * a draft post, opened in the editor, and the canvas is searched for warnings,
 * placeholders, missing blocks and icons that did not draw. A screenshot of
 * each goes to .dev/editor-shots/ (not committed) for a person to look at.
 *
 *   WP_URL=http://127.0.0.1:9492 node .dev/editor-check.mjs
 *   PATTERNS=hero,booking node .dev/editor-check.mjs
 */

import { chromium } from 'playwright';
import { mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const url = ( process.env.WP_URL || 'http://127.0.0.1:9492' ).replace( /\/$/, '' );
const shots = join( resolve( dirname( fileURLToPath( import.meta.url ) ) ), 'editor-shots' );
const only = process.env.PATTERNS ? process.env.PATTERNS.split( ',' ).map( ( s ) => 'montana/' + s ) : null;
mkdirSync( shots, { recursive: true } );

const browser = await chromium.launch();
const page = await ( await browser.newContext( { viewport: { width: 1440, height: 1600 } } ) ).newPage();
await page.goto( url + '/wp-login.php', { waitUntil: 'commit' } );
await page.fill( '#user_login', process.env.WP_USER || 'admin' );
await page.fill( '#user_pass', process.env.WP_PASS || 'password' );
await page.click( '#wp-submit' );
await page.waitForFunction( () => 'complete' === document.readyState );

// Any admin page gives us wp.apiFetch.
await page.goto( url + '/wp-admin/post-new.php', { waitUntil: 'domcontentloaded' } );
await page.waitForFunction( () => window.wp && window.wp.apiFetch && window.wp.blocks, null, { timeout: 60000 } );

const patterns = await page.evaluate( async () => {
	const all = await window.wp.apiFetch( { path: '/wp/v2/block-patterns/patterns' } );
	return all.filter( ( p ) => 0 === p.name.indexOf( 'montana/' ) ).map( ( p ) => ( { name: p.name, content: p.content } ) );
} );

const problems = [];
let checked = 0;

for ( const pattern of patterns ) {
	if ( only && ! only.includes( pattern.name ) ) {
		continue;
	}
	// A page pattern is only references; the sections are checked on their own.
	const id = await page.evaluate( async ( p ) => {
		const post = await window.wp.apiFetch( {
			path: '/wp/v2/posts',
			method: 'POST',
			// A post with comments open: on a page the comment form's own
			// "comments are not enabled" notice reads as a block warning.
			data: { title: 'Editor check: ' + p.name, content: p.content, status: 'draft', comment_status: 'open' },
		} );
		return post.id;
	}, pattern );

	await page.goto( `${ url }/wp-admin/post.php?post=${ id }&action=edit`, { waitUntil: 'domcontentloaded' } );
	// Dismiss the welcome guide if it opens.
	await page.waitForSelector( 'iframe[name="editor-canvas"]', { timeout: 60000 } );
	await page.evaluate( () => {
		window.wp.data.dispatch( 'core/preferences' )?.set( 'core/edit-post', 'welcomeGuide', false );
	} );
	const frame = page.frameLocator( 'iframe[name="editor-canvas"]' );
	await frame.locator( '.is-root-container' ).waitFor( { timeout: 60000 } );
	await page.waitForTimeout( 3500 );

	const result = await page.evaluate( () => {
		const doc = document.querySelector( 'iframe[name="editor-canvas"]' ).contentDocument;
		const warnings = doc.querySelectorAll( '.block-editor-warning' ).length;
		const missing = doc.querySelectorAll( '.wp-block-missing, [data-type="core/missing"]' ).length;
		const text = doc.body.innerText;
		const placeholders = ( text.match( /Type \/ to choose a block/g ) || [] ).length;
		// Every element that asks for an icon must have drawn one: a mask on
		// ::before with a real size.
		const icons = [ ...doc.querySelectorAll( '[class*="montana-icon--"], .montana-header__square' ) ];
		const blankIcons = icons.filter( ( el ) => {
			const cs = getComputedStyle( el, '::before' );
			const mask = cs.maskImage || cs.webkitMaskImage || '';
			return 'none' === cs.content || ! mask || 'none' === mask || parseFloat( cs.width ) < 4;
		} ).length;
		const invalid = window.wp.data.select( 'core/block-editor' ).getBlocks().flatMap( function walk( b ) {
			return [ b.isValid === false ? b.name : null, ...b.innerBlocks.flatMap( walk ) ];
		} ).filter( Boolean );
		return { warnings, missing, placeholders, icons: icons.length, blankIcons, invalid };
	} );

	checked++;
	const file = join( shots, pattern.name.replace( 'montana/', '' ) + '.png' );
	await page.locator( 'iframe[name="editor-canvas"]' ).screenshot( { path: file } );

	const bad = [];
	if ( result.warnings ) {
		bad.push( `${ result.warnings } block warning(s)` );
	}
	if ( result.missing ) {
		bad.push( `${ result.missing } missing block(s)` );
	}
	if ( result.placeholders ) {
		bad.push( `${ result.placeholders } "Type / to choose a block"` );
	}
	if ( result.blankIcons ) {
		bad.push( `${ result.blankIcons } of ${ result.icons } icons not drawn` );
	}
	if ( result.invalid.length ) {
		bad.push( 'invalid: ' + result.invalid.join( ', ' ) );
	}
	console.log( `${ bad.length ? 'FAIL' : 'ok  ' }  ${ pattern.name }${ bad.length ? '  — ' + bad.join( '; ' ) : '' }${ result.icons ? `  (${ result.icons } icons)` : '' }` );
	if ( bad.length ) {
		problems.push( pattern.name );
	}

	await page.evaluate( async ( postId ) => {
		await window.wp.apiFetch( { path: '/wp/v2/posts/' + postId + '?force=true', method: 'DELETE' } );
	}, id );
}

await browser.close();

if ( problems.length ) {
	console.error( `\n${ problems.length } of ${ checked } patterns have problems in the editor` );
	process.exit( 1 );
}
console.log( `\nall ${ checked } patterns open clean in the editor; screenshots in .dev/editor-shots/` );
