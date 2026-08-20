<?php
/**
 * Pattern categories.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_pattern_categories' );

/**
 * Register the pattern categories used by patterns/*.php.
 */
function zvg_fse_pattern_categories() {

	$categories = array(
		'zvg-fse-section' => __( 'ZVG: Sections', 'zvg-fse' ),
	);

	foreach ( $categories as $slug => $label ) {
		register_block_pattern_category( $slug, array( 'label' => $label ) );
	}
}
