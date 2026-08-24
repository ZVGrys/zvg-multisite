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
	 * The builds the table compares, as row-value key => control key holding the name.
	 *
	 * @return string[]
	 */
	private function get_column_keys() {
		return array(
			'value_fse'       => 'column_fse',
			'value_elementor' => 'column_elementor',
			'value_acf'       => 'column_acf',
		);
	}

	/**
	 * The name each column carries, as row-value key => name.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return string[]
	 */
	private function get_columns( $settings = array() ) {
		$defaults = array(
			'value_fse'       => _x( 'FSE', 'Compare table column', 'zvg-elementor' ),
			'value_elementor' => _x( 'Elementor', 'Compare table column', 'zvg-elementor' ),
			'value_acf'       => _x( 'ACF theme', 'Compare table column', 'zvg-elementor' ),
		);

		$columns = array();

		foreach ( $this->get_column_keys() as $value_key => $control_key ) {
			$name = isset( $settings[ $control_key ] ) ? trim( $settings[ $control_key ] ) : '';

			$columns[ $value_key ] = '' === $name ? $defaults[ $value_key ] : $name;
		}

		return $columns;
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

		$columns = $this->get_columns();

		foreach ( $this->get_column_keys() as $value_key => $control_key ) {
			$this->add_control(
				$control_key,
				array(
					'label'       => sprintf( esc_html__( 'Column: %s', 'zvg-elementor' ), $columns[ $value_key ] ),
					'type'        => Controls_Manager::TEXT,
					'default'     => $columns[ $value_key ],
					'label_block' => true,
				)
			);
		}

		$rows = new Repeater();

		$rows->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Label', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		foreach ( $columns as $key => $name ) {
			$rows->add_control(
				$key,
				array(
					'label'       => sprintf( esc_html__( '%s: Value', 'zvg-elementor' ), $name ),
					'type'        => Controls_Manager::TEXT,
					'description' => esc_html__( 'Leave empty for a check that has not been run yet.', 'zvg-elementor' ),
					'label_block' => true,
				)
			);
		}

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
		$columns  = $this->get_columns( $settings );

		$rows = ( isset( $settings['rows'] ) && is_array( $settings['rows'] ) ) ? $settings['rows'] : array();
		$rows = array_filter(
			$rows,
			static function ( $row ) {
				return ! empty( $row['label'] );
			}
		);

		if ( empty( $rows ) ) {
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
						<?php foreach ( $columns as $name ) { ?>
						<th scope="col"><?php echo esc_html( $name ); ?></th>
						<?php } ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ( $rows as $row ) { ?>
					<tr>
						<th scope="row"><?php echo esc_html( $row['label'] ); ?></th>
						<?php
						foreach ( $columns as $key => $name ) {
							$value = isset( $row[ $key ] ) ? trim( $row[ $key ] ) : '';
							?>
						<td>
							<?php // Read out on narrow screens, where the stacked layout drops the header row. ?>
							<span class="zvg-elementor-compare-table__column"><?php echo esc_html( $name ); ?></span>
							<span class="zvg-elementor-compare-table__value"><?php echo esc_html( '' === $value ? $blank : $value ); ?></span>
						</td>
							<?php
						}
						?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
