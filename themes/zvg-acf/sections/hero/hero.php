<?php
/**
 * The hero section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_eyebrow = get_sub_field( 'eyebrow' );
$zvg_acf_title   = get_sub_field( 'title' );
$zvg_acf_lead    = get_sub_field( 'lead' );
$zvg_acf_byline  = get_sub_field( 'byline' );
$zvg_acf_btn_1_l = get_sub_field( 'button_1_label' );
$zvg_acf_btn_1_u = get_sub_field( 'button_1_url' );
$zvg_acf_btn_2_l = get_sub_field( 'button_2_label' );
$zvg_acf_btn_2_u = get_sub_field( 'button_2_url' );

?>
<section class="zvg-acf-section zvg-acf-hero">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_eyebrow ) ) { ?>
		<p class="zvg-acf-eyebrow"><?php echo esc_html( $zvg_acf_eyebrow ); ?></p>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h1 class="zvg-acf-hero__title"><?php echo esc_html( $zvg_acf_title ); ?></h1>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_lead ) ) { ?>
		<p class="zvg-acf-lead"><?php echo esc_html( $zvg_acf_lead ); ?></p>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_byline ) ) { ?>
		<p class="zvg-acf-hero__byline">
			<?php
			echo wp_kses(
				$zvg_acf_byline,
				array(
					'strong' => array(),
					'em'     => array(),
					'a'      => array( 'href' => array() ),
				)
			);
			?>
		</p>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_btn_1_l ) || ! empty( $zvg_acf_btn_2_l ) ) { ?>
		<div class="zvg-acf-hero__buttons">
			<?php if ( ! empty( $zvg_acf_btn_1_l ) ) { ?>
			<a class="zvg-acf-button" href="<?php echo esc_url( $zvg_acf_btn_1_u ); ?>"><?php echo esc_html( $zvg_acf_btn_1_l ); ?></a>
			<?php } ?>

			<?php if ( ! empty( $zvg_acf_btn_2_l ) ) { ?>
			<a class="zvg-acf-button zvg-acf-button--outline" href="<?php echo esc_url( $zvg_acf_btn_2_u ); ?>"><?php echo esc_html( $zvg_acf_btn_2_l ); ?></a>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</section>
