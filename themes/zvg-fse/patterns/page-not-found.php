<?php
/**
 * Title: Page not found
 * Slug: zvg-fse/page-not-found
 * Categories: zvg-fse-section
 * Description: What the 404 template says, with links resolved from the site rather than written by hand.
 * Keywords: 404, not found, error
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_posts_page = (int) get_option( 'page_for_posts' );
$zvg_fse_blog_url   = $zvg_fse_posts_page ? get_permalink( $zvg_fse_posts_page ) : '';

?>
<!-- wp:paragraph {"className":"is-style-eyebrow"} -->
<p class="is-style-eyebrow"><?php echo esc_html_x( 'Error 404', 'Not found eyebrow', 'zvg-fse' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zvg-fse-404__code"} -->
<p class="zvg-fse-404__code" aria-hidden="true">404</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"zvg-fse-404__lead"} -->
<h1 class="wp-block-heading zvg-fse-404__lead"><?php echo esc_html_x( 'This page does not exist on any of the three builds.', 'Not found lead', 'zvg-fse' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:search {"label":"<?php echo esc_attr_x( 'Search the site', 'Not found search', 'zvg-fse' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Type a word or two', 'Not found search', 'zvg-fse' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'Not found search', 'zvg-fse' ); ?>","className":"zvg-fse-404__search","style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|20"}}}} /-->

<!-- wp:paragraph {"className":"zvg-fse-404__hint is-style-note"} -->
<p class="zvg-fse-404__hint is-style-note"><?php echo esc_html_x( 'Looks through the posts and pages of this build.', 'Not found search hint', 'zvg-fse' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"zvg-fse-404__links","style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-buttons zvg-fse-404__links" style="margin-top:var(--wp--preset--spacing--50)">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html_x( 'Back to the homepage', 'Not found button', 'zvg-fse' ); ?></a></div>
	<!-- /wp:button -->

	<?php if ( $zvg_fse_blog_url ) : ?>
	<!-- wp:button {"className":"is-style-outline-dark"} -->
	<div class="wp-block-button is-style-outline-dark"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $zvg_fse_blog_url ); ?>"><?php echo esc_html_x( 'Read the blog', 'Not found button', 'zvg-fse' ); ?></a></div>
	<!-- /wp:button -->
	<?php endif; ?>
</div>
<!-- /wp:buttons -->
