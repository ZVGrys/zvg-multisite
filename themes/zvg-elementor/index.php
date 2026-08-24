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
	<div class="zvg-elementor-archive">
	<h1 class="zvg-elementor-archive__title">
		<?php
		if ( is_search() ) {
			/* translators: %s: search query. */
			printf( esc_html_x( 'Search results for %s', 'Archive title', 'zvg-elementor' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
		} elseif ( is_home() ) {
			echo esc_html_x( 'Latest posts', 'Blog archive title', 'zvg-elementor' );
		} else {
			the_archive_title();
		}
		?>
	</h1>

	<?php if ( have_posts() ) { ?>
	<div class="zvg-elementor-archive__grid">
		<?php
		while ( have_posts() ) {
			the_post();

			$zvg_elementor_excerpt = wp_trim_words( get_the_excerpt(), 20 );
			?>
			<article <?php post_class( 'zvg-elementor-post' ); ?>>
				<?php if ( has_post_thumbnail() ) { ?>
				<a class="zvg-elementor-post__thumbnail-link" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'zvg-elementor-post__thumbnail' ) ); ?>
				</a>
				<?php } ?>

				<p class="zvg-elementor-post__date">
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</p>

				<h2 class="zvg-elementor-post__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>

				<?php if ( '' !== trim( $zvg_elementor_excerpt ) ) { ?>
				<p class="zvg-elementor-post__excerpt"><?php echo esc_html( $zvg_elementor_excerpt ); ?></p>
				<?php } ?>

				<a class="zvg-elementor-post__link" href="<?php the_permalink(); ?>">
					<?php echo esc_html_x( 'Read more', 'Archive card link', 'zvg-elementor' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
				</a>
			</article>
			<?php
		}
		?>
	</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => esc_html_x( 'Previous', 'Archive pagination', 'zvg-elementor' ),
				'next_text' => esc_html_x( 'Next', 'Archive pagination', 'zvg-elementor' ),
			)
		);
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
	</div>
	<?php
}

get_footer();
