<?php
/**
 * The token flow section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title       = get_sub_field( 'title' );
$zvg_acf_intro       = get_sub_field( 'intro' );
$zvg_acf_source_name = trim( (string) get_sub_field( 'source_name' ) );
$zvg_acf_source_meta = trim( (string) get_sub_field( 'source_meta' ) );

if ( ! have_rows( 'outputs' ) ) {
	return;
}

?>
<section class="zvg-acf-section zvg-acf-flow" id="how-it-works">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_intro ) ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-token-flow">
			<?php if ( '' !== $zvg_acf_source_name || '' !== $zvg_acf_source_meta ) { ?>
			<p class="zvg-acf-token-flow__source">
				<?php if ( '' !== $zvg_acf_source_name ) { ?>
				<span class="zvg-acf-token-flow__name"><?php echo esc_html( $zvg_acf_source_name ); ?></span>
				<?php } ?>

				<?php if ( '' !== $zvg_acf_source_meta ) { ?>
				<span class="zvg-acf-token-flow__meta"><?php echo esc_html( $zvg_acf_source_meta ); ?></span>
				<?php } ?>
			</p>
			<?php } ?>

			<div class="zvg-acf-token-flow__trunk" aria-hidden="true"></div>

			<div class="zvg-acf-token-flow__elbows" aria-hidden="true">
				<div class="zvg-acf-token-flow__elbow zvg-acf-token-flow__elbow--left"></div>
				<div class="zvg-acf-token-flow__elbow zvg-acf-token-flow__elbow--right"></div>
				<div class="zvg-acf-token-flow__mid"></div>
			</div>

			<ul class="zvg-acf-token-flow__outputs">
				<?php
				while ( have_rows( 'outputs' ) ) {
					the_row();

					$zvg_acf_name = trim( (string) get_sub_field( 'name' ) );
					$zvg_acf_meta = trim( (string) get_sub_field( 'meta' ) );

					if ( '' === $zvg_acf_name && '' === $zvg_acf_meta ) {
						continue;
					}
					?>
				<li class="zvg-acf-token-flow__output">
					<?php if ( '' !== $zvg_acf_name ) { ?>
					<span class="zvg-acf-token-flow__name"><?php echo esc_html( $zvg_acf_name ); ?></span>
					<?php } ?>

					<?php if ( '' !== $zvg_acf_meta ) { ?>
					<span class="zvg-acf-token-flow__meta"><?php echo esc_html( $zvg_acf_meta ); ?></span>
					<?php } ?>
				</li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
</section>
