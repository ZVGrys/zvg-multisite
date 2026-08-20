<?php
/**
 * The three builds section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title = get_sub_field( 'title' );
$zvg_acf_intro = get_sub_field( 'intro' );

if ( ! have_rows( 'items' ) ) {
	return;
}

?>
<section class="zvg-acf-section zvg-acf-builds" id="builds">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_intro ) ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-builds__grid">
			<?php
			while ( have_rows( 'items' ) ) {
				the_row();

				$zvg_acf_card_title = get_sub_field( 'title' );
				$zvg_acf_card_tag   = get_sub_field( 'title_tag' );
				$zvg_acf_card_tag   = in_array( $zvg_acf_card_tag, array( 'h2', 'h3', 'h4', 'h5' ), true ) ? $zvg_acf_card_tag : 'h3';
				$zvg_acf_card_text  = get_sub_field( 'text' );
				$zvg_acf_link_label = get_sub_field( 'link_label' );
				$zvg_acf_link_url   = get_sub_field( 'link_url' );
				?>
			<article class="zvg-acf-build">
				<?php if ( ! empty( $zvg_acf_card_title ) ) { ?>
					<?php
					printf(
						'<%1$s class="zvg-acf-build__title">%2$s</%1$s>',
						esc_html( $zvg_acf_card_tag ),
						esc_html( $zvg_acf_card_title )
					);
					?>
				<?php } ?>

				<?php if ( ! empty( $zvg_acf_card_text ) ) { ?>
				<p class="zvg-acf-build__text"><?php echo esc_html( $zvg_acf_card_text ); ?></p>
				<?php } ?>

				<?php if ( ! empty( $zvg_acf_link_label ) && ! empty( $zvg_acf_link_url ) ) { ?>
				<p class="zvg-acf-build__link">
					<a href="<?php echo esc_url( $zvg_acf_link_url ); ?>"><?php echo esc_html( $zvg_acf_link_label ); ?></a>
				</p>
				<?php } ?>

				<?php if ( have_rows( 'stats' ) ) { ?>
				<dl class="zvg-acf-build__stats">
					<?php
					while ( have_rows( 'stats' ) ) {
						the_row();

						$zvg_acf_stat_label = trim( (string) get_sub_field( 'label' ) );

						if ( '' === $zvg_acf_stat_label ) {
							continue;
						}

						$zvg_acf_stat_value = trim( (string) get_sub_field( 'value' ) );

						if ( '' === $zvg_acf_stat_value ) {
							/* translators: an em dash, standing in for a measurement that has not been taken yet. */
							$zvg_acf_stat_value = _x( '—', 'Unmeasured statistic', 'zvg-acf' );
						}

						if ( preg_match( '/^([0-9][0-9\s.,]*)\s*([A-Za-z%]{1,4})$/', $zvg_acf_stat_value, $zvg_acf_parts ) ) {
							$zvg_acf_stat_markup = sprintf(
								'%1$s<span class="zvg-acf-build__unit">%2$s</span>',
								esc_html( trim( $zvg_acf_parts[1] ) ),
								esc_html( $zvg_acf_parts[2] )
							);
						} else {
							$zvg_acf_stat_markup = esc_html( $zvg_acf_stat_value );
						}
						?>
					<div class="zvg-acf-build__stat">
						<dt class="zvg-acf-build__label"><?php echo esc_html( $zvg_acf_stat_label ); ?></dt>
						<dd class="zvg-acf-build__value"><?php echo $zvg_acf_stat_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- both parts escaped above. ?></dd>
					</div>
						<?php
					}
					?>
				</dl>
				<?php } ?>
			</article>
				<?php
			}
			?>
		</div>
	</div>
</section>
