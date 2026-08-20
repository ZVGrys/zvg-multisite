<?php
/**
 * The site's entry point.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

get_header();

$zvg_acf_title = zvg_acf_archive_title();

if ( ! empty( $zvg_acf_title ) ) {
	?>
<h1 class="zvg-acf-archive__title"><?php echo wp_kses_post( $zvg_acf_title ); ?></h1>
	<?php
}

if ( is_search() ) {
	get_search_form();
}

if ( have_posts() ) {
	?>
<div class="zvg-acf-archive__list">
	<?php
	while ( have_posts() ) {
		the_post();

		$zvg_acf_card_title   = get_the_title();
		$zvg_acf_card_excerpt = get_the_excerpt();
		?>
	<article <?php post_class( 'zvg-acf-card' ); ?>>
		<?php if ( ! empty( $zvg_acf_card_title ) ) { ?>
		<h2 class="zvg-acf-card__title">
			<a href="<?php the_permalink(); ?>"><?php echo esc_html( $zvg_acf_card_title ); ?></a>
		</h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_card_excerpt ) ) { ?>
		<div class="zvg-acf-card__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php } ?>
	</article>
		<?php
	}
	?>
</div>

	<?php
	the_posts_pagination();
} else {
	?>
<p class="zvg-acf-archive__empty">
	<?php
	if ( is_search() ) {
		echo esc_html_x( 'Nothing here matches that search.', 'Empty search', 'zvg-acf' );
	} else {
		echo esc_html_x( 'Nothing has been published here yet.', 'Empty archive', 'zvg-acf' );
	}
	?>
</p>
	<?php
}

get_footer();
