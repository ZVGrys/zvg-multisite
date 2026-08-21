<?php
/**
 * Title: Header navigation
 * Slug: zvg-fse/header-nav
 * Categories: zvg-fse-section
 * Description: The header menu and the build switcher, with every label resolved in PHP so it can be translated.
 * Keywords: header, menu, navigation
 * Inserter: false
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_nav = zvg_fse_block_attrs(
	array(
		'ariaLabel'              => _x( 'Sections', 'Header menu label', 'zvg-fse' ),
		'className'              => 'zvg-fse-header__nav',
		'overlayMenu'            => 'mobile',
		'overlayBackgroundColor' => 'surface',
		'overlayTextColor'       => 'text',
		'style'                  => array( 'spacing' => array( 'blockGap' => 'var:preset|spacing|40' ) ),
		'layout'                 => array(
			'type'     => 'flex',
			'flexWrap' => 'wrap',
		),
		'fontSize'               => 'medium',
	)
);

$zvg_fse_home = home_url( '/' );

$zvg_fse_links = array(
	array(
		'label' => _x( 'Builds', 'Header menu', 'zvg-fse' ),
		'url'   => $zvg_fse_home . '#builds',
	),
	array(
		'label' => _x( 'How it works', 'Header menu', 'zvg-fse' ),
		'url'   => $zvg_fse_home . '#how-it-works',
	),
	array(
		'label' => _x( 'Measured', 'Header menu', 'zvg-fse' ),
		'url'   => $zvg_fse_home . '#measured',
	),
	array(
		'label' => _x( 'Contact', 'Header menu', 'zvg-fse' ),
		'url'   => $zvg_fse_home . '#contact',
	),
);

?>
<!-- wp:navigation <?php echo $zvg_fse_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> -->
	<?php foreach ( $zvg_fse_links as $zvg_fse_link ) : ?>
		<?php $zvg_fse_link['kind'] = 'custom'; ?>
	<!-- wp:navigation-link <?php echo zvg_fse_block_attrs( $zvg_fse_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attributes are JSON, not markup. ?> /-->
	<?php endforeach; ?>

	<!-- wp:zvg-fse/build-switcher {"className":"zvg-fse-header__switcher"} /-->
<!-- /wp:navigation -->
