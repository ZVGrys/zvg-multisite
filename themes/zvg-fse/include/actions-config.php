<?php
/**
 * Asset registration.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'zvg_fse_enqueue_scripts', 999 );
add_action( 'init', 'zvg_fse_enqueue_block_styles' );
add_action( 'init', 'zvg_fse_enqueue_class_styles' );
add_action( 'wp_head', 'zvg_fse_flag_script_support', 1 );
add_action( 'wp_head', 'zvg_fse_preload_fonts', 2 );
add_action( 'init', 'zvg_fse_drop_emoji_support' );
add_action( 'init', 'zvg_fse_translate_editor_scripts', 20 );
add_filter( 'wp_omit_loading_attr_threshold', 'zvg_fse_lazy_load_every_image' );

/**
 * Mark the document as scripted before the body is painted.
 */
function zvg_fse_flag_script_support() {
	if ( is_admin() ) {
		return;
	}

	wp_print_inline_script_tag( 'document.documentElement.classList.add("zvg-fse-js");' );
}

/**
 * Hand the editor scripts their translations, so the strings inside them can be
 * translated like the PHP ones.
 */
function zvg_fse_translate_editor_scripts() {
	foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $block_type ) {
		if ( 0 !== strpos( $name, 'zvg-fse/' ) ) {
			continue;
		}

		foreach ( (array) $block_type->editor_script_handles as $handle ) {
			wp_set_script_translations( $handle, 'zvg-fse', ZVG_FSE_T_PATH . '/languages' );
		}
	}
}

/**
 * Drop the emoji detection script and its styles.
 */
function zvg_fse_drop_emoji_support() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

/**
 * The landing carries no image above the fold, so none of them are worth loading eagerly.
 *
 * @param int $threshold Number of images that skip lazy loading.
 *
 * @return int
 */
function zvg_fse_lazy_load_every_image( $threshold ) {
	return is_front_page() ? 0 : $threshold;
}

/**
 * Preload the faces used above the fold.
 */
function zvg_fse_preload_fonts() {
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
			esc_url( ZVG_FSE_T_URI . '/assets/fonts/' . $face )
		);
	}
}

/**
 * Resolve asset version.
 *
 * @param string $relative_path Path relative to the theme root, starting with '/'.
 *
 * @return string|null
 */
function zvg_fse_get_asset_version( $relative_path ) {
	$use_theme_version = defined( 'ZVG_FSE_USE_THEME_VERSION' ) && ZVG_FSE_USE_THEME_VERSION;

	if ( $use_theme_version && defined( 'ZVG_FSE_VERSION' ) ) {
		$version = ZVG_FSE_VERSION;
	} else {
		$version = null;

		if ( ! empty( $relative_path ) ) {
			$file = ZVG_FSE_T_PATH . $relative_path;

			if ( file_exists( $file ) ) {
				$mtime = filemtime( $file );

				if ( $mtime ) {
					$version = (string) $mtime;
				}
			}
		}

		if ( null === $version && defined( 'ZVG_FSE_VERSION' ) ) {
			$version = ZVG_FSE_VERSION;
		}
	}

	/**
	 * Filter the resolved asset version.
	 *
	 * @param string|null $version       Calculated version string.
	 * @param string      $relative_path Relative path originally requested.
	 */
	return apply_filters( 'zvg_fse_asset_version', $version, $relative_path );
}

/**
 * Enqueue one of the theme's stylesheets.
 *
 * @param string   $name     Handle suffix.
 * @param string   $relative Path relative to the theme root, starting with '/'.
 * @param string[] $deps     Handles this stylesheet depends on.
 */
function zvg_fse_enqueue_style( $name, $relative, $deps = array() ) {
	wp_enqueue_style(
		'zvg-fse-' . $name,
		ZVG_FSE_T_URI . $relative,
		$deps,
		zvg_fse_get_asset_version( $relative )
	);
}

/**
 * Front-end assets.
 */
function zvg_fse_enqueue_scripts() {
	if ( is_admin() ) {
		return;
	}

	zvg_fse_enqueue_style( 'main', '/assets/css/main.css' );

	if ( is_404() ) {
		zvg_fse_enqueue_style( '404', '/assets/css/templates/404.css', array( 'zvg-fse-main' ) );
	}

	if ( is_singular( 'post' ) ) {
		zvg_fse_enqueue_style( 'single', '/assets/css/templates/single.css', array( 'zvg-fse-main' ) );
	}

	wp_enqueue_script(
		'zvg-fse-script',
		ZVG_FSE_T_URI . '/assets/js/script.min.js',
		array(),
		zvg_fse_get_asset_version( '/assets/js/script.min.js' ),
		true
	);
}

