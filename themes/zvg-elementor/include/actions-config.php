<?php
/**
 * Asset registration.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'zvg_elementor_enqueue_scripts', 999 );
add_action( 'wp_head', 'zvg_elementor_flag_script_support', 1 );

/*
 * Load only the core blocks a page actually renders, instead of the whole block library.
 *
 * Block themes get this for free since WP 5.9; a classic theme has to opt in. Post content on
 * this build is a handful of paragraphs, and without the split WordPress still ships the full
 * block-library stylesheet with it.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Mark the document as scripted before the body is painted.
 */
function zvg_elementor_flag_script_support() {
	if ( is_admin() ) {
		return;
	}

	wp_print_inline_script_tag( 'document.documentElement.classList.add("zvg-elementor-js");' );
}

/**
 * Resolve asset version.
 *
 * @param string $relative_path Path relative to the theme root, starting with '/'.
 *
 * @return string|null
 */
function zvg_elementor_get_asset_version( $relative_path ) {
	$use_theme_version = defined( 'ZVG_ELEMENTOR_USE_THEME_VERSION' ) && ZVG_ELEMENTOR_USE_THEME_VERSION;

	if ( $use_theme_version && defined( 'ZVG_ELEMENTOR_VERSION' ) ) {
		$version = ZVG_ELEMENTOR_VERSION;
	} else {
		$version = null;

		if ( ! empty( $relative_path ) ) {
			$file = ZVG_ELEMENTOR_T_PATH . $relative_path;

			if ( file_exists( $file ) ) {
				$mtime = filemtime( $file );

				if ( $mtime ) {
					$version = (string) $mtime;
				}
			}
		}

		if ( null === $version && defined( 'ZVG_ELEMENTOR_VERSION' ) ) {
			$version = ZVG_ELEMENTOR_VERSION;
		}
	}

	/**
	 * Filter the resolved asset version.
	 *
	 * @param string|null $version       Calculated version string.
	 * @param string      $relative_path Relative path originally requested.
	 */
	return apply_filters( 'zvg_elementor_asset_version', $version, $relative_path );
}

/**
 * Enqueue one of the theme's stylesheets.
 *
 * @param string   $name     Handle suffix.
 * @param string   $relative Path relative to the theme root, starting with '/'.
 * @param string[] $deps     Handles this stylesheet depends on.
 */
function zvg_elementor_enqueue_style( $name, $relative, $deps = array() ) {
	wp_enqueue_style(
		'zvg-elementor-' . $name,
		ZVG_ELEMENTOR_T_URI . $relative,
		$deps,
		zvg_elementor_get_asset_version( $relative )
	);
}

/**
 * Front-end assets.
 */
function zvg_elementor_enqueue_scripts() {
	if ( is_admin() ) {
		return;
	}

	$blog_page      = is_home() || is_archive() || is_search();
	$elementor_page = zvg_elementor_is_builder_page();

	zvg_elementor_enqueue_style( 'general', '/assets/css/general.css' );

	wp_register_style(
		'zvg-elementor-switcher',
		ZVG_ELEMENTOR_T_URI . '/assets/css/switcher.css',
		array( 'zvg-elementor-general' ),
		zvg_elementor_get_asset_version( '/assets/css/switcher.css' )
	);

	wp_register_style(
		'zvg-elementor-share',
		ZVG_ELEMENTOR_T_URI . '/assets/css/share.css',
		array( 'zvg-elementor-general' ),
		zvg_elementor_get_asset_version( '/assets/css/share.css' )
	);

	wp_register_style(
		'zvg-elementor-post-card',
		ZVG_ELEMENTOR_T_URI . '/assets/css/blog/post-card.css',
		array( 'zvg-elementor-general' ),
		zvg_elementor_get_asset_version( '/assets/css/blog/post-card.css' )
	);

	wp_register_style(
		'zvg-elementor-pagination',
		ZVG_ELEMENTOR_T_URI . '/assets/css/blog/pagination.css',
		array( 'zvg-elementor-general' ),
		zvg_elementor_get_asset_version( '/assets/css/blog/pagination.css' )
	);

	wp_register_script(
		'zvg-elementor-share',
		ZVG_ELEMENTOR_T_URI . '/assets/js/share.min.js',
		array(),
		zvg_elementor_get_asset_version( '/assets/js/share.min.js' ),
		true
	);

	if ( ! zvg_elementor_has_location( 'header' ) ) {
		zvg_elementor_enqueue_style( 'header', '/assets/css/header.css', array( 'zvg-elementor-general' ) );
		wp_enqueue_style( 'zvg-elementor-switcher' );
	}

	if ( ! zvg_elementor_has_location( 'footer' ) ) {
		zvg_elementor_enqueue_style( 'footer', '/assets/css/footer.css', array( 'zvg-elementor-general' ) );
	}

	if ( ! $elementor_page || $blog_page ) {
		zvg_elementor_enqueue_style( 'typography', '/assets/css/typography.css', array( 'zvg-elementor-general' ) );
	}

	if ( is_404() && ! zvg_elementor_owns_content() ) {
		zvg_elementor_enqueue_style( 'error-page', '/assets/css/error-page.css', array( 'zvg-elementor-general' ) );
	}

	if ( is_single() && ! zvg_elementor_owns_content() ) {
		zvg_elementor_enqueue_style( 'blog-single', '/assets/css/blog/blog-single.css', array( 'zvg-elementor-general' ) );

		wp_enqueue_style( 'zvg-elementor-share' );
		wp_enqueue_script( 'zvg-elementor-share' );
	}

	if ( $blog_page && ! zvg_elementor_owns_content() ) {
		zvg_elementor_enqueue_style( 'blog-list', '/assets/css/blog/blog-list.css', array( 'zvg-elementor-general' ) );

		wp_enqueue_style( 'zvg-elementor-post-card' );
		wp_enqueue_style( 'zvg-elementor-pagination' );
	}

	if ( has_nav_menu( 'primary' ) && ! zvg_elementor_has_location( 'header' ) ) {
		wp_enqueue_script(
			'zvg-elementor-navigation',
			ZVG_ELEMENTOR_T_URI . '/assets/js/navigation.min.js',
			array(),
			zvg_elementor_get_asset_version( '/assets/js/navigation.min.js' ),
			true
		);
	}
}
