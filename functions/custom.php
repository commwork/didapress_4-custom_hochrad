<?php
/**
 * Customer-specific PHP hooks and helpers.
 *
 * Examples:
 * - add_filter() / add_action() for small behavior tweaks
 * - Custom shortcodes
 * - Template-related helpers
 *
 * ACF field groups: use /acf_fields.customer.php in this theme root.
 * Template overrides: copy files from the parent theme and edit them here.
 *
 * @package didapress_4_custom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reuse the layout H header-video controls on the homepage video.
 */
function didapress_4_custom_header_video_script() {
	if ( ! is_front_page() ) {
		return;
	}

	$script = get_template_directory() . '/assets/js/header-video.js';

	if ( ! file_exists( $script ) ) {
		return;
	}

	wp_enqueue_script(
		'didapress_4_headerVideoJs',
		get_template_directory_uri() . '/assets/js/header-video.js',
		array(),
		(string) filemtime( $script ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'didapress_4_custom_header_video_script', 20 );

/**
 * Mark the body when the top bar is rendered.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function didapress_4_custom_has_top_bar_body_class( $classes ) {
	if ( function_exists( 'didapress_4_top_bar_is_visible' ) && didapress_4_top_bar_is_visible() ) {
		$classes[] = 'has-top-bar';
	}

	return $classes;
}
add_filter( 'body_class', 'didapress_4_custom_has_top_bar_body_class' );
