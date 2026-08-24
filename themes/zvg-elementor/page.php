<?php
/**
 * The template for a single page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! zvg_elementor_do_location( 'single' ) ) {
	while ( have_posts() ) {
		the_post();

		if ( ! zvg_elementor_is_builder_page() ) {
			?>
		<h1 class="zvg-elementor-entry__title"><?php the_title(); ?></h1>
			<?php
		}

		the_content();
		wp_link_pages();
	}
}

get_footer();