/**
 * Per-block styles, loaded only when the block renders.
 *
 * @return array<string, string> Block name => stylesheet basename.
 */
function zvg_fse_block_stylesheets() {
	return array(
		'core/button'        => 'blocks/button',
		'core/navigation'    => 'blocks/navigation',
		'core/post-template' => 'blocks/post-template',
		'core/search'        => 'blocks/search',
	);
}

/**
 * Styles keyed to a class, loaded only when a block carrying it renders.
 *
 * @return array<string, array<string, string>> Stylesheet => block name and class.
 */
function zvg_fse_class_stylesheets() {
	return array(
		'blocks/button-outline' => array(
			'block' => 'core/button',
			'class' => 'is-style-outline-dark',
		),
		'sections/base'         => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-section',
		),
		'sections/hero'         => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-hero',
		),
		'sections/builds'       => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-builds',
		),
		'sections/flow'         => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-flow',
		),
		'sections/editors'      => array(
			'block'  => 'core/group',
			'class'  => 'zvg-fse-editors',
			'script' => 'editors-track',
		),
		'sections/measured'     => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-measured',
		),
		'sections/team'         => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-team',
		),
		'sections/blog'         => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-post',
		),
		'sections/contact'      => array(
			'block' => 'core/group',
			'class' => 'zvg-fse-contact',
		),
	);
}

add_filter( 'wpcf7_load_js', 'zvg_fse_page_has_contact_form' );
add_filter( 'wpcf7_load_css', 'zvg_fse_page_has_contact_form' );
add_filter( 'render_block_core/shortcode', 'zvg_fse_render_shortcode_block', 10, 2 );

/**
 * Contact Form 7 loads its script and stylesheet on every page of the site.
 *
 * @return bool
 */
function zvg_fse_page_has_contact_form() {
	global $_wp_current_template_content;

	if ( is_string( $_wp_current_template_content )
		&& false !== strpos( $_wp_current_template_content, 'zvg-fse/section-contact' ) ) {
		return true;
	}

	return is_singular() && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'contact-form-7' );
}

/**
 * Run shortcodes placed in a template.
 *
 * @param string $html  Rendered block markup.
 * @param array  $block Parsed block.
 *
 * @return string
 */
function zvg_fse_render_shortcode_block( $html, $block ) {
	$content = isset( $block['innerHTML'] ) ? $block['innerHTML'] : '';

	return '' === trim( $content ) ? $html : do_shortcode( trim( $content ) );
}

/**
 * Register the class-triggered assets.
 */
function zvg_fse_enqueue_class_styles() {
	$by_block = array();

	foreach ( zvg_fse_class_stylesheets() as $file => $args ) {
		$relative = '/assets/css/' . $file . '.css';
		$handle   = 'zvg-fse-' . str_replace( '/', '-', $file );

		wp_register_style( $handle, ZVG_FSE_T_URI . $relative, array(), zvg_fse_get_asset_version( $relative ) );
		wp_style_add_data( $handle, 'path', ZVG_FSE_T_PATH . $relative );

		$script = isset( $args['script'] ) ? $args['script'] : '';

		if ( '' !== $script ) {
			$script_path = '/assets/js/' . $script . '.min.js';

			wp_register_script(
				'zvg-fse-' . $script,
				ZVG_FSE_T_URI . $script_path,
				array(),
				zvg_fse_get_asset_version( $script_path ),
				true
			);
		}

		$by_block[ $args['block'] ][] = array(
			'class'  => $args['class'],
			'handle' => $handle,
			'script' => $script,
		);
	}

	foreach ( $by_block as $block_name => $assets ) {
		add_filter(
			'render_block_' . $block_name,
			static function ( $html, $block ) use ( $assets ) {
				$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

				if ( '' === $class ) {
					return $html;
				}

				foreach ( $assets as $asset ) {
					if ( false === strpos( $class, $asset['class'] ) ) {
						continue;
					}

					wp_enqueue_style( $asset['handle'] );

					if ( '' !== $asset['script'] ) {
						wp_enqueue_script( 'zvg-fse-' . $asset['script'] );
					}
				}

				return $html;
			},
			10,
			2
		);
	}
}

/**
 * Register the per-block stylesheets.
 */
function zvg_fse_enqueue_block_styles() {
	foreach ( zvg_fse_block_stylesheets() as $block => $file ) {
		$relative = '/assets/css/' . $file . '.css';

		wp_enqueue_block_style(
			$block,
			array(
				'handle' => 'zvg-fse-block-' . basename( $file ),
				'src'    => ZVG_FSE_T_URI . $relative,
				'path'   => ZVG_FSE_T_PATH . $relative,
				'ver'    => zvg_fse_get_asset_version( $relative ),
			)
		);
	}
}
