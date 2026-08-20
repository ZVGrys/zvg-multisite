<?php
/**
 * Content types the landing page demonstrates.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_elementor_register_member_post_type' );
add_action( 'init', 'zvg_elementor_register_member_role_taxonomy' );
add_action( 'template_redirect', 'zvg_elementor_close_member_role_archive' );
add_filter( 'rest_endpoints', 'zvg_elementor_close_member_rest_reads' );

/**
 * Register the team member post type.
 */
function zvg_elementor_register_member_post_type() {
	$labels = array(
		'name'                  => _x( 'Team members', 'Post type general name', 'zvg-elementor' ),
		'singular_name'         => _x( 'Team member', 'Post type singular name', 'zvg-elementor' ),
		'menu_name'             => _x( 'Team', 'Admin menu text', 'zvg-elementor' ),
		'add_new_item'          => __( 'Add team member', 'zvg-elementor' ),
		'edit_item'             => __( 'Edit team member', 'zvg-elementor' ),
		'view_item'             => __( 'View team member', 'zvg-elementor' ),
		'search_items'          => __( 'Search team members', 'zvg-elementor' ),
		'not_found'             => __( 'No team members found.', 'zvg-elementor' ),
		'not_found_in_trash'    => __( 'No team members found in Trash.', 'zvg-elementor' ),
		'featured_image'        => __( 'Portrait', 'zvg-elementor' ),
		'set_featured_image'    => __( 'Set portrait', 'zvg-elementor' ),
		'remove_featured_image' => __( 'Remove portrait', 'zvg-elementor' ),
		'use_featured_image'    => __( 'Use as portrait', 'zvg-elementor' ),
		'archives'              => __( 'Team member archives', 'zvg-elementor' ),
		'item_published'        => __( 'Team member published.', 'zvg-elementor' ),
		'item_updated'          => __( 'Team member updated.', 'zvg-elementor' ),
	);

	register_post_type(
		'zvg_member',
		array(
			'labels'              => $labels,
			'description'         => __( 'Fictional profiles that demonstrate a custom post type across the three builds.', 'zvg-elementor' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'          => array( 'zvg_member_role' ),
		)
	);
}

/**
 * Register the member role taxonomy.
 */
function zvg_elementor_register_member_role_taxonomy() {
	$labels = array(
		'name'          => _x( 'Roles', 'Taxonomy general name', 'zvg-elementor' ),
		'singular_name' => _x( 'Role', 'Taxonomy singular name', 'zvg-elementor' ),
		'menu_name'     => __( 'Roles', 'zvg-elementor' ),
		'add_new_item'  => __( 'Add role', 'zvg-elementor' ),
		'edit_item'     => __( 'Edit role', 'zvg-elementor' ),
		'search_items'  => __( 'Search roles', 'zvg-elementor' ),
		'not_found'     => __( 'No roles found.', 'zvg-elementor' ),
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
 * Answer the role archive with a 404.
 */
function zvg_elementor_close_member_role_archive() {
	if ( ! is_tax( 'zvg_member_role' ) ) {
		return;
	}

	global $wp_query;

	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}

/**
 * The post type has no pages of its own, so its REST routes answer editors only.
 * The editor keeps working because whoever opens it is signed in.
 *
 * @param array<string, array<int, array<string, mixed>>> $endpoints Registered REST routes.
 *
 * @return array<string, array<int, array<string, mixed>>>
 */
function zvg_elementor_close_member_rest_reads( $endpoints ) {
	$routes = array(
		'/wp/v2/zvg_member',
		'/wp/v2/zvg_member/(?P<id>[\d]+)',
		'/wp/v2/zvg_member/(?P<parent>[\d]+)/revisions',
		'/wp/v2/zvg_member/(?P<parent>[\d]+)/autosaves',
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
						__( 'Team members are not readable over the REST API.', 'zvg-elementor' ),
						array( 'status' => rest_authorization_required_code() )
					);
				}

				return is_callable( $original ) ? call_user_func( $original, $request ) : true;
			};
		}
	}

	return $endpoints;
}
