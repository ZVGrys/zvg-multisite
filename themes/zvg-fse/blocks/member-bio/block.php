<?php
/**
 * ZVG Member Bio — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;

if ( ! $zvg_fse_post_id ) {
	return;
}

$zvg_fse_bio = (string) get_post_meta( $zvg_fse_post_id, '_zvg_member_bio', true );

if ( '' === trim( $zvg_fse_bio ) ) {
	return;
}

?>
<p <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>><?php echo esc_html( $zvg_fse_bio ); ?></p>
