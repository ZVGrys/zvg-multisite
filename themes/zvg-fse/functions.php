<?php
/**
 * ZVG FSE functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

defined( 'ZVG_FSE_T_URI' ) || define( 'ZVG_FSE_T_URI', get_template_directory_uri() );
defined( 'ZVG_FSE_T_PATH' ) || define( 'ZVG_FSE_T_PATH', get_template_directory() );

defined( 'ZVG_FSE_USE_THEME_VERSION' ) || define( 'ZVG_FSE_USE_THEME_VERSION', false );

if ( ! defined( 'ZVG_FSE_VERSION' ) ) {
	$zvg_fse_theme   = wp_get_theme();
	$zvg_fse_version = $zvg_fse_theme instanceof WP_Theme ? $zvg_fse_theme->get( 'Version' ) : '';

	define( 'ZVG_FSE_VERSION', $zvg_fse_version ? $zvg_fse_version : '1.0.0' );
}

require_once ZVG_FSE_T_PATH . '/include/actions-config.php';
require_once ZVG_FSE_T_PATH . '/include/helper-functions.php';
require_once ZVG_FSE_T_PATH . '/include/post-types.php';
require_once ZVG_FSE_T_PATH . '/include/metaboxes.php';
require_once ZVG_FSE_T_PATH . '/include/block-styles.php';
require_once ZVG_FSE_T_PATH . '/include/patterns.php';
require_once ZVG_FSE_T_PATH . '/include/gutenberg-blocks.php';

if ( ! function_exists( 'zvg_fse_setup' ) ) :

	/**
	 * Theme supports.
	 */
	function zvg_fse_setup() {
		load_theme_textdomain( 'zvg-fse', ZVG_FSE_T_PATH . '/languages' );

		add_theme_support( 'wp-block-styles' );

		add_theme_support( 'editor-styles' );

		add_editor_style(
			array(
				'assets/css/main.css',
				'assets/css/templates/single.css',
				'assets/css/templates/404.css',
				'assets/css/sections/base.css',
				'assets/css/sections/hero.css',
				'assets/css/sections/builds.css',
				'assets/css/sections/flow.css',
				'assets/css/sections/editors.css',
				'assets/css/sections/measured.css',
				'assets/css/sections/team.css',
				'assets/css/sections/blog.css',
				'assets/css/sections/contact.css',
			)
		);
	}
endif;

add_action( 'after_setup_theme', 'zvg_fse_setup' );
