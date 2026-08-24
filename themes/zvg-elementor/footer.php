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

	<?php
	if ( ! zvg_elementor_do_location( 'footer' ) ) {
		$zvg_elementor_copy = (string) get_theme_mod( 'zvg_elementor_footer_copyright', '' );
		$zvg_elementor_copy = str_replace( '{{year}}', wp_date( 'Y' ), $zvg_elementor_copy );
		?>
	<footer class="zvg-elementor-footer">
		<div class="zvg-elementor-footer__inner">
			<?php if ( '' !== trim( $zvg_elementor_copy ) ) { ?>
			<p class="zvg-elementor-footer__copy"><?php echo esc_html( $zvg_elementor_copy ); ?></p>
			<?php } ?>

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
		<?php
	}
	?>

	<?php wp_footer(); ?>
</body>

</html>
