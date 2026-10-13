<?php
/**
 * DidaPRESS 4 — customer customizations (child theme).
 *
 * Parent theme: didapress_4
 *
 * @package didapress_4_custom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue customer styles after the parent theme bundle.
 */
function didapress_4_custom_enqueue_styles() {
	$css_path = get_stylesheet_directory() . '/assets/css/custom.css';
	$deps     = array( 'didapress_4_styles' );

	if ( ! file_exists( $css_path ) ) {
		return;
	}

	wp_enqueue_style(
		'didapress_4_custom',
		get_stylesheet_directory_uri() . '/assets/css/custom.css',
		$deps,
		(string) filemtime( $css_path )
	);
}
add_action( 'wp_enqueue_scripts', 'didapress_4_custom_enqueue_styles', 20 );

/**
 * Load the hand-written customer stylesheet after the theme bundles.
 */
function didapress_4_custom_enqueue_extra_styles() {
	$extra_css_path = get_stylesheet_directory() . '/css-custom.css';

	if ( ! file_exists( $extra_css_path ) ) {
		return;
	}

	$deps = array();
	foreach ( array( 'didapress_4_styles', 'didapress_4_custom', 'didapress_4_theme_variant', 'didapress_4_nav_variant' ) as $handle ) {
		if ( wp_style_is( $handle, 'enqueued' ) || wp_style_is( $handle, 'registered' ) ) {
			$deps[] = $handle;
		}
	}

	wp_enqueue_style(
		'didapress_4_custom_extra',
		get_stylesheet_directory_uri() . '/css-custom.css',
		$deps,
		(string) filemtime( $extra_css_path )
	);
}
add_action( 'wp_enqueue_scripts', 'didapress_4_custom_enqueue_extra_styles', 100 );

/**
 * Enqueue customer admin styles (optional).
 *
 * @param string $hook Admin hook suffix.
 */
function didapress_4_custom_admin_styles( $hook ) {
	if ( function_exists( 'didapress_4_admin_needs_theme_styles' ) && ! didapress_4_admin_needs_theme_styles( $hook ) ) {
		return;
	}

	$css_path = get_stylesheet_directory() . '/assets/css/custom-admin.css';

	if ( ! file_exists( $css_path ) ) {
		return;
	}

	wp_enqueue_style(
		'didapress_4_custom_admin',
		get_stylesheet_directory_uri() . '/assets/css/custom-admin.css',
		array( 'didapress_4_admin_styles' ),
		(string) filemtime( $css_path )
	);
}
add_action( 'admin_enqueue_scripts', 'didapress_4_custom_admin_styles', 20 );

$didapress_4_custom_includes = array(
	'/functions/custom.php',
);

foreach ( $didapress_4_custom_includes as $file ) {
	$path = get_stylesheet_directory() . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
