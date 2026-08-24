<?php
/**
 * Token flow widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * The route design tokens take out of one source and into each build, drawn as a diagram.
 */
class ZVG_Elementor_Token_Flow extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-token-flow',
			ZVG_ELEMENTOR_T_URI . '/widgets/token-flow/token-flow.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/token-flow/token-flow.css' )
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-token-flow';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Token Flow', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-sitemap';
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
		return array( 'zvg-elementor-token-flow' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_source',
			array(
				'label' => esc_html__( 'Source', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'source_name',
			array(
				'label'       => esc_html__( 'Name', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Figma variables', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'source_meta',
			array(
				'label'       => esc_html__( 'Note', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'colour · type · spacing · radius · shadow', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_outputs',
			array(
				'label' => esc_html__( 'Outputs', 'zvg-elementor' ),
			)
		);

		$outputs = new Repeater();

		$outputs->add_control(
			'name',
			array(
				'label'       => esc_html__( 'Name', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'theme.json', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$outputs->add_control(
			'meta',
			array(
				'label'       => esc_html__( 'Note', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'FSE', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'outputs',
			array(
				'label'       => esc_html__( 'Outputs', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $outputs->get_controls(),
				'title_field' => '{{{ name }}}',
				'min_items'   => 3,
				'max_items'   => 3,
				'default'     => array(
					array(
						'name' => esc_html__( 'theme.json', 'zvg-elementor' ),
						'meta' => esc_html__( 'FSE', 'zvg-elementor' ),
					),
					array(
						'name' => esc_html__( 'Elementor kit', 'zvg-elementor' ),
						'meta' => esc_html__( 'Elementor', 'zvg-elementor' ),
					),
					array(
						'name' => esc_html__( 'SCSS variables', 'zvg-elementor' ),
						'meta' => esc_html__( 'ACF theme', 'zvg-elementor' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_source',
			array(
				'label' => esc_html__( 'Source', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'source_name_typography',
				'label'    => esc_html__( 'Name', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-token-flow__source .zvg-elementor-token-flow__name',
			)
		);

		$this->add_control(
			'source_name_color',
			array(
				'label'     => esc_html__( 'Name color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-token-flow__source .zvg-elementor-token-flow__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_outputs',
			array(
				'label' => esc_html__( 'Outputs', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'output_name_typography',
				'label'    => esc_html__( 'Name', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-token-flow__output .zvg-elementor-token-flow__name',
			)
		);

		$this->add_control(
			'output_name_color',
			array(
				'label'     => esc_html__( 'Name color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-token-flow__output .zvg-elementor-token-flow__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'label'    => esc_html__( 'Note', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-token-flow__meta',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Note color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-token-flow__meta' => 'color: {{VALUE}};',
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
		$outputs  = ( isset( $settings['outputs'] ) && is_array( $settings['outputs'] ) ) ? $settings['outputs'] : array();

		$outputs = array_filter(
			$outputs,
			static function ( $output ) {
				return ! empty( $output['name'] ) || ! empty( $output['meta'] );
			}
		);

		if ( empty( $outputs ) ) {
			return;
		}

		$total = count( $outputs );
		?>
		<div class="zvg-elementor-token-flow">
			<?php if ( ! empty( $settings['source_name'] ) || ! empty( $settings['source_meta'] ) ) { ?>
			<p class="zvg-elementor-token-flow__source">
				<?php if ( ! empty( $settings['source_name'] ) ) { ?>
					<span class="zvg-elementor-token-flow__name"><?php echo esc_html( $settings['source_name'] ); ?></span>
				<?php } ?>

				<?php if ( ! empty( $settings['source_meta'] ) ) { ?>
					<span class="zvg-elementor-token-flow__meta"><?php echo esc_html( $settings['source_meta'] ); ?></span>
				<?php } ?>
			</p>
			<?php } ?>

			<?php // The connectors carry no meaning of their own: the list below says the same thing in words. ?>
			<div class="zvg-elementor-token-flow__trunk" aria-hidden="true"></div>

			<?php if ( $total > 1 ) { ?>
			<div class="zvg-elementor-token-flow__elbows" aria-hidden="true">
				<div class="zvg-elementor-token-flow__elbow zvg-elementor-token-flow__elbow--left"></div>
				<div class="zvg-elementor-token-flow__elbow zvg-elementor-token-flow__elbow--right"></div>

				<?php
				for ( $position = 1; $position < $total - 1; $position++ ) {
					printf(
						'<div class="zvg-elementor-token-flow__mid" style="--zvg-elementor-mid:%s%%"></div>',
						esc_attr( (string) round( ( $position + 0.5 ) / $total * 100, 4 ) )
					);
				}
				?>
			</div>
			<?php } ?>

			<ul class="zvg-elementor-token-flow__outputs">
				<?php
				foreach ( $outputs as $output ) {
					?>
				<li class="zvg-elementor-token-flow__output">
					<?php if ( ! empty( $output['name'] ) ) { ?>
						<span class="zvg-elementor-token-flow__name"><?php echo esc_html( $output['name'] ); ?></span>
					<?php } ?>

					<?php if ( ! empty( $output['meta'] ) ) { ?>
						<span class="zvg-elementor-token-flow__meta"><?php echo esc_html( $output['meta'] ); ?></span>
					<?php } ?>
				</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}
}
