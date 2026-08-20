<?php
/**
 * The menus field type class.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

/**
 * A select of the menus registered on the site.
 */
class Zvg_Acf_Field_Menus extends acf_field {

	/**
	 * Whether the field is exposed in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Set the field type up.
	 */
	public function __construct() {
		$this->name     = 'menus';
		$this->label    = esc_html__( 'Menus', 'zvg-acf' );
		$this->category = 'relational';

		parent::__construct();
	}

	/**
	 * The control shown on the edit screen.
	 *
	 * @param array $field The field settings and value.
	 */
	public function render_field( $field ) {
		$menus = wp_get_nav_menus();
		$id    = isset( $field['id'] ) ? $field['id'] : '';
		$class = isset( $field['class'] ) ? $field['class'] : '';

		?>
		<select id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>">
			<option value=""><?php echo esc_html_x( '— Select —', 'Empty menu choice', 'zvg-acf' ); ?></option>
			<?php foreach ( $menus as $menu ) { ?>
			<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $field['value'], $menu->term_id ); ?>><?php echo esc_html( $menu->name ); ?></option>
			<?php } ?>
		</select>
		<?php
	}
}
