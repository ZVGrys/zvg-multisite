<?php
/**
 * The contact section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title        = trim( (string) get_sub_field( 'title' ) );
$zvg_acf_intro        = trim( (string) get_sub_field( 'intro' ) );
$zvg_acf_form         = (int) get_sub_field( 'form' );
$zvg_acf_direct_label = trim( (string) get_sub_field( 'direct_label' ) );
$zvg_acf_direct       = have_rows( 'direct_links' );

$zvg_acf_shortcode = '';

if ( $zvg_acf_form > 0 && shortcode_exists( 'contact-form-7' ) && 'publish' === get_post_status( $zvg_acf_form ) ) {
	$zvg_acf_shortcode = do_shortcode( sprintf( '[contact-form-7 id="%d"]', $zvg_acf_form ) );
}

if ( '' === $zvg_acf_shortcode && ! $zvg_acf_direct ) {
	return;
}

?>
<section class="zvg-acf-section zvg-acf-contact" id="contact">
	<div class="zvg-acf-section__inner">
		<?php if ( '' !== $zvg_acf_title ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_intro ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-contact__layout">
			<?php echo $zvg_acf_shortcode; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 escapes its own output. ?>

			<?php if ( $zvg_acf_direct ) { ?>
			<div class="zvg-acf-contact__direct">
				<?php if ( '' !== $zvg_acf_direct_label ) { ?>
				<p class="zvg-acf-contact__label"><?php echo esc_html( $zvg_acf_direct_label ); ?></p>
				<?php } ?>

				<?php
				while ( have_rows( 'direct_links' ) ) {
					the_row();

					$zvg_acf_label = trim( (string) get_sub_field( 'label' ) );
					$zvg_acf_url   = trim( (string) get_sub_field( 'url' ) );

					if ( '' === $zvg_acf_label ) {
						continue;
					}
					?>
				<p class="zvg-acf-contact__line">
					<?php if ( '' !== $zvg_acf_url ) { ?>
					<a href="<?php echo esc_url( $zvg_acf_url ); ?>"><?php echo esc_html( $zvg_acf_label ); ?></a>
					<?php } else { ?>
						<?php echo esc_html( $zvg_acf_label ); ?>
					<?php } ?>
				</p>
					<?php
				}
				?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
