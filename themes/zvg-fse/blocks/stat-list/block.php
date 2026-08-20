<?php
/**
 * ZVG Stat List — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_items = isset( $attributes['items'] ) && is_array( $attributes['items'] ) ? $attributes['items'] : array();

if ( empty( $zvg_fse_items ) ) {
	return;
}

?>
<dl <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<?php
	foreach ( $zvg_fse_items as $zvg_fse_item ) :
		$zvg_fse_label = isset( $zvg_fse_item['label'] ) ? trim( $zvg_fse_item['label'] ) : '';

		if ( '' === $zvg_fse_label ) {
			continue;
		}

		$zvg_fse_value = isset( $zvg_fse_item['value'] ) ? trim( $zvg_fse_item['value'] ) : '';

		if ( '' === $zvg_fse_value ) {
			/* translators: an em dash, standing in for a measurement that has not been taken yet. */
			$zvg_fse_value = _x( '—', 'Unmeasured statistic', 'zvg-fse' );
		}

		if ( preg_match( '/^([0-9][0-9\s.,]*)\s*([A-Za-z%]{1,4})$/', $zvg_fse_value, $zvg_fse_parts ) ) {
			$zvg_fse_display = sprintf(
				'%1$s<span class="wp-block-zvg-fse-stat-list__unit">%2$s</span>',
				esc_html( trim( $zvg_fse_parts[1] ) ),
				esc_html( $zvg_fse_parts[2] )
			);
		} else {
			$zvg_fse_display = esc_html( $zvg_fse_value );
		}
		?>
		<div class="wp-block-zvg-fse-stat-list__item">
			<dt class="wp-block-zvg-fse-stat-list__label"><?php echo esc_html( $zvg_fse_label ); ?></dt>
			<dd class="wp-block-zvg-fse-stat-list__value"><?php echo $zvg_fse_display; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- both parts escaped above. ?></dd>
		</div>
	<?php endforeach; ?>
</dl>
