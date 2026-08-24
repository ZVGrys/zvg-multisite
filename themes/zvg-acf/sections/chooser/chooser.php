<?php
/**
 * The build chooser section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title    = get_sub_field( 'title' );
$zvg_acf_intro    = get_sub_field( 'intro' );
$zvg_acf_aside    = trim( (string) get_sub_field( 'aside_title' ) );
$zvg_acf_progress = trim( (string) get_sub_field( 'step_label' ) );
$zvg_acf_next     = trim( (string) get_sub_field( 'next_label' ) );
$zvg_acf_see      = trim( (string) get_sub_field( 'see_label' ) );
$zvg_acf_back     = trim( (string) get_sub_field( 'back_label' ) );
$zvg_acf_restart  = trim( (string) get_sub_field( 'restart_label' ) );
$zvg_acf_winner   = trim( (string) get_sub_field( 'winner_label' ) );
$zvg_acf_others   = trim( (string) get_sub_field( 'others_label' ) );
$zvg_acf_builds   = array( 'fse', 'elementor', 'acf' );
$zvg_acf_steps    = array();

if ( have_rows( 'steps' ) ) {
	while ( have_rows( 'steps' ) ) {
		the_row();

		$zvg_acf_question = trim( (string) get_sub_field( 'question' ) );
		$zvg_acf_name     = trim( (string) get_sub_field( 'name' ) );
		$zvg_acf_choices  = array();

		if ( have_rows( 'choices' ) ) {
			while ( have_rows( 'choices' ) ) {
				the_row();

				$zvg_acf_label = trim( (string) get_sub_field( 'label' ) );

				if ( '' === $zvg_acf_label ) {
					continue;
				}

				$zvg_acf_weights = array();

				foreach ( $zvg_acf_builds as $zvg_acf_build ) {
					$zvg_acf_weights[ $zvg_acf_build ] = (int) get_sub_field( 'weight_' . $zvg_acf_build );
				}

				$zvg_acf_choices[] = array(
					'label'   => $zvg_acf_label,
					'weights' => $zvg_acf_weights,
				);
			}
		}

		if ( '' === $zvg_acf_question || empty( $zvg_acf_choices ) ) {
			continue;
		}

		$zvg_acf_steps[] = array(
			'question' => $zvg_acf_question,
			'name'     => '' !== $zvg_acf_name ? $zvg_acf_name : 'step-' . ( count( $zvg_acf_steps ) + 1 ),
			'choices'  => $zvg_acf_choices,
		);
	}
}

if ( empty( $zvg_acf_steps ) ) {
	return;
}

$zvg_acf_definitions = array();

if ( have_rows( 'definitions' ) ) {
	while ( have_rows( 'definitions' ) ) {
		the_row();

		$zvg_acf_term = trim( (string) get_sub_field( 'term' ) );

		if ( '' === $zvg_acf_term ) {
			continue;
		}

		$zvg_acf_definitions[] = array(
			'term'        => $zvg_acf_term,
			'description' => trim( (string) get_sub_field( 'description' ) ),
		);
	}
}

$zvg_acf_total  = count( $zvg_acf_steps );
$zvg_acf_labels = wp_json_encode(
	array(
		'next' => $zvg_acf_next,
		'see'  => $zvg_acf_see,
	)
);

?>
<section class="zvg-acf-section zvg-acf-chooser" id="when-to-choose">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_intro ) ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<div class="zvg-acf-chooser__body">
			<form class="zvg-acf-chooser__form" data-chooser data-chooser-labels="<?php echo esc_attr( $zvg_acf_labels ); ?>" novalidate>
				<?php foreach ( $zvg_acf_steps as $zvg_acf_index => $zvg_acf_step ) { ?>
				<fieldset class="zvg-acf-chooser__step" data-chooser-step>
					<legend>
						<?php if ( '' !== $zvg_acf_progress ) { ?>
						<span class="zvg-acf-chooser__count">
							<?php
							echo esc_html(
								str_replace(
									array( '%1$d', '%2$d' ),
									array( (int) $zvg_acf_index + 1, (int) $zvg_acf_total ),
									$zvg_acf_progress
								)
							);
							?>
						</span>
						<?php } ?>

						<span class="zvg-acf-chooser__question"><?php echo esc_html( $zvg_acf_step['question'] ); ?></span>
					</legend>

					<?php foreach ( $zvg_acf_step['choices'] as $zvg_acf_choice_index => $zvg_acf_choice ) { ?>
					<label class="zvg-acf-chooser__choice">
						<input
							type="radio"
							name="<?php echo esc_attr( $zvg_acf_step['name'] ); ?>"
							value="<?php echo esc_attr( $zvg_acf_choice_index ); ?>"
							<?php foreach ( $zvg_acf_choice['weights'] as $zvg_acf_build => $zvg_acf_weight ) { ?>
							data-<?php echo esc_attr( $zvg_acf_build ); ?>="<?php echo esc_attr( $zvg_acf_weight ); ?>"
							<?php } ?>
						>
						<span><?php echo esc_html( $zvg_acf_choice['label'] ); ?></span>
					</label>
					<?php } ?>
				</fieldset>
				<?php } ?>

				<div class="zvg-acf-chooser__actions" data-chooser-actions hidden>
					<?php if ( '' !== $zvg_acf_back ) { ?>
					<button class="zvg-acf-button zvg-acf-button--outline zvg-acf-chooser__button" type="button" data-chooser-back>
						<?php echo esc_html( $zvg_acf_back ); ?>
					</button>
					<?php } ?>

					<?php if ( '' !== $zvg_acf_next ) { ?>
					<button class="zvg-acf-button zvg-acf-chooser__button" type="submit" data-chooser-next>
						<?php echo esc_html( $zvg_acf_next ); ?>
					</button>
					<?php } ?>
				</div>

				<div class="zvg-acf-chooser__result" data-chooser-result role="status">
					<?php if ( '' !== $zvg_acf_winner ) { ?>
					<p class="zvg-acf-eyebrow"><?php echo esc_html( $zvg_acf_winner ); ?></p>
					<?php } ?>

					<div data-chooser-winner></div>

					<?php if ( '' !== $zvg_acf_others ) { ?>
					<p class="zvg-acf-eyebrow" data-chooser-others-title hidden><?php echo esc_html( $zvg_acf_others ); ?></p>
					<?php } ?>

					<div class="zvg-acf-chooser__others" data-chooser-others></div>

					<div data-chooser-pool>
						<?php
						if ( have_rows( 'verdicts' ) ) {
							while ( have_rows( 'verdicts' ) ) {
								the_row();

								$zvg_acf_build         = get_sub_field( 'build' );
								$zvg_acf_verdict_title = trim( (string) get_sub_field( 'title' ) );
								$zvg_acf_verdict_text  = trim( (string) get_sub_field( 'text' ) );

								if ( ! in_array( $zvg_acf_build, $zvg_acf_builds, true ) ) {
									continue;
								}

								if ( '' === $zvg_acf_verdict_title && '' === $zvg_acf_verdict_text ) {
									continue;
								}
								?>
						<div class="zvg-acf-chooser__verdict" data-build="<?php echo esc_attr( $zvg_acf_build ); ?>">
							<?php if ( '' !== $zvg_acf_verdict_title ) { ?>
							<h3 class="zvg-acf-chooser__verdict-title"><?php echo esc_html( $zvg_acf_verdict_title ); ?></h3>
							<?php } ?>

							<?php if ( '' !== $zvg_acf_verdict_text ) { ?>
							<p class="zvg-acf-chooser__verdict-text"><?php echo esc_html( $zvg_acf_verdict_text ); ?></p>
							<?php } ?>
						</div>
								<?php
							}
						}
						?>
					</div>

					<?php if ( '' !== $zvg_acf_restart ) { ?>
					<button class="zvg-acf-button zvg-acf-button--outline zvg-acf-chooser__button" type="button" data-chooser-restart hidden>
						<?php echo esc_html( $zvg_acf_restart ); ?>
					</button>
					<?php } ?>
				</div>
			</form>

			<?php if ( ! empty( $zvg_acf_definitions ) ) { ?>
			<aside class="zvg-acf-chooser__aside">
				<?php if ( '' !== $zvg_acf_aside ) { ?>
				<p class="zvg-acf-eyebrow"><?php echo esc_html( $zvg_acf_aside ); ?></p>
				<?php } ?>

				<dl class="zvg-acf-chooser__definitions">
					<?php foreach ( $zvg_acf_definitions as $zvg_acf_definition ) { ?>
					<dt><?php echo esc_html( $zvg_acf_definition['term'] ); ?></dt>

						<?php if ( '' !== $zvg_acf_definition['description'] ) { ?>
					<dd><?php echo esc_html( $zvg_acf_definition['description'] ); ?></dd>
						<?php } ?>
					<?php } ?>
				</dl>
			</aside>
			<?php } ?>
		</div>
	</div>
</section>
