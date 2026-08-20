<?php
/**
 * Title: Section: Blog
 * Slug: zvg-fse/section-blog
 * Categories: zvg-fse-section
 * Description: The three most recent posts, each linking to the full article.
 * Keywords: blog, posts, articles, latest
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: Blog"},"align":"full","anchor":"blog","className":"zvg-fse-section zvg-fse-blog","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-blog" id="blog">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'From the blog', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"zvg-fse-blog__query","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-query zvg-fse-blog__query" style="margin-top:var(--wp--preset--spacing--50)">
			<!-- wp:post-template {"className":"zvg-fse-blog__grid"} -->
				<!-- wp:group {"className":"zvg-fse-archive__card zvg-fse-post","layout":{"type":"default"}} -->
				<div class="wp-block-group zvg-fse-archive__card zvg-fse-post">
					<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

					<!-- wp:post-date {"format":"d M Y","className":"zvg-fse-post__date"} /-->

					<!-- wp:post-title {"level":3,"isLink":true} /-->

					<!-- wp:post-excerpt {"excerptLength":20,"className":"zvg-fse-post__excerpt","textColor":"muted"} /-->

					<!-- wp:read-more {"content":"<?php echo esc_attr_x( 'Read more', 'Blog card link', 'zvg-fse' ); ?>","className":"zvg-fse-post__link is-style-arrow-link"} /-->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
