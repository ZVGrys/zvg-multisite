<?php
/**
 * ZVG ACF functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

defined( 'ZVG_ACF_T_URI' ) || define( 'ZVG_ACF_T_URI', get_template_directory_uri() );
defined( 'ZVG_ACF_T_PATH' ) || define( 'ZVG_ACF_T_PATH', get_template_directory() );

defined( 'ZVG_ACF_USE_THEME_VERSION' ) || define( 'ZVG_ACF_USE_THEME_VERSION', false );

if ( ! defined( 'ZVG_ACF_VERSION' ) ) {
	$zvg_acf_theme   = wp_get_theme();
	$zvg_acf_version = $zvg_acf_theme instanceof WP_Theme ? $zvg_acf_theme->get( 'Version' ) : '';

	define( 'ZVG_ACF_VERSION', $zvg_acf_version ? $zvg_acf_version : '1.0.0' );
}

require_once ZVG_ACF_T_PATH . '/include/actions-config.php';
require_once ZVG_ACF_T_PATH . '/include/helper-functions.php';
require_once ZVG_ACF_T_PATH . '/include/site-options.php';
require_once ZVG_ACF_T_PATH . '/acf-custom-fields/acf-menus/init.php';

if ( ! function_exists( 'zvg_acf_setup' ) ) :

	/**
	 * Theme supports.
	 */
	function zvg_acf_setup() {
		load_theme_textdomain( 'zvg-acf', ZVG_ACF_T_PATH . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'editor-styles' );
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
				'primary' => esc_html__( 'Primary', 'zvg-acf' ),
				'footer'  => esc_html__( 'Footer', 'zvg-acf' ),
			)
		);

		add_editor_style(
			array(
				'assets/css/general.css',
				'assets/css/typography.css',
			)
		);
	}
endif;

add_action( 'after_setup_theme', 'zvg_acf_setup' );

if ( ! function_exists( 'zvg_acf_content_width' ) ) :

	/**
	 * Default content width.
	 */
	function zvg_acf_content_width() {
		/**
		 * Filter the content width.
		 *
		 * @param int $content_width Width in pixels.
		 */
		$GLOBALS['content_width'] = apply_filters( 'zvg_acf_content_width', 1200 );
	}
endif;

add_action( 'after_setup_theme', 'zvg_acf_content_width', 0 );
