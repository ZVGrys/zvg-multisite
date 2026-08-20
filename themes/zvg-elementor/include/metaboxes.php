<?php
/**
 * The team member's dialog fields — via CMB2, as a deliberate contrast with
 * FSE's native metabox for the same feature.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'zvg_elementor_member_metabox' ) ) {

	/**
	 * Register the member details metabox.
	 */
	function zvg_elementor_member_metabox() {
		$cmb = new_cmb2_box(
			array(
				'id'           => 'zvg_elementor_member_details',
				'title'        => esc_html__( 'Member details', 'zvg-elementor' ),
				'object_types' => array( 'zvg_member' ),
			)
		);

		$cmb->add_field(
			array(
				'name' => esc_html__( 'Link', 'zvg-elementor' ),
				'desc' => esc_html__( 'Shown as the "Get in touch" link in the dialog. Leave empty to drop the link.', 'zvg-elementor' ),
				'id'   => '_zvg_member_link',
				'type' => 'text_url',
			)
		);

		$cmb->add_field(
			array(
				'name'    => esc_html__( 'Popup text', 'zvg-elementor' ),
				'id'      => '_zvg_member_profile',
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 10,
					'media_buttons' => false,
				),
			)
		);
	}

	add_action( 'cmb2_admin_init', 'zvg_elementor_member_metabox' );
}
