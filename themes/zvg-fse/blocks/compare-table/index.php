<?php
/**
 * Register the ZVG Compare Table block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_compare_table_block' );

/**
 * Register the block from its metadata.
 */
function zvg_fse_register_compare_table_block() {
	register_block_type_from_metadata( ZVG_FSE_T_PATH . '/blocks/compare-table' );
}
