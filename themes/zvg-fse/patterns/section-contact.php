<?php
/**
 * Title: Section: Contact
 * Slug: zvg-fse/section-contact
 * Categories: zvg-fse-section
 * Description: Closing section: the contact form beside the direct ways to reach me.
 * Keywords: contact, form, email
 * Inserter: true
 *
 * @package ZVG_FSE
 */

defined( 'ABSPATH' ) || exit;

$zvg_fse_form = shortcode_exists( 'contact-form-7' ) ? '[contact-form-7 title="Contact"]' : '';

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Section: Contact"},"align":"full","anchor":"contact","className":"zvg-fse-section zvg-fse-contact","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull zvg-fse-section zvg-fse-contact" id="contact">
	<!-- wp:group {"className":"zvg-fse-section__inner","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group zvg-fse-section__inner">
		<!-- wp:heading {"className":"zvg-fse-section__title"} -->
		<h2 class="wp-block-heading zvg-fse-section__title"><?php echo esc_html_x( 'Get in touch', 'Section title', 'zvg-fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-section-intro"} -->
		<p class="is-style-section-intro"><?php echo esc_html_x( 'Questions about the experiment, or work enquiries — whichever’s easier for you.', 'Section intro', 'zvg-fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"zvg-fse-contact__layout","style":{"spacing":{"blockGap":"var:preset|spacing|60","margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group zvg-fse-contact__layout" style="margin-top:var(--wp--preset--spacing--50)">
			<?php if ( '' !== $zvg_fse_form ) : ?>
			<!-- wp:shortcode --><?php echo $zvg_fse_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- a literal shortcode, not user input. ?><!-- /wp:shortcode -->
			<?php endif; ?>

			<!-- wp:group {"className":"zvg-fse-contact__direct","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group zvg-fse-contact__direct">
				<!-- wp:paragraph {"className":"zvg-fse-contact__label","textColor":"muted","fontSize":"medium"} -->
				<p class="zvg-fse-contact__label has-muted-color has-text-color has-medium-font-size"><?php echo esc_html_x( 'Or reach me directly', 'Contact label', 'zvg-fse' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontSize":"medium"} -->
				<p class="has-medium-font-size"><a href="mailto:hello@example.com">hello@example.com</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontSize":"medium"} -->
				<p class="has-medium-font-size"><a href="https://linkedin.com/in/zorianagrys">linkedin.com/in/zorianagrys</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
