<?php
/**
 * Small reusable helpers.
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'zvg_elementor_do_location' ) ) :

	/**
	 * Output a Theme Builder location.
	 *
	 * @param string $location Location name.
	 *
	 * @return bool Whether the location was output.
	 */
	function zvg_elementor_do_location( $location ) {
		return function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location );
	}
endif;

if ( ! function_exists( 'zvg_elementor_has_location' ) ) :

	/**
	 * Whether a Theme Builder template answers this request, without printing it.
	 *
	 * @param string $location Location name.
	 *
	 * @return bool
	 */
	function zvg_elementor_has_location( $location ) {
		if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			return false;
		}

		return \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager()->location_exits( $location, true );
	}
endif;

if ( ! function_exists( 'zvg_elementor_is_builder_page' ) ) :

	/**
	 * Whether the queried entry was built in the Elementor editor.
	 *
	 * @return bool
	 */
	function zvg_elementor_is_builder_page() {
		$post_id = is_singular() || is_home() ? get_queried_object_id() : 0;

		return $post_id && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
	}
endif;

if ( ! function_exists( 'zvg_elementor_owns_content' ) ) :

	/**
	 * Whether Elementor lays out the content of this request.
	 *
	 * @return bool
	 */
	function zvg_elementor_owns_content() {
		if ( zvg_elementor_is_builder_page() ) {
			return true;
		}

		if ( is_archive() || is_home() || is_search() ) {
			$location = 'archive';
		} elseif ( is_singular() || is_404() ) {
			$location = 'single';
		} else {
			return false;
		}

		return zvg_elementor_has_location( $location );
	}
endif;

if ( ! function_exists( 'zvg_elementor_build_labels' ) ) :

	/**
	 * The name each build of this landing page goes by.
	 *
	 * @return array<string, string> Path segment => label.
	 */
	function zvg_elementor_build_labels() {
		$zvg_elementor_labels = array(
			''          => _x( 'FSE', 'Build name', 'zvg-elementor' ),
			'elementor' => _x( 'Elementor', 'Build name', 'zvg-elementor' ),
			'acf'       => _x( 'ACF', 'Build name', 'zvg-elementor' ),
		);

		/**
		 * Filter the build names.
		 *
		 * @param array<string, string> $zvg_elementor_labels Path segment => label.
		 */
		return apply_filters( 'zvg_elementor_build_labels', $zvg_elementor_labels );
	}
endif;

if ( ! function_exists( 'zvg_elementor_build_sites' ) ) :

	/**
	 * The builds this network actually carries, in the order they are named.
	 *
	 * @return array<string, int> Path segment => blog id.
	 */
	function zvg_elementor_build_sites() {
		$zvg_elementor_labels = zvg_elementor_build_labels();
		$zvg_elementor_builds = array();

		foreach ( is_multisite() ? get_sites( array( 'number' => 10 ) ) : array() as $zvg_elementor_site ) {
			$zvg_elementor_path = trim( substr( $zvg_elementor_site->path, strlen( PATH_CURRENT_SITE ) ), '/' );

			if ( isset( $zvg_elementor_labels[ $zvg_elementor_path ] ) ) {
				$zvg_elementor_builds[ $zvg_elementor_path ] = (int) $zvg_elementor_site->blog_id;
			}
		}

		return $zvg_elementor_builds;
	}
endif;

if ( ! function_exists( 'zvg_elementor_render_build_switcher' ) ) :

	/**
	 * Print the links to each build of this landing page.
	 */
	function zvg_elementor_render_build_switcher() {
		$zvg_elementor_builds = zvg_elementor_build_sites();

		if ( count( $zvg_elementor_builds ) < 2 ) {
			return;
		}
		?>
		<div class="zvg-elementor-switcher" role="group" aria-label="<?php echo esc_attr_x( 'Build version', 'Build switcher label', 'zvg-elementor' ); ?>">
			<?php foreach ( array_intersect_key( zvg_elementor_build_labels(), $zvg_elementor_builds ) as $zvg_elementor_path => $zvg_elementor_label ) { ?>
			<a class="zvg-elementor-switcher__link" href="<?php echo esc_url( get_home_url( $zvg_elementor_builds[ $zvg_elementor_path ], '/' ) ); ?>"<?php echo get_current_blog_id() === $zvg_elementor_builds[ $zvg_elementor_path ] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $zvg_elementor_label ); ?></a>
			<?php } ?>
		</div>
		<?php
	}
endif;
