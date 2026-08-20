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
		return array( 'zvg-elementor-blog' );
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
			'posts_per_page',
			array(
				'label'   => esc_html__( 'How many to show', 'zvg-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 12,
				'default' => 3,
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => esc_html__( 'Newest first', 'zvg-elementor' ),
					'ASC'  => esc_html__( 'Oldest first', 'zvg-elementor' ),
				),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'   => esc_html__( 'Category', 'zvg-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_category_options(),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'       => esc_html__( 'Link "Read more" text', 'zvg-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read more', 'zvg-elementor' ),
				'description' => esc_html__( 'Leave empty to drop the link.', 'zvg-elementor' ),
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
				'selector' => '{{WRAPPER}} .zvg-elementor-blog__date',
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-blog__date' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .zvg-elementor-blog__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-blog__title' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .zvg-elementor-blog__excerpt',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-blog__excerpt' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .zvg-elementor-blog__link',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'zvg-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .zvg-elementor-blog__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The posts this widget renders.
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return \WP_Query
	 */
	protected function get_posts( $settings ) {
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
		$posts    = $this->get_posts( $settings );

		if ( ! $posts->have_posts() ) {
			return;
		}

		$link_text = isset( $settings['link_text'] ) ? $settings['link_text'] : '';
		?>
		<div class="zvg-elementor-blog">
			<div class="zvg-elementor-blog__grid">
				<?php
				while ( $posts->have_posts() ) {
					$posts->the_post();

					$excerpt = wp_trim_words( get_the_excerpt(), 20 );
					?>
				<article class="zvg-elementor-blog__card">
					<?php if ( has_post_thumbnail() ) { ?>
						<a class="zvg-elementor-blog__thumbnail-link" href="<?php the_permalink(); ?>">
							<?php
							echo wp_kses(
								get_the_post_thumbnail(
									get_the_ID(),
									'medium_large',
									array( 'class' => 'zvg-elementor-blog__thumbnail' )
								),
								'post'
							);
							?>
						</a>
					<?php } ?>

					<p class="zvg-elementor-blog__date"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></p>

					<h3 class="zvg-elementor-blog__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>

					<?php if ( '' !== trim( $excerpt ) ) { ?>
						<div class="zvg-elementor-blog__excerpt"><?php echo esc_html( $excerpt ); ?></div>
					<?php } ?>

					<?php if ( '' !== $link_text ) { ?>
						<a class="zvg-elementor-blog__link" href="<?php the_permalink(); ?>">
							<?php echo esc_html( $link_text ); ?>
						</a>
					<?php } ?>
				</article>
					<?php
				}

				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	}
}
