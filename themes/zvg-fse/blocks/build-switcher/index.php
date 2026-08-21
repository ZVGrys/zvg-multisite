<?php
/**
 * Register the ZVG Build Switcher block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_build_switcher_block' );
add_filter( 'block_type_metadata_settings', 'zvg_fse_allow_switcher_in_navigation', 10, 2 );

/**
 * How long the resolved site list is cached.
 */
const ZVG_FSE_BUILDS_TTL = 12 * HOUR_IN_SECONDS;

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_build_switcher_block() {

	wp_register_script(
		'zvg-fse-build-switcher-editor',
		ZVG_FSE_T_URI . '/blocks/build-switcher/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/build-switcher/index.js' ),
		true
	);

	wp_add_inline_script(
		'zvg-fse-build-switcher-editor',
		'window.zvgFseBuildLabels = ' . wp_json_encode( array_values( zvg_fse_build_labels() ) ) . ';',
		'before'
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/build-switcher',
		array(
			'editor_script' => 'zvg-fse-build-switcher-editor',
		)
	);
}

/**
 * Let the switcher be inserted inside a Navigation block, where it rides along
 * into the responsive overlay.
 *
 * @param array $settings Block type registration arguments.
 * @param array $metadata Raw block.json contents.
 *
 * @return array
 */
function zvg_fse_allow_switcher_in_navigation( $settings, $metadata ) {
	if ( isset( $metadata['name'] ) && 'core/navigation' === $metadata['name'] && ! empty( $settings['allowed_blocks'] ) ) {
		$settings['allowed_blocks'][] = 'zvg-fse/build-switcher';
	}

	return $settings;
}

/**
 * The builds this network exposes, in the order the design lists them.
 *
 * @return array<string, string> Path segment => label.
 */
function zvg_fse_build_labels() {
	return apply_filters(
		'zvg_fse_build_labels',
		array(
			''          => _x( 'FSE', 'Build name', 'zvg-fse' ),
			'elementor' => _x( 'Elementor', 'Build name', 'zvg-fse' ),
			'acf'       => _x( 'ACF', 'Build name', 'zvg-fse' ),
		)
	);
}

/**
 * Resolve the network's sites to the builds they hold.
 *
 * @return array<int, array<string, mixed>> Each entry has blog_id, slug, label, url and current.
 */
function zvg_fse_build_sites() {
	$cached = get_transient( 'zvg_fse_build_sites' );

	if ( is_array( $cached ) && ( ! $cached || isset( $cached[0]['slug'] ) ) ) {
		return zvg_fse_mark_current_build( $cached );
	}

	$labels = zvg_fse_build_labels();
	$builds = array();

	if ( is_multisite() ) {
		$network_path = defined( 'PATH_CURRENT_SITE' ) ? PATH_CURRENT_SITE : '/';

		foreach ( get_sites( array( 'number' => 50 ) ) as $site ) {
			$segment = trim( substr( $site->path, strlen( $network_path ) ), '/' );

			if ( ! isset( $labels[ $segment ] ) ) {
				continue;
			}

			$builds[ $segment ] = array(
				'blog_id' => (int) $site->blog_id,
				'slug'    => $segment ? $segment : 'fse',
				'label'   => $labels[ $segment ],
				'url'     => get_home_url( (int) $site->blog_id, '/' ),
			);
		}
	}

	if ( empty( $builds ) ) {
		$builds[''] = array(
			'blog_id' => get_current_blog_id(),
			'slug'    => 'fse',
			'label'   => $labels[''],
			'url'     => home_url( '/' ),
		);
	}

	$ordered = array();

	foreach ( array_keys( $labels ) as $segment ) {
		if ( isset( $builds[ $segment ] ) ) {
			$ordered[] = $builds[ $segment ];
		}
	}

	set_transient( 'zvg_fse_build_sites', $ordered, ZVG_FSE_BUILDS_TTL );

	return zvg_fse_mark_current_build( $ordered );
}

/**
 * Flag the entry the visitor is on. Kept out of the cache so one site's request
 * cannot mark the wrong entry for another.
 *
 * @param array<int, array<string, mixed>> $builds Resolved builds.
 *
 * @return array<int, array<string, mixed>>
 */
function zvg_fse_mark_current_build( $builds ) {
	$current = get_current_blog_id();

	foreach ( $builds as $index => $build ) {
		$builds[ $index ]['current'] = ( (int) $build['blog_id'] === $current );
	}

	return $builds;
}
