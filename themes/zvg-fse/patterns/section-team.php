<?php
/**
 * Title: Section: Team
 * Slug: zvg-fse/section-team
 * Categories: zvg-fse-section
 * Description: Six fictional profiles from the team member post type, each opening in a dialog.
 * Keywords: team, members, profiles, custom post type
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: Team"},"align":"full","anchor":"team","className":"zvg-fse-section zvg-fse-team","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-team" id="team">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'The team that did not build this', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'A demonstration of the same custom post type rendered in all three builds.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"className":"is-style-note","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
		<p class="is-style-note" style="margin-top:var(--wp--preset--spacing--50)"><?php echo esc_html_x( 'Fictional profiles — this section demonstrates a custom post type.', 'Team disclaimer', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"zvg_member","order":"asc","orderBy":"date","inherit":false},"className":"zvg-fse-team__query","layout":{"type":"default"}} -->
		<div class="wp-block-query zvg-fse-team__query">
			<!-- wp:post-template {"className":"zvg-fse-team__grid"} -->
				<!-- wp:post-featured-image {"isLink":false,"aspectRatio":"1","className":"zvg-fse-member__portrait"} /-->

				<!-- wp:post-title {"level":3,"isLink":false,"className":"zvg-fse-member__name"} /-->

				<!-- wp:post-terms {"term":"zvg_member_role","className":"zvg-fse-member__role"} /-->

				<!-- wp:zvg-fse/member-bio {"className":"zvg-fse-member__bio","textColor":"muted"} /-->

				<!-- wp:zvg-fse/member-trigger /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

		<!-- wp:zvg-fse/member-dialog /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
