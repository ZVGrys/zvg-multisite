<?php
/**
 * Register the ZVG Member Dialog block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_dialog_block' );

/**
 * Register the block from its metadata.
 */
function zvg_fse_register_member_dialog_block() {
	register_block_type_from_metadata( ZVG_FSE_T_PATH . '/blocks/member-dialog' );
}
