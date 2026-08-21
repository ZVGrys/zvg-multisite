<?php
/**
 * Title: Search form
 * Slug: zvg-fse/search-form
 * Categories: zvg-fse-section
 * Description: The search field on the search template, with its label and button text resolved in PHP so they can be translated.
 * Keywords: search, form, field
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_search = zvg_fse_block_attrs(
	array(
		'label'      => _x( 'Search', 'Search field label', 'zvg-fse' ),
		'showLabel'  => false,
		'buttonText' => _x( 'Search', 'Search button', 'zvg-fse' ),
		'style'      => array( 'spacing' => array( 'margin' => array( 'bottom' => 'var:preset|spacing|50' ) ) ),
	)
);

?>
<!-- wp:search <?php echo $zvg_fse_search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
