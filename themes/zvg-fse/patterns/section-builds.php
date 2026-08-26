<?php
/**
 * Title: Section: The three builds
 * Slug: zvg-fse/section-builds
 * Categories: zvg-fse-section
 * Description: Three cards, one per build, each closing with the same set of measurements.
 * Keywords: builds, cards, comparison
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_builds = array(
	array(
		'title'  => _x( 'Full Site Editing', 'Build card title', 'zvg-fse' ),
		'text'   => _x( 'Built entirely in the block editor: theme.json for styling, native block patterns for layout, no custom PHP templates.', 'Build card text', 'zvg-fse' ),
		'url'    => home_url( '/' ),
		'values' => array( '7012', '341 KB', '576' ),
	),
	array(
		'title'  => _x( 'Elementor', 'Build card title', 'zvg-fse' ),
		'text'   => _x( 'Built with Elementor’s page builder, styled with an Elementor kit generated from the same design tokens.', 'Build card text', 'zvg-fse' ),
		'url'    => home_url( '/elementor/' ),
		'values' => array( '9347', '788 KB', '580' ),
	),
	array(
		'title'  => _x( 'ACF theme', 'Build card title', 'zvg-fse' ),
		'text'   => _x( 'A classic theme: hand-written PHP templates, with ACF fields for anything an editor needs to change.', 'Build card text', 'zvg-fse' ),
		'url'    => home_url( '/acf/' ),
		'values' => array( '6652', '228 KB', '503' ),
	),
);

$zvg_fse_labels = array(
	_x( 'Lines of code', 'Statistic', 'zvg-fse' ),
	_x( 'Page weight', 'Statistic', 'zvg-fse' ),
	_x( 'DOM nodes', 'Statistic', 'zvg-fse' ),
);

foreach ( $zvg_fse_builds as $zvg_fse_index => $zvg_fse_build ) {
	$zvg_fse_items = array();

	foreach ( $zvg_fse_labels as $zvg_fse_key => $zvg_fse_label ) {
		$zvg_fse_items[] = array(
			'label' => $zvg_fse_label,
			'value' => $zvg_fse_build['values'][ $zvg_fse_key ],
		);
	}

	$zvg_fse_builds[ $zvg_fse_index ]['stats'] = wp_json_encode( $zvg_fse_items );
}

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: The three builds"},"align":"full","anchor":"builds","className":"zvg-fse-section zvg-fse-builds","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-builds" id="builds">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'The three builds', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'Same design, same content. Different WordPress approach under the hood.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"zvg-fse-builds__grid","style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group zvg-fse-builds__grid" style="margin-top:var(--wp--preset--spacing--50)">
			<?php foreach ( $zvg_fse_builds as $zvg_fse_build ) : ?>
			<!-- wp:group {"tagName":"article","className":"zvg-fse-build","backgroundColor":"surface-alt","style":{"spacing":{"blockGap":"0","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
			<article class="wp-block-group zvg-fse-build has-surface-alt-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"className":"zvg-fse-build__title"} -->
				<h3 class="wp-block-heading zvg-fse-build__title"><?php echo esc_html( $zvg_fse_build['title'] ); ?></h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":"zvg-fse-build__text","textColor":"muted","fontSize":"medium"} -->
				<p class="zvg-fse-build__text has-muted-color has-text-color has-medium-font-size"><?php echo esc_html( $zvg_fse_build['text'] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"zvg-fse-build__link is-style-arrow-link","fontSize":"small"} -->
				<p class="zvg-fse-build__link has-small-font-size is-style-arrow-link"><a href="<?php echo esc_url( $zvg_fse_build['url'] ); ?>"><?php echo esc_html_x( 'View live build', 'Build card link', 'zvg-fse' ); ?></a></p>
				<!-- /wp:paragraph -->

				<!-- wp:zvg-fse/stat-list {"items":<?php echo $zvg_fse_build['stats']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are raw JSON, not markup. ?>} /-->
			</article>
			<!-- /wp:group -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
