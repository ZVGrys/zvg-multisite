<?php
/**
 * Site-wide options page.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'zvg_acf_register_options_page' );

add_filter( 'acf/load_field', 'zvg_acf_load_error_default' );
add_filter( 'acf/load_field', 'zvg_acf_load_post_default' );
add_filter( 'acf/load_field/name=post_share_networks', 'zvg_acf_load_share_networks' );

add_filter( 'acf/load_value/name=header_menu', 'zvg_acf_load_menu_location', 10, 3 );
add_filter( 'acf/update_value/name=header_menu', 'zvg_acf_update_menu_location', 10, 3 );
add_filter( 'acf/load_value/name=footer_menu', 'zvg_acf_load_menu_location', 10, 3 );
add_filter( 'acf/update_value/name=footer_menu', 'zvg_acf_update_menu_location', 10, 3 );
add_filter( 'acf/load_value/name=header_logo_image', 'zvg_acf_load_custom_logo', 10, 2 );
add_filter( 'acf/update_value/name=header_logo_image', 'zvg_acf_update_custom_logo', 10, 2 );
add_filter( 'acf/load_value/name=header_logo_text', 'zvg_acf_load_site_title', 10, 2 );
add_filter( 'acf/update_value/name=header_logo_text', 'zvg_acf_update_site_title', 10, 2 );

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

/**
 * The copy the not found page starts with.
 *
 * Read by the template through zvg_acf_error_option() and pre-filled into the fields
 * themselves by zvg_acf_load_error_default(), so the strings live here alone.
 *
 * @return array<string, string> Site Options field name => value.
 */
function zvg_acf_error_defaults() {
	return array(
		'error_eyebrow'            => _x( 'Error 404', 'Not found eyebrow', 'zvg-acf' ),
		'error_code'               => _x( '404', 'Not found code', 'zvg-acf' ),
		'error_lead'               => _x( 'This page does not exist on any of the three builds.', 'Not found lead', 'zvg-acf' ),
		'error_search_placeholder' => _x( 'Type a word or two', 'Not found search', 'zvg-acf' ),
		'error_search_button'      => _x( 'Search', 'Not found search', 'zvg-acf' ),
		'error_search_hint'        => _x( 'Looks through the posts and pages of this build.', 'Not found search hint', 'zvg-acf' ),
		'error_button_1_label'     => _x( 'Back to the homepage', 'Not found button', 'zvg-acf' ),
		'error_button_2_label'     => _x( 'Read the blog', 'Not found button', 'zvg-acf' ),
	);
}

/**
 * Pre-fill a not found field with the copy the theme ships.
 *
 * @param array $field Field being loaded.
 *
 * @return array
 */
function zvg_acf_load_error_default( $field ) {
	$defaults = zvg_acf_error_defaults();

	if ( isset( $field['name'], $defaults[ $field['name'] ] ) && '' === $field['default_value'] ) {
		$field['default_value'] = $defaults[ $field['name'] ];
	}

	return $field;
}

/**
 * A not found page option, falling back to the copy the theme ships.
 *
 * @param string $name Site Options field name.
 *
 * @return mixed
 */
function zvg_acf_error_option( $name ) {
	$defaults = zvg_acf_error_defaults();

	return zvg_acf_option( $name, isset( $defaults[ $name ] ) ? $defaults[ $name ] : '' );
}

/**
 * The copy a single post is built with, and the share networks it offers.
 *
 * Read by the template through zvg_acf_post_option() and pre-filled into the text
 * fields by zvg_acf_load_post_default(), so the strings live here alone.
 *
 * @return array<string, mixed> Site Options field name => value.
 */
function zvg_acf_post_defaults() {
	return array(
		'post_share_show'     => true,
		'post_share_label'    => _x( 'Share this post', 'Share links label', 'zvg-acf' ),
		'post_share_networks' => array_keys( zvg_acf_share_networks() ),
		'post_share_copy'     => true,
		'post_nav_show'       => true,
		'post_nav_prev_label' => _x( 'Previous', 'Post navigation', 'zvg-acf' ),
		'post_nav_next_label' => _x( 'Next', 'Post navigation', 'zvg-acf' ),
	);
}

/**
 * Pre-fill a single post field with the copy the theme ships.
 *
 * @param array $field Field being loaded.
 *
 * @return array
 */
function zvg_acf_load_post_default( $field ) {
	$defaults = zvg_acf_post_defaults();
	$name     = isset( $field['name'] ) ? $field['name'] : '';

	if ( isset( $defaults[ $name ] ) && is_string( $defaults[ $name ] ) && '' === $field['default_value'] ) {
		$field['default_value'] = $defaults[ $name ];
	}

	return $field;
}

/**
 * Offer the networks the theme ships, so a filtered catalogue reaches the field.
 *
 * @param array $field Field being loaded.
 *
 * @return array
 */
