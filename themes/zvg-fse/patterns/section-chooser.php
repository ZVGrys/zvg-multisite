<?php
/**
 * Title: Section: When to choose what
 * Slug: zvg-fse/section-chooser
 * Categories: zvg-fse-section
 * Description: Three questions that weigh the answers and suggest one of the builds.
 * Keywords: chooser, questionnaire, recommendation
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: When to choose what"},"align":"full","anchor":"when-to-choose","className":"zvg-fse-section zvg-fse-chooser","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-chooser" id="when-to-choose">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'When to choose what', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'A short questionnaire — three questions, then one of the three builds is suggested.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:zvg-fse/build-chooser {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
