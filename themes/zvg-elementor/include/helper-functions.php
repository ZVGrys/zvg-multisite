<?php
/**
 * Small reusable helpers.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'zvg_elementor_do_location' ) ) :

	/**
	 * Output a Theme Builder location.
	 *
	 * @param string $location Location name.
	 *
	 * @return bool Whether the location was output.
	 */
	function zvg_elementor_do_location( $location ) {
		return function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location );
	}
endif;

if ( ! function_exists( 'zvg_elementor_has_location' ) ) :

	/**
	 * Whether a Theme Builder template answers this request, without printing it.
	 *
	 * @param string $location Location name.
	 *
	 * @return bool
	 */
	function zvg_elementor_has_location( $location ) {
		if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			return false;
		}

		return \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->location_exits( $location, true );
	}
endif;

if ( ! function_exists( 'zvg_elementor_is_builder_page' ) ) :

	/**
	 * Whether the queried entry was built in the Elementor editor.
	 *
	 * @return bool
	 */
	function zvg_elementor_is_builder_page() {
		$post_id = is_singular() || is_home() ? get_queried_object_id() : 0;

		return $post_id && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
	}
endif;

if ( ! function_exists( 'zvg_elementor_owns_content' ) ) :

	/**
	 * Whether Elementor lays out the content of this request.
	 *
	 * @return bool
	 */
	function zvg_elementor_owns_content() {
		if ( zvg_elementor_is_builder_page() ) {
			return true;
		}

		if ( is_archive() || is_home() || is_search() ) {
			$location = 'archive';
		} elseif ( is_singular() || is_404() ) {
			$location = 'single';
		} else {
			return false;
		}

		return zvg_elementor_has_location( $location );
	}
endif;
