<?php
/**
 * The search form.
 *
 * @link https://developer.wordpress.org/themes/functionality/search/
 *
 * @package ZVG_ACF
 *
 * @var array $args Arguments passed to get_search_form(). 'placeholder' and 'button'
 *                  replace the wording the form carries by default.
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_field_id = wp_unique_id( 'zvg-acf-search-' );
$zvg_acf_args     = isset( $args ) && is_array( $args ) ? $args : array();

$zvg_acf_placeholder = empty( $zvg_acf_args['placeholder'] )
	? _x( 'What are you looking for?', 'Search form placeholder', 'zvg-acf' )
	: $zvg_acf_args['placeholder'];

$zvg_acf_button = empty( $zvg_acf_args['button'] )
	? _x( 'Search', 'Search form button', 'zvg-acf' )
	: $zvg_acf_args['button'];

?>
<form role="search" method="get" class="zvg-acf-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $zvg_acf_field_id ); ?>"><?php echo esc_html_x( 'Search for', 'Search form label', 'zvg-acf' ); ?></label>

	<input
		class="zvg-acf-search__field"
		id="<?php echo esc_attr( $zvg_acf_field_id ); ?>"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php echo esc_attr( $zvg_acf_placeholder ); ?>"
	>

	<button class="zvg-acf-button zvg-acf-search__submit" type="submit"><?php echo esc_html( $zvg_acf_button ); ?></button>
</form>
