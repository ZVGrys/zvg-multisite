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
<div class="zvg-acf-archive__grid">
	<?php
	while ( have_posts() ) {
		the_post();

		$zvg_acf_card_title   = get_the_title();
		$zvg_acf_card_excerpt = trim( (string) get_the_excerpt() );
		?>
	<article <?php post_class( 'zvg-acf-post' ); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
		<a class="zvg-acf-post__thumbnail-link" href="<?php the_permalink(); ?>">
			<?php
			the_post_thumbnail( 'medium_large', array( 'class' => 'zvg-acf-post__thumbnail' ) );
			?>
		</a>
		<?php } ?>

		<p class="zvg-acf-post__date">
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>

		<?php if ( ! empty( $zvg_acf_card_title ) ) { ?>
		<h2 class="zvg-acf-post__title">
			<a href="<?php the_permalink(); ?>"><?php echo esc_html( $zvg_acf_card_title ); ?></a>
		</h2>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_card_excerpt ) { ?>
		<p class="zvg-acf-post__excerpt"><?php echo esc_html( $zvg_acf_card_excerpt ); ?></p>
		<?php } ?>

		<a class="zvg-acf-post__link" href="<?php the_permalink(); ?>">
			<?php echo esc_html_x( 'Read more', 'Archive card link', 'zvg-acf' ); ?>
			<span class="screen-reader-text"><?php echo esc_html( ': ' . $zvg_acf_card_title ); ?></span>
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
			'prev_text' => esc_html_x( 'Previous', 'Archive pagination', 'zvg-acf' ),
			'next_text' => esc_html_x( 'Next', 'Archive pagination', 'zvg-acf' ),
		)
	);
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
