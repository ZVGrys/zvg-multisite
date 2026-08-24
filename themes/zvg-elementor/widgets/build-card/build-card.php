<?php
/**
 * Build card widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;


defined( 'ABSPATH' ) || exit;

/**
 * One build card, closing with its own set of measurements.
 */
class ZVG_Elementor_Build_Card extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-build-card',
			ZVG_ELEMENTOR_T_URI . '/widgets/build-card/build-card.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/build-card/build-card.css' )
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-build-card';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Build Card', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'dashicons dashicons-index-card';
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
		return array( 'zvg-elementor-build-card' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_card',
			array(
				'label' => esc_html__( 'Card', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Full Site Editing', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title tag', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Built entirely in the block editor: theme.json for styling, native block patterns for layout, no custom PHP templates.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'       => esc_html__( 'Link text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'View live build', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link',
			array(
				'label'   => esc_html__( 'Link', 'zvg-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url' => network_home_url( '/' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stats',
			array(
				'label' => esc_html__( 'Measurements', 'zvg-elementor' ),
			)
		);

		$stats = new Repeater();

		$stats->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Label', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lines of code', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$stats->add_control(
			'value',
			array(
				'label'       => esc_html__( 'Value', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Leave empty for a measurement that has not been taken yet.', 'zvg-elementor' ),
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => esc_html__( 'Measurements', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $stats->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'label' => __( 'Lines of code', 'zvg-elementor' ),
						'value' => '',
					),
					array(
						'label' => __( 'Page weight', 'zvg-elementor' ),
						'value' => '',
					),
					array(
						'label' => __( 'DOM nodes', 'zvg-elementor' ),
						'value' => '',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Title and text', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-build-card__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title colour', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-build-card__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Text', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-build-card__text',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text colour', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-build-card__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_link',
			array(
				'label' => esc_html__( 'Link', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-build-card__link',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Colour', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-build-card__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_stats',
			array(
				'label' => esc_html__( 'Measurements', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'label'    => esc_html__( 'Value', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-build-card__value',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Value colour', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-build-card__value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Label', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-build-card__label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Label colour', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-build-card__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render one measurement value, with any trailing unit in its own span.
	 *
	 * @param string $value Measurement value.
	 *
	 * @return string Escaped markup.
	 */
	protected function stat_value_markup( $value ) {
		$value = is_string( $value ) ? trim( $value ) : '';

		if ( '' === $value ) {
			/* translators: an em dash, standing in for a measurement that has not been taken yet. */
			$value = _x( '—', 'Unmeasured statistic', 'zvg-elementor' );
		}

		if ( preg_match( '/^([0-9][0-9\s.,]*)\s*([A-Za-z%]{1,4})$/', $value, $parts ) ) {
			return sprintf(
				'%1$s<span class="zvg-elementor-build-card__unit">%2$s</span>',
				esc_html( trim( $parts[1] ) ),
				esc_html( $parts[2] )
			);
		}

		return esc_html( $value );
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_tag = in_array( $settings['title_tag'] ?? '', array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $settings['title_tag'] : 'h3';

		$link      = ( isset( $settings['link'] ) && is_array( $settings['link'] ) ) ? $settings['link'] : array();
		$link_text = isset( $settings['link_text'] ) ? trim( $settings['link_text'] ) : '';
		$has_link  = '' !== $link_text && ! empty( $link['url'] );

		if ( $has_link ) {
			$this->add_render_attribute( 'link', 'class', 'zvg-elementor-build-card__link' );
			$this->add_link_attributes( 'link', $link );
		}

		$stats = ( isset( $settings['stats'] ) && is_array( $settings['stats'] ) ) ? $settings['stats'] : array();
		?>
		<article class="zvg-elementor-build-card">
			<?php if ( ! empty( $settings['title'] ) ) { ?>
				<?php
				printf(
					'<%1$s class="zvg-elementor-build-card__title">%2$s</%1$s>',
					esc_html( $title_tag ),
					esc_html( $settings['title'] )
				);
				?>
			<?php } ?>

			<?php if ( ! empty( $settings['text'] ) ) { ?>
				<p class="zvg-elementor-build-card__text"><?php echo esc_html( $settings['text'] ); ?></p>
			<?php } ?>

			<?php if ( $has_link ) { ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>>
					<?php echo esc_html( $link_text ); ?>
					<?php if ( ! empty( $settings['title'] ) ) { ?>
						<span class="screen-reader-text"><?php echo esc_html( ': ' . $settings['title'] ); ?></span>
					<?php } ?>
				</a>
			<?php } ?>

			<?php if ( ! empty( $stats ) ) { ?>
			<dl class="zvg-elementor-build-card__stats">
				<?php
				foreach ( $stats as $stat ) {
					$label = isset( $stat['label'] ) ? trim( $stat['label'] ) : '';

					if ( '' === $label ) {
						continue;
					}
					?>
				<div class="zvg-elementor-build-card__stat">
					<dt class="zvg-elementor-build-card__label"><?php echo esc_html( $label ); ?></dt>
					<dd class="zvg-elementor-build-card__value"><?php echo $this->stat_value_markup( isset( $stat['value'] ) ? $stat['value'] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every part is escaped in stat_value_markup(). ?></dd>
				</div>
					<?php
				}
				?>
			</dl>
			<?php } ?>
		</article>
		<?php
	}
}
