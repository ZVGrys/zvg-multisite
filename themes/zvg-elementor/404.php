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
	?>
<section class="zvg-elementor-404">
	<p class="zvg-elementor-404__code" aria-hidden="true">404</p>

	<h1 class="zvg-elementor-404__lead"><?php echo esc_html_x( 'This page does not exist on any of the three builds.', 'Not found lead', 'zvg-elementor' ); ?></h1>

	<p class="zvg-elementor-404__body"><?php echo esc_html_x( 'Either the address has a typo in it, or the link that brought you here points at something that has since moved.', 'Not found body', 'zvg-elementor' ); ?></p>

	<p class="zvg-elementor-404__links">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html_x( 'Back to the homepage', 'Not found link', 'zvg-elementor' ); ?></a>
	</p>
</section>
	<?php
}

get_footer();
