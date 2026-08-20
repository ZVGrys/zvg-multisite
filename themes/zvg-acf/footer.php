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

	<?php
	$zvg_acf_copy = (string) zvg_acf_option( 'footer_copyright' );
	$zvg_acf_copy = str_replace( '{{year}}', wp_date( 'Y' ), $zvg_acf_copy );
	?>

	<footer class="zvg-acf-footer">
		<div class="zvg-acf-footer__inner">
			<?php if ( '' !== trim( $zvg_acf_copy ) ) { ?>
			<p class="zvg-acf-footer__copy"><?php echo esc_html( $zvg_acf_copy ); ?></p>
			<?php } ?>

			<?php if ( has_nav_menu( 'footer' ) ) { ?>
			<div class="zvg-acf-footer__nav">
				<nav aria-label="<?php esc_attr_e( 'Footer', 'zvg-acf' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'items_wrap'     => '<ul class="zvg-acf-menu">%3$s</ul>',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			</div>
			<?php } ?>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>

</html>
