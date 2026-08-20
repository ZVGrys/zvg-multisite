<?php
/**
 * Register the ZVG Build Chooser block.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'zvg_fse_register_build_chooser_block' );

/**
 * Register the block and its editor script.
 */
function zvg_fse_register_build_chooser_block() {

	wp_register_script(
		'zvg-fse-build-chooser-editor',
		ZVG_FSE_T_URI . '/blocks/build-chooser/index.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
		zvg_fse_get_asset_version( '/blocks/build-chooser/index.js' ),
		true
	);

	wp_add_inline_script(
		'zvg-fse-build-chooser-editor',
		'window.zvgFseChooserDefaults = ' . wp_json_encode(
			array(
				'steps'       => zvg_fse_chooser_steps(),
				'verdicts'    => zvg_fse_chooser_verdicts(),
				'definitions' => zvg_fse_chooser_definitions(),
			)
		) . ';',
		'before'
	);

	// Registered by hand, like the editor script above — block.json's own
	// "viewScript" auto-registration versions by the WP/Gutenberg version, not
	// by file mtime, so an edit to view.js was served stale from the browser
	// cache until the next unrelated core/Gutenberg update.
	wp_register_script(
		'zvg-fse-build-chooser-view',
		ZVG_FSE_T_URI . '/blocks/build-chooser/js/view.min.js',
		array(),
		zvg_fse_get_asset_version( '/blocks/build-chooser/js/view.min.js' ),
		true
	);

	register_block_type_from_metadata(
		ZVG_FSE_T_PATH . '/blocks/build-chooser',
		array(
			'editor_script'       => 'zvg-fse-build-chooser-editor',
			'view_script_handles' => array( 'zvg-fse-build-chooser-view' ),
		)
	);
}

/**
 * The questions, and what each answer is worth to each build.
 *
 * @return array<int, array<string, mixed>>
 */
function zvg_fse_chooser_steps() {
	return apply_filters(
		'zvg_fse_chooser_steps',
		array(
			array(
				'name'     => 'editor',
				'question' => _x( 'Who edits the content after launch?', 'Chooser question', 'zvg-fse' ),
				'choices'  => array(
					array(
						'value'     => 'dev',
						'label'     => _x( 'A developer', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 0,
						'elementor' => 0,
						'acf'       => 2,
					),
					array(
						'value'     => 'marketing',
						'label'     => _x( 'A marketing team', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 1,
						'elementor' => 2,
						'acf'       => 0,
					),
					array(
						'value'     => 'client',
						'label'     => _x( 'The client, occasionally', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 2,
						'elementor' => 1,
						'acf'       => 0,
					),
				),
			),
			array(
				'name'     => 'design',
				'question' => _x( 'How much of the design is fixed?', 'Chooser question', 'zvg-fse' ),
				'choices'  => array(
					array(
						'value'     => 'exact',
						'label'     => _x( 'Pixel-exact from a mockup', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 0,
						'elementor' => 1,
						'acf'       => 2,
					),
					array(
						'value'     => 'system',
						'label'     => _x( 'A design system with room to move', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 2,
						'elementor' => 0,
						'acf'       => 1,
					),
					array(
						'value'     => 'often',
						'label'     => _x( 'It will change often', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 1,
						'elementor' => 2,
						'acf'       => 0,
					),
				),
			),
			array(
				'name'     => 'budget',
				'question' => _x( 'What is the budget shape?', 'Chooser question', 'zvg-fse' ),
				'choices'  => array(
					array(
						'value'     => 'tight',
						'label'     => _x( 'Fixed and tight', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 1,
						'elementor' => 2,
						'acf'       => 0,
					),
					array(
						'value'     => 'proper',
						'label'     => _x( 'Room for a proper build', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 1,
						'elementor' => 0,
						'acf'       => 2,
					),
					array(
						'value'     => 'retainer',
						'label'     => _x( 'Ongoing retainer', 'Chooser answer', 'zvg-fse' ),
						'fse'       => 2,
						'elementor' => 0,
						'acf'       => 1,
					),
				),
			),
		)
	);
}

/**
 * What each build is recommended for, and what it costs you.
 *
 * @return array<string, array<string, string>>
 */
function zvg_fse_chooser_verdicts() {
	return apply_filters(
		'zvg_fse_chooser_verdicts',
		array(
			'fse'       => array(
				'title' => _x( 'Full Site Editing', 'Build name', 'zvg-fse' ),
				'text'  => _x( 'Editors get native block controls and the styling stays in theme.json, so content changes rarely need a developer. The trade-off is less control over markup than a hand-written theme.', 'Chooser verdict', 'zvg-fse' ),
			),
			'elementor' => array(
				'title' => _x( 'Elementor', 'Build name', 'zvg-fse' ),
				'text'  => _x( 'Assembly is fast and a non-technical team can keep changing layouts after launch. The trade-off is heavier markup and a dependency on the builder.', 'Chooser verdict', 'zvg-fse' ),
			),
			'acf'       => array(
				'title' => _x( 'ACF theme', 'Build name', 'zvg-fse' ),
				'text'  => _x( 'Templates are hand-written, so the output matches the design exactly and custom logic is straightforward. The trade-off is that most layout changes go back through a developer.', 'Chooser verdict', 'zvg-fse' ),
			),
		)
	);
}

/**
 * The short definitions listed beside the questions.
 *
 * @return array<int, array<string, string>>
 */
function zvg_fse_chooser_definitions() {
	return apply_filters(
		'zvg_fse_chooser_definitions',
		array(
			array(
				'term'        => _x( 'Full Site Editing', 'Build name', 'zvg-fse' ),
				'description' => _x( 'Editors work in native blocks; styling stays in theme.json.', 'Build definition', 'zvg-fse' ),
			),
			array(
				'term'        => _x( 'Elementor', 'Build name', 'zvg-fse' ),
				'description' => _x( 'Fast assembly and open layout control, at the cost of markup weight.', 'Build definition', 'zvg-fse' ),
			),
			array(
				'term'        => _x( 'ACF theme', 'Build name', 'zvg-fse' ),
				'description' => _x( 'Hand-written templates and custom logic; changes go via a developer.', 'Build definition', 'zvg-fse' ),
			),
		)
	);
}
