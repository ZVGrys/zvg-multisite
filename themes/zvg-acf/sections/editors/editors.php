<?php
/**
 * The three editors section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title = get_sub_field( 'title' );
$zvg_acf_intro = get_sub_field( 'intro' );
$zvg_acf_items = array();

if ( have_rows( 'items' ) ) {
	while ( have_rows( 'items' ) ) {
		the_row();

		$zvg_acf_item = array(
			'image'       => (int) get_sub_field( 'image' ),
			'placeholder' => trim( (string) get_sub_field( 'placeholder' ) ),
			'caption'     => trim( (string) get_sub_field( 'caption' ) ),
		);

		if ( empty( $zvg_acf_item['image'] ) && '' === $zvg_acf_item['placeholder'] && '' === $zvg_acf_item['caption'] ) {
			continue;
		}

		$zvg_acf_items[] = $zvg_acf_item;
	}
}

if ( empty( $zvg_acf_items ) ) {
	return;
}

?>
<section class="zvg-acf-section zvg-acf-editors" id="three-editors">
	<div class="zvg-acf-section__inner">
		<?php if ( ! empty( $zvg_acf_title ) ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( ! empty( $zvg_acf_intro ) ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>
	</div>

	<div class="zvg-acf-editors__track" tabindex="0" role="group" aria-label="<?php echo esc_attr_x( 'Editor screenshots, scroll horizontally', 'Scrollable region', 'zvg-acf' ); ?>">
		<?php foreach ( $zvg_acf_items as $zvg_acf_item ) { ?>
		<figure class="zvg-acf-editors__item">
			<?php if ( ! empty( $zvg_acf_item['image'] ) ) { ?>
				<?php
				echo wp_get_attachment_image(
					$zvg_acf_item['image'],
					'large',
					false,
					array(
						'class'   => 'zvg-acf-editors__shot',
						'loading' => 'lazy',
					)
				);
				?>
			<?php } elseif ( '' !== $zvg_acf_item['placeholder'] ) { ?>
			<p class="zvg-acf-editors__placeholder"><?php echo esc_html( $zvg_acf_item['placeholder'] ); ?></p>
			<?php } ?>

			<?php if ( '' !== $zvg_acf_item['caption'] ) { ?>
			<figcaption class="zvg-acf-editors__caption"><?php echo esc_html( $zvg_acf_item['caption'] ); ?></figcaption>
			<?php } ?>
		</figure>
		<?php } ?>
	</div>
</section>
