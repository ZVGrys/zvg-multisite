<?php
/**
 * ZVG Compare Table — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_columns = isset( $attributes['columns'] ) && is_array( $attributes['columns'] ) ? $attributes['columns'] : array();
$zvg_fse_rows    = isset( $attributes['rows'] ) && is_array( $attributes['rows'] ) ? $attributes['rows'] : array();

if ( empty( $zvg_fse_columns ) || empty( $zvg_fse_rows ) ) {
	return;
}

$zvg_fse_caption = isset( $attributes['caption'] ) ? trim( $attributes['caption'] ) : '';

/* translators: an em dash, standing in for a measurement that has not been taken yet. */
$zvg_fse_blank = _x( '—', 'Unmeasured statistic', 'zvg-fse' );

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<table class="wp-block-zvg-fse-compare-table__table" role="table">
		<?php if ( '' !== $zvg_fse_caption ) : ?>
			<caption class="screen-reader-text"><?php echo esc_html( $zvg_fse_caption ); ?></caption>
		<?php endif; ?>

		<thead role="rowgroup">
			<tr role="row">
				<td></td>
				<?php foreach ( $zvg_fse_columns as $zvg_fse_column ) : ?>
					<th scope="col" role="columnheader"><?php echo esc_html( $zvg_fse_column ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody role="rowgroup">
			<?php
			foreach ( $zvg_fse_rows as $zvg_fse_row ) :
				$zvg_fse_label = isset( $zvg_fse_row['label'] ) ? trim( $zvg_fse_row['label'] ) : '';

				if ( '' === $zvg_fse_label ) {
					continue;
				}

				$zvg_fse_values = isset( $zvg_fse_row['values'] ) && is_array( $zvg_fse_row['values'] ) ? $zvg_fse_row['values'] : array();
				?>
				<tr role="row">
					<th scope="row" role="rowheader"><?php echo esc_html( $zvg_fse_label ); ?></th>
					<?php foreach ( $zvg_fse_columns as $zvg_fse_index => $zvg_fse_column ) : ?>
						<?php $zvg_fse_value = isset( $zvg_fse_values[ $zvg_fse_index ] ) ? trim( $zvg_fse_values[ $zvg_fse_index ] ) : ''; ?>
						<td role="cell">
							<span class="wp-block-zvg-fse-compare-table__column"><?php echo esc_html( $zvg_fse_column ); ?></span>
							<span class="wp-block-zvg-fse-compare-table__value"><?php echo esc_html( '' === $zvg_fse_value ? $zvg_fse_blank : $zvg_fse_value ); ?></span>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
