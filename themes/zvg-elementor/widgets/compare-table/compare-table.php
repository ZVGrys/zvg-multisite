<?php
/**
 * Compare table widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * The same checks run against each build, side by side.
 */
class ZVG_Elementor_Compare_Table extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-compare-table',
			ZVG_ELEMENTOR_T_URI . '/widgets/compare-table/compare-table.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/compare-table/compare-table.css' )
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-compare-table';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Compare Table', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-table';
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
		return array( 'zvg-elementor-compare-table' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_table',
			array(
				'label' => esc_html__( 'Table', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'       => esc_html__( 'Caption for screen readers', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$columns = new Repeater();

		$columns->add_control(
			'name',
			array(
				'label'       => esc_html__( 'Name', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'       => esc_html__( 'Columns', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $columns->get_controls(),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rows',
			array(
				'label' => esc_html__( 'Rows', 'zvg-elementor' ),
			)
		);

		$rows = new Repeater();

		$rows->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Label', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$rows->add_control(
			'values',
			array(
				'label'       => esc_html__( 'Values', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'One value per column, separated by a vertical bar: 6955 | 7100 | 6800. Leave a value empty for a check that has not been run yet.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => esc_html__( 'Rows', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rows->get_controls(),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_head',
			array(
				'label' => esc_html__( 'Column names', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'column_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-compare-table__table thead th, {{WRAPPER}} .zvg-elementor-compare-table__column',
			)
		);

		$this->add_control(
			'column_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-compare-table__table thead th' => 'color: {{VALUE}};',
					'{{WRAPPER}} .zvg-elementor-compare-table__column' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => esc_html__( 'Row labels', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-compare-table__table tbody th',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-compare-table__table tbody th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_value',
			array(
				'label' => esc_html__( 'Values', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-compare-table__table tbody td',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-compare-table__table tbody td' => 'color: {{VALUE}};',
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

		$columns = ( isset( $settings['columns'] ) && is_array( $settings['columns'] ) ) ? $settings['columns'] : array();
		$columns = array_values(
			array_filter(
				$columns,
				static function ( $column ) {
					return ! empty( $column['name'] );
				}
			)
		);

		$rows = ( isset( $settings['rows'] ) && is_array( $settings['rows'] ) ) ? $settings['rows'] : array();
		$rows = array_filter(
			$rows,
			static function ( $row ) {
				return ! empty( $row['label'] );
			}
		);

		if ( empty( $columns ) || empty( $rows ) ) {
			return;
		}

		/* translators: an em dash, standing in for a measurement that has not been taken yet. */
		$blank = _x( '—', 'Unmeasured statistic', 'zvg-elementor' );
		?>
		<div class="zvg-elementor-compare-table">
			<table class="zvg-elementor-compare-table__table">
				<?php if ( ! empty( $settings['caption'] ) ) { ?>
					<caption class="screen-reader-text"><?php echo esc_html( $settings['caption'] ); ?></caption>
				<?php } ?>

				<thead>
					<tr>
						<td></td>
						<?php foreach ( $columns as $column ) { ?>
						<th scope="col"><?php echo esc_html( $column['name'] ); ?></th>
						<?php } ?>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ( $rows as $row ) {
						$values = isset( $row['values'] ) ? array_map( 'trim', explode( '|', $row['values'] ) ) : array();
						?>
					<tr>
						<th scope="row"><?php echo esc_html( $row['label'] ); ?></th>
						<?php foreach ( $columns as $index => $column ) { ?>
						<td>
							<?php // Read out on narrow screens, where the stacked layout drops the header row. ?>
							<span class="zvg-elementor-compare-table__column"><?php echo esc_html( $column['name'] ); ?></span>
							<span class="zvg-elementor-compare-table__value"><?php echo esc_html( empty( $values[ $index ] ) ? $blank : $values[ $index ] ); ?></span>
						</td>
						<?php } ?>
					</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
