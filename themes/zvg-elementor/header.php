<?php
/**
 * The header.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="zvg-elementor-skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'zvg-elementor' ); ?></a>

	<?php if ( ! zvg_elementor_do_location( 'header' ) ) { ?>
	<header class="zvg-elementor-header">
		<div class="zvg-elementor-header__inner">
			<?php
			$zvg_elementor_site_name = (string) get_bloginfo( 'name', 'display' );

			if ( zvg_elementor_logo_is_image() && has_custom_logo() ) {
				the_custom_logo();
			} elseif ( '' !== trim( $zvg_elementor_site_name ) ) {
				?>
			<p class="zvg-elementor-header__title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $zvg_elementor_site_name ); ?></a>
			</p>
				<?php
			}
			?>

			<?php if ( has_nav_menu( 'primary' ) ) { ?>
			<button class="zvg-elementor-header__toggle" type="button" aria-expanded="false" aria-controls="zvg-elementor-nav">
				<span class="screen-reader-text"><?php echo esc_html_x( 'Menu', 'Mobile menu button', 'zvg-elementor' ); ?></span>
				<svg class="zvg-elementor-header__icon zvg-elementor-header__icon--open" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M4 7.5h16v1.5H4z"></path><path d="M4 15h16v1.5H4z"></path></svg>
				<svg class="zvg-elementor-header__icon zvg-elementor-header__icon--close" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="m13.06 12 6.47-6.47-1.06-1.06L12 10.94 5.53 4.47 4.47 5.53 10.94 12l-6.47 6.47 1.06 1.06L12 13.06l6.47 6.47 1.06-1.06L13.06 12Z"></path></svg>
			</button>

			<nav id="zvg-elementor-nav" class="zvg-elementor-header__nav" aria-label="<?php echo esc_attr_x( 'Sections', 'Header menu label', 'zvg-elementor' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'zvg-elementor-menu',
						'depth'          => 2,
					)
				);

				if ( get_theme_mod( 'zvg_elementor_header_show_switcher', true ) ) {
					zvg_elementor_render_build_switcher();
				}
				?>
			</nav>
			<?php } ?>
		</div>
	</header>
	<?php } ?>

	<main id="content" class="zvg-elementor-main<?php echo zvg_elementor_owns_content() ? '' : ' zvg-elementor-main--boxed'; ?>" tabindex="-1">
