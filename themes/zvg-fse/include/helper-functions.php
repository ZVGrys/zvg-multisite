<?php
/**
 * Small reusable helpers.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'render_block_core/group', 'zvg_fse_mark_scroll_regions', 10, 2 );
add_filter( 'render_block_core/navigation-link', 'zvg_fse_send_anchors_home', 10, 2 );
add_filter( 'render_block_core/navigation-link', 'zvg_fse_resolve_privacy_link', 10, 2 );

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
 * Carry section anchors to the front page from templates that do not hold them.
 *
 * @param string $html  Rendered block markup.
 * @param array  $block Parsed block.
 *
 * @return string
 */
function zvg_fse_send_anchors_home( $html, $block ) {
	$url = isset( $block['attrs']['url'] ) ? $block['attrs']['url'] : '';

	if ( '' === $url || '#' !== substr( $url, 0, 1 ) ) {
		return $html;
	}

	$tags = new WP_HTML_Tag_Processor( $html );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'href', home_url( '/' ) . $url );
	}

	return $tags->get_updated_html();
}

/**
 * Point the footer's privacy link at the page WordPress holds as the privacy policy.
 *
 * @param string $html  Rendered block markup.
 * @param array  $block Parsed block.
 *
 * @return string
 */
function zvg_fse_resolve_privacy_link( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

	if ( false === strpos( $class, 'zvg-fse-footer__privacy' ) ) {
		return $html;
	}

	$url = get_privacy_policy_url();

	if ( '' === $url ) {
		return '';
	}

	$tags = new WP_HTML_Tag_Processor( $html );

	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'href', $url );
	}

	return $tags->get_updated_html();
}
