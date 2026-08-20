<?php
/**
 * The FAQ section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title  = get_sub_field( 'title' );
$zvg_acf_tag    = get_sub_field( 'heading_tag' );
$zvg_acf_tag    = in_array( $zvg_acf_tag, array( 'h2', 'h3', 'h4', 'h5' ), true ) ? $zvg_acf_tag : 'h3';
$zvg_acf_row    = get_row_index();
$zvg_acf_schema = array();

if ( ! have_rows( 'items' ) ) {
	return;
}

?>
<section class="zvg-acf-faq">
	<?php if ( ! empty( $zvg_acf_title ) ) { ?>
	<h2 class="zvg-acf-faq__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
	<?php } ?>

	<div class="zvg-acf-faq__list">
		<?php
		while ( have_rows( 'items' ) ) {
			the_row();

			$zvg_acf_question = get_sub_field( 'question' );
			$zvg_acf_answer   = get_sub_field( 'answer' );

			if ( empty( $zvg_acf_question ) || empty( $zvg_acf_answer ) ) {
				continue;
			}

			$zvg_acf_answer_id = 'zvg-acf-faq-' . $zvg_acf_row . '-' . get_row_index();

			$zvg_acf_schema[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $zvg_acf_question ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $zvg_acf_answer ),
				),
			);
			?>
		<div class="zvg-acf-faq__item">
			<?php printf( '<%s class="zvg-acf-faq__question">', esc_html( $zvg_acf_tag ) ); ?>
				<button class="zvg-acf-faq__trigger" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr( $zvg_acf_answer_id ); ?>">
					<span class="zvg-acf-faq__label"><?php echo esc_html( $zvg_acf_question ); ?></span>

					<span class="zvg-acf-faq__icon" aria-hidden="true">
						<svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
							<path d="M8.81152 9.32251L2.31509 2.32257" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
							<path d="M8.81141 9.32244L15.9998 2.32246" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
						</svg>
					</span>
				</button>
			<?php printf( '</%s>', esc_html( $zvg_acf_tag ) ); ?>

			<div class="zvg-acf-faq__answer" id="<?php echo esc_attr( $zvg_acf_answer_id ); ?>">
				<?php echo esc_html( $zvg_acf_answer ); ?>
			</div>
		</div>
			<?php
		}
		?>
	</div>

	<?php if ( ! empty( $zvg_acf_schema ) ) { ?>
	<script type="application/ld+json">
		<?php
		echo wp_json_encode(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $zvg_acf_schema,
			),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode with JSON_HEX_TAG is the escaping for a JSON-LD body.
		?>
	</script>
	<?php } ?>
</section>
