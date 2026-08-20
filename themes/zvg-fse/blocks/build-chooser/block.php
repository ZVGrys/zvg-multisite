<?php
/**
 * ZVG Build Chooser — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_steps    = ! empty( $attributes['steps'] ) ? $attributes['steps'] : zvg_fse_chooser_steps();
$zvg_fse_verdicts = ! empty( $attributes['verdicts'] ) ? $attributes['verdicts'] : zvg_fse_chooser_verdicts();

$zvg_fse_definitions = ! empty( $attributes['definitions'] ) ? $attributes['definitions'] : zvg_fse_chooser_definitions();

$zvg_fse_steps = array_values(
	array_filter(
		$zvg_fse_steps,
		static function ( $zvg_fse_step ) {
			return ! empty( $zvg_fse_step['question'] ) && ! empty( $zvg_fse_step['choices'] );
		}
	)
);

if ( empty( $zvg_fse_steps ) ) {
	return;
}

$zvg_fse_total = count( $zvg_fse_steps );

$zvg_fse_labels = wp_json_encode(
	array(
		'next' => _x( 'Next', 'Chooser button', 'zvg-fse' ),
		'see'  => _x( 'See recommendation', 'Chooser button', 'zvg-fse' ),
	)
);

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<form class="wp-block-zvg-fse-build-chooser__form" data-chooser data-chooser-labels="<?php echo esc_attr( $zvg_fse_labels ); ?>" novalidate>
		<?php foreach ( $zvg_fse_steps as $zvg_fse_index => $zvg_fse_step ) : ?>
			<fieldset class="wp-block-zvg-fse-build-chooser__step" data-chooser-step>
				<legend>
					<span class="wp-block-zvg-fse-build-chooser__count">
						<?php
						printf(
							/* translators: 1: current question number, 2: how many questions there are. */
							esc_html_x( 'Step %1$d of %2$d', 'Chooser progress', 'zvg-fse' ),
							(int) $zvg_fse_index + 1,
							(int) $zvg_fse_total
						);
						?>
					</span>
					<span class="wp-block-zvg-fse-build-chooser__question"><?php echo esc_html( $zvg_fse_step['question'] ); ?></span>
				</legend>

				<?php
				foreach ( $zvg_fse_step['choices'] as $zvg_fse_choice ) {
					if ( empty( $zvg_fse_choice['label'] ) ) {
						continue;
					}
					?>
					<label class="wp-block-zvg-fse-build-chooser__choice">
						<input
							type="radio"
							name="<?php echo esc_attr( $zvg_fse_step['name'] ); ?>"
							value="<?php echo esc_attr( $zvg_fse_choice['value'] ); ?>"
							data-fse="<?php echo esc_attr( $zvg_fse_choice['fse'] ); ?>"
							data-elementor="<?php echo esc_attr( $zvg_fse_choice['elementor'] ); ?>"
							data-acf="<?php echo esc_attr( $zvg_fse_choice['acf'] ); ?>"
						>
						<span><?php echo esc_html( $zvg_fse_choice['label'] ); ?></span>
					</label>
					<?php
				}
				?>
			</fieldset>
		<?php endforeach; ?>

		<div class="wp-block-zvg-fse-build-chooser__actions" data-chooser-actions hidden>
			<button class="wp-element-button wp-block-zvg-fse-build-chooser__button wp-block-zvg-fse-build-chooser__button--outline" type="button" data-chooser-back>
				<?php echo esc_html_x( 'Back', 'Chooser button', 'zvg-fse' ); ?>
			</button>
			<button class="wp-element-button wp-block-zvg-fse-build-chooser__button" type="submit" data-chooser-next>
				<?php echo esc_html_x( 'Next', 'Chooser button', 'zvg-fse' ); ?>
			</button>
		</div>

		<div class="wp-block-zvg-fse-build-chooser__result" data-chooser-result role="status">
			<p class="is-style-eyebrow"><?php echo esc_html_x( 'Suggested build', 'Chooser result', 'zvg-fse' ); ?></p>

			<div data-chooser-winner></div>

			<p class="is-style-eyebrow" data-chooser-others-title hidden><?php echo esc_html_x( 'The other two', 'Chooser result', 'zvg-fse' ); ?></p>

			<div class="wp-block-zvg-fse-build-chooser__others" data-chooser-others></div>

			<div data-chooser-pool>
				<?php
				foreach ( $zvg_fse_verdicts as $zvg_fse_build => $zvg_fse_verdict ) {
					$zvg_fse_title = isset( $zvg_fse_verdict['title'] ) ? trim( $zvg_fse_verdict['title'] ) : '';
					$zvg_fse_text  = isset( $zvg_fse_verdict['text'] ) ? trim( $zvg_fse_verdict['text'] ) : '';

					if ( '' === $zvg_fse_title && '' === $zvg_fse_text ) {
						continue;
					}
					?>
					<div class="wp-block-zvg-fse-build-chooser__verdict" data-build="<?php echo esc_attr( $zvg_fse_build ); ?>">
						<?php if ( '' !== $zvg_fse_title ) { ?>
							<h3 class="wp-block-zvg-fse-build-chooser__verdict-title"><?php echo esc_html( $zvg_fse_title ); ?></h3>
						<?php } ?>

						<?php if ( '' !== $zvg_fse_text ) { ?>
							<p class="wp-block-zvg-fse-build-chooser__verdict-text"><?php echo esc_html( $zvg_fse_text ); ?></p>
						<?php } ?>
					</div>
					<?php
				}
				?>
			</div>

			<button class="wp-element-button wp-block-zvg-fse-build-chooser__button wp-block-zvg-fse-build-chooser__button--outline" type="button" data-chooser-restart hidden>
				<?php echo esc_html_x( 'Start over', 'Chooser button', 'zvg-fse' ); ?>
			</button>
		</div>
	</form>

	<aside class="wp-block-zvg-fse-build-chooser__aside">
		<p class="is-style-eyebrow"><?php echo esc_html_x( 'The three options', 'Chooser aside', 'zvg-fse' ); ?></p>

		<dl class="wp-block-zvg-fse-build-chooser__definitions">
			<?php
			foreach ( $zvg_fse_definitions as $zvg_fse_definition ) {
				$zvg_fse_term = isset( $zvg_fse_definition['term'] ) ? trim( $zvg_fse_definition['term'] ) : '';
				$zvg_fse_desc = isset( $zvg_fse_definition['description'] ) ? trim( $zvg_fse_definition['description'] ) : '';

				if ( '' === $zvg_fse_term ) {
					continue;
				}
				?>
				<dt><?php echo esc_html( $zvg_fse_term ); ?></dt>

				<?php if ( '' !== $zvg_fse_desc ) { ?>
					<dd><?php echo esc_html( $zvg_fse_desc ); ?></dd>
				<?php } ?>
				<?php
			}
			?>
		</dl>
	</aside>
</div>
