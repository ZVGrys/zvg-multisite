<?php
/**
 * The site's entry point.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_singular() ) {

	if ( ! zvg_elementor_do_location( 'single' ) ) {
		while ( have_posts() ) {
			the_post();

			the_content();

		}
	}
} elseif ( ! zvg_elementor_do_location( 'archive' ) ) {
	?>
	<h1 class="zvg-elementor-archive__title">
		<?php
		$zvg_elementor_posts_page = (int) get_option( 'page_for_posts' );

		if ( is_search() ) {
			/* translators: %s: search query. */
			printf( esc_html_x( 'Search results for %s', 'Archive title', 'zvg-elementor' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
		} elseif ( is_home() && $zvg_elementor_posts_page ) {
			echo esc_html( get_the_title( $zvg_elementor_posts_page ) );
		} else {
			the_archive_title();
		}
		?>
	</h1>

	<?php if ( have_posts() ) { ?>
	<div class="zvg-elementor-archive__list">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class( 'zvg-elementor-card' ); ?>>
				<h2 class="zvg-elementor-card__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>

				<?php if ( has_excerpt() || get_the_content() ) { ?>
				<div class="zvg-elementor-card__excerpt">
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
		?>
	<?php } else { ?>
	<p class="zvg-elementor-archive__empty">
		<?php
		if ( is_search() ) {
			echo esc_html_x( 'Nothing here matches that search.', 'Empty search', 'zvg-elementor' );
		} else {
			echo esc_html_x( 'Nothing has been published here yet.', 'Empty archive', 'zvg-elementor' );
		}
		?>
	</p>
	<?php } ?>
	<?php
}

get_footer();
