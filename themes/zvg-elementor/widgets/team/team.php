<?php
/**
 * Team widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * The team member post type as a grid of cards, each opening in a dialog.
 */
class ZVG_Elementor_Team extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-team',
			ZVG_ELEMENTOR_T_URI . '/widgets/team/team.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/team/team.css' )
		);

		wp_register_script(
			'zvg-elementor-team',
			ZVG_ELEMENTOR_T_URI . '/widgets/team/team.min.js',
			array(),
			zvg_elementor_get_asset_version( '/widgets/team/team.min.js' ),
			true
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-team';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Team', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-person';
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
		return array( 'zvg-elementor-team' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-team' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_members',
			array(
				'label' => esc_html__( 'Members', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'Oldest first', 'zvg-elementor' ),
					'DESC' => esc_html__( 'Newest first', 'zvg-elementor' ),
				),
			)
		);

		$this->add_control(
			'toggle_label',
			array(
				'label'       => esc_html__( 'Card button', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read profile', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dialog',
			array(
				'label' => esc_html__( 'Dialog', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'close_label',
			array(
				'label'       => esc_html__( 'Close button', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Close', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'       => esc_html__( 'Link "Get in touch" text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Get in touch', 'zvg-elementor' ),
				'description' => esc_html__( 'Shown only for members with a link set on their profile.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_name',
			array(
				'label' => esc_html__( 'Name', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-team__name',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-team__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_role',
			array(
				'label' => esc_html__( 'Role', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'role_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-team__role',
			)
		);

		$this->add_control(
			'role_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-team__role' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_bio',
			array(
				'label' => esc_html__( 'Bio', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bio_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-team__bio',
			)
		);

		$this->add_control(
			'bio_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-team__bio' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The members this widget renders.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return \WP_Query
	 */
	protected function get_members( $settings ) {
		$order = ( isset( $settings['order'] ) && 'DESC' === $settings['order'] ) ? 'DESC' : 'ASC';

		return new \WP_Query(
			array(
				'post_type'              => 'zvg_member',
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'orderby'                => 'date',
				'order'                  => $order,
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => true,
			)
		);
	}

	/**
	 * The role a member holds.
	 *
	 * @param int $post_id Member ID.
	 *
	 * @return string
	 */
	protected function get_role( $post_id ) {
		$roles = get_the_terms( $post_id, 'zvg_member_role' );

		if ( ! $roles || is_wp_error( $roles ) ) {
			return '';
		}

		$role = reset( $roles );

		return $role->name;
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$members  = $this->get_members( $settings );

		if ( ! $members->have_posts() ) {
			return;
		}

		$toggle_label = isset( $settings['toggle_label'] ) ? $settings['toggle_label'] : '';
		$close_label  = isset( $settings['close_label'] ) ? $settings['close_label'] : '';
		$link_text    = isset( $settings['link_text'] ) ? $settings['link_text'] : '';
		$name_id      = 'zvg-elementor-team-name-' . $this->get_id();
		?>
		<div class="zvg-elementor-team">
			<div class="zvg-elementor-team__grid">
				<?php
				while ( $members->have_posts() ) {
					$members->the_post();

					$member_id = get_the_ID();
					$role      = $this->get_role( $member_id );
					$bio       = get_the_excerpt();
					$profile   = get_post_meta( $member_id, '_zvg_member_profile', true );
					$link      = get_post_meta( $member_id, '_zvg_member_link', true );
					?>
				<article class="zvg-elementor-team__member">
					<?php if ( has_post_thumbnail() ) { ?>
						<?php
						echo wp_kses(
							get_the_post_thumbnail(
								$member_id,
								'medium_large',
								array( 'class' => 'zvg-elementor-team__portrait' )
							),
							'post'
						);
						?>
					<?php } ?>

					<h3 class="zvg-elementor-team__name"><?php the_title(); ?></h3>

					<?php if ( '' !== $role ) { ?>
						<p class="zvg-elementor-team__role"><?php echo esc_html( $role ); ?></p>
					<?php } ?>

					<?php if ( '' !== trim( $bio ) ) { ?>
						<p class="zvg-elementor-team__bio"><?php echo esc_html( $bio ); ?></p>
					<?php } ?>

					<?php if ( '' !== trim( $profile ) && '' !== $toggle_label ) { ?>
						<button class="zvg-elementor-team__toggle" type="button" data-member-open data-member-link="<?php echo esc_attr( $link ); ?>" hidden>
							<?php echo esc_html( $toggle_label ); ?>
							<span class="elementor-screen-only"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
						</button>

						<div class="zvg-elementor-team__profile" data-member-profile hidden>
							<?php
							echo wp_kses_post( apply_filters( 'the_content', $profile ) );
							?>
						</div>
					<?php } ?>
				</article>
					<?php
				}

				wp_reset_postdata();
				?>
			</div>

			<dialog class="zvg-elementor-team__dialog" data-member-dialog closedby="any" aria-labelledby="<?php echo esc_attr( $name_id ); ?>">
				<div class="zvg-elementor-team__dialog-head">
					<div>
						<h3 class="zvg-elementor-team__dialog-name" id="<?php echo esc_attr( $name_id ); ?>" data-member-name></h3>
						<p class="zvg-elementor-team__dialog-role" data-member-role></p>
					</div>

					<?php if ( '' !== $close_label ) { ?>
						<button class="zvg-elementor-team__dialog-close" type="button" data-member-close><?php echo esc_html( $close_label ); ?></button>
					<?php } ?>
				</div>

				<img class="zvg-elementor-team__dialog-portrait" data-member-portrait src="" alt="" decoding="async">

				<p class="zvg-elementor-team__dialog-bio" data-member-bio></p>

				<div data-member-profile-slot></div>

				<?php if ( '' !== $link_text ) { ?>
					<a class="zvg-elementor-team__dialog-link" href="" data-member-link hidden>
						<?php echo esc_html( $link_text ); ?>
					</a>
				<?php } ?>
			</dialog>
		</div>
		<?php
	}
}
