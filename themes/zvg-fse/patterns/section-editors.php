<?php
/**
 * Title: Section: Three editors
 * Slug: zvg-fse/section-editors
 * Categories: zvg-fse-section
 * Description: The same section opened for editing in each build, on a track that scrolls sideways.
 * Keywords: editors, screenshots, comparison
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_editors = array(
	array(
		'shot'    => _x( 'Screenshot — block editor', 'Editor screenshot placeholder', 'zvg-fse' ),
		'image'   => 'editor-fse.webp',
		'alt'     => _x( 'The landing page open in the WordPress block editor, with a measurements block selected and its settings in the sidebar', 'Editor screenshot', 'zvg-fse' ),
		'caption' => _x( 'Editing means selecting blocks in place; styling choices are limited to what theme.json allows.', 'Editor caption', 'zvg-fse' ),
	),
	array(
		'shot'    => _x( 'Screenshot — Elementor canvas', 'Editor screenshot placeholder', 'zvg-fse' ),
		'caption' => _x( 'Editing is drag-and-drop with every setting exposed, which is fast but easy to drift from the tokens.', 'Editor caption', 'zvg-fse' ),
	),
	array(
		'shot'    => _x( 'Screenshot — WP admin with ACF fields', 'Editor screenshot placeholder', 'zvg-fse' ),
		'caption' => _x( 'Editing is filling labelled fields; the layout itself is not editable, which keeps the design intact.', 'Editor caption', 'zvg-fse' ),
	),
);

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: Three editors"},"align":"full","anchor":"three-editors","className":"zvg-fse-section zvg-fse-editors","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-editors" id="three-editors">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'Three editors, one page', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'The same section, opened for editing in each of the three builds.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"full","className":"zvg-fse-editors__track","style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="wp-block-group alignfull zvg-fse-editors__track" style="margin-top:var(--wp--preset--spacing--50)">
			<?php foreach ( $zvg_fse_editors as $zvg_fse_editor ) : ?>
			<!-- wp:group {"tagName":"figure","className":"zvg-fse-editors__item","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
			<figure class="wp-block-group zvg-fse-editors__item">
				<?php if ( isset( $zvg_fse_editor['image'] ) ) : ?>
				<!-- wp:image {"className":"zvg-fse-editors__shot","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full zvg-fse-editors__shot"><img src="<?php echo esc_url( ZVG_FSE_T_URI . '/assets/img/' . $zvg_fse_editor['image'] ); ?>" alt="<?php echo esc_attr( $zvg_fse_editor['alt'] ); ?>"/></figure>
				<!-- /wp:image -->
				<?php else : ?>
				<!-- wp:group {"className":"zvg-fse-placeholder zvg-fse-placeholder--wide","layout":{"type":"default"}} -->
				<div class="wp-block-group zvg-fse-placeholder zvg-fse-placeholder--wide">
					<!-- wp:paragraph {"fontSize":"small"} -->
					<p class="has-small-font-size"><?php echo esc_html( $zvg_fse_editor['shot'] ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
				<?php endif; ?>

				<!-- wp:paragraph {"className":"zvg-fse-editors__caption","textColor":"muted","fontSize":"medium","style":{"typography":{"lineHeight":"1.55"}}} -->
				<p class="zvg-fse-editors__caption has-muted-color has-text-color has-medium-font-size" style="line-height:1.55"><?php echo esc_html( $zvg_fse_editor['caption'] ); ?></p>
				<!-- /wp:paragraph -->
			</figure>
			<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
