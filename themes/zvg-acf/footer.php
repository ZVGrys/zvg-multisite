<?php
/**
 * The footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

?>
	</main>

	<?php $zvg_acf_copy = function_exists( 'get_field' ) ? get_field( 'footer_copyright', 'option' ) : ''; ?>

	<footer class="zvg-acf-footer">
		<div class="zvg-acf-footer__inner">
			<?php if ( ! empty( $zvg_acf_copy ) ) { ?>
			<p class="zvg-acf-footer__copy"><?php echo esc_html( $zvg_acf_copy ); ?></p>
			<?php } ?>

			<?php if ( has_nav_menu( 'footer' ) || count( zvg_acf_build_sites() ) > 1 ) { ?>
			<div class="zvg-acf-footer__nav">
				<?php if ( has_nav_menu( 'footer' ) ) { ?>
				<nav aria-label="<?php esc_attr_e( 'Footer', 'zvg-acf' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'footer',
							'container'       => false,
							'items_wrap'      => '<ul class="zvg-acf-menu">%3$s</ul>',
							'depth'           => 1,
						)
					);
					?>
				</nav>
				<?php } ?>

				<?php zvg_acf_render_build_switcher( 'list', true ); ?>
			</div>
			<?php } ?>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>

</html>
