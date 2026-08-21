<?php
/**
 * Register the ZVG Member Bio block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_bio_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_member_bio_block() {

	wp_register_script(
		'zvg-fse-member-bio-editor',
		ZVG_FSE_T_URI . '/blocks/member-bio/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/member-bio/index.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/member-bio',
		array(
			'editor_script' => 'zvg-fse-member-bio-editor',
		)
	);
}
