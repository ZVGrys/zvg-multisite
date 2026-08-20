<?php
/**
 * Site-wide options page.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'zvg_acf_register_options_page' );

/**
 * Register the Site Options page.
 */
function zvg_acf_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Options', 'zvg-acf' ),
			'menu_title' => __( 'Site Options', 'zvg-acf' ),
			'menu_slug'  => 'zvg-acf-site-options',
			'capability' => 'manage_options',
			'redirect'   => false,
		)
	);
}
