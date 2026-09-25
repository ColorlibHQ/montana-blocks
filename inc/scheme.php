<?php
/**
 * Visitor-facing dark mode.
 *
 * Plenty of browsing happens late — show results on a phone, a camp form in bed. This
 * adds a toggle that a visitor controls, separate from whichever palette the
 * site owner chose.
 *
 * It **lifts the active palette rather than replacing it**. A Montana site set to
 * Hunter stays green in dark mode; only the grounds and the text invert. That
 * means one set of rules works for all eight palettes, including the two that
 * are already dark — for those, dark mode is simply the default and the toggle
 * turns the lights *up*.
 *
 * Three rules that make this behave:
 *
 * 1. The stored preference is applied by a tiny inline script in the head,
 *    before anything paints. Deferred, it flashes light then snaps to dark.
 * 2. The default follows `prefers-color-scheme` until a visitor chooses. Once
 *    they do, their choice wins and is remembered.
 * 3. Nothing here runs in the editor, where the Site Editor's own preview
 *    owns the canvas.
 *
 * @package Montana
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether dark mode is offered.
 *
 * A site that wants only its chosen palette can switch the whole feature off:
 *
 *     add_filter( 'montana_enable_dark_mode', '__return_false' );
 *
 * @return bool
 */
function montana_dark_mode_enabled() {
	return (bool) apply_filters( 'montana_enable_dark_mode', true );
}

/**
 * Apply the stored preference before first paint.
 *
 * Inline and in the head on purpose: an external or deferred script cannot run
 * before the browser paints, so the page would flash light and then snap to
 * dark on every single load.
 */
function montana_scheme_boot_script() {
	if ( ! montana_dark_mode_enabled() ) {
		return;
	}
	?>
<script>
( function () {
	try {
		var stored = localStorage.getItem( 'montana-scheme' );
		var system = window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
		var dark = stored ? 'dark' === stored : system;
		document.documentElement.classList.toggle( 'montana-dark', dark );
		document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
	} catch ( e ) {}
}() );
</script>
	<?php
}
add_action( 'wp_head', 'montana_scheme_boot_script', 1 );

/**
 * The stylesheet and the toggle's behaviour.
 */
function montana_scheme_assets() {
	if ( ! montana_dark_mode_enabled() ) {
		return;
	}

	wp_enqueue_style(
		'montana-scheme',
		get_template_directory_uri() . '/assets/css/scheme.css',
		array( 'montana-style' ),
		MONTANA_VERSION
	);

	wp_enqueue_script(
		'montana-scheme-toggle',
		get_template_directory_uri() . '/assets/js/scheme-toggle.js',
		array(),
		MONTANA_VERSION,
		true
	);

	wp_localize_script(
		'montana-scheme-toggle',
		'montanaScheme',
		array(
			'toDark'  => __( 'Switch to dark mode', 'montana' ),
			'toLight' => __( 'Switch to light mode', 'montana' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'montana_scheme_assets' );

/**
 * Turn any button carrying `montana-scheme-toggle` into the switch.
 *
 * The class has to be on the BUTTON, not on the wrapping `wp:buttons` block:
 * the script binds every element carrying it, so a class on both would bind
 * two listeners and one click would toggle twice and land back where it began.
 *
 * @param string $content Rendered block.
 * @param array  $block   Block data.
 * @return string
 */
function montana_scheme_toggle_markup( $content, $block ) {
	$classes = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
	if ( false === strpos( $classes, 'montana-scheme-toggle' ) ) {
		return $content;
	}

	// With dark mode switched off the switch would be a link to "#" that does
	// nothing, so it is not drawn at all.
	if ( ! montana_dark_mode_enabled() ) {
		return '';
	}

	// A control, not a link: give it button semantics and a starting label.
	$content = str_replace(
		'<a ',
		'<a role="button" aria-pressed="false" aria-label="' . esc_attr__( 'Switch to dark mode', 'montana' ) . '" ',
		$content
	);

	return $content;
}
add_filter( 'render_block_core/button', 'montana_scheme_toggle_markup', 10, 2 );
