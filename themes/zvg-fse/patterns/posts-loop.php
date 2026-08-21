<?php
/**
 * Title: Posts loop
 * Slug: zvg-fse/posts-loop
 * Categories: zvg-fse-section
 * Description: The post list shared by the blog, the archives and the fallback index, with its link and pagination labels resolved in PHP rather than typed into the template.
 * Keywords: posts, loop, archive, query
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built and escaped by the helper.
echo zvg_fse_posts_loop( _x( 'No posts found.', 'Empty archive', 'zvg-fse' ) );
