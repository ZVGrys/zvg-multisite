<?php
/**
 * Elementor widget registration.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable Universal.Files.SeparateFunctionsFromOO -- the registrar and the functions that feed it belong together.

if ( ! function_exists( 'zvg_elementor_widget_categories' ) ) {

	/**
	 * Register the theme's widget category.
	 *
	 * @param Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	function zvg_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'zvg-elementor',
			array(
				'title' => esc_html__( 'ZVG', 'zvg-elementor' ),
				'icon'  => 'dashicons-screenoptions',
			)
		);
	}

	add_action( 'elementor/elements/categories_registered', 'zvg_elementor_widget_categories', 99 );
}

if ( ! function_exists( 'zvg_elementor_widgets' ) ) {

	/**
	 * The widgets this theme registers.
	 *
	 * @return array<string, string> Widget slug => class name inside the Elementor namespace.
	 */
	function zvg_elementor_widgets() {
		/**
		 * Filter the registered widgets.
		 *
		 * @param array<string, string> $widgets Widget slug => class name.
		 */
		return apply_filters(
			'zvg_elementor_widgets',
			array(
				'blog'           => 'ZVG_Elementor_Blog',
				'build-card'     => 'ZVG_Elementor_Build_Card',
				'build-chooser'  => 'ZVG_Elementor_Build_Chooser',
				'compare-table'  => 'ZVG_Elementor_Compare_Table',
				'editor-track'   => 'ZVG_Elementor_Editor_Track',
				'faqs'           => 'ZVG_Elementor_FAQS',
				'highlight-text' => 'ZVG_Elementor_Highlight_Text',
				'menu'           => 'ZVG_Elementor_Menu',
				'team'           => 'ZVG_Elementor_Team',
				'token-flow'     => 'ZVG_Elementor_Token_Flow',
			)
		);
	}
}

if ( ! class_exists( 'ZVG_Elementor_Widgets' ) ) {

	/**
	 * Loads and registers the theme's Elementor widgets.
	 */
	class ZVG_Elementor_Widgets {

		/**
		 * Single instance.
		 *
		 * @var ZVG_Elementor_Widgets|null
		 */
		protected static $instance = null;

		/**
		 * Retrieve the instance.
		 *
		 * @return ZVG_Elementor_Widgets
		 */
		public static function get_instance() {
			if ( ! isset( static::$instance ) ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		 * Load the widget files.
		 */
		protected function __construct() {
			foreach ( array_keys( zvg_elementor_widgets() ) as $slug ) {
				$file = ZVG_ELEMENTOR_T_PATH . '/widgets/' . $slug . '/' . $slug . '.php';

				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}

			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		}

		/**
		 * Register the widgets with Elementor.
		 */
		public function register_widgets() {
			foreach ( zvg_elementor_widgets() as $class ) {
				$name = '\\Elementor\\' . $class;

				if ( class_exists( $name ) ) {
					\Elementor\Plugin::instance()->widgets_manager->register( new $name() );
				}
			}
		}
	}
}

if ( ! function_exists( 'zvg_elementor_widgets_init' ) ) {

	/**
	 * Boot the widget registrar.
	 */
	function zvg_elementor_widgets_init() {
		ZVG_Elementor_Widgets::get_instance();
	}

	add_action( 'init', 'zvg_elementor_widgets_init' );
}
