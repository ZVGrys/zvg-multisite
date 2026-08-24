<?php
/**
 * The template for a single post.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! zvg_elementor_do_location( 'single' ) ) {
	while ( have_posts() ) {
		the_post();

		$zvg_elementor_id      = get_the_ID();
		$zvg_elementor_terms   = get_the_term_list( $zvg_elementor_id, 'category', '', ', ' );
		$zvg_elementor_content = trim( get_the_content() );
		$zvg_elementor_prev    = get_previous_post_link( '<div class="zvg-elementor-entry__nav-item zvg-elementor-entry__nav-item--prev">%link</div>', esc_html_x( 'Previous', 'Post navigation', 'zvg-elementor' ) );
		$zvg_elementor_next    = get_next_post_link( '<div class="zvg-elementor-entry__nav-item zvg-elementor-entry__nav-item--next">%link</div>', esc_html_x( 'Next', 'Post navigation', 'zvg-elementor' ) );
		?>
<article <?php post_class( 'zvg-elementor-entry' ); ?>>
		<?php if ( ! is_wp_error( $zvg_elementor_terms ) && ! empty( $zvg_elementor_terms ) ) { ?>
	<p class="zvg-elementor-entry__terms">
			<?php
			echo $zvg_elementor_terms; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_term_list() markup, escaped by core.
			?>
	</p>
		<?php } ?>

	<h1 class="zvg-elementor-entry__title"><?php the_title(); ?></h1>

	<p class="zvg-elementor-entry__meta">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></time>
	</p>

		<?php if ( has_post_thumbnail() ) { ?>
	<figure class="zvg-elementor-entry__media">
			<?php the_post_thumbnail( 'large' ); ?>
	</figure>
		<?php } ?>

		<?php if ( '' !== $zvg_elementor_content ) { ?>
	<div class="zvg-elementor-entry__content">
			<?php
			the_content();
			wp_link_pages();
			?>
	</div>
		<?php } ?>

		<?php zvg_elementor_render_share_links( $zvg_elementor_id ); ?>

		<?php if ( ! empty( $zvg_elementor_prev ) || ! empty( $zvg_elementor_next ) ) { ?>
	<nav class="zvg-elementor-entry__nav" aria-label="<?php echo esc_attr_x( 'Posts', 'Post navigation label', 'zvg-elementor' ); ?>">
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- adjacent post link markup, escaped by core; the link text is escaped where it is passed in.
			echo $zvg_elementor_prev;
			echo $zvg_elementor_next;
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
	</nav>
		<?php } ?>
</article>
		<?php
	}
}

get_footer();
