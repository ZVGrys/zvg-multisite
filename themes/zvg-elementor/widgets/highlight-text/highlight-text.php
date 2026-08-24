<?php
/**
 * Highlight text widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * A line of text in which a marked part is styled apart from the rest.
 */
class ZVG_Elementor_Highlight_Text extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-highlight-text',
			ZVG_ELEMENTOR_T_URI . '/widgets/highlight-text/highlight-text.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/highlight-text/highlight-text.css' )
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-highlight-text';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Highlight Text', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-t-letter';
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
		return array( 'zvg-elementor-highlight-text' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => _x( 'Built by {{the person who signs it}}.', 'Highlight text placeholder', 'zvg-elementor' ),
				'description' => esc_html__( 'Put double braces around the part you want highlighted: Built by {{someone}}.', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML tag', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'p',
				'options' => array(
					'p'    => 'p',
					'div'  => 'div',
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'span' => 'span',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Text', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-highlight-text',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-highlight-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'zvg-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'zvg-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'zvg-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'zvg-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-highlight-text' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_highlight',
			array(
				'label' => esc_html__( 'Highlight', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'highlight_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-highlight-text__mark',
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-highlight-text__mark' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'highlight_background',
			array(
				'label'     => esc_html__( 'Background', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-highlight-text__mark' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$text     = isset( $settings['text'] ) ? trim( $settings['text'] ) : '';

		if ( '' === $text ) {
			return;
		}

		$allowed = array( 'p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span' );
		$tag     = in_array( $settings['html_tag'], $allowed, true ) ? $settings['html_tag'] : 'p';

		$marked = preg_replace_callback(
			'/\{\{(.+?)\}\}/s',
			static function ( $matches ) {
				return '<span class="zvg-elementor-highlight-text__mark">' . $matches[1] . '</span>';
			},
			esc_html( $text )
		);

		printf(
			'<%1$s class="zvg-elementor-highlight-text">%2$s</%1$s>',
			esc_html( $tag ),
			$marked // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the text is escaped before the marks are turned into spans.
		);
	}
}
