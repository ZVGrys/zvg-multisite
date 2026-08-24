<?php
/**
 * The search form.
 *
 * @link https://developer.wordpress.org/themes/functionality/search/
 *
 * @package ZVG_Elementor
 *
 * @var array $args Arguments passed to get_search_form(). 'placeholder' and 'button'
 *                  replace the wording the form carries by default.
 */

defined( 'ABSPATH' ) || exit;

$zvg_elementor_field_id = wp_unique_id( 'zvg-elementor-search-' );
$zvg_elementor_args     = isset( $args ) && is_array( $args ) ? $args : array();

$zvg_elementor_placeholder = empty( $zvg_elementor_args['placeholder'] )
	? _x( 'What are you looking for?', 'Search form placeholder', 'zvg-elementor' )
	: $zvg_elementor_args['placeholder'];

$zvg_elementor_button = empty( $zvg_elementor_args['button'] )
	? _x( 'Search', 'Search form button', 'zvg-elementor' )
	: $zvg_elementor_args['button'];

?>
<form role="search" method="get" class="zvg-elementor-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $zvg_elementor_field_id ); ?>"><?php echo esc_html_x( 'Search for', 'Search form label', 'zvg-elementor' ); ?></label>

	<input
		class="zvg-elementor-search__field"
		id="<?php echo esc_attr( $zvg_elementor_field_id ); ?>"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php echo esc_attr( $zvg_elementor_placeholder ); ?>"
	>

	<button class="zvg-elementor-search__submit" type="submit"><?php echo esc_html( $zvg_elementor_button ); ?></button>
</form>
