<?php
/**
 * The theme constants, declared for static analysis only.
 *
 * Each theme defines these in its own functions.php as `defined( … ) || define( … )`. PHPStan
 * cannot resolve a conditional define, so it reports every use as an unknown constant. Declaring
 * them here, with the types the themes actually give them, is the documented way around it.
 *
 * This file is never loaded by WordPress.
 *
 * @package ZVG
 */

$zvg_themes = dirname( __DIR__ );

// The paths are real ones, so that require_once() of a theme file resolves during analysis.
define( 'ZVG_FSE_T_PATH', $zvg_themes . '/zvg-fse' );
define( 'ZVG_ACF_T_PATH', $zvg_themes . '/zvg-acf' );
define( 'ZVG_ELEMENTOR_T_PATH', $zvg_themes . '/zvg-elementor' );

define( 'ZVG_FSE_T_URI', '' );
define( 'ZVG_ACF_T_URI', '' );
define( 'ZVG_ELEMENTOR_T_URI', '' );

define( 'ZVG_FSE_VERSION', '' );
define( 'ZVG_ACF_VERSION', '' );
define( 'ZVG_ELEMENTOR_VERSION', '' );

// Declared false here only to have a value; phpstan.neon.dist lists them as dynamic, because
// flipping one is the whole point of the constant.
define( 'ZVG_FSE_USE_THEME_VERSION', false );
define( 'ZVG_ACF_USE_THEME_VERSION', false );
define( 'ZVG_ELEMENTOR_USE_THEME_VERSION', false );

// Defined by wp-config.php on a subdirectory multisite, never by the theme.
define( 'PATH_CURRENT_SITE', '/' );
