<?php
/**
 * Content types the landing page demonstrates.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_acf_register_member_post_type' );
add_action( 'init', 'zvg_acf_register_member_role_taxonomy' );

/**
 * Register the team member post type.
 */
function zvg_acf_register_member_post_type() {
	$labels = array(
		'name'                  => _x( 'Team members', 'Post type general name', 'zvg-acf' ),
		'singular_name'         => _x( 'Team member', 'Post type singular name', 'zvg-acf' ),
		'menu_name'             => _x( 'Team', 'Admin menu text', 'zvg-acf' ),
		'add_new_item'          => __( 'Add team member', 'zvg-acf' ),
		'edit_item'             => __( 'Edit team member', 'zvg-acf' ),
		'view_item'             => __( 'View team member', 'zvg-acf' ),
		'search_items'          => __( 'Search team members', 'zvg-acf' ),
		'not_found'             => __( 'No team members found.', 'zvg-acf' ),
		'not_found_in_trash'    => __( 'No team members found in Trash.', 'zvg-acf' ),
		'featured_image'        => __( 'Portrait', 'zvg-acf' ),
		'set_featured_image'    => __( 'Set portrait', 'zvg-acf' ),
		'remove_featured_image' => __( 'Remove portrait', 'zvg-acf' ),
		'use_featured_image'    => __( 'Use as portrait', 'zvg-acf' ),
		'archives'              => __( 'Team member archives', 'zvg-acf' ),
		'item_published'        => __( 'Team member published.', 'zvg-acf' ),
		'item_updated'          => __( 'Team member updated.', 'zvg-acf' ),
	);

	register_post_type(
		'zvg_member',
		array(
			'labels'              => $labels,
			'description'         => __( 'Fictional profiles that demonstrate a custom post type across the three builds.', 'zvg-acf' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
			'taxonomies'          => array( 'zvg_member_role' ),
		)
	);
}

/**
 * Register the member role taxonomy.
 */
function zvg_acf_register_member_role_taxonomy() {
	$labels = array(
		'name'          => _x( 'Roles', 'Taxonomy general name', 'zvg-acf' ),
		'singular_name' => _x( 'Role', 'Taxonomy singular name', 'zvg-acf' ),
		'menu_name'     => __( 'Roles', 'zvg-acf' ),
		'add_new_item'  => __( 'Add role', 'zvg-acf' ),
		'edit_item'     => __( 'Edit role', 'zvg-acf' ),
		'search_items'  => __( 'Search roles', 'zvg-acf' ),
		'not_found'     => __( 'No roles found.', 'zvg-acf' ),
	);

	register_taxonomy(
		'zvg_member_role',
		'zvg_member',
		array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'rewrite'            => false,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => false,
		)
	);
}
