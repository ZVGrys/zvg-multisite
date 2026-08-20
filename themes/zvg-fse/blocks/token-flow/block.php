<?php
/**
 * ZVG Token Flow — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_source  = isset( $attributes['source'] ) && is_array( $attributes['source'] ) ? $attributes['source'] : array();
$zvg_fse_outputs = isset( $attributes['outputs'] ) && is_array( $attributes['outputs'] ) ? $attributes['outputs'] : array();

if ( empty( $zvg_fse_outputs ) ) {
	return;
}

$zvg_fse_source_name = isset( $zvg_fse_source['name'] ) ? $zvg_fse_source['name'] : '';
$zvg_fse_source_meta = isset( $zvg_fse_source['meta'] ) ? $zvg_fse_source['meta'] : '';

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<p class="wp-block-zvg-fse-token-flow__source">
		<?php if ( '' !== trim( $zvg_fse_source_name ) ) { ?>
			<span class="wp-block-zvg-fse-token-flow__name"><?php echo esc_html( $zvg_fse_source_name ); ?></span>
		<?php } ?>

		<?php if ( '' !== trim( $zvg_fse_source_meta ) ) { ?>
			<span class="wp-block-zvg-fse-token-flow__meta"><?php echo esc_html( $zvg_fse_source_meta ); ?></span>
		<?php } ?>
	</p>

	<div class="wp-block-zvg-fse-token-flow__trunk" aria-hidden="true"></div>

	<div class="wp-block-zvg-fse-token-flow__elbows" aria-hidden="true">
		<div class="wp-block-zvg-fse-token-flow__elbow wp-block-zvg-fse-token-flow__elbow--left"></div>
		<div class="wp-block-zvg-fse-token-flow__elbow wp-block-zvg-fse-token-flow__elbow--right"></div>
		<div class="wp-block-zvg-fse-token-flow__mid"></div>
	</div>

	<ul class="wp-block-zvg-fse-token-flow__outputs">
		<?php
		foreach ( $zvg_fse_outputs as $zvg_fse_output ) {
			$zvg_fse_name = isset( $zvg_fse_output['name'] ) ? trim( $zvg_fse_output['name'] ) : '';
			$zvg_fse_meta = isset( $zvg_fse_output['meta'] ) ? trim( $zvg_fse_output['meta'] ) : '';

			if ( '' === $zvg_fse_name && '' === $zvg_fse_meta ) {
				continue;
			}
			?>
			<li class="wp-block-zvg-fse-token-flow__output">
				<?php if ( '' !== $zvg_fse_name ) { ?>
					<span class="wp-block-zvg-fse-token-flow__name"><?php echo esc_html( $zvg_fse_name ); ?></span>
				<?php } ?>

				<?php if ( '' !== $zvg_fse_meta ) { ?>
					<span class="wp-block-zvg-fse-token-flow__meta"><?php echo esc_html( $zvg_fse_meta ); ?></span>
				<?php } ?>
			</li>
			<?php
		}
		?>
	</ul>
</div>
