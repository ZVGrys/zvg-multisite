<?php
/**
 * ZVG Blockquote — front-end render.
 *
 * @var array  $block      Block settings.
 * @var string $content    Block inner HTML.
 * @var bool   $is_preview Whether the block is rendering inside the editor.
 * @var int    $post_id    Entry the block belongs to.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_text   = trim( (string) get_field( 'text' ) );
$zvg_acf_name   = trim( (string) get_field( 'author_name' ) );
$zvg_acf_role   = trim( (string) get_field( 'author_role' ) );
$zvg_acf_author = '' !== $zvg_acf_name || '' !== $zvg_acf_role;

if ( '' === $zvg_acf_text && ! $zvg_acf_author ) {
	if ( ! empty( $is_preview ) ) {
		?>
<p class="zvg-acf-blockquote__empty"><?php echo esc_html_x( 'Add the quote in the sidebar.', 'Blockquote block placeholder', 'zvg-acf' ); ?></p>
		<?php
	}

	return;
}

$zvg_acf_classes = array( 'zvg-acf-blockquote' );

if ( ! empty( $block['className'] ) ) {
	$zvg_acf_classes[] = $block['className'];
}

$zvg_acf_anchor = empty( $block['anchor'] ) ? '' : $block['anchor'];
?>
<figure class="<?php echo esc_attr( implode( ' ', $zvg_acf_classes ) ); ?>"<?php echo '' === $zvg_acf_anchor ? '' : ' id="' . esc_attr( $zvg_acf_anchor ) . '"'; ?>>
	<blockquote class="zvg-acf-blockquote__quote">
		<?php if ( '' !== $zvg_acf_text ) { ?>
		<p class="zvg-acf-blockquote__text"><?php echo wp_kses( $zvg_acf_text, 'post' ); ?></p>
		<?php } ?>
	</blockquote>

	<?php if ( $zvg_acf_author ) { ?>
	<figcaption class="zvg-acf-blockquote__author">
		<?php if ( '' !== $zvg_acf_name ) { ?>
		<cite class="zvg-acf-blockquote__name"><?php echo esc_html( $zvg_acf_name ); ?></cite>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_role ) { ?>
		<span class="zvg-acf-blockquote__role"><?php echo esc_html( $zvg_acf_role ); ?></span>
		<?php } ?>
	</figcaption>
	<?php } ?>
</figure>
