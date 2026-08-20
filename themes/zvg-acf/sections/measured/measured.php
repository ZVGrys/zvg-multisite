<?php
/**
 * The measurements section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title   = get_sub_field( 'title' );
$zvg_acf_intro   = get_sub_field( 'intro' );
$zvg_acf_caption = trim( (string) get_sub_field( 'caption' ) );
$zvg_acf_columns = array(
	'value_fse'       => 'FSE',
	'value_elementor' => 'Elementor',
	'value_acf'       => 'ACF theme',
);

if ( ! have_rows( 'rows' ) ) {
	return;
}

/* translators: an em dash, standing in for a measurement that has not been taken yet. */
$zvg_acf_blank = _x( '—', 'Unmeasured statistic', 'zvg-acf' );

?>
<section class="zvg-acf-section zvg-acf-measured" id="measured">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_intro ) ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-compare-table">
			<table class="zvg-acf-compare-table__table" role="table">
				<?php if ( '' !== $zvg_acf_caption ) { ?>
				<caption class="screen-reader-text"><?php echo esc_html( $zvg_acf_caption ); ?></caption>
				<?php } ?>

				<thead role="rowgroup">
					<tr role="row">
						<td></td>
						<?php foreach ( $zvg_acf_columns as $zvg_acf_column ) { ?>
						<th scope="col" role="columnheader"><?php echo esc_html( $zvg_acf_column ); ?></th>
						<?php } ?>
					</tr>
				</thead>

				<tbody role="rowgroup">
					<?php
					while ( have_rows( 'rows' ) ) {
						the_row();

						$zvg_acf_label = trim( (string) get_sub_field( 'label' ) );

						if ( '' === $zvg_acf_label ) {
							continue;
						}
						?>
					<tr role="row">
						<th scope="row" role="rowheader"><?php echo esc_html( $zvg_acf_label ); ?></th>
						<?php
						foreach ( $zvg_acf_columns as $zvg_acf_key => $zvg_acf_column ) {
							$zvg_acf_value = trim( (string) get_sub_field( $zvg_acf_key ) );
							?>
						<td role="cell">
							<span class="zvg-acf-compare-table__column"><?php echo esc_html( $zvg_acf_column ); ?></span>
							<span class="zvg-acf-compare-table__value"><?php echo esc_html( '' === $zvg_acf_value ? $zvg_acf_blank : $zvg_acf_value ); ?></span>
						</td>
							<?php
						}
						?>
					</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>
