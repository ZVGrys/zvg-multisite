<?php
/**
 * The footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ZVG_Elementor
 */

defined( 'ABSPATH' ) || exit;

?>
	</main>

	<?php if ( ! zvg_elementor_do_location( 'footer' ) ) { ?>
	<footer class="zvg-elementor-footer">
		<div class="zvg-elementor-footer__inner">
			<p class="zvg-elementor-footer__copy"><?php echo esc_html_x( '© 2026 Zoriana Grys, WordPress Full-Stack Engineer.', 'Footer copyright', 'zvg-elementor' ); ?></p>

			<?php if ( has_nav_menu( 'footer' ) ) { ?>
			<nav class="zvg-elementor-footer__nav" aria-label="<?php echo esc_attr_x( 'Footer', 'Footer menu label', 'zvg-elementor' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'zvg-elementor-menu zvg-elementor-footer__menu',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			<?php } ?>
		</div>
	</footer>
	<?php } ?>

	<?php wp_footer(); ?>
</body>

</html>
