<?php
/**
 * ACF blocks.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'zvg_acf_register_blocks' );

if ( ! function_exists( 'zvg_acf_blocks' ) ) :

	/**
	 * The blocks the theme registers.
	 *
	 * A block's folder name is its slug: blocks/<name>/<name>.php is the render
	 * template and blocks/<name>/css/<name>.css the stylesheet, handed to
	 * wp_enqueue_block_style() so it reaches the front end and the editor canvas alike.
	 *
	 * @return array<string, array<string, mixed>> Slug => acf_register_block_type() arguments.
	 */
	function zvg_acf_blocks() {
		$zvg_acf_blocks = array(
			'blockquote' => array(
				'title'       => _x( 'ZVG Blockquote', 'Block title', 'zvg-acf' ),
				'description' => _x( 'A quote card with an oversized quote mark, the quote itself and an optional author name and role.', 'Block description', 'zvg-acf' ),
				'category'    => 'text',
				'icon'        => 'format-quote',
				'keywords'    => array( 'quote', 'blockquote', 'testimonial', 'citation' ),
				'example'     => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'text'        => _x( 'The three builds share one design token set. What differs is who can change what, and how easily.', 'Block preview', 'zvg-acf' ),
							'author_name' => 'Marta Lindqvist',
							'author_role' => _x( 'Content strategist', 'Block preview', 'zvg-acf' ),
						),
					),
				),
			),
		);

		/**
		 * Filter the blocks the theme registers.
		 *
		 * @param array<string, array<string, mixed>> $zvg_acf_blocks Slug => acf_register_block_type() arguments.
		 */
		return apply_filters( 'zvg_acf_blocks', $zvg_acf_blocks );
	}
endif;

if ( ! function_exists( 'zvg_acf_register_blocks' ) ) :

	/**
	 * Register every block, and the stylesheet it enqueues on render.
	 */
	function zvg_acf_register_blocks() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		foreach ( zvg_acf_blocks() as $zvg_acf_slug => $zvg_acf_block ) {
			$zvg_acf_style = '/blocks/' . $zvg_acf_slug . '/css/' . $zvg_acf_slug . '.css';

			if ( file_exists( ZVG_ACF_T_PATH . $zvg_acf_style ) ) {
				wp_enqueue_block_style(
					'acf/zvg-acf-' . $zvg_acf_slug,
					array(
						'handle' => 'zvg-acf-' . $zvg_acf_slug,
						'src'    => ZVG_ACF_T_URI . $zvg_acf_style,
						'ver'    => zvg_acf_get_asset_version( $zvg_acf_style ),
						'path'   => ZVG_ACF_T_PATH . $zvg_acf_style,
					)
				);
			}

			acf_register_block_type(
				array_merge(
					$zvg_acf_block,
					array(
						'name'            => 'zvg-acf-' . $zvg_acf_slug,
						'render_template' => 'blocks/' . $zvg_acf_slug . '/' . $zvg_acf_slug . '.php',
						'mode'            => 'preview',
						'supports'        => array(
							'align'  => false,
							'anchor' => true,
							'jsx'    => false,
						),
					)
				)
			);
		}
	}
endif;
