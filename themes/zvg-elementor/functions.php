<?php
/**
 * ZVG Elementor functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

defined( 'ZVG_ELEMENTOR_T_URI' ) || define( 'ZVG_ELEMENTOR_T_URI', get_template_directory_uri() );
defined( 'ZVG_ELEMENTOR_T_PATH' ) || define( 'ZVG_ELEMENTOR_T_PATH', get_template_directory() );

defined( 'ZVG_ELEMENTOR_USE_THEME_VERSION' ) || define( 'ZVG_ELEMENTOR_USE_THEME_VERSION', false );

if ( ! defined( 'ZVG_ELEMENTOR_VERSION' ) ) {
	$zvg_elementor_theme   = wp_get_theme();
	$zvg_elementor_version = $zvg_elementor_theme instanceof WP_Theme ? $zvg_elementor_theme->get( 'Version' ) : '';

	define( 'ZVG_ELEMENTOR_VERSION', $zvg_elementor_version ? $zvg_elementor_version : '1.0.0' );
}

require_once ZVG_ELEMENTOR_T_PATH . '/include/actions-config.php';
require_once ZVG_ELEMENTOR_T_PATH . '/include/helper-functions.php';
require_once ZVG_ELEMENTOR_T_PATH . '/include/site-options.php';
require_once ZVG_ELEMENTOR_T_PATH . '/include/post-types.php';
require_once ZVG_ELEMENTOR_T_PATH . '/include/share-links.php';
require_once ZVG_ELEMENTOR_T_PATH . '/cmb2/init.php';
require_once ZVG_ELEMENTOR_T_PATH . '/include/metaboxes.php';

if ( defined( 'ELEMENTOR_VERSION' ) ) {
	require_once ZVG_ELEMENTOR_T_PATH . '/include/elementor-widgets.php';
}

if ( ! function_exists( 'zvg_elementor_setup' ) ) :

	/**
	 * Theme supports.
	 */
	function zvg_elementor_setup() {
		load_theme_textdomain( 'zvg-elementor', ZVG_ELEMENTOR_T_PATH . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-logo' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'zvg-elementor' ),
				'footer'  => esc_html__( 'Footer', 'zvg-elementor' ),
			)
		);
	}
endif;

add_action( 'after_setup_theme', 'zvg_elementor_setup' );

if ( ! function_exists( 'zvg_elementor_content_width' ) ) :

	/**
	 * Default content width.
	 */
	function zvg_elementor_content_width() {
		/**
		 * Filter the content width.
		 *
		 * @param int $content_width Width in pixels.
		 */
		$GLOBALS['content_width'] = apply_filters( 'zvg_elementor_content_width', 1200 );
	}
endif;

add_action( 'after_setup_theme', 'zvg_elementor_content_width', 0 );

if ( ! function_exists( 'zvg_elementor_register_locations' ) ) :

	/**
	 * Register the Theme Builder locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Locations manager.
	 */
	function zvg_elementor_register_locations( $manager ) {
		$manager->register_all_core_location();
	}
endif;

add_action( 'elementor/theme/register_locations', 'zvg_elementor_register_locations' );

if ( ! function_exists( 'zvg_elementor_clean_search_combobox' ) ) :

	/**
	 * Drop the combobox ARIA the Search widget prints when Live Results are off.
	 *
	 * @param ElementorPro\Modules\Search\Widgets\Search $widget Search widget instance.
	 */
	function zvg_elementor_clean_search_combobox( $widget ) {
		if ( 'yes' === $widget->get_settings_for_display( 'live_results' ) ) {
			return;
		}

		foreach ( array( 'role', 'aria-autocomplete', 'aria-expanded', 'aria-controls', 'aria-haspopup' ) as $zvg_elementor_attribute ) {
			$widget->remove_render_attribute( 'input', $zvg_elementor_attribute );
		}
	}
endif;

add_action( 'elementor_pro/search/before_input', 'zvg_elementor_clean_search_combobox' );
