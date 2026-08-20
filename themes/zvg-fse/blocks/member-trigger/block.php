<?php
/**
 * ZVG Member Profile — front-end render.
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

$zvg_fse_profile = get_post_meta( $zvg_fse_post_id, '_zvg_member_profile', true );

if ( '' === trim( $zvg_fse_profile ) ) {
	return;
}

$zvg_fse_profile = apply_filters( 'the_content', $zvg_fse_profile );
$zvg_fse_name    = get_the_title( $zvg_fse_post_id );
$zvg_fse_link    = get_post_meta( $zvg_fse_post_id, '_zvg_member_link', true );

$zvg_fse_toggle_label = isset( $attributes['toggleLabel'] ) ? trim( $attributes['toggleLabel'] ) : '';

if ( '' === $zvg_fse_toggle_label ) {
	$zvg_fse_toggle_label = _x( 'Read profile', 'Team member button', 'zvg-fse' );
}

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<button class="zvg-fse-member__toggle" type="button" data-member-open data-member-link="<?php echo esc_attr( $zvg_fse_link ); ?>" hidden>
		<?php echo esc_html( $zvg_fse_toggle_label ); ?>
		<span class="screen-reader-text"><?php echo esc_html( ': ' . $zvg_fse_name ); ?></span>
	</button>

	<div class="zvg-fse-dialog__profile" data-member-profile>
		<?php echo $zvg_fse_profile; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- post content, filtered by the_content. ?>
	</div>
</div>
