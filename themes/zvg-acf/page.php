<?php
/**
 * The template for a single page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$zvg_acf_title   = get_the_title();
	$zvg_acf_content = trim( get_the_content() );
	?>
<article <?php post_class( 'zvg-acf-entry zvg-acf-entry--page' ); ?>>
	<?php if ( ! empty( $zvg_acf_title ) ) { ?>
	<h1 class="zvg-acf-entry__title"><?php echo esc_html( $zvg_acf_title ); ?></h1>
	<?php } ?>

	<?php if ( ! empty( $zvg_acf_content ) ) { ?>
	<div class="zvg-acf-entry__content">
		<?php the_content(); ?>
	</div>
	<?php } ?>
</article>
	<?php
}

get_footer();
