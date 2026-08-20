<?php
/**
 * Register the ZVG Member Profile block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_trigger_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_member_trigger_block() {

	wp_register_script(
		'zvg-fse-member-trigger-editor',
		ZVG_FSE_T_URI . '/blocks/member-trigger/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/member-trigger/index.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/member-trigger',
		array(
			'editor_script' => 'zvg-fse-member-trigger-editor',
		)
	);
}
