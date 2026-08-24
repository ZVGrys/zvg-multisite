<?php
/**
 * The team section.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

$zvg_acf_title     = trim( (string) get_sub_field( 'title' ) );
$zvg_acf_intro     = trim( (string) get_sub_field( 'intro' ) );
$zvg_acf_note      = trim( (string) get_sub_field( 'note' ) );
$zvg_acf_toggle    = trim( (string) get_sub_field( 'toggle_label' ) );
$zvg_acf_close     = trim( (string) get_sub_field( 'close_label' ) );
$zvg_acf_link_text = trim( (string) get_sub_field( 'link_text' ) );
$zvg_acf_order     = 'DESC' === get_sub_field( 'order' ) ? 'DESC' : 'ASC';

$zvg_acf_members = new WP_Query(
	array(
		'post_type'              => 'zvg_member',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'orderby'                => 'date',
		'order'                  => $zvg_acf_order,
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
	)
);

if ( ! $zvg_acf_members->have_posts() ) {
	return;
}

$zvg_acf_dialog_id = 'zvg-acf-dialog-name-' . get_row_index();

?>
<section class="zvg-acf-section zvg-acf-team" id="team">
	<div class="zvg-acf-section__inner">
		<?php if ( '' !== $zvg_acf_title ) { ?>
		<h2 class="zvg-acf-section__title"><?php echo esc_html( $zvg_acf_title ); ?></h2>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_intro ) { ?>
		<p class="zvg-acf-section-intro"><?php echo esc_html( $zvg_acf_intro ); ?></p>
		<?php } ?>

		<?php if ( '' !== $zvg_acf_note ) { ?>
		<p class="zvg-acf-team__note"><?php echo esc_html( $zvg_acf_note ); ?></p>
		<?php } ?>

		<div class="zvg-acf-team__grid">
			<?php
			while ( $zvg_acf_members->have_posts() ) {
				$zvg_acf_members->the_post();

				$zvg_acf_member_id = get_the_ID();
				$zvg_acf_role      = zvg_acf_member_role( $zvg_acf_member_id );
				$zvg_acf_bio       = trim( (string) get_field( 'member_bio', $zvg_acf_member_id ) );
				$zvg_acf_profile   = trim( (string) get_field( 'member_profile', $zvg_acf_member_id ) );
				$zvg_acf_link      = trim( (string) get_field( 'member_link', $zvg_acf_member_id ) );
				?>
			<article class="zvg-acf-member">
				<?php if ( has_post_thumbnail( $zvg_acf_member_id ) ) { ?>
					<?php
					echo get_the_post_thumbnail(
						$zvg_acf_member_id,
						'post-thumbnail',
						array(
							'class'   => 'zvg-acf-member__portrait',
							'loading' => 'lazy',
						)
					);
					?>
				<?php } ?>

				<h3 class="zvg-acf-member__name"><?php the_title(); ?></h3>

				<?php if ( '' !== $zvg_acf_role ) { ?>
				<p class="zvg-acf-member__role"><?php echo esc_html( $zvg_acf_role ); ?></p>
				<?php } ?>

				<?php if ( '' !== $zvg_acf_bio ) { ?>
				<p class="zvg-acf-member__bio"><?php echo esc_html( $zvg_acf_bio ); ?></p>
				<?php } ?>

				<?php if ( '' !== $zvg_acf_profile ) { ?>
					<?php if ( '' !== $zvg_acf_toggle ) { ?>
					<button class="zvg-acf-member__toggle" type="button" data-member-open data-member-link="<?php echo esc_attr( $zvg_acf_link ); ?>" hidden>
						<?php echo esc_html( $zvg_acf_toggle ); ?>
						<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
					</button>
					<?php } ?>

				<div class="zvg-acf-member__profile" data-member-profile>
					<?php echo wp_kses_post( $zvg_acf_profile ); ?>
				</div>
				<?php } ?>
			</article>
				<?php
			}

			wp_reset_postdata();
			?>
		</div>
	</div>

	<dialog class="zvg-acf-dialog" data-member-dialog closedby="any" aria-labelledby="<?php echo esc_attr( $zvg_acf_dialog_id ); ?>">
		<div class="zvg-acf-dialog__head">
			<div>
				<h3 class="zvg-acf-dialog__name" id="<?php echo esc_attr( $zvg_acf_dialog_id ); ?>" data-member-name></h3>
				<p class="zvg-acf-dialog__role" data-member-role></p>
			</div>

			<?php if ( '' !== $zvg_acf_close ) { ?>
			<button class="zvg-acf-dialog__close" type="button" data-member-close><?php echo esc_html( $zvg_acf_close ); ?></button>
			<?php } ?>
		</div>

		<img
			class="zvg-acf-dialog__portrait"
			data-member-portrait
			src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
			width="800"
			height="800"
			alt=""
			decoding="async"
			hidden
		>

		<p class="zvg-acf-dialog__bio" data-member-bio></p>

		<div data-member-profile-slot></div>

		<?php if ( '' !== $zvg_acf_link_text ) { ?>
		<a class="zvg-acf-dialog__link" href="" data-member-link hidden><?php echo esc_html( $zvg_acf_link_text ); ?></a>
		<?php } ?>
	</dialog>
</section>
