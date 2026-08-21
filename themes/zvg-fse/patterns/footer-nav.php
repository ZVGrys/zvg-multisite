<?php
/**
 * Title: Footer navigation
 * Slug: zvg-fse/footer-nav
 * Categories: zvg-fse-section
 * Description: The footer menu. Labels and addresses are both resolved here, so the menu reads the same in the editor as it does on the front.
 * Keywords: footer, menu, navigation
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_nav = zvg_fse_block_attrs(
	array(
		'ariaLabel'   => _x( 'Footer', 'Footer menu label', 'zvg-fse' ),
		'className'   => 'zvg-fse-footer__nav',
		'overlayMenu' => 'never',
		'style'       => array( 'spacing' => array( 'blockGap' => 'var:preset|spacing|40' ) ),
		'layout'      => array(
			'type'     => 'flex',
			'flexWrap' => 'wrap',
		),
		'fontSize'    => 'small',
	)
);

$zvg_fse_links = array(
	array(
		'label' => _x( 'GitHub', 'Footer menu', 'zvg-fse' ),
		'url'   => 'https://github.com/ZVGrys/zvg-multisite',
	),
);

foreach ( zvg_fse_build_sites() as $zvg_fse_build ) {
	$zvg_fse_links[] = array(
		/* translators: %s: build name, for example "FSE". */
		'label'     => sprintf( _x( '%s build', 'Footer menu', 'zvg-fse' ), $zvg_fse_build['label'] ),
		'url'       => $zvg_fse_build['url'],
		'className' => $zvg_fse_build['current'] ? 'zvg-fse-footer__build--current' : '',
	);
}

$zvg_fse_privacy = get_privacy_policy_url();

if ( '' !== $zvg_fse_privacy ) {
	$zvg_fse_links[] = array(
		'label' => _x( 'Privacy policy', 'Footer menu', 'zvg-fse' ),
		'url'   => $zvg_fse_privacy,
	);
}

?>
<!-- wp:navigation <?php echo $zvg_fse_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> -->
	<?php foreach ( $zvg_fse_links as $zvg_fse_link ) : ?>
		<?php
		$zvg_fse_link['kind'] = 'custom';

		if ( empty( $zvg_fse_link['className'] ) ) {
			unset( $zvg_fse_link['className'] );
		}
		?>
	<!-- wp:navigation-link <?php echo zvg_fse_block_attrs( $zvg_fse_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
	<?php endforeach; ?>
<!-- /wp:navigation -->
