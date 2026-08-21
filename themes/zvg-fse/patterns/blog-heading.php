<?php
/**
 * Title: Blog heading
 * Slug: zvg-fse/blog-heading
 * Categories: zvg-fse-section
 * Description: The heading above the post list on the posts page, resolved in PHP so it can be translated.
 * Keywords: blog, heading, posts
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
<h1 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--50)"><?php echo esc_html_x( 'Latest posts', 'Posts page title', 'zvg-fse' ); ?></h1>
<!-- /wp:heading -->
