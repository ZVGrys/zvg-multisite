<?php
/**
 * Register the ZVG Compare Table block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_compare_table_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_compare_table_block() {

	wp_register_script(
		'zvg-fse-compare-table-editor',
		ZVG_FSE_T_URI . '/blocks/compare-table/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/compare-table/index.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/compare-table',
		array(
			'editor_script' => 'zvg-fse-compare-table-editor',
		)
	);
}