function zvg_acf_load_share_networks( $field ) {
	$defaults = zvg_acf_post_defaults();

	foreach ( zvg_acf_share_networks() as $key => $network ) {
		$field['choices'][ $key ] = $network['name'];
	}

	if ( empty( $field['default_value'] ) ) {
		$field['default_value'] = $defaults['post_share_networks'];
	}

	return $field;
}

/**
 * A single post option, falling back to the copy the theme ships.
 *
 * @param string $name Site Options field name.
 *
 * @return mixed
 */
function zvg_acf_post_option( $name ) {
	$defaults = zvg_acf_post_defaults();

	return zvg_acf_option( $name, isset( $defaults[ $name ] ) ? $defaults[ $name ] : '' );
}

/**
 * Whether ACF is reading or writing a site-wide option.
 *
 * @param mixed $post_id Identifier ACF resolved for the current value.
 *
 * @return bool
 */
function zvg_acf_is_option_id( $post_id ) {
	if ( ! function_exists( 'acf_get_post_id_info' ) ) {
		return false;
	}

	$info = acf_get_post_id_info( $post_id );

	return isset( $info['type'] ) && 'option' === $info['type'];
}

/**
 * Read the logo image from the Customizer.
 *
 * @param mixed $value   Value ACF loaded.
 * @param mixed $post_id Identifier ACF is reading.
 *
 * @return mixed
 */
function zvg_acf_load_custom_logo( $value, $post_id ) {
	if ( ! zvg_acf_is_option_id( $post_id ) ) {
		return $value;
	}

	$logo_id = (int) get_theme_mod( 'custom_logo' );

	return $logo_id ? $logo_id : '';
}

/**
 * Write the logo image to the Customizer.
 *
 * @param mixed $value   Value submitted for the field.
 * @param mixed $post_id Identifier ACF is writing.
 *
 * @return mixed Null when the Customizer owns the value.
 */
function zvg_acf_update_custom_logo( $value, $post_id ) {
	if ( ! zvg_acf_is_option_id( $post_id ) ) {
		return $value;
	}

	$logo_id = (int) $value;

	if ( $logo_id ) {
		set_theme_mod( 'custom_logo', $logo_id );
	} else {
		remove_theme_mod( 'custom_logo' );
	}

	return null;
}

/**
 * Read the logo text from the site title.
 *
 * @param mixed $value   Value ACF loaded.
 * @param mixed $post_id Identifier ACF is reading.
 *
 * @return mixed
 */
function zvg_acf_load_site_title( $value, $post_id ) {
	if ( ! zvg_acf_is_option_id( $post_id ) ) {
		return $value;
	}

	return get_option( 'blogname' );
}

/**
 * Write the logo text to the site title.
 *
 * @param mixed $value   Value submitted for the field.
 * @param mixed $post_id Identifier ACF is writing.
 *
 * @return mixed Null when the Customizer owns the value.
 */
function zvg_acf_update_site_title( $value, $post_id ) {
	if ( ! zvg_acf_is_option_id( $post_id ) ) {
		return $value;
	}

	update_option( 'blogname', sanitize_text_field( (string) $value ) );

	return null;
}

/**
 * The theme location each Site Options menu field stands in for.
 *
 * @return array<string, string> Field name => registered nav menu location.
 */
function zvg_acf_menu_locations() {
	return array(
		'header_menu' => 'primary',
		'footer_menu' => 'footer',
	);
}

/**
 * Read a menu field from the theme locations.
 *
 * @param mixed $value   Value ACF loaded.
 * @param mixed $post_id Identifier ACF is reading.
 * @param array $field   Field being loaded.
 *
 * @return mixed
 */
function zvg_acf_load_menu_location( $value, $post_id, $field ) {
	$locations = zvg_acf_menu_locations();
	$name      = isset( $field['name'] ) ? $field['name'] : '';

	if ( ! zvg_acf_is_option_id( $post_id ) || ! isset( $locations[ $name ] ) ) {
		return $value;
	}

	$assigned = get_nav_menu_locations();
	$location = $locations[ $name ];

	return isset( $assigned[ $location ] ) ? (int) $assigned[ $location ] : '';
}

/**
 * Write a menu field to the theme locations.
 *
 * @param mixed $value   Value submitted for the field.
 * @param mixed $post_id Identifier ACF is writing.
 * @param array $field   Field being saved.
 *
 * @return mixed Null when the theme location owns the value.
 */
function zvg_acf_update_menu_location( $value, $post_id, $field ) {
	$locations = zvg_acf_menu_locations();
	$name      = isset( $field['name'] ) ? $field['name'] : '';

	if ( ! zvg_acf_is_option_id( $post_id ) || ! isset( $locations[ $name ] ) ) {
		return $value;
	}

	$assigned = get_nav_menu_locations();
	$location = $locations[ $name ];
	$menu_id  = (int) $value;

	if ( $menu_id && is_nav_menu( $menu_id ) ) {
		$assigned[ $location ] = $menu_id;
	} else {
		unset( $assigned[ $location ] );
	}

	set_theme_mod( 'nav_menu_locations', $assigned );

	return null;
}
