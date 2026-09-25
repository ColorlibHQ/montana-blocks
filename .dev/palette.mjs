/**
 * Render a page under any of the theme's colour palettes, client-side.
 *
 * The rendered checks measure whatever palette the site has active, which is
 * one of eight. Overriding the preset variables in the page itself is exact --
 * it is the same thing a style variation does -- and changes nothing on the
 * site. With dark mode on, scheme.css is added again afterwards, so its
 * redefinitions still win over the palette, as they do on a real page.
 *
 *   MONTANA_PALETTE=colors-7-midnight node .dev/contrast-rendered.mjs
 *
 * @package Montana
 */

import { readFileSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );

export function paletteCss( slug ) {
	const data = JSON.parse( readFileSync( join( root, 'styles/colors', slug + '.json' ), 'utf8' ) );
	const vars = data.settings.color.palette.map( ( p ) => `--wp--preset--color--${ p.slug }:${ p.color };` );
	( data.settings.color.gradients || [] ).forEach( ( g ) => vars.push( `--wp--preset--gradient--${ g.slug }:${ g.gradient };` ) );
	return `:root{${ vars.join( '' ) }}`;
}

export async function applyPalette( page, slug, dark ) {
	if ( ! slug ) {
		return;
	}
	await page.addStyleTag( { content: paletteCss( slug ) } );
	if ( dark ) {
		const href = await page.$eval( 'link#montana-scheme-css', ( l ) => l.href ).catch( () => null );
		if ( href ) {
			await page.addStyleTag( { url: href } );
		}
	}
	await page.waitForTimeout( 300 );
}
