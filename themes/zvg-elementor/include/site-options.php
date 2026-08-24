<?php
/**
 * Customizer settings for the theme's own header, footer and not found page.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'zvg_elementor_logo_types' ) ) :

	/**
	 * The ways the header can present the site.
	 *
	 * @return array<string, string> Value => label.
	 */
	function zvg_elementor_logo_types() {
		return array(
			'text'  => _x( 'Site title', 'Header logo type', 'zvg-elementor' ),
			'image' => _x( 'Logo image', 'Header logo type', 'zvg-elementor' ),
		);
	}
endif;

if ( ! function_exists( 'zvg_elementor_sanitize_logo_type' ) ) :

	/**
	 * Keep the logo type within the offered choices.
	 *
	 * @param string $value Submitted value.
	 *
	 * @return string
	 */
	function zvg_elementor_sanitize_logo_type( $value ) {
		return array_key_exists( $value, zvg_elementor_logo_types() ) ? $value : 'text';
	}
endif;

if ( ! function_exists( 'zvg_elementor_logo_is_image' ) ) :

	/**
	 * Whether the header shows an uploaded logo.
	 *
	 * @return bool
	 */
	function zvg_elementor_logo_is_image() {
		return 'image' === get_theme_mod( 'zvg_elementor_header_logo_type', 'text' );
	}
endif;

if ( ! function_exists( 'zvg_elementor_logo_is_text' ) ) :

	/**
	 * Whether the header shows the site title.
	 *
	 * @return bool
	 */
	function zvg_elementor_logo_is_text() {
		return ! zvg_elementor_logo_is_image();
	}
endif;

if ( ! function_exists( 'zvg_elementor_move_control' ) ) :

	/**
	 * Re-home a control WordPress registered elsewhere.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @param string               $control      Control id.
	 * @param string               $section      Section to move it to.
	 * @param array                $args         Optional. 'priority' int, 'label' string to use
	 *                                           instead of the registered one, 'active_callback'
	 *                                           callable deciding whether it shows.
	 */
	function zvg_elementor_move_control( $wp_customize, $control, $section, $args = array() ) {
		$object = $wp_customize->get_control( $control );

		if ( ! $object ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			array(
				'priority'        => 10,
				'label'           => '',
				'active_callback' => null,
			)
		);

		$object->section  = $section;
		$object->priority = $args['priority'];

		if ( '' !== $args['label'] ) {
			$object->label = $args['label'];
		}

		if ( null !== $args['active_callback'] ) {
			$object->active_callback = $args['active_callback'];
		}
	}
endif;

