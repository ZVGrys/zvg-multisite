<?php
/**
 * Title: Section: Hero
 * Slug: zvg-fse/section-hero
 * Categories: zvg-fse-section
 * Description: Opening section of the landing page: eyebrow, title, lead, byline and two buttons.
 * Keywords: hero, intro, landing
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_byline = sprintf(
	/* translators: %s: author name, wrapped so it can be highlighted. */
	esc_html_x( 'Built by %s, WordPress Full-Stack Engineer.', 'Hero byline', 'zvg-fse' ),
	'<strong>' . esc_html_x( 'Zoriana Grys', 'Author name', 'zvg-fse' ) . '</strong>'
);

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: Hero"},"align":"full","className":"zvg-fse-section zvg-fse-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-hero">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
	<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
	<p class="is-style-eyebrow"><?php echo esc_html_x( 'Same design, three stacks', 'Hero eyebrow', 'zvg-fse' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"className":"zvg-fse-hero__title"} -->
	<h1 class="wp-block-heading zvg-fse-hero__title"><?php echo esc_html_x( 'One landing page. Three WordPress builds.', 'Hero title', 'zvg-fse' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"is-style-lead","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<p class="is-style-lead" style="margin-top:var(--wp--preset--spacing--30)"><?php echo esc_html_x( 'The same page — same content, same design tokens — built three ways: Full Site Editing, Elementor, and a classic ACF theme. This site compares how each build turned out.', 'Hero lead', 'zvg-fse' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"className":"zvg-fse-hero__byline","textColor":"muted"} -->
	<p class="zvg-fse-hero__byline has-muted-color has-text-color"><?php echo $zvg_fse_byline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"top":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#measured"><?php echo esc_html_x( 'See the comparison', 'Hero button', 'zvg-fse' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline-dark"} -->
		<div class="wp-block-button is-style-outline-dark"><a class="wp-block-button__link wp-element-button" href="https://github.com/ZVGrys/zvg-multisite"><?php echo esc_html_x( 'View the code', 'Hero button', 'zvg-fse' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
