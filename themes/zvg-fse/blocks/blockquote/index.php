<?php
/**
 * Register the ZVG Blockquote block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_blockquote_block' );

/**
 * Register the block from its metadata.
 */
function zvg_fse_register_blockquote_block() {
	register_block_type_from_metadata( ZVG_FSE_T_PATH . '/blocks/blockquote' );
}
