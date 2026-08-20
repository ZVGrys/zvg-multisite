<?php
/**
 * Register the ZVG Member Dialog block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_dialog_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_member_dialog_block() {

	wp_register_script(
		'zvg-fse-member-dialog-editor',
		ZVG_FSE_T_URI . '/blocks/member-dialog/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/member-dialog/index.js' ),
		true
	);

	// Registered by hand, like the editor script above — block.json's own
	// "viewScript" auto-registration versions by the WP/Gutenberg version, not
	// by file mtime, so an edit to view.js was served stale from the browser
	// cache until the next unrelated core/Gutenberg update.
	wp_register_script(
		'zvg-fse-member-dialog-view',
		ZVG_FSE_T_URI . '/blocks/member-dialog/js/view.min.js',
		array(),
		zvg_fse_get_asset_version( '/blocks/member-dialog/js/view.min.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/member-dialog',
		array(
			'editor_script'       => 'zvg-fse-member-dialog-editor',
			'view_script_handles' => array( 'zvg-fse-member-dialog-view' ),
		)
	);
}
