<?php
/**
 * ZVG Member Dialog — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_close_label = isset( $attributes['closeLabel'] ) ? trim( $attributes['closeLabel'] ) : '';

if ( '' === $zvg_fse_close_label ) {
	$zvg_fse_close_label = _x( 'Close', 'Team dialog button', 'zvg-fse' );
}

$zvg_fse_link_text = isset( $attributes['linkText'] ) ? trim( $attributes['linkText'] ) : '';

if ( '' === $zvg_fse_link_text ) {
	$zvg_fse_link_text = _x( 'Get in touch', 'Team dialog link', 'zvg-fse' );
}

$zvg_fse_name_id = wp_unique_id( 'zvg-fse-dialog-name-' );

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<dialog class="zvg-fse-dialog" data-member-dialog closedby="any" aria-labelledby="<?php echo esc_attr( $zvg_fse_name_id ); ?>">
		<div class="zvg-fse-dialog__head">
			<div>
				<h3 class="zvg-fse-dialog__name" id="<?php echo esc_attr( $zvg_fse_name_id ); ?>" data-member-name></h3>
				<p class="zvg-fse-dialog__role" data-member-role></p>
			</div>

			<button class="zvg-fse-dialog__close" type="button" data-member-close>
				<?php echo esc_html( $zvg_fse_close_label ); ?>
			</button>
		</div>

		<img
			class="zvg-fse-dialog__portrait"
			data-member-portrait
			src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
			width="800"
			height="800"
			alt=""
			decoding="async"
			hidden
		>

		<p class="zvg-fse-dialog__bio" data-member-bio></p>

		<div data-member-profile-slot></div>

		<a class="zvg-fse-dialog__link is-style-arrow-link" href="" data-member-link hidden><?php echo esc_html( $zvg_fse_link_text ); ?></a>
	</dialog>
</div>
