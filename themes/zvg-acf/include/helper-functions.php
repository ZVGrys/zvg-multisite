<?php
/**
 * Small reusable helpers.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'excerpt_length', 'zvg_acf_excerpt_length' );

if ( ! function_exists( 'zvg_acf_sections' ) ) :

	/**
	 * The sections an entry is built from.
	 *
	 * @param int $post_id Entry to read. Defaults to the queried entry on singular views.
	 *
	 * @return string[] Section names, in the order they render.
	 */
	function zvg_acf_sections( $post_id = 0 ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$post_id  = $post_id ? $post_id : ( is_singular() ? get_queried_object_id() : 0 );
		$rows     = $post_id ? get_field( 'zvg_acf_sections', $post_id ) : false;
		$sections = array();

		if ( ! is_array( $rows ) ) {
			return $sections;
		}

		foreach ( $rows as $row ) {
			if ( ! empty( $row['acf_fc_layout'] ) ) {
				$sections[] = $row['acf_fc_layout'];
			}
		}

		return $sections;
	}
endif;

if ( ! function_exists( 'zvg_acf_render_sections' ) ) :

	/**
	 * Render the sections of the entry in the loop.
	 */
	function zvg_acf_render_sections() {
		if ( ! function_exists( 'have_rows' ) ) {
			return;
		}

		while ( have_rows( 'zvg_acf_sections' ) ) {
			the_row();

			$section = get_row_layout();

			get_template_part( 'sections/' . $section . '/' . $section );
		}
	}
endif;

if ( ! function_exists( 'zvg_acf_archive_title' ) ) :

	/**
	 * The title of the current list of posts.
	 *
	 * @return string
	 */
	function zvg_acf_archive_title() {
		$posts_page = (int) get_option( 'page_for_posts' );

		if ( is_search() ) {
			/* translators: %s: search query. */
			return sprintf( esc_html_x( 'Search results for %s', 'Archive title', 'zvg-acf' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
		}

		if ( is_home() && $posts_page ) {
			return esc_html( get_the_title( $posts_page ) );
		}

		return get_the_archive_title();
	}
endif;

if ( ! function_exists( 'zvg_acf_excerpt_length' ) ) :

	/**
	 * Excerpt length, in words.
	 *
	 * @return int
	 */
	function zvg_acf_excerpt_length() {
		/**
		 * Filter the excerpt length.
		 *
		 * @param int $length Number of words.
		 */
		return apply_filters( 'zvg_acf_excerpt_length', 20 );
	}
endif;

if ( ! function_exists( 'zvg_acf_build_sites' ) ) :

	/**
	 * The network's builds of this landing page, in a fixed order.
	 *
	 * @return array<int, array<string, mixed>> Each entry has blog_id, label, url and current.
	 */
	function zvg_acf_build_sites() {
		$labels = array(
			''          => _x( 'FSE', 'Build name', 'zvg-acf' ),
			'elementor' => _x( 'Elementor', 'Build name', 'zvg-acf' ),
			'acf'       => _x( 'ACF', 'Build name', 'zvg-acf' ),
		);

		$builds = array();

		if ( is_multisite() ) {
			$network_path = defined( 'PATH_CURRENT_SITE' ) ? PATH_CURRENT_SITE : '/';

			foreach ( get_sites( array( 'number' => 50 ) ) as $site ) {
				$segment = trim( substr( $site->path, strlen( $network_path ) ), '/' );

				if ( ! isset( $labels[ $segment ] ) ) {
					continue;
				}

				$builds[ $segment ] = array(
					'blog_id' => (int) $site->blog_id,
					'label'   => $labels[ $segment ],
					'url'     => get_home_url( (int) $site->blog_id, '/' ),
				);
			}
		}

		if ( empty( $builds ) ) {
			$builds[''] = array(
				'blog_id' => get_current_blog_id(),
				'label'   => $labels[''],
				'url'     => home_url( '/' ),
			);
		}

		$current = get_current_blog_id();
		$ordered = array();

		foreach ( array_keys( $labels ) as $segment ) {
			if ( ! isset( $builds[ $segment ] ) ) {
				continue;
			}

			$builds[ $segment ]['current'] = ( $builds[ $segment ]['blog_id'] === $current );
			$ordered[]                     = $builds[ $segment ];
		}

		return $ordered;
	}
endif;

if ( ! function_exists( 'zvg_acf_render_build_switcher' ) ) :

	/**
	 * Print the links to each build of this landing page.
	 *
	 * @param string $variant     'segmented' or 'list'.
	 * @param bool   $long_labels Whether to print "FSE build" instead of "FSE".
	 */
	function zvg_acf_render_build_switcher( $variant = 'segmented', $long_labels = false ) {
		$builds = zvg_acf_build_sites();

		if ( count( $builds ) < 2 ) {
			return;
		}
		?>
		<nav class="zvg-acf-build-switcher is-variant-<?php echo esc_attr( $variant ); ?>" aria-label="<?php esc_attr_e( 'Build version', 'zvg-acf' ); ?>">
			<?php
			foreach ( $builds as $zvg_acf_build ) {
				$zvg_acf_label = $long_labels
					/* translators: %s: build name, such as FSE. */
					? sprintf( _x( '%s build', 'Build name in a text menu', 'zvg-acf' ), $zvg_acf_build['label'] )
					: $zvg_acf_build['label'];
				?>
			<a class="zvg-acf-build-switcher__link" href="<?php echo esc_url( $zvg_acf_build['url'] ); ?>"<?php echo $zvg_acf_build['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $zvg_acf_label ); ?></a>
				<?php
			}
			?>
		</nav>
		<?php
	}
endif;
