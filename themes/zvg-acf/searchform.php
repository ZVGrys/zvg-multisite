<?php
/**
 * The search form.
 *
 * @link https://developer.wordpress.org/themes/functionality/search/
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_field_id = wp_unique_id( 'zvg-acf-search-' );

?>
<form role="search" method="get" class="zvg-acf-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $zvg_acf_field_id ); ?>"><?php echo esc_html_x( 'Search for', 'Search form label', 'zvg-acf' ); ?></label>

	<input
		class="zvg-acf-search__field"
		id="<?php echo esc_attr( $zvg_acf_field_id ); ?>"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php echo esc_attr_x( 'What are you looking for?', 'Search form placeholder', 'zvg-acf' ); ?>"
	>

	<button class="zvg-acf-search__submit" type="submit"><?php echo esc_html_x( 'Search', 'Search form button', 'zvg-acf' ); ?></button>
</form>
