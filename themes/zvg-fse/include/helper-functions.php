<?php
/**
 * Small reusable helpers.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', 'zvg_fse_redirect_empty_search' );
add_filter( 'render_block_core/group', 'zvg_fse_mark_scroll_regions', 10, 2 );
add_filter( 'render_block_core/navigation-link', 'zvg_fse_mark_current_build_link', 10, 2 );

/**
 * Send a search with no keyword back to the posts page.
 *
 * WordPress answers `?s=` as a search, so the document title and the `query-title` block
 * both render the empty term and the page is left indexable with nothing on it.
 */
function zvg_fse_redirect_empty_search() {
	if ( ! is_search() || '' !== trim( (string) get_query_var( 's' ) ) ) {
		return;
	}

	$posts_page = (int) get_option( 'page_for_posts' );
	$target     = $posts_page ? get_permalink( $posts_page ) : home_url( '/' );

	if ( ! $target ) {
		return;
	}

	wp_safe_redirect( $target, 302 );
	exit;
}

/**
 * Serialise block attributes for a block comment.
 *
 * `esc_attr()` is wrong here — the parser runs `json_decode()` on this string, so an
 * entity-encoded apostrophe from a translation would survive into the rendered label.
 *
 * @param array $attributes Block attributes.
 *
 * @return string Attributes as JSON, or an empty string when there are none.
 */
function zvg_fse_block_attrs( $attributes ) {
	return $attributes ? (string) wp_json_encode( $attributes ) : '';
}

/**
 * The post card and its query, shared by every template that lists posts.
 *
 * Lives in PHP because `templates/*.html` cannot call `__()`, which would freeze the
 * card's link and pagination labels in English.
 *
 * @param string $no_results Sentence shown when the query returns nothing.
 *
 * @return string Block markup.
 */
function zvg_fse_posts_loop( $no_results ) {
	$query = zvg_fse_block_attrs(
		array(
			'queryId'   => 0,
			'query'     => array(
				'perPage'  => 10,
				'pages'    => 0,
				'offset'   => 0,
				'postType' => 'post',
				'order'    => 'desc',
				'orderBy'  => 'date',
				'inherit'  => true,
			),
			'className' => 'zvg-fse-archive',
			'layout'    => array( 'type' => 'default' ),
		)
	);

	$read_more = zvg_fse_block_attrs(
		array(
			'content'   => _x( 'Read more', 'Post card link', 'zvg-fse' ),
			'className' => 'zvg-fse-post__link is-style-arrow-link',
		)
	);

	$previous = zvg_fse_block_attrs( array( 'label' => _x( 'Previous', 'Pagination', 'zvg-fse' ) ) );
	$next     = zvg_fse_block_attrs( array( 'label' => _x( 'Next', 'Pagination', 'zvg-fse' ) ) );

	$pagination = zvg_fse_block_attrs(
		array(
			'style'  => array( 'spacing' => array( 'margin' => array( 'top' => 'var:preset|spacing|60' ) ) ),
			'layout' => array(
				'type'           => 'flex',
				'justifyContent' => 'center',
			),
		)
	);

	ob_start();
	?>
<!-- wp:query <?php echo $query; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> -->
<div class="wp-block-query zvg-fse-archive">
	<!-- wp:post-template {"className":"zvg-fse-archive__grid"} -->
		<!-- wp:group {"className":"zvg-fse-archive__card zvg-fse-post","layout":{"type":"default"}} -->
		<div class="wp-block-group zvg-fse-archive__card zvg-fse-post">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

			<!-- wp:post-date {"className":"zvg-fse-post__date"} /-->

			<!-- wp:post-title {"level":2,"isLink":true} /-->

			<!-- wp:post-excerpt {"excerptLength":20,"className":"zvg-fse-post__excerpt","textColor":"muted"} /-->

			<!-- wp:read-more <?php echo $read_more; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination <?php echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> -->
		<!-- wp:query-pagination-previous <?php echo $previous; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next <?php echo $next; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p><?php echo esc_html( $no_results ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
	<?php
	return (string) ob_get_clean();
}

/**
 * Groups that scroll sideways, and the label each one announces.
 *
 * @return array<string, string> Class name => accessible label.
 */
function zvg_fse_scroll_regions() {
	return apply_filters(
		'zvg_fse_scroll_regions',
		array(
			'zvg-fse-editors__track' => _x( 'Editor screenshots, scroll horizontally', 'Scrollable region', 'zvg-fse' ),
		)
	);
}

/**
 * Turn the groups listed above into keyboard-reachable regions.
 *
 * @param string $html  Rendered block markup.
 * @param array  $block Parsed block.
 *
 * @return string
 */
function zvg_fse_mark_scroll_regions( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

	if ( '' === $class ) {
		return $html;
	}

	foreach ( zvg_fse_scroll_regions() as $region => $label ) {
		if ( false === strpos( $class, $region ) ) {
			continue;
		}

		$tags = new WP_HTML_Tag_Processor( $html );

		if ( $tags->next_tag() ) {
			$tags->set_attribute( 'tabindex', '0' );
			$tags->set_attribute( 'role', 'group' );
			$tags->set_attribute( 'aria-label', $label );
		}

		return $tags->get_updated_html();
	}

	return $html;
}

/**
 * Mark the footer link that points at the build being viewed.
 *
 * `core/navigation-link` has no attribute for `aria-current`, so it has to be written at
 * render time. The address itself does not: `patterns/footer-nav.php` resolves every URL
 * before the markup is built, which is what keeps the menu readable in the editor.
 *
 * @param string $html  Rendered block markup.
 * @param array  $block Parsed block.
 *
 * @return string
 */
function zvg_fse_mark_current_build_link( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

	if ( false === strpos( $class, 'zvg-fse-footer__build--current' ) ) {
		return $html;
	}

	$tags = new WP_HTML_Tag_Processor( $html );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'aria-current', 'page' );
	}

	return $tags->get_updated_html();
}
