<?php
/**
 * Register the ZVG Share Links block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_post_share_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_post_share_block() {

	wp_register_script(
		'zvg-fse-post-share-editor',
		ZVG_FSE_T_URI . '/blocks/post-share/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/post-share/index.js' ),
		true
	);

	$zvg_fse_for_editor = array();

	foreach ( zvg_fse_share_networks() as $zvg_fse_key => $zvg_fse_network ) {
		$zvg_fse_for_editor[] = array(
			'key'    => $zvg_fse_key,
			'name'   => $zvg_fse_network['name'],
			'icon'   => $zvg_fse_network['icon'],
			'stroke' => ! empty( $zvg_fse_network['stroke'] ),
		);
	}

	$zvg_fse_for_editor[] = array(
		'key'    => 'copy',
		'name'   => _x( 'Copy link', 'Share links', 'zvg-fse' ),
		'icon'   => ZVG_FSE_SHARE_COPY_ICON,
		'stroke' => true,
	);

	wp_add_inline_script(
		'zvg-fse-post-share-editor',
		'window.zvgFseShareNetworks = ' . wp_json_encode( $zvg_fse_for_editor ) . ';',
		'before'
	);

	// Registered by hand, like the editor script above — block.json's own
	// "viewScript" auto-registration versions by the WP/Gutenberg version, not
	// by file mtime, so an edit to view.js was served stale from the browser
	// cache until the next unrelated core/Gutenberg update.
	wp_register_script(
		'zvg-fse-post-share-view',
		ZVG_FSE_T_URI . '/blocks/post-share/js/view.min.js',
		array(),
		zvg_fse_get_asset_version( '/blocks/post-share/js/view.min.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/post-share',
		array(
			'editor_script'       => 'zvg-fse-post-share-editor',
			'view_script_handles' => array( 'zvg-fse-post-share-view' ),
		)
	);
}

/**
 * The networks the block can hand a post to.
 *
 * @return array<string, array<string, string>> Attribute key => name, share address and icon path.
 */
function zvg_fse_share_networks() {
	return array(
		'linkedin' => array(
			'name' => 'LinkedIn',
			'url'  => 'https://www.linkedin.com/sharing/share-offsite/?url=%1$s',
			'icon' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
		),
		'facebook' => array(
			'name' => 'Facebook',
			'url'  => 'https://www.facebook.com/sharer/sharer.php?u=%1$s',
			'icon' => 'M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647z',
		),
		'x'        => array(
			'name' => 'X',
			'url'  => 'https://x.com/intent/post?url=%1$s&text=%2$s',
			'icon' => 'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932zM17.61 20.644h2.039L6.486 3.24H4.298z',
		),
		'telegram' => array(
			'name' => 'Telegram',
			'url'  => 'https://t.me/share/url?url=%1$s&text=%2$s',
			'icon' => 'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z',
		),
		'whatsapp' => array(
			'name' => 'WhatsApp',
			'url'  => 'https://wa.me/?text=%2$s%%20%1$s',
			'icon' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z',
		),
		'email'    => array(
			'name'   => _x( 'Email', 'Share network', 'zvg-fse' ),
			'url'    => 'mailto:?subject=%2$s&body=%1$s',
			'icon'   => 'M3 6h18v12H3z M3 6l9 6 9-6',
			'stroke' => true,
		),
	);
}

/**
 * The icon of the copy button, which is not a network and has no share address.
 */
const ZVG_FSE_SHARE_COPY_ICON = 'M10 13a5 5 0 0 0 7.07 0l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71 M14 11a5 5 0 0 0-7.07 0l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71';
