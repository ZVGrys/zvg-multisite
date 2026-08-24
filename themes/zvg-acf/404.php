<?php
/**
 * The template for the not found page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

get_header();

$zvg_acf_eyebrow    = (string) zvg_acf_error_option( 'error_eyebrow' );
$zvg_acf_code       = (string) zvg_acf_error_option( 'error_code' );
$zvg_acf_lead       = (string) zvg_acf_error_option( 'error_lead' );
$zvg_acf_hint       = (string) zvg_acf_error_option( 'error_search_hint' );
$zvg_acf_posts_page = (int) get_option( 'page_for_posts' );

$zvg_acf_buttons = array();

$zvg_acf_defined = array(
	array(
		'label'    => (string) zvg_acf_error_option( 'error_button_1_label' ),
		'url'      => (string) zvg_acf_option( 'error_button_1_url' ),
		'fallback' => home_url( '/' ),
		'class'    => 'zvg-acf-button',
	),
	array(
		'label'    => (string) zvg_acf_error_option( 'error_button_2_label' ),
		'url'      => (string) zvg_acf_option( 'error_button_2_url' ),
		'fallback' => $zvg_acf_posts_page ? get_permalink( $zvg_acf_posts_page ) : '',
		'class'    => 'zvg-acf-button zvg-acf-button--outline',
	),
);

foreach ( $zvg_acf_defined as $zvg_acf_button ) {
	$zvg_acf_button['url'] = '' !== trim( $zvg_acf_button['url'] ) ? $zvg_acf_button['url'] : $zvg_acf_button['fallback'];

	if ( '' !== trim( $zvg_acf_button['label'] ) && '' !== $zvg_acf_button['url'] ) {
		$zvg_acf_buttons[] = $zvg_acf_button;
	}
}

?>
<section class="zvg-acf-404">
	<?php if ( '' !== trim( $zvg_acf_eyebrow ) ) { ?>
	<p class="zvg-acf-404__eyebrow"><?php echo esc_html( $zvg_acf_eyebrow ); ?></p>
	<?php } ?>

	<?php if ( '' !== trim( $zvg_acf_code ) ) { ?>
	<p class="zvg-acf-404__code" aria-hidden="true"><?php echo esc_html( $zvg_acf_code ); ?></p>
	<?php } ?>

	<?php if ( '' !== trim( $zvg_acf_lead ) ) { ?>
	<h1 class="zvg-acf-404__lead"><?php echo esc_html( $zvg_acf_lead ); ?></h1>
	<?php } ?>

	<?php
	if ( zvg_acf_option( 'error_show_search', true ) ) {
		get_search_form(
			array(
				'placeholder' => zvg_acf_error_option( 'error_search_placeholder' ),
				'button'      => zvg_acf_error_option( 'error_search_button' ),
			)
		);

		if ( '' !== trim( $zvg_acf_hint ) ) {
			?>
	<p class="zvg-acf-404__hint"><?php echo esc_html( $zvg_acf_hint ); ?></p>
			<?php
		}
	}
	?>

	<?php if ( $zvg_acf_buttons ) { ?>
	<div class="zvg-acf-404__links">
		<?php foreach ( $zvg_acf_buttons as $zvg_acf_button ) { ?>
		<a class="<?php echo esc_attr( $zvg_acf_button['class'] ); ?>" href="<?php echo esc_url( $zvg_acf_button['url'] ); ?>"><?php echo esc_html( $zvg_acf_button['label'] ); ?></a>
		<?php } ?>
	</div>
	<?php } ?>
</section>
<?php

get_footer();
