<?php
/**
 * Menu widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * A menu that turns into a full screen panel on small screens, with the build switcher inside it.
 */
class ZVG_Elementor_Menu extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-menu',
			ZVG_ELEMENTOR_T_URI . '/widgets/menu/menu.css',
			array( 'zvg-elementor-switcher' ),
			zvg_elementor_get_asset_version( '/widgets/menu/menu.css' )
		);

		wp_register_script(
			'zvg-elementor-menu',
			ZVG_ELEMENTOR_T_URI . '/widgets/menu/menu.min.js',
			array(),
			zvg_elementor_get_asset_version( '/widgets/menu/menu.min.js' ),
			true
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-menu';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Menu', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
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
		return array( 'zvg-elementor-menu' );
	}

	/**
	 * Scripts this widget depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'zvg-elementor-menu' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$menus = array();

		foreach ( wp_get_nav_menus() as $menu ) {
			$menus[ $menu->slug ] = $menu->name;
		}

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'       => esc_html__( 'Menu', 'zvg-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $menus,
				'default'     => array_key_first( $menus ),
				'description' => esc_html__( 'Menus are edited in Appearance → Menus.', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'show_switcher',
			array(
				'label'        => esc_html__( 'Build switcher', 'zvg-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_links',
			array(
				'label' => esc_html__( 'Links', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-nav__list a',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-nav__list a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Hover color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-nav__list a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .zvg-elementor-nav__list a:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_panel',
			array(
				'label' => esc_html__( 'Panel', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'panel_typography',
				'label'    => esc_html__( 'Links', 'zvg-elementor' ),
				'selector' => '{{WRAPPER}} .zvg-elementor-nav__panel .zvg-elementor-nav__list a',
			)
		);

		$this->add_control(
			'panel_link_color',
			array(
				'label'     => esc_html__( 'Link color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-nav__panel .zvg-elementor-nav__list a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => esc_html__( 'Toggle color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-nav__toggle' => 'color: {{VALUE}};',
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
		$menu     = isset( $settings['menu'] ) ? $settings['menu'] : '';

		$items = $menu ? (string) wp_nav_menu(
			array(
				'menu'        => $menu,
				'container'   => false,
				'menu_class'  => 'zvg-elementor-nav__list',
				'depth'       => 1,
				'fallback_cb' => false,
				'echo'        => false,
			)
		) : '';
		$items = trim( $items );

		$switcher = isset( $settings['show_switcher'] ) && 'yes' === $settings['show_switcher'];
		$builds   = $switcher ? zvg_elementor_build_sites() : array();

		if ( '' === $items && count( $builds ) < 2 ) {
			return;
		}

		$panel_id = 'zvg-elementor-nav-' . $this->get_id();
		?>
		<nav class="zvg-elementor-nav" aria-label="<?php echo esc_attr_x( 'Sections', 'Menu label', 'zvg-elementor' ); ?>">
			<button class="zvg-elementor-nav__toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
				<span class="screen-reader-text"><?php echo esc_html_x( 'Menu', 'Mobile menu button', 'zvg-elementor' ); ?></span>
				<svg class="zvg-elementor-nav__icon zvg-elementor-nav__icon--open" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M4 7.5h16v1.5H4z"></path><path d="M4 15h16v1.5H4z"></path></svg>
				<svg class="zvg-elementor-nav__icon zvg-elementor-nav__icon--close" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="m13.06 12 6.47-6.47-1.06-1.06L12 10.94 5.53 4.47 4.47 5.53 10.94 12l-6.47 6.47 1.06 1.06L12 13.06l6.47 6.47 1.06-1.06L13.06 12Z"></path></svg>
			</button>

			<div class="zvg-elementor-nav__panel" id="<?php echo esc_attr( $panel_id ); ?>">
				<?php if ( ! empty( $items ) ) { ?>
					<?php echo $items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_nav_menu() markup, escaped by core. ?>
				<?php } ?>

				<?php
				if ( $switcher ) {
					zvg_elementor_render_build_switcher();
				}
				?>
			</div>
		</nav>
		<?php
	}
}
