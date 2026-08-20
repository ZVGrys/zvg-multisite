<?php
/**
 * Block style variations.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_block_styles' );

/**
 * Register the theme's block style variations.
 */
function zvg_fse_block_styles() {

	$styles = array(
		'core/button'     => array(
			array(
				'name'  => 'outline-dark',
				'label' => __( 'Outline (dark)', 'zvg-fse' ),
			),
		),
		'core/paragraph'  => array(
			array(
				'name'  => 'eyebrow',
				'label' => __( 'Eyebrow', 'zvg-fse' ),
			),
			array(
				'name'  => 'lead',
				'label' => __( 'Lead', 'zvg-fse' ),
			),
			array(
				'name'  => 'arrow-link',
				'label' => __( 'Arrow link', 'zvg-fse' ),
			),
			array(
				'name'  => 'section-intro',
				'label' => __( 'Section intro', 'zvg-fse' ),
			),
			array(
				'name'  => 'note',
				'label' => __( 'Note', 'zvg-fse' ),
			),
		),
		'core/read-more'  => array(
			array(
				'name'  => 'arrow-link',
				'label' => __( 'Arrow link', 'zvg-fse' ),
			),
		),
		'core/post-terms' => array(
			array(
				'name'  => 'eyebrow',
				'label' => __( 'Eyebrow', 'zvg-fse' ),
			),
		),
	);

	foreach ( $styles as $block_name => $variations ) {
		foreach ( $variations as $variation ) {
			register_block_style( $block_name, $variation );
		}
	}
}
