<?php
/**
 * The template for the not found page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! zvg_elementor_do_location( 'single' ) ) {
	$zvg_elementor_eyebrow    = (string) get_theme_mod( 'zvg_elementor_error_eyebrow', _x( 'Error 404', 'Not found eyebrow', 'zvg-elementor' ) );
	$zvg_elementor_code       = (string) get_theme_mod( 'zvg_elementor_error_code', _x( '404', 'Not found code', 'zvg-elementor' ) );
	$zvg_elementor_lead       = (string) get_theme_mod( 'zvg_elementor_error_lead', _x( 'This page does not exist on any of the three builds.', 'Not found lead', 'zvg-elementor' ) );
	$zvg_elementor_hint       = (string) get_theme_mod( 'zvg_elementor_error_search_hint', _x( 'Looks through the posts and pages of this build.', 'Not found search hint', 'zvg-elementor' ) );
	$zvg_elementor_posts_page = (int) get_option( 'page_for_posts' );
	$zvg_elementor_buttons    = array();

	$zvg_elementor_defined = array(
		array(
			'label'    => (string) get_theme_mod( 'zvg_elementor_error_button_1_label', _x( 'Back to the homepage', 'Not found button', 'zvg-elementor' ) ),
			'url'      => (string) get_theme_mod( 'zvg_elementor_error_button_1_url', '' ),
			'fallback' => home_url( '/' ),
			'class'    => 'elementor-button zvg-elementor-404__button',
		),
		array(
			'label'    => (string) get_theme_mod( 'zvg_elementor_error_button_2_label', _x( 'Read the blog', 'Not found button', 'zvg-elementor' ) ),
			'url'      => (string) get_theme_mod( 'zvg_elementor_error_button_2_url', '' ),
			'fallback' => $zvg_elementor_posts_page ? (string) get_permalink( $zvg_elementor_posts_page ) : '',
			'class'    => 'elementor-button zvg-elementor-404__button zvg-elementor-404__button--outline',
		),
	);

	foreach ( $zvg_elementor_defined as $zvg_elementor_button ) {
		$zvg_elementor_button['url'] = '' !== trim( $zvg_elementor_button['url'] ) ? $zvg_elementor_button['url'] : $zvg_elementor_button['fallback'];

		if ( '' !== trim( $zvg_elementor_button['label'] ) && '' !== $zvg_elementor_button['url'] ) {
			$zvg_elementor_buttons[] = $zvg_elementor_button;
		}
	}
	?>
<section class="zvg-elementor-404">
	<?php if ( '' !== trim( $zvg_elementor_eyebrow ) ) { ?>
	<p class="zvg-elementor-404__eyebrow"><?php echo esc_html( $zvg_elementor_eyebrow ); ?></p>
	<?php } ?>

	<?php if ( '' !== trim( $zvg_elementor_code ) ) { ?>
	<p class="zvg-elementor-404__code" aria-hidden="true"><?php echo esc_html( $zvg_elementor_code ); ?></p>
	<?php } ?>

	<?php if ( '' !== trim( $zvg_elementor_lead ) ) { ?>
	<h1 class="zvg-elementor-404__lead"><?php echo esc_html( $zvg_elementor_lead ); ?></h1>
	<?php } ?>

	<?php
	if ( get_theme_mod( 'zvg_elementor_error_show_search', true ) ) {
		get_search_form(
			array(
				'placeholder' => (string) get_theme_mod( 'zvg_elementor_error_search_placeholder', _x( 'Type a word or two', 'Not found search', 'zvg-elementor' ) ),
				'button'      => (string) get_theme_mod( 'zvg_elementor_error_search_button', _x( 'Search', 'Not found search', 'zvg-elementor' ) ),
			)
		);

		if ( '' !== trim( $zvg_elementor_hint ) ) {
			?>
	<p class="zvg-elementor-404__hint"><?php echo esc_html( $zvg_elementor_hint ); ?></p>
			<?php
		}
	}
	?>

	<?php if ( $zvg_elementor_buttons ) { ?>
	<div class="zvg-elementor-404__links">
		<?php foreach ( $zvg_elementor_buttons as $zvg_elementor_button ) { ?>
		<a class="<?php echo esc_attr( $zvg_elementor_button['class'] ); ?>" href="<?php echo esc_url( $zvg_elementor_button['url'] ); ?>"><?php echo esc_html( $zvg_elementor_button['label'] ); ?></a>
		<?php } ?>
	</div>
	<?php } ?>
</section>
	<?php
}

get_footer();
