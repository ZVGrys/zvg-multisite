<?php
/**
 * Register the ZVG Stat List block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_stat_list_block' );

/**
 * Register the block from its metadata.
 */
function zvg_fse_register_stat_list_block() {
	register_block_type_from_metadata( ZVG_FSE_T_PATH . '/blocks/stat-list' );
}
