<?php
/**
 * Content types the landing page demonstrates.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_member_post_type' );
add_action( 'init', 'zvg_fse_register_member_role_taxonomy' );
add_filter( 'term_links-zvg_member_role', 'zvg_fse_plain_member_roles' );
add_filter( 'query_loop_block_query_vars', 'zvg_fse_query_members', 10, 2 );
add_action( 'template_redirect', 'zvg_fse_close_member_role_archive' );
add_filter( 'rest_endpoints', 'zvg_fse_close_member_rest_reads' );

/**
 * Register the team member post type.
 */
function zvg_fse_register_member_post_type() {
	$labels = array(
		'name'                  => _x( 'Team members', 'Post type general name', 'zvg-fse' ),
		'singular_name'         => _x( 'Team member', 'Post type singular name', 'zvg-fse' ),
		'menu_name'             => _x( 'Team', 'Admin menu text', 'zvg-fse' ),
		'add_new_item'          => __( 'Add team member', 'zvg-fse' ),
		'edit_item'             => __( 'Edit team member', 'zvg-fse' ),
		'view_item'             => __( 'View team member', 'zvg-fse' ),
		'search_items'          => __( 'Search team members', 'zvg-fse' ),
		'not_found'             => __( 'No team members found.', 'zvg-fse' ),
		'not_found_in_trash'    => __( 'No team members found in Trash.', 'zvg-fse' ),
		'featured_image'        => __( 'Portrait', 'zvg-fse' ),
		'set_featured_image'    => __( 'Set portrait', 'zvg-fse' ),
		'remove_featured_image' => __( 'Remove portrait', 'zvg-fse' ),
		'use_featured_image'    => __( 'Use as portrait', 'zvg-fse' ),
		'archives'              => __( 'Team member archives', 'zvg-fse' ),
		'item_published'        => __( 'Team member published.', 'zvg-fse' ),
		'item_updated'          => __( 'Team member updated.', 'zvg-fse' ),
	);

	register_post_type(
		'zvg_member',
		array(
			'labels'              => $labels,
			'description'         => __( 'Fictional profiles that demonstrate a custom post type across the three builds.', 'zvg-fse' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
			'taxonomies'          => array( 'zvg_member_role' ),
		)
	);
}

/**
 * Register the member role taxonomy.
 *
 * `publicly_queryable` has to stay true: `render_block_core_post_terms()` bails on
 * `is_taxonomy_viewable()`, which reads exactly that flag, so turning it off empties the
 * role line on every team card. The archive it opens up is closed again by
 * `zvg_fse_close_member_role_archive()`.
 */
function zvg_fse_register_member_role_taxonomy() {
	$labels = array(
		'name'          => _x( 'Roles', 'Taxonomy general name', 'zvg-fse' ),
		'singular_name' => _x( 'Role', 'Taxonomy singular name', 'zvg-fse' ),
		'menu_name'     => __( 'Roles', 'zvg-fse' ),
		'add_new_item'  => __( 'Add role', 'zvg-fse' ),
		'edit_item'     => __( 'Edit role', 'zvg-fse' ),
		'search_items'  => __( 'Search roles', 'zvg-fse' ),
		'not_found'     => __( 'No roles found.', 'zvg-fse' ),
	);

	register_taxonomy(
		'zvg_member_role',
		'zvg_member',
		array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'rewrite'            => false,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		)
	);
}

/**
 * Point a query block at the member post type.
 *
 * @param array    $query Query variables.
 * @param WP_Block $block Block instance.
 *
 * @return array
 */
function zvg_fse_query_members( $query, $block ) {
	$requested = isset( $block->context['query']['postType'] ) ? $block->context['query']['postType'] : '';

	if ( 'zvg_member' === $requested ) {
		$query['post_type'] = 'zvg_member';
	}

	return $query;
}

/**
 * Answer the role archive with a 404.
 */
function zvg_fse_close_member_role_archive() {
	if ( ! is_tax( 'zvg_member_role' ) ) {
		return;
	}

	global $wp_query;

	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}

/**
 * Render member roles without links.
 *
 * @param string[] $links Term links.
 *
 * @return string[]
 */
function zvg_fse_plain_member_roles( $links ) {
	return array_map( 'wp_strip_all_tags', $links );
}

/**
 * The post type and its taxonomy have no pages of their own, so their REST routes
 * answer editors only. The editor keeps working because whoever opens it is signed in.
 *
 * @param array<string, array<int, array<string, mixed>>> $endpoints Registered REST routes.
 *
 * @return array<string, array<int, array<string, mixed>>>
 */
function zvg_fse_close_member_rest_reads( $endpoints ) {
	$routes = array(
		'/wp/v2/zvg_member',
		'/wp/v2/zvg_member/(?P<id>[\d]+)',
		'/wp/v2/zvg_member/(?P<parent>[\d]+)/revisions',
		'/wp/v2/zvg_member/(?P<parent>[\d]+)/autosaves',
		'/wp/v2/zvg_member_role',
		'/wp/v2/zvg_member_role/(?P<id>[\d]+)',
	);

	foreach ( $routes as $route ) {
		if ( ! isset( $endpoints[ $route ] ) ) {
			continue;
		}

		foreach ( $endpoints[ $route ] as $index => $endpoint ) {
			if ( ! isset( $endpoint['methods'] ) || false === strpos( (string) $endpoint['methods'], 'GET' ) ) {
				continue;
			}

			$original = isset( $endpoint['permission_callback'] ) ? $endpoint['permission_callback'] : null;

			$endpoints[ $route ][ $index ]['permission_callback'] = static function ( $request ) use ( $original ) {
				if ( ! current_user_can( 'edit_posts' ) ) {
					return new WP_Error(
						'rest_forbidden',
						__( 'Team members and their roles are not readable over the REST API.', 'zvg-fse' ),
						array( 'status' => rest_authorization_required_code() )
					);
				}

				return is_callable( $original ) ? call_user_func( $original, $request ) : true;
			};
		}
	}

	return $endpoints;
}
