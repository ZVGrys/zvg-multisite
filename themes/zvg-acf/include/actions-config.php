<?php
/**
 * Asset registration.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'zvg_acf_enqueue_scripts', 999 );
add_action( 'wp_head', 'zvg_acf_flag_script_support', 1 );
add_action( 'wp_head', 'zvg_acf_preload_fonts', 2 );
add_action( 'init', 'zvg_acf_drop_emoji_support' );
add_filter( 'wpcf7_load_js', 'zvg_acf_page_has_contact_form' );
add_filter( 'wpcf7_load_css', 'zvg_acf_page_has_contact_form' );

/**
 * Contact Form 7 loads its script and stylesheet on every page of the site.
 *
 * @return bool
 */
function zvg_acf_page_has_contact_form() {
	if ( zvg_acf_is_sections_page() && in_array( 'contact', zvg_acf_sections(), true ) ) {
		return true;
	}

	return is_singular() && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'contact-form-7' );
}

/**
 * Mark the document as scripted before the body is painted.
 */
function zvg_acf_flag_script_support() {
	if ( is_admin() ) {
		return;
	}

	wp_print_inline_script_tag( 'document.documentElement.classList.add("zvg-acf-js");' );
}

/**
 * Drop the emoji detection script and its styles.
 */
function zvg_acf_drop_emoji_support() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

/**
 * Resolve asset version.
 *
 * @param string $relative_path Path relative to the theme root, starting with '/'.
 *
 * @return string|null
 */
function zvg_acf_get_asset_version( $relative_path ) {
	$use_theme_version = defined( 'ZVG_ACF_USE_THEME_VERSION' ) && ZVG_ACF_USE_THEME_VERSION;

	if ( $use_theme_version && defined( 'ZVG_ACF_VERSION' ) ) {
		$version = ZVG_ACF_VERSION;
	} else {
		$version = null;

		if ( ! empty( $relative_path ) ) {
			$file = ZVG_ACF_T_PATH . $relative_path;

			if ( file_exists( $file ) ) {
				$mtime = filemtime( $file );

				if ( $mtime ) {
					$version = (string) $mtime;
				}
			}
		}

		if ( null === $version && defined( 'ZVG_ACF_VERSION' ) ) {
			$version = ZVG_ACF_VERSION;
		}
	}

	/**
	 * Filter the resolved asset version.
	 *
	 * @param string|null $version       Calculated version string.
	 * @param string      $relative_path Relative path originally requested.
	 */
	return apply_filters( 'zvg_acf_asset_version', $version, $relative_path );
}

/**
 * Enqueue one of the theme's stylesheets.
 *
 * @param string   $name     Handle suffix.
 * @param string   $relative Path relative to the theme root, starting with '/'.
 * @param string[] $deps     Handles this stylesheet depends on.
 */
function zvg_acf_enqueue_style( $name, $relative, $deps = array() ) {
	wp_enqueue_style(
		'zvg-acf-' . $name,
		ZVG_ACF_T_URI . $relative,
		$deps,
		zvg_acf_get_asset_version( $relative )
	);
}

/**
 * Preload the faces used above the fold.
 */
function zvg_acf_preload_fonts() {
	if ( is_admin() ) {
		return;
	}

	$faces = array(
		'space-grotesk-latin-400-normal.woff2',
		'space-grotesk-latin-600-normal.woff2',
		'ibm-plex-mono-latin-600-normal.woff2',
	);

	foreach ( $faces as $face ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( ZVG_ACF_T_URI . '/assets/fonts/' . $face )
		);
	}
}

/**
 * The stylesheet and script of every section the entry is built from.
 */
function zvg_acf_enqueue_section_assets() {
	if ( ! zvg_acf_is_sections_page() ) {
		return;
	}

	foreach ( array_unique( zvg_acf_sections() ) as $section ) {
		$style = '/sections/' . $section . '/css/' . $section . '.css';

		if ( file_exists( ZVG_ACF_T_PATH . $style ) ) {
			zvg_acf_enqueue_style( $section, $style, array( 'zvg-acf-general', 'zvg-acf-sections' ) );
		}

		if ( 'blog' === $section ) {
			wp_enqueue_style( 'zvg-acf-post-card' );
		}

		$script = '/sections/' . $section . '/js/' . $section . '.min.js';

		if ( file_exists( ZVG_ACF_T_PATH . $script ) ) {
			wp_enqueue_script(
				'zvg-acf-' . $section,
				ZVG_ACF_T_URI . $script,
				array(),
				zvg_acf_get_asset_version( $script ),
				true
			);
		}
	}
}

/**
 * Front-end assets.
 */
function zvg_acf_enqueue_scripts() {
	if ( is_admin() ) {
		return;
	}

	zvg_acf_enqueue_style( 'general', '/assets/css/general.css' );
	zvg_acf_enqueue_style( 'typography', '/assets/css/typography.css', array( 'zvg-acf-general' ) );
	zvg_acf_enqueue_style( 'main', '/assets/css/main.css', array( 'zvg-acf-general' ) );

	wp_register_style(
		'zvg-acf-post-card',
		ZVG_ACF_T_URI . '/assets/css/blog/post-card.css',
		array( 'zvg-acf-general' ),
		zvg_acf_get_asset_version( '/assets/css/blog/post-card.css' )
	);

	wp_register_style(
		'zvg-acf-pagination',
		ZVG_ACF_T_URI . '/assets/css/blog/pagination.css',
		array( 'zvg-acf-general' ),
		zvg_acf_get_asset_version( '/assets/css/blog/pagination.css' )
	);

	if ( is_404() ) {
		zvg_acf_enqueue_style( 'error-page', '/assets/css/error-page.css', array( 'zvg-acf-general' ) );
	}

	if ( is_singular() ) {
		if ( zvg_acf_is_sections_page() ) {
			zvg_acf_enqueue_style( 'sections', '/assets/css/sections.css', array( 'zvg-acf-general' ) );
		} else {
			zvg_acf_enqueue_style( 'singular', '/assets/css/singular.css', array( 'zvg-acf-general' ) );
		}
	}

	if ( is_single() ) {
		zvg_acf_enqueue_style( 'blog-single', '/assets/css/blog/blog-single.css', array( 'zvg-acf-general' ) );

		wp_enqueue_style( 'zvg-acf-share' );
		wp_enqueue_script( 'zvg-acf-share' );
	}

	if ( is_home() || is_archive() || is_search() ) {
		zvg_acf_enqueue_style( 'blog-list', '/assets/css/blog/blog-list.css', array( 'zvg-acf-general' ) );

		wp_enqueue_style( 'zvg-acf-post-card' );
		wp_enqueue_style( 'zvg-acf-pagination' );
	}

	if ( is_search() || is_404() ) {
		zvg_acf_enqueue_style( 'search-form', '/assets/css/search-form.css', array( 'zvg-acf-general' ) );
	}

	zvg_acf_enqueue_section_assets();

	wp_enqueue_script(
		'zvg-acf-navigation',
		ZVG_ACF_T_URI . '/assets/js/navigation.min.js',
		array(),
		zvg_acf_get_asset_version( '/assets/js/navigation.min.js' ),
		true
	);
}
