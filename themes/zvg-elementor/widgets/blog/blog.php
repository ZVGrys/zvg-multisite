<?php
/**
 * Blog widget.
 *
 * @package ZVG_Elementor
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * The most recent posts as a grid of cards, each linking to the full article.
 */
class ZVG_Elementor_Blog extends Widget_Base {

	/**
	 * Register the widget assets.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'zvg-elementor-blog',
			ZVG_ELEMENTOR_T_URI . '/widgets/blog/blog.css',
			array(),
			zvg_elementor_get_asset_version( '/widgets/blog/blog.css' )
		);
	}

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'zvg-elementor-blog';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'ZVG Blog', 'zvg-elementor' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
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
		return array( 'zvg-elementor-post-card', 'zvg-elementor-pagination', 'zvg-elementor-blog' );
	}

	/**
	 * The blog categories available as a filter.
	 *
	 * @return array<int|string, string>
	 */
	protected function get_category_options() {
		$options = array(
			'' => esc_html__( 'All categories', 'zvg-elementor' ),
		);

		$categories = get_categories( array( 'hide_empty' => false ) );

		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		return $options;
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_posts',
			array(
				'label' => esc_html__( 'Posts', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'       => esc_html__( 'Source', 'zvg-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'latest',
				'options'     => array(
					'latest'        => esc_html__( 'Latest posts', 'zvg-elementor' ),
					'current_query' => esc_html__( 'Current query', 'zvg-elementor' ),
				),
				'description' => esc_html__( 'Current query follows the blog, category and search results, and adds pagination.', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'     => esc_html__( 'How many to show', 'zvg-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 12,
				'default'   => 3,
				'condition' => array( 'source' => 'latest' ),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'zvg-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => esc_html__( 'Newest first', 'zvg-elementor' ),
					'ASC'  => esc_html__( 'Oldest first', 'zvg-elementor' ),
				),
				'condition' => array( 'source' => 'latest' ),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'     => esc_html__( 'Category', 'zvg-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_category_options(),
				'condition' => array( 'source' => 'latest' ),
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => esc_html__( 'Text when nothing is found', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => _x( 'Nothing has been published here yet.', 'Empty archive', 'zvg-elementor' ),
				'label_block' => true,
				'condition'   => array( 'source' => 'current_query' ),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'       => esc_html__( 'Link "Read more" text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read more', 'zvg-elementor' ),
				'description' => esc_html__( 'Leave empty to drop the link.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'       => esc_html__( 'Title tag', 'zvg-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'description' => esc_html__( 'Pick the level that follows the heading above this list.', 'zvg-elementor' ),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Date format', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'd M Y',
				'description' => esc_html__( 'PHP date format. Leave empty to follow Settings → General.', 'zvg-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_date',
			array(
				'label' => esc_html__( 'Date', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-post__date',
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-post__date' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-post__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-post__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			array(
				'label' => esc_html__( 'Excerpt', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-post__excerpt',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-post__excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_link',
			array(
				'label' => esc_html__( 'Link', 'zvg-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .zvg-elementor-post__link',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-post__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Whether the widget renders the request's own query rather than its own.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return bool
	 */
	protected function is_current_query( $settings ) {
		return isset( $settings['source'] ) && 'current_query' === $settings['source'];
	}

	/**
	 * The posts this widget renders.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return \WP_Query
	 */
	protected function get_posts( $settings ) {
		if ( $this->is_current_query( $settings ) ) {
			return $GLOBALS['wp_query'];
		}

		$per_page = isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 3;
		$order    = ( isset( $settings['order'] ) && 'ASC' === $settings['order'] ) ? 'ASC' : 'DESC';

		$args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => $per_page > 0 ? $per_page : 3,
			'orderby'                => 'date',
			'order'                  => $order,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		);

		if ( ! empty( $settings['category'] ) ) {
			$args['cat'] = (int) $settings['category'];
		}

		return new \WP_Query( $args );
	}

	/**
	 * Front-end output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$archive  = $this->is_current_query( $settings );
		$posts    = $this->get_posts( $settings );

		if ( ! $posts->have_posts() ) {
			$empty = $archive && isset( $settings['empty_text'] ) ? trim( $settings['empty_text'] ) : '';

			if ( '' !== $empty ) {
				printf( '<p class="zvg-elementor-blog__empty">%s</p>', esc_html( $empty ) );
			}

			return;
		}

		$link_text   = isset( $settings['link_text'] ) ? $settings['link_text'] : '';
		$title_tag   = in_array( $settings['title_tag'] ?? '', array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $settings['title_tag'] : 'h3';
		$date_format = isset( $settings['date_format'] ) ? trim( $settings['date_format'] ) : '';
		$date_format = '' === $date_format ? (string) get_option( 'date_format' ) : $date_format;
		?>
		<div class="zvg-elementor-blog<?php echo $archive ? ' zvg-elementor-blog--archive' : ''; ?>">
			<div class="zvg-elementor-blog__grid">
				<?php
				while ( $posts->have_posts() ) {
					$posts->the_post();

					$excerpt = wp_trim_words( get_the_excerpt(), 20 );
					?>
				<article class="zvg-elementor-post">
					<?php if ( has_post_thumbnail() ) { ?>
						<a class="zvg-elementor-post__thumbnail-link" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium_large', array( 'class' => 'zvg-elementor-post__thumbnail' ) ); ?>
						</a>
					<?php } ?>

					<p class="zvg-elementor-post__date">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( $date_format ) ); ?></time>
					</p>

					<?php
					printf(
						'<%1$s class="zvg-elementor-post__title"><a href="%2$s">%3$s</a></%1$s>',
						esc_html( $title_tag ),
						esc_url( (string) get_permalink() ),
						esc_html( get_the_title() )
					);
					?>

					<?php if ( '' !== trim( $excerpt ) ) { ?>
						<p class="zvg-elementor-post__excerpt"><?php echo esc_html( $excerpt ); ?></p>
					<?php } ?>

					<?php if ( '' !== $link_text ) { ?>
						<a class="zvg-elementor-post__link" href="<?php the_permalink(); ?>">
							<?php echo esc_html( $link_text ); ?>
							<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
						</a>
					<?php } ?>
				</article>
					<?php
				}

				if ( $archive ) {
					rewind_posts();
				} else {
					wp_reset_postdata();
				}
				?>
			</div>

			<?php
			if ( $archive ) {
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => esc_html_x( 'Previous', 'Archive pagination', 'zvg-elementor' ),
						'next_text' => esc_html_x( 'Next', 'Archive pagination', 'zvg-elementor' ),
					)
				);
			}
			?>
		</div>
		<?php
	}
}
