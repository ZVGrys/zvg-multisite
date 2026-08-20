<?php
/**
 * The menus field type.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_acf_register_field_menus' );

/**
 * Register the field type with ACF.
 */
function zvg_acf_register_field_menus() {
	if ( ! function_exists( 'acf_register_field_type' ) ) {
		return;
	}

	require_once __DIR__ . '/class-zvg-acf-field-menus.php';

	acf_register_field_type( 'Zvg_Acf_Field_Menus' );
}
