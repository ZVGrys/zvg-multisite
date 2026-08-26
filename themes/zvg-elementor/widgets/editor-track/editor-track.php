<?php
/**
 * Editor track widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * The same page opened for editing in each build, on a track that scrolls sideways.
 */
class ZVG_Elementor_Editor_Track extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-editor-track',
			ZVG_ELEMENTOR_T_URI . '/widgets/editor-track/editor-track.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/editor-track/editor-track.css' )
		);

		wp_register_script(
			'zvg-elementor-editor-track',
			ZVG_ELEMENTOR_T_URI . '/widgets/editor-track/editor-track.min.js',
			array(),
			zvg_elementor_get_asset_version( '/widgets/editor-track/editor-track.min.js' ),
			true
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-editor-track';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Editor Track', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-push';
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
		return array( 'zvg-elementor-editor-track' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-editor-track' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_items',
			array(
				'label' => esc_html__( 'Editors', 'zvg-elementor' ),
			)
		);

		$items = new Repeater();

		$items->add_control(
			'image',
			array(
				'label'       => esc_html__( 'Screenshot', 'zvg-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => esc_html__( 'Leave empty to show the placeholder text instead.', 'zvg-elementor' ),
			)
		);

		$items->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
			)
		);

		$items->add_control(
			'placeholder',
			array(
				'label'       => esc_html__( 'Placeholder text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Screenshot', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$items->add_control(
			'caption',
			array(
				'label'       => esc_html__( 'Caption', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Editors', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $items->get_controls(),
				'title_field' => '{{{ placeholder }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			array(
				'label' => esc_html__( 'Caption', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-editor-track__caption',
			)
		);

		$this->add_control(
			'caption_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-editor-track__caption' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_placeholder',
			array(
				'label' => esc_html__( 'Placeholder', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'placeholder_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-editor-track__placeholder',
			)
		);

		$this->add_control(
			'placeholder_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-editor-track__placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The registered size one screenshot is served at.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return string|int[] Size name, or a width/height pair for a custom size.
	 */
	protected function get_image_size( $item ) {
		$size = isset( $item['image_size'] ) ? $item['image_size'] : 'full';

		if ( 'custom' !== $size ) {
			return $size;
		}

		$custom = isset( $item['image_custom_dimension'] ) ? $item['image_custom_dimension'] : array();

		return array(
			isset( $custom['width'] ) ? (int) $custom['width'] : 0,
			isset( $custom['height'] ) ? (int) $custom['height'] : 0,
		);
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = ( isset( $settings['items'] ) && is_array( $settings['items'] ) ) ? $settings['items'] : array();

		$items = array_filter(
			$items,
			static function ( $item ) {
				return ! empty( $item['image']['url'] ) || ! empty( $item['placeholder'] ) || ! empty( $item['caption'] );
			}
		);

		if ( empty( $items ) ) {
			return;
		}

		?>
		<div class="zvg-elementor-editor-track" tabindex="0" role="group" aria-label="<?php echo esc_attr_x( 'Editor screenshots, scroll horizontally', 'Scrollable region', 'zvg-elementor' ); ?>">
			<?php
			foreach ( $items as $item ) {
				?>
			<figure class="zvg-elementor-editor-track__item">
				<?php if ( ! empty( $item['image']['id'] ) ) { ?>
					<?php
					echo wp_get_attachment_image(
						(int) $item['image']['id'],
						$this->get_image_size( $item ),
						false,
						array( 'class' => 'zvg-elementor-editor-track__shot' )
					);
					?>
				<?php } elseif ( ! empty( $item['placeholder'] ) ) { ?>
					<p class="zvg-elementor-editor-track__placeholder"><?php echo esc_html( $item['placeholder'] ); ?></p>
				<?php } ?>

				<?php if ( ! empty( $item['caption'] ) ) { ?>
					<figcaption class="zvg-elementor-editor-track__caption"><?php echo esc_html( $item['caption'] ); ?></figcaption>
				<?php } ?>
			</figure>
				<?php
			}
			?>
		</div>
		<?php
	}
}
