<?php
/**
 * Template Name: Sections
 *
 * The template for a page assembled from section rows.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	zvg_acf_render_sections();
}

get_footer();
