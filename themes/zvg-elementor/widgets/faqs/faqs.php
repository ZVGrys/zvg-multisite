<?php
/**
 * FAQs widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * A list of questions that expand to reveal their answer.
 */
class ZVG_Elementor_FAQS extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-faqs',
			ZVG_ELEMENTOR_T_URI . '/widgets/faqs/faqs.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/faqs/faqs.css' )
		);

		wp_register_script(
			'zvg-elementor-faqs',
			ZVG_ELEMENTOR_T_URI . '/widgets/faqs/faqs.min.js',
			array( 'elementor-frontend' ),
			zvg_elementor_get_asset_version( '/widgets/faqs/faqs.min.js' ),
			true
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-faqs';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG FAQs', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'dashicons dashicons-editor-table';
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
		return array( 'zvg-elementor-faqs' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-faqs' );
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
			'heading_tag',
			array(
				'label'   => esc_html__( 'Question tag', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
				),
			)
		);

		$faqs = new Repeater();

		$faqs->add_control(
			'question',
			array(
				'label'       => esc_html__( 'Question', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Question', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$faqs->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Answer', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'There is some text for FAQ item', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'faqs',
			array(
				'label'       => esc_html__( 'Items', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $faqs->get_controls(),
				'title_field' => '{{{ question }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['faqs'] ) ) {
			return;
		}

		$allowed = array( 'h2', 'h3', 'h4', 'h5' );
		$tag     = in_array( $settings['heading_tag'], $allowed, true ) ? $settings['heading_tag'] : 'h3';

		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(),
		);
		?>
		<div class="zvg-elementor-faqs">
			<?php
			foreach ( $settings['faqs'] as $index => $item ) {
				if ( empty( $item['question'] ) || empty( $item['text'] ) ) {
					continue;
				}

				$answer_id = $this->get_id() . '-faq-' . $index;

				$schema['mainEntity'][] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item['question'] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $item['text'] ),
					),
				);
				?>
			<div class="zvg-elementor-faqs__item">
				<?php printf( '<%s class="zvg-elementor-faqs__question">', esc_html( $tag ) ); ?>
					<button class="zvg-elementor-faqs__trigger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $answer_id ); ?>">
						<span class="zvg-elementor-faqs__label"><?php echo esc_html( $item['question'] ); ?></span>

						<span class="zvg-elementor-faqs__icon" aria-hidden="true">
							<svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
								<path d="M8.81152 9.32251L2.31509 2.32257" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
								<path d="M8.81141 9.32244L15.9998 2.32246" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
							</svg>
						</span>
					</button>
				<?php printf( '</%s>', esc_html( $tag ) ); ?>

				<div class="zvg-elementor-faqs__answer" id="<?php echo esc_attr( $answer_id ); ?>" hidden>
					<?php echo esc_html( $item['text'] ); ?>
				</div>
			</div>
				<?php
			}
			?>

			<?php if ( ! empty( $schema['mainEntity'] ) ) { ?>
			<script type="application/ld+json">
				<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode with JSON_HEX_TAG is the escaping for a JSON-LD body. ?>
			</script>
			<?php } ?>
		</div>
		<?php
	}
}
