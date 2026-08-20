<?php
/**
 * ZVG Build Switcher — front-end render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_builds = zvg_fse_build_sites();

if ( count( $zvg_fse_builds ) < 2 ) {
	return;
}

$zvg_fse_wrapper = get_block_wrapper_attributes(
	array(
		'role'       => 'group',
		'aria-label' => __( 'Build version', 'zvg-fse' ),
	)
);
?>
<div <?php echo $zvg_fse_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<?php foreach ( $zvg_fse_builds as $zvg_fse_build ) : ?>
		<a
			class="wp-block-zvg-fse-build-switcher__link"
			href="<?php echo esc_url( $zvg_fse_build['url'] ); ?>"
			<?php echo $zvg_fse_build['current'] ? 'aria-current="page"' : ''; ?>
		><?php echo esc_html( $zvg_fse_build['label'] ); ?></a>
	<?php endforeach; ?>
</div>
