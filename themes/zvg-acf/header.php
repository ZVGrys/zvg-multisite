<?php
/**
 * The header.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ZVG_ACF
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

	<a class="zvg-acf-skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'zvg-acf' ); ?></a>

	<header class="zvg-acf-header">
		<div class="zvg-acf-header__inner">
			<?php
			$zvg_acf_logo_type = zvg_acf_option( 'header_logo_type', 'text' );
			$zvg_acf_site_name = (string) zvg_acf_option( 'header_logo_text', get_bloginfo( 'name', 'display' ) );

			if ( 'image' === $zvg_acf_logo_type && has_custom_logo() ) {
				the_custom_logo();
			} elseif ( '' !== trim( $zvg_acf_site_name ) ) {
				?>
			<p class="zvg-acf-header__title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $zvg_acf_site_name ); ?></a>
			</p>
				<?php
			}
			?>

			<button class="zvg-acf-header__toggle" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="zvg-acf-nav" aria-label="<?php esc_attr_e( 'Open menu', 'zvg-acf' ); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<path d="M4 7.5h16v1.5H4z" fill="currentColor" />
					<path d="M4 15h16v1.5H4z" fill="currentColor" />
				</svg>
			</button>

			<div class="zvg-acf-header__nav" id="zvg-acf-nav">
				<button class="zvg-acf-header__close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'zvg-acf' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
						<path d="m13.06 12 6.47-6.47-1.06-1.06L12 10.94 5.53 4.47 4.47 5.53 10.94 12l-6.47 6.47 1.06 1.06L12 13.06l6.47 6.47 1.06-1.06L13.06 12Z" fill="currentColor" />
					</svg>
				</button>

				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => 'nav',
							'menu_class'     => 'zvg-acf-menu',
							'depth'          => 2,
						)
					);
				}

				if ( zvg_acf_option( 'header_show_build_switcher', true ) ) {
					zvg_acf_render_build_switcher();
				}
				?>
			</div>
		</div>
	</header>

	<?php
	$zvg_acf_main_class = 'zvg-acf-main';

	if ( zvg_acf_is_sections_page() ) {
		$zvg_acf_main_class .= ' zvg-acf-main--sections';
	} elseif ( is_404() ) {
		$zvg_acf_main_class .= ' zvg-acf-main--404';
	} elseif ( is_home() || is_archive() || is_search() ) {
		$zvg_acf_main_class .= ' zvg-acf-main--archive';
	} elseif ( is_page() ) {
		$zvg_acf_main_class .= ' zvg-acf-main--page';
	}
	?>

	<main id="content" class="<?php echo esc_attr( $zvg_acf_main_class ); ?>">
