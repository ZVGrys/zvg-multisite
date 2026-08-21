<?php
/**
 * The team member's fields — plain WordPress, no field-management library.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'zvg_fse_register_member_metabox' );
add_action( 'save_post_zvg_member', 'zvg_fse_save_member_metabox' );

/**
 * Register the member details metabox.
 */
function zvg_fse_register_member_metabox() {
	add_meta_box(
		'zvg_fse_member_details',
		esc_html__( 'Member details', 'zvg-fse' ),
		'zvg_fse_render_member_metabox',
		'zvg_member',
		'normal',
		'high'
	);
}

/**
 * Render the member details fields.
 *
 * @param WP_Post $post The member being edited.
 */
function zvg_fse_render_member_metabox( $post ) {
	wp_nonce_field( 'zvg_fse_member_details', 'zvg_fse_member_details_nonce' );

	$bio     = get_post_meta( $post->ID, '_zvg_member_bio', true );
	$link    = get_post_meta( $post->ID, '_zvg_member_link', true );
	$profile = get_post_meta( $post->ID, '_zvg_member_profile', true );
	?>
	<p>
		<label for="zvg_fse_member_bio"><strong><?php esc_html_e( 'Short bio', 'zvg-fse' ); ?></strong></label><br>
		<textarea
			id="zvg_fse_member_bio"
			name="zvg_fse_member_bio"
			class="widefat"
			rows="2"
		><?php echo esc_textarea( $bio ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One sentence, shown on the card under the role.', 'zvg-fse' ); ?></span>
	</p>
	<p>
		<label for="zvg_fse_member_link"><strong><?php esc_html_e( 'Link', 'zvg-fse' ); ?></strong></label><br>
		<input
			type="url"
			id="zvg_fse_member_link"
			name="zvg_fse_member_link"
			class="widefat"
			value="<?php echo esc_attr( $link ); ?>"
			placeholder="#contact"
		>
		<span class="description"><?php esc_html_e( 'Shown as the "Get in touch" link in the dialog. Leave empty to drop the link.', 'zvg-fse' ); ?></span>
	</p>
	<p><label for="zvg_fse_member_profile"><strong><?php esc_html_e( 'Popup text', 'zvg-fse' ); ?></strong></label></p>
	<?php
	wp_editor(
		$profile,
		'zvg_fse_member_profile',
		array(
			'textarea_name' => 'zvg_fse_member_profile',
			'textarea_rows' => 10,
			'media_buttons' => false,
		)
	);
}

/**
 * Save the member details fields.
 *
 * @param int $post_id Member being saved.
 */
function zvg_fse_save_member_metabox( $post_id ) {
	if ( ! isset( $_POST['zvg_fse_member_details_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['zvg_fse_member_details_nonce'] ), 'zvg_fse_member_details' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['zvg_fse_member_bio'] ) ) {
		update_post_meta( $post_id, '_zvg_member_bio', sanitize_textarea_field( wp_unslash( $_POST['zvg_fse_member_bio'] ) ) );
	}

	if ( isset( $_POST['zvg_fse_member_link'] ) ) {
		update_post_meta( $post_id, '_zvg_member_link', esc_url_raw( wp_unslash( $_POST['zvg_fse_member_link'] ) ) );
	}

	if ( isset( $_POST['zvg_fse_member_profile'] ) ) {
		update_post_meta( $post_id, '_zvg_member_profile', wp_kses_post( wp_unslash( $_POST['zvg_fse_member_profile'] ) ) );
	}
}
