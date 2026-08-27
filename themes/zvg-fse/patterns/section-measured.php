<?php
/**
 * Title: Section: What I measured
 * Slug: zvg-fse/section-measured
 * Categories: zvg-fse-section
 * Description: The same checks run against each build, side by side in a table.
 * Keywords: measured, comparison, table, results
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_table = wp_json_encode(
	array(
		'caption' => _x( 'Four checks compared across the three builds.', 'Table caption', 'zvg-fse' ),
		'columns' => array(
			_x( 'FSE', 'Build name', 'zvg-fse' ),
			_x( 'Elementor', 'Build name', 'zvg-fse' ),
			_x( 'ACF theme', 'Build name', 'zvg-fse' ),
		),
		'rows'    => array(
			array(
				'label'  => _x( 'Lines of code', 'Measurement', 'zvg-fse' ),
				'values' => array( '7012', '9347', '6652' ),
			),
			array(
				'label'  => _x( 'Page weight', 'Measurement', 'zvg-fse' ),
				'values' => array( '341 KB', '788 KB', '228 KB' ),
			),
			array(
				'label'  => _x( 'DOM nodes', 'Measurement', 'zvg-fse' ),
				'values' => array( '576', '580', '503' ),
			),
			array(
				'label'  => _x( 'Lighthouse mobile', 'Measurement', 'zvg-fse' ),
				'values' => array( '100', '99', '100' ),
			),
		),
	)
);

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: What I measured"},"align":"full","anchor":"measured","className":"zvg-fse-section zvg-fse-measured","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-measured" id="measured">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'What I measured', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'The same four checks, run against each finished build.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:zvg-fse/compare-table <?php echo $zvg_fse_table; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are raw JSON, not markup. ?> /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
