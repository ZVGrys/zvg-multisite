<?php
/**
 * ZVG Share widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Share links for a single entry.
 */
class ZVG_Elementor_Share extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-share';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Share', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-share';
	}

	/**
	 * Widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'zvg-elementor' );
	}

	/**
	 * Stylesheets this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'zvg-elementor-share' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-share' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Share', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( zvg_elementor_share_networks() as $zvg_elementor_key => $zvg_elementor_network ) {
			$this->add_control(
				$zvg_elementor_key,
				array(
					'label'        => esc_html( $zvg_elementor_network['name'] ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Show', 'zvg-elementor' ),
					'label_off'    => esc_html__( 'Hide', 'zvg-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->add_control(
			'copy',
			array(
				'label'        => esc_html_x( 'Copy link', 'Share links', 'zvg-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'zvg-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'zvg-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		if ( ! function_exists( 'zvg_elementor_render_share_links' ) ) {
			return;
		}

		$zvg_elementor_post_id = get_the_ID();

		if ( ! $zvg_elementor_post_id ) {
			return;
		}

		$zvg_elementor_settings = $this->get_settings_for_display();
		$zvg_elementor_chosen   = array( 'copy' => ! empty( $zvg_elementor_settings['copy'] ) );

		foreach ( array_keys( zvg_elementor_share_networks() ) as $zvg_elementor_key ) {
			$zvg_elementor_chosen[ $zvg_elementor_key ] = ! empty( $zvg_elementor_settings[ $zvg_elementor_key ] );
		}

		zvg_elementor_render_share_links( $zvg_elementor_post_id, $zvg_elementor_chosen );
	}
}
