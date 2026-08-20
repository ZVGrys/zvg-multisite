<?php
/**
 * Title: Section: From Figma to three builds
 * Slug: zvg-fse/section-flow
 * Categories: zvg-fse-section
 * Description: The route design tokens take out of Figma and into each build, drawn as a diagram.
 * Keywords: tokens, figma, flow, diagram
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: From Figma to three builds"},"align":"full","anchor":"how-it-works","className":"zvg-fse-section zvg-fse-flow","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-flow" id="how-it-works">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'From Figma to three builds', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'Variables leave Figma once, as a tokens file. From there each build takes its own route into the values it will actually use.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:zvg-fse/token-flow {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
