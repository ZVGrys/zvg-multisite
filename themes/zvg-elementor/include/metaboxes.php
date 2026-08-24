<?php
/**
 * The team member's fields, via CMB2.
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
				'name'       => esc_html__( 'Short bio', 'zvg-elementor' ),
				'desc'       => esc_html__( 'One sentence, shown on the card under the role.', 'zvg-elementor' ),
				'id'         => '_zvg_member_bio',
				'type'       => 'textarea_small',
				'attributes' => array( 'rows' => 2 ),
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
				'desc'    => esc_html__( 'The long profile, shown inside the dialog. Leave empty and the card gets no button at all.', 'zvg-elementor' ),
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

