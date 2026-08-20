<?php
/**
 * Title: Footer copyright
 * Slug: zvg-fse/footer-copy
 * Categories: zvg-fse-section
 * Description: The footer's copyright line, with the year taken from the site clock rather than written by hand.
 * Keywords: footer, copyright, year
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_copy = sprintf(
	/* translators: 1: current year, 2: author name. */
	_x( '© %1$s %2$s, WordPress Full-Stack Engineer.', 'Footer copyright', 'zvg-fse' ),
	wp_date( 'Y' ),
	_x( 'Zoriana Grys', 'Author name', 'zvg-fse' )
);

?>
<!-- wp:paragraph {"className":"zvg-fse-footer__copy","textColor":"muted","fontSize":"small"} -->
<p class="zvg-fse-footer__copy has-muted-color has-text-color has-small-font-size"><?php echo esc_html( $zvg_fse_copy ); ?></p>
<!-- /wp:paragraph -->
