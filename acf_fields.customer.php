<?php
/**
 * Customer-only ACF field groups (PHP).
 *
 * Loaded by the parent theme (functions/acf-fields.php) when this child theme is active.
 * Export from ACF → Tools → Export → PHP, then paste registrations below.
 *
 * @package didapress_4_custom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/include_fields',
	static function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		// acf_add_local_field_group( array(
		// 	'key' => 'group_customer_example',
		// 	'title' => 'Customer Example',
		// 	'fields' => array(),
		// 	'location' => array(),
		// ) );
	}
);
