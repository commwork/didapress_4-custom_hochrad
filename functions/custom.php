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
 * Whether the current page header is layout G.
 *
 * @return bool
 */
function didapress_4_custom_is_layout_g() {
	return function_exists( 'didapress_4_get_page_header_layout' )
		&& didapress_4_get_page_header_layout() === 'main_layout_g';
}

/**
 * Flag layout G before paint so the hero and navigation stay hidden until ready.
 */
function didapress_4_custom_layout_g_preloader_flag() {
	if ( ! didapress_4_custom_is_layout_g() ) {
		return;
	}

	$video_src = get_stylesheet_directory_uri() . '/assets/video/Hochrad_Drohnenvideo.mp4';
	$image_src = get_stylesheet_directory_uri() . '/assets/img/video-placeholder.jpg';

	echo '<script>';
	echo '(function(){';
	echo 'var src=window.matchMedia("(min-width: 768px)").matches?' . wp_json_encode( $video_src ) . ':' . wp_json_encode( $image_src ) . ';';
	echo 'window.hochradHeaderMedia=src;';
	echo 'document.documentElement.classList.add("is-header-opening");';
	echo 'try{if(localStorage.getItem("hochrad-header-ready")!==src){document.documentElement.classList.add("is-layout-g-loading");}}';
	echo 'catch(e){document.documentElement.classList.add("is-layout-g-loading");}';
	echo '})();';
	echo "</script>\n";
}
add_action( 'wp_head', 'didapress_4_custom_layout_g_preloader_flag', 0 );

/**
 * Reuse the layout H header-video controls on the homepage video.
 */
function didapress_4_custom_header_video_script() {
	if ( didapress_4_custom_is_layout_g() ) {
		$preloader = get_stylesheet_directory() . '/assets/js/header-preloader.js';
		if ( file_exists( $preloader ) ) {
			wp_enqueue_script(
				'didapress_4_custom_headerPreloaderJs',
				get_stylesheet_directory_uri() . '/assets/js/header-preloader.js',
				array(),
				(string) filemtime( $preloader ),
				true
			);
		}
	}

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
