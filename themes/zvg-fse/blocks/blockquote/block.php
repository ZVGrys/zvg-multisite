<?php
/**
 * ZVG Blockquote — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_text   = isset( $attributes['text'] ) ? trim( $attributes['text'] ) : '';
$zvg_fse_name   = isset( $attributes['authorName'] ) ? trim( $attributes['authorName'] ) : '';
$zvg_fse_role   = isset( $attributes['authorRole'] ) ? trim( $attributes['authorRole'] ) : '';
$zvg_fse_author = '' !== $zvg_fse_name || '' !== $zvg_fse_role;

if ( '' === $zvg_fse_text && ! $zvg_fse_author ) {
	return;
}
?>
<figure <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped by core. ?>>
	<blockquote class="wp-block-zvg-fse-blockquote__quote">
		<?php if ( '' !== $zvg_fse_text ) : ?>
			<p class="wp-block-zvg-fse-blockquote__text"><?php echo wp_kses( $zvg_fse_text, 'post' ); ?></p>
		<?php endif; ?>
	</blockquote>

	<?php if ( $zvg_fse_author ) : ?>
		<figcaption class="wp-block-zvg-fse-blockquote__author">
			<?php if ( '' !== $zvg_fse_name ) : ?>
				<cite class="wp-block-zvg-fse-blockquote__name"><?php echo esc_html( $zvg_fse_name ); ?></cite>
			<?php endif; ?>

			<?php if ( '' !== $zvg_fse_role ) : ?>
				<span class="wp-block-zvg-fse-blockquote__role"><?php echo esc_html( $zvg_fse_role ); ?></span>
			<?php endif; ?>
		</figcaption>
	<?php endif; ?>
</figure>
