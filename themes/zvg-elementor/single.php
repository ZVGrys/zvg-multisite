<?php
/**
 * The template for a single post.
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

		the_content();

		wp_link_pages();

	}
}

get_footer();
