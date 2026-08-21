<?php
/**
 * Title: Search results loop
 * Slug: zvg-fse/search-loop
 * Categories: zvg-fse-section
 * Description: The post list on the search template. Same cards as the archives, with a sentence of its own when nothing matched.
 * Keywords: search, results, loop, query
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built and escaped by the helper.
echo zvg_fse_posts_loop( _x( 'Nothing matched your search. Try a different keyword.', 'Empty search', 'zvg-fse' ) );