if ( ! function_exists( 'zvg_elementor_customize_register' ) ) :

	/**
	 * Register the header, footer and not found sections.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	function zvg_elementor_customize_register( $wp_customize ) {
		$zvg_elementor_has_search = static function () {
			return (bool) get_theme_mod( 'zvg_elementor_error_show_search', true );
		};

		$wp_customize->add_section(
			'zvg_elementor_header',
			array(
				'title'       => _x( 'Header', 'Customizer section', 'zvg-elementor' ),
				'description' => esc_html__( 'Applies to the theme header. Elementor Theme Builder header replaces it.', 'zvg-elementor' ),
				'priority'    => 21,
			)
		);

		$wp_customize->add_section(
			'zvg_elementor_footer',
			array(
				'title'       => _x( 'Footer', 'Customizer section', 'zvg-elementor' ),
				'description' => esc_html__( 'Applies to the theme footer. Elementor Theme Builder footer replaces it.', 'zvg-elementor' ),
				'priority'    => 22,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_header_logo_type',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => 'text',
				'sanitize_callback' => 'zvg_elementor_sanitize_logo_type',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_header_logo_type',
			array(
				'type'    => 'radio',
				'section' => 'zvg_elementor_header',
				'label'   => __( 'Logo type', 'zvg-elementor' ),
				'choices' => zvg_elementor_logo_types(),
			)
		);

		zvg_elementor_move_control(
			$wp_customize,
			'custom_logo',
			'zvg_elementor_header',
			array(
				'priority'        => 20,
				'active_callback' => 'zvg_elementor_logo_is_image',
			)
		);

		zvg_elementor_move_control(
			$wp_customize,
			'blogname',
			'zvg_elementor_header',
			array(
				'priority'        => 30,
				'active_callback' => 'zvg_elementor_logo_is_text',
			)
		);

		zvg_elementor_move_control(
			$wp_customize,
			'nav_menu_locations[primary]',
			'zvg_elementor_header',
			array(
				'priority' => 40,
				'label'    => _x( 'Menu', 'Customizer control', 'zvg-elementor' ),
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_header_show_switcher',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_header_show_switcher',
			array(
				'type'     => 'checkbox',
				'section'  => 'zvg_elementor_header',
				'label'    => __( 'Show build switcher', 'zvg-elementor' ),
				'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_footer_copyright',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_footer_copyright',
			array(
				'type'        => 'textarea',
				'section'     => 'zvg_elementor_footer',
				'label'       => __( 'Copyright', 'zvg-elementor' ),
				'description' => esc_html__( 'For a dynamic year use {{year}}. For example: © {{year}} Zoriana Grys.', 'zvg-elementor' ),
				'priority'    => 10,
			)
		);

		zvg_elementor_move_control(
			$wp_customize,
			'nav_menu_locations[footer]',
			'zvg_elementor_footer',
			array(
				'priority' => 20,
				'label'    => _x( 'Menu', 'Customizer control', 'zvg-elementor' ),
			)
		);

		$wp_customize->add_section(
			'zvg_elementor_error',
			array(
				'title'       => _x( '404', 'Customizer section', 'zvg-elementor' ),
				'description' => esc_html__( 'Applies to the theme not found page. An Elementor Theme Builder 404 template replaces it.', 'zvg-elementor' ),
				'priority'    => 23,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_eyebrow',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_eyebrow',
			array(
				'type'     => 'text',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'Label', 'zvg-elementor' ),
				'priority' => 10,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_code',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_code',
			array(
				'type'     => 'text',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'Code', 'zvg-elementor' ),
				'priority' => 20,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_lead',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_lead',
			array(
				'type'     => 'text',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'Lead', 'zvg-elementor' ),
				'priority' => 30,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_show_search',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_show_search',
			array(
				'type'     => 'checkbox',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'Show search form', 'zvg-elementor' ),
				'priority' => 40,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_search_placeholder',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_search_placeholder',
			array(
				'type'            => 'text',
				'section'         => 'zvg_elementor_error',
				'label'           => __( 'Search placeholder', 'zvg-elementor' ),
				'priority'        => 50,
				'active_callback' => $zvg_elementor_has_search,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_search_button',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_search_button',
			array(
				'type'            => 'text',
				'section'         => 'zvg_elementor_error',
				'label'           => __( 'Search button', 'zvg-elementor' ),
				'priority'        => 60,
				'active_callback' => $zvg_elementor_has_search,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_search_hint',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_search_hint',
			array(
				'type'            => 'text',
				'section'         => 'zvg_elementor_error',
				'label'           => __( 'Search hint', 'zvg-elementor' ),
				'priority'        => 70,
				'active_callback' => $zvg_elementor_has_search,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_button_1_label',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_button_1_label',
			array(
				'type'     => 'text',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'First Button: Label', 'zvg-elementor' ),
				'priority' => 80,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_button_1_url',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_button_1_url',
			array(
				'type'        => 'url',
				'section'     => 'zvg_elementor_error',
				'label'       => __( 'First Button: URL', 'zvg-elementor' ),
				'description' => esc_html__( 'Leave empty to link to the homepage.', 'zvg-elementor' ),
				'priority'    => 90,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_button_2_label',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_button_2_label',
			array(
				'type'     => 'text',
				'section'  => 'zvg_elementor_error',
				'label'    => __( 'Second Button: Label', 'zvg-elementor' ),
				'priority' => 100,
			)
		);

		$wp_customize->add_setting(
			'zvg_elementor_error_button_2_url',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'zvg_elementor_error_button_2_url',
			array(
				'type'        => 'url',
				'section'     => 'zvg_elementor_error',
				'label'       => __( 'Second Button: URL', 'zvg-elementor' ),
				'description' => esc_html__( 'Leave empty to link to the posts page. The button is hidden while there is neither.', 'zvg-elementor' ),
				'priority'    => 110,
			)
		);
	}
endif;

add_action( 'customize_register', 'zvg_elementor_customize_register', 20 );
