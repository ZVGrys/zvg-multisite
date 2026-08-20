<?php
/**
 * ZVG Share Links — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();

if ( ! $zvg_fse_post_id ) {
	return;
}

$zvg_fse_url   = (string) get_permalink( $zvg_fse_post_id );
$zvg_fse_title = get_the_title( $zvg_fse_post_id );

if ( '' === $zvg_fse_url ) {
	return;
}

$zvg_fse_chosen = array();

foreach ( zvg_fse_share_networks() as $zvg_fse_key => $zvg_fse_network ) {
	if ( empty( $attributes[ $zvg_fse_key ] ) ) {
		continue;
	}

	$zvg_fse_chosen[] = array(
		'name'   => $zvg_fse_network['name'],
		'icon'   => $zvg_fse_network['icon'],
		'stroke' => ! empty( $zvg_fse_network['stroke'] ),
		'href'   => sprintf( $zvg_fse_network['url'], rawurlencode( $zvg_fse_url ), rawurlencode( $zvg_fse_title ) ),
	);
}

$zvg_fse_copy = ! empty( $attributes['copy'] );

if ( empty( $zvg_fse_chosen ) && ! $zvg_fse_copy ) {
	return;
}

$zvg_fse_label = isset( $attributes['label'] ) ? trim( $attributes['label'] ) : '';

if ( '' === $zvg_fse_label ) {
	$zvg_fse_label = _x( 'Share this post', 'Share links label', 'zvg-fse' );
}

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<p class="wp-block-zvg-fse-post-share__label"><?php echo esc_html( $zvg_fse_label ); ?></p>

	<ul class="wp-block-zvg-fse-post-share__list">
		<?php foreach ( $zvg_fse_chosen as $zvg_fse_item ) : ?>
			<li class="wp-block-zvg-fse-post-share__item">
				<a class="wp-block-zvg-fse-post-share__link" href="<?php echo esc_url( $zvg_fse_item['href'] ); ?>" target="_blank" rel="noopener noreferrer">
					<svg class="wp-block-zvg-fse-post-share__icon<?php echo $zvg_fse_item['stroke'] ? ' is-stroked' : ''; ?>" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="<?php echo esc_attr( $zvg_fse_item['icon'] ); ?>" />
					</svg>
					<?php echo esc_html( $zvg_fse_item['name'] ); ?>
					<span class="screen-reader-text"><?php echo esc_html_x( '(opens in a new tab)', 'Share link', 'zvg-fse' ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>

		<?php if ( $zvg_fse_copy ) : ?>
			<li class="wp-block-zvg-fse-post-share__item">
				<button class="wp-block-zvg-fse-post-share__link wp-block-zvg-fse-post-share__copy" type="button" data-share-copy="<?php echo esc_url( $zvg_fse_url ); ?>" data-share-done="<?php echo esc_attr_x( 'Link copied', 'Share links', 'zvg-fse' ); ?>">
					<svg class="wp-block-zvg-fse-post-share__icon is-stroked" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="<?php echo esc_attr( ZVG_FSE_SHARE_COPY_ICON ); ?>" />
					</svg>
					<span class="wp-block-zvg-fse-post-share__copy-label" aria-live="polite"><?php echo esc_html_x( 'Copy link', 'Share links', 'zvg-fse' ); ?></span>
				</button>
			</li>
		<?php endif; ?>
	</ul>
</div>
