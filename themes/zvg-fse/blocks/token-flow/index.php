<?php
/**
 * Register the ZVG Token Flow block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_token_flow_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_token_flow_block() {

	wp_register_script(
		'zvg-fse-token-flow-editor',
		ZVG_FSE_T_URI . '/blocks/token-flow/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/token-flow/index.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/token-flow',
		array(
			'editor_script' => 'zvg-fse-token-flow-editor',
		)
	);
}
