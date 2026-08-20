<?php
/**
 * The template for a single post.
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
<article <?php post_class( 'zvg-acf-entry' ); ?>>
	<?php if ( ! empty( $zvg_acf_title ) ) { ?>
	<h1 class="zvg-acf-entry__title"><?php echo esc_html( $zvg_acf_title ); ?></h1>
	<?php } ?>

	<p class="zvg-acf-entry__meta">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</p>

	<?php if ( ! empty( $zvg_acf_content ) ) { ?>
	<div class="zvg-acf-entry__content">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
	<?php } ?>
</article>
	<?php
}

get_footer();
