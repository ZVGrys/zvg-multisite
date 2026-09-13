<?php
/**
 * Register the ZVG Member Profile block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_trigger_block' );

/**
 * Register the block from its metadata.
 */
function zvg_fse_register_member_trigger_block() {
	register_block_type_from_metadata( ZVG_FSE_T_PATH . '/blocks/member-trigger' );
}
