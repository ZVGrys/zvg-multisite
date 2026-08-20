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

$zvg_fse_variant = isset( $attributes['variant'] ) ? $attributes['variant'] : 'segmented';
$zvg_fse_long    = ! empty( $attributes['longLabels'] );
$zvg_fse_wrapper = get_block_wrapper_attributes(
	array( 'class' => 'is-variant-' . sanitize_html_class( $zvg_fse_variant ) )
);
?>
<nav <?php echo $zvg_fse_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?> aria-label="<?php esc_attr_e( 'Build version', 'zvg-fse' ); ?>">
	<?php
	foreach ( $zvg_fse_builds as $zvg_fse_build ) :
		$zvg_fse_label = $zvg_fse_long
			/* translators: %s: build name, such as FSE. */
			? sprintf( _x( '%s build', 'Build name in a text menu', 'zvg-fse' ), $zvg_fse_build['label'] )
			: $zvg_fse_build['label'];
		?>
		<a
			class="wp-block-zvg-fse-build-switcher__link"
			href="<?php echo esc_url( $zvg_fse_build['url'] ); ?>"
			<?php echo $zvg_fse_build['current'] ? 'aria-current="page"' : ''; ?>
		><?php echo esc_html( $zvg_fse_label ); ?></a>
	<?php endforeach; ?>
</nav>
