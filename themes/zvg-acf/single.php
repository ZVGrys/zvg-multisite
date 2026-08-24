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

	$zvg_acf_id      = get_the_ID();
	$zvg_acf_title   = get_the_title();
	$zvg_acf_terms   = get_the_term_list( $zvg_acf_id, 'category', '', ', ' );
	$zvg_acf_content = trim( get_the_content() );
	$zvg_acf_prev    = '';
	$zvg_acf_next    = '';

	if ( zvg_acf_post_option( 'post_nav_show' ) ) {
		$zvg_acf_prev_label = trim( (string) zvg_acf_post_option( 'post_nav_prev_label' ) );
		$zvg_acf_next_label = trim( (string) zvg_acf_post_option( 'post_nav_next_label' ) );

		$zvg_acf_prev = get_previous_post_link(
			'<div class="zvg-acf-entry__nav-item zvg-acf-entry__nav-item--prev">%link</div>',
			'' === $zvg_acf_prev_label ? '%title' : esc_html( $zvg_acf_prev_label )
		);

		$zvg_acf_next = get_next_post_link(
			'<div class="zvg-acf-entry__nav-item zvg-acf-entry__nav-item--next">%link</div>',
			'' === $zvg_acf_next_label ? '%title' : esc_html( $zvg_acf_next_label )
		);
	}
	?>
<article <?php post_class( 'zvg-acf-entry zvg-acf-entry--post' ); ?>>
	<?php if ( ! is_wp_error( $zvg_acf_terms ) && ! empty( $zvg_acf_terms ) ) { ?>
	<p class="zvg-acf-entry__terms">
		<?php
		echo $zvg_acf_terms; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_term_list() markup, escaped by core.
		?>
	</p>
	<?php } ?>

	<?php if ( ! empty( $zvg_acf_title ) ) { ?>
	<h1 class="zvg-acf-entry__title"><?php echo esc_html( $zvg_acf_title ); ?></h1>
	<?php } ?>

	<p class="zvg-acf-entry__meta">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</p>

	<?php if ( has_post_thumbnail() ) { ?>
		<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'zvg-acf-entry__image' ) ); ?>
	<?php } ?>

	<?php if ( ! empty( $zvg_acf_content ) ) { ?>
	<div class="zvg-acf-entry__content">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
	<?php } ?>

	<?php zvg_acf_render_share_links( $zvg_acf_id ); ?>

	<?php if ( ! empty( $zvg_acf_prev ) || ! empty( $zvg_acf_next ) ) { ?>
	<nav class="zvg-acf-entry__nav" aria-label="<?php echo esc_attr_x( 'Posts', 'Post navigation label', 'zvg-acf' ); ?>">
		<?php
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- adjacent post link markup, escaped by core; the link text is escaped where it is passed in.
		echo $zvg_acf_prev;
		echo $zvg_acf_next;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</nav>
	<?php } ?>
</article>
	<?php
}

get_footer();
