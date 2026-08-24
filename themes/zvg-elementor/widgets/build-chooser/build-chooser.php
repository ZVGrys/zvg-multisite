<?php
/**
 * Build chooser widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * A few questions that weigh the answers and suggest one of the builds.
 */
class ZVG_Elementor_Build_Chooser extends Widget_Base {

	/**
	 * How many answers a question offers.
	 *
	 * @var int
	 */
	const CHOICES = 3;

	/**
	 * The builds the answers are weighed against.
	 *
	 * @var string[]
	 */
	const BUILDS = array( 'fse', 'elementor', 'acf' );

	/**
	 * The builds, with the names the editor sees.
	 *
	 * @return array<string, string>
	 */
	protected function get_builds() {
		return array(
			'fse'       => esc_html__( 'Full Site Editing', 'zvg-elementor' ),
			'elementor' => esc_html__( 'Elementor', 'zvg-elementor' ),
			'acf'       => esc_html__( 'ACF theme', 'zvg-elementor' ),
		);
	}

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-build-chooser',
			ZVG_ELEMENTOR_T_URI . '/widgets/build-chooser/build-chooser.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/build-chooser/build-chooser.css' )
		);

		wp_register_script(
			'zvg-elementor-build-chooser',
			ZVG_ELEMENTOR_T_URI . '/widgets/build-chooser/build-chooser.min.js',
			array(),
			zvg_elementor_get_asset_version( '/widgets/build-chooser/build-chooser.min.js' ),
			true
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-build-chooser';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Build Chooser', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
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
		return array( 'zvg-elementor-build-chooser' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-build-chooser' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_steps',
			array(
				'label' => esc_html__( 'Questions', 'zvg-elementor' ),
			)
		);

		$steps = new Repeater();

		$steps->add_control(
			'question',
			array(
				'label'       => esc_html__( 'Question', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$steps->add_control(
			'name',
			array(
				'label'       => esc_html__( 'Field name', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Groups the answers of this question. Lowercase, no spaces.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		foreach ( range( 1, self::CHOICES ) as $number ) {
			$steps->add_control(
				'choice_' . $number . '_heading',
				array(
					/* translators: %d: which answer of the question this is. */
					'label'     => sprintf( esc_html__( 'Answer %d', 'zvg-elementor' ), $number ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$steps->add_control(
				'choice_' . $number . '_label',
				array(
					'label'       => esc_html__( 'Label', 'zvg-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
				)
			);

			foreach ( $this->get_builds() as $build => $title ) {
				$steps->add_control(
					'choice_' . $number . '_' . $build,
					array(
						'label'     => $title,
						'type'      => Controls_Manager::NUMBER,
						'default'   => 0,
						'min'       => 0,
						'max'       => 9,
						'condition' => array(
							'choice_' . $number . '_label!' => '',
						),
					)
				);
			}
		}

		$this->add_control(
			'steps',
			array(
				'label'       => esc_html__( 'Questions', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $steps->get_controls(),
				'title_field' => '{{{ question }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_verdicts',
			array(
				'label' => esc_html__( 'Verdicts', 'zvg-elementor' ),
			)
		);

		$verdicts = new Repeater();

		$verdicts->add_control(
			'build',
			array(
				'label'   => esc_html__( 'Build', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_builds(),
			)
		);

		$verdicts->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$verdicts->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
			)
		);

		$this->add_control(
			'verdicts',
			array(
				'label'       => esc_html__( 'Verdicts', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $verdicts->get_controls(),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_aside',
			array(
				'label' => esc_html__( 'Definitions', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'aside_title',
			array(
				'label'       => esc_html__( 'Heading', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$definitions = new Repeater();

		$definitions->add_control(
			'term',
			array(
				'label'       => esc_html__( 'Term', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$definitions->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
			)
		);

		$this->add_control(
			'definitions',
			array(
				'label'       => esc_html__( 'Definitions', 'zvg-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $definitions->get_controls(),
				'title_field' => '{{{ term }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_labels',
			array(
				'label' => esc_html__( 'Labels', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'step_label',
			array(
				'label'       => esc_html__( 'Progress', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Use %1$d for the current question and %2$d for how many there are.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'next_label',
			array(
				'label'       => esc_html__( 'Next', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'see_label',
			array(
				'label'       => esc_html__( 'Last question', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'back_label',
			array(
				'label'       => esc_html__( 'Back', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'restart_label',
			array(
				'label'       => esc_html__( 'Start over', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'winner_label',
			array(
				'label'       => esc_html__( 'Result heading', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'others_label',
			array(
				'label'       => esc_html__( 'Runners-up heading', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_question',
			array(
				'label' => esc_html__( 'Questions', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'question_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__question',
			)
		);

		$this->add_control(
			'question_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__question' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_heading',
			array(
				'label'     => esc_html__( 'Progress', 'zvg-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__count',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_result',
			array(
				'label' => esc_html__( 'Result', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eyebrow_typography',
				'label'    => esc_html__( 'Headings', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__eyebrow',
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Heading color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'verdict_typography',
				'label'    => esc_html__( 'Verdict', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__verdict-text',
			)
		);

		$this->add_control(
			'verdict_color',
			array(
				'label'     => esc_html__( 'Verdict color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__verdict-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_definitions',
			array(
				'label' => esc_html__( 'Definitions', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'term_typography',
				'label'    => esc_html__( 'Term', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__definitions dt',
			)
		);

		$this->add_control(
			'term_color',
			array(
				'label'     => esc_html__( 'Term color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__definitions dt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => esc_html__( 'Description', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-chooser__definitions dd',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Description color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-chooser__definitions dd' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The questions that have something to ask and something to pick.
	 *
	 * @param array $settings Widget settings.
	 * @return array<int, array<string, mixed>>
	 */
	protected function get_steps( $settings ) {
		$steps = ( isset( $settings['steps'] ) && is_array( $settings['steps'] ) ) ? $settings['steps'] : array();
		$ready = array();

		foreach ( $steps as $index => $step ) {
			if ( empty( $step['question'] ) ) {
				continue;
			}

			$choices = array();

			foreach ( range( 1, self::CHOICES ) as $number ) {
				if ( empty( $step[ 'choice_' . $number . '_label' ] ) ) {
					continue;
				}

				$weights = array();

				foreach ( self::BUILDS as $build ) {
					$key               = 'choice_' . $number . '_' . $build;
					$weights[ $build ] = isset( $step[ $key ] ) ? (int) $step[ $key ] : 0;
				}

				$choices[] = array(
					'label'   => $step[ 'choice_' . $number . '_label' ],
					'weights' => $weights,
				);
			}

			if ( empty( $choices ) ) {
				continue;
			}

			$ready[] = array(
				'question' => $step['question'],
				'name'     => ! empty( $step['name'] ) ? $step['name'] : 'step-' . ( $index + 1 ),
				'choices'  => $choices,
			);
		}

		return $ready;
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$steps    = $this->get_steps( $settings );

		if ( empty( $steps ) ) {
			return;
		}

		$total    = count( $steps );
		$verdicts = ( isset( $settings['verdicts'] ) && is_array( $settings['verdicts'] ) ) ? $settings['verdicts'] : array();

		$definitions = ( isset( $settings['definitions'] ) && is_array( $settings['definitions'] ) ) ? $settings['definitions'] : array();
		$definitions = array_filter(
			$definitions,
			static function ( $definition ) {
				return ! empty( $definition['term'] );
			}
		);

		$labels = wp_json_encode(
			array(
				'next' => ! empty( $settings['next_label'] ) ? $settings['next_label'] : '',
				'see'  => ! empty( $settings['see_label'] ) ? $settings['see_label'] : '',
			)
		);

		$group = 'zvg-elementor-chooser-' . $this->get_id() . '-';
		?>
		<div class="zvg-elementor-chooser">
			<form class="zvg-elementor-chooser__form" data-chooser data-chooser-labels="<?php echo esc_attr( $labels ); ?>" novalidate>
				<?php foreach ( $steps as $index => $step ) { ?>
				<fieldset class="zvg-elementor-chooser__step" data-chooser-step>
					<legend>
						<?php if ( ! empty( $settings['step_label'] ) ) { ?>
							<span class="zvg-elementor-chooser__count">
								<?php
								echo esc_html(
									str_replace(
										array( '%1$d', '%2$d' ),
										array( (int) $index + 1, (int) $total ),
										$settings['step_label']
									)
								);
								?>
							</span>
						<?php } ?>

						<span class="zvg-elementor-chooser__question"><?php echo esc_html( $step['question'] ); ?></span>
					</legend>

					<?php foreach ( $step['choices'] as $choice_index => $choice ) { ?>
					<label class="zvg-elementor-chooser__choice">
						<input
							type="radio"
							name="<?php echo esc_attr( $group . $step['name'] ); ?>"
							value="<?php echo esc_attr( $choice_index ); ?>"
							<?php foreach ( $choice['weights'] as $build => $weight ) { ?>
								data-<?php echo esc_attr( $build ); ?>="<?php echo esc_attr( $weight ); ?>"
							<?php } ?>
						>
						<span><?php echo esc_html( $choice['label'] ); ?></span>
					</label>
					<?php } ?>
				</fieldset>
				<?php } ?>

				<div class="zvg-elementor-chooser__actions" data-chooser-actions hidden>
					<?php if ( ! empty( $settings['back_label'] ) ) { ?>
						<button class="zvg-elementor-chooser__button zvg-elementor-chooser__button--outline" type="button" data-chooser-back>
							<?php echo esc_html( $settings['back_label'] ); ?>
						</button>
					<?php } ?>

					<?php if ( ! empty( $settings['next_label'] ) ) { ?>
						<button class="zvg-elementor-chooser__button" type="submit" data-chooser-next>
							<?php echo esc_html( $settings['next_label'] ); ?>
						</button>
					<?php } ?>
				</div>

				<div class="zvg-elementor-chooser__result" data-chooser-result role="status">
					<?php if ( ! empty( $settings['winner_label'] ) ) { ?>
						<p class="zvg-elementor-chooser__eyebrow"><?php echo esc_html( $settings['winner_label'] ); ?></p>
					<?php } ?>

					<div data-chooser-winner></div>

					<?php if ( ! empty( $settings['others_label'] ) ) { ?>
						<p class="zvg-elementor-chooser__eyebrow" data-chooser-others-title hidden><?php echo esc_html( $settings['others_label'] ); ?></p>
					<?php } ?>

					<div class="zvg-elementor-chooser__others" data-chooser-others></div>

					<div data-chooser-pool>
						<?php
						foreach ( $verdicts as $verdict ) {
							if ( empty( $verdict['build'] ) || ( empty( $verdict['title'] ) && empty( $verdict['text'] ) ) ) {
								continue;
							}
							?>
						<div class="zvg-elementor-chooser__verdict" data-build="<?php echo esc_attr( $verdict['build'] ); ?>">
							<?php if ( ! empty( $verdict['title'] ) ) { ?>
								<h3 class="zvg-elementor-chooser__verdict-title"><?php echo esc_html( $verdict['title'] ); ?></h3>
							<?php } ?>

							<?php if ( ! empty( $verdict['text'] ) ) { ?>
								<p class="zvg-elementor-chooser__verdict-text"><?php echo esc_html( $verdict['text'] ); ?></p>
							<?php } ?>
						</div>
							<?php
						}
						?>
					</div>

					<?php if ( ! empty( $settings['restart_label'] ) ) { ?>
						<button class="zvg-elementor-chooser__button zvg-elementor-chooser__button--outline" type="button" data-chooser-restart hidden>
							<?php echo esc_html( $settings['restart_label'] ); ?>
						</button>
					<?php } ?>
				</div>
			</form>

			<?php if ( ! empty( $definitions ) ) { ?>
				<aside class="zvg-elementor-chooser__aside">
					<?php if ( ! empty( $settings['aside_title'] ) ) { ?>
						<p class="zvg-elementor-chooser__eyebrow"><?php echo esc_html( $settings['aside_title'] ); ?></p>
					<?php } ?>

					<dl class="zvg-elementor-chooser__definitions">
						<?php foreach ( $definitions as $definition ) { ?>
							<dt><?php echo esc_html( $definition['term'] ); ?></dt>

							<?php if ( ! empty( $definition['description'] ) ) { ?>
								<dd><?php echo esc_html( $definition['description'] ); ?></dd>
							<?php } ?>
						<?php } ?>
					</dl>
				</aside>
			<?php } ?>
		</div>
		<?php
	}
}
