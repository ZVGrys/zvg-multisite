<?php
/**
 * The blog section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title     = trim( (string) get_sub_field( 'title' ) );
$zvg_acf_intro     = trim( (string) get_sub_field( 'intro' ) );
$zvg_acf_per_page  = (int) get_sub_field( 'posts_per_page' );
$zvg_acf_order     = 'ASC' === get_sub_field( 'order' ) ? 'ASC' : 'DESC';
$zvg_acf_category  = (int) get_sub_field( 'category' );
$zvg_acf_title_tag = get_sub_field( 'title_tag' );
$zvg_acf_title_tag = in_array( $zvg_acf_title_tag, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $zvg_acf_title_tag : 'h3';
$zvg_acf_format    = trim( (string) get_sub_field( 'date_format' ) );
$zvg_acf_format    = '' === $zvg_acf_format ? (string) get_option( 'date_format' ) : $zvg_acf_format;
$zvg_acf_link_text = trim( (string) get_sub_field( 'link_text' ) );

$zvg_acf_args = array(
	'post_type'              => 'post',
	'post_status'            => 'publish',
	'posts_per_page'         => $zvg_acf_per_page > 0 ? $zvg_acf_per_page : 3,
	'orderby'                => 'date',
	'order'                  => $zvg_acf_order,
	'ignore_sticky_posts'    => true,
	'no_found_rows'          => true,
	'update_post_term_cache' => false,
);

if ( $zvg_acf_category > 0 ) {
	$zvg_acf_args['cat'] = $zvg_acf_category;
}

$zvg_acf_posts = new WP_Query( $zvg_acf_args );

if ( ! $zvg_acf_posts->have_posts() ) {
	return;
}

?>
<section class="zvg-acf-section zvg-acf-blog" id="blog">
	<div class="zvg-acf-section__inner">
		<?php if ( '' !== $zvg_acf_title ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_intro ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-blog__grid">
			<?php
			while ( $zvg_acf_posts->have_posts() ) {
				$zvg_acf_posts->the_post();

				$zvg_acf_excerpt = trim( (string) get_the_excerpt() );
				?>
			<article class="zvg-acf-post">
				<?php if ( has_post_thumbnail() ) { ?>
				<a class="zvg-acf-post__thumbnail-link" href="<?php the_permalink(); ?>">
					<?php
					the_post_thumbnail(
						'medium_large',
						array(
							'class'   => 'zvg-acf-post__thumbnail',
							'loading' => 'lazy',
						)
					);
					?>
				</a>
				<?php } ?>

				<p class="zvg-acf-post__date">
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( $zvg_acf_format ) ); ?></time>
				</p>

				<?php
				printf(
					'<%1$s class="zvg-acf-post__title"><a href="%2$s">%3$s</a></%1$s>',
					esc_html( $zvg_acf_title_tag ),
					esc_url( (string) get_permalink() ),
					esc_html( get_the_title() )
				);
				?>

				<?php if ( '' !== $zvg_acf_excerpt ) { ?>
				<p class="zvg-acf-post__excerpt"><?php echo esc_html( $zvg_acf_excerpt ); ?></p>
				<?php } ?>

				<?php if ( '' !== $zvg_acf_link_text ) { ?>
				<a class="zvg-acf-post__link" href="<?php the_permalink(); ?>">
					<?php echo esc_html( $zvg_acf_link_text ); ?>
					<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
				</a>
				<?php } ?>
			</article>
				<?php
			}

			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
