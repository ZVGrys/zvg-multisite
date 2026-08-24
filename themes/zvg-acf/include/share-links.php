<?php
/**
 * Share links for single entries.
 *
 * @package ZVG_ACF
 */

defined( 'ABSPATH' ) || exit;

/**
 * The icon of the copy button, which is not a network and has no share address.
 */
const ZVG_ACF_SHARE_COPY_ICON = 'M10 13a5 5 0 0 0 7.07 0l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71 M14 11a5 5 0 0 0-7.07 0l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71';

if ( ! function_exists( 'zvg_acf_share_networks' ) ) :

	/**
	 * The networks an entry can be handed to.
	 *
	 * @return array<string, array<string, mixed>> Key => name, share address and icon path.
	 */
	function zvg_acf_share_networks() {
		$zvg_acf_networks = array(
			'linkedin' => array(
				'name' => 'LinkedIn',
				'url'  => 'https://www.linkedin.com/sharing/share-offsite/?url=%1$s',
				'icon' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
			),
			'facebook' => array(
				'name' => 'Facebook',
				'url'  => 'https://www.facebook.com/sharer/sharer.php?u=%1$s',
				'icon' => 'M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647z',
			),
			'x'        => array(
				'name' => 'X',
				'url'  => 'https://x.com/intent/post?url=%1$s&text=%2$s',
				'icon' => 'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932zM17.61 20.644h2.039L6.486 3.24H4.298z',
			),
			'telegram' => array(
				'name' => 'Telegram',
				'url'  => 'https://t.me/share/url?url=%1$s&text=%2$s',
				'icon' => 'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z',
			),
			'whatsapp' => array(
				'name' => 'WhatsApp',
				'url'  => 'https://wa.me/?text=%2$s%%20%1$s',
				'icon' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z',
			),
			'email'    => array(
				'name'   => _x( 'Email', 'Share network', 'zvg-acf' ),
				'url'    => 'mailto:?subject=%2$s&body=%1$s',
				'icon'   => 'M3 6h18v12H3z M3 6l9 6 9-6',
				'stroke' => true,
			),
		);

		/**
		 * Filter the share networks.
		 *
		 * @param array<string, array<string, mixed>> $zvg_acf_networks Key => name, share address and icon path.
		 */
		return apply_filters( 'zvg_acf_share_networks', $zvg_acf_networks );
	}
endif;

if ( ! function_exists( 'zvg_acf_chosen_share_networks' ) ) :

	/**
	 * The networks Site Options hands the entry to, in the order the catalogue declares them.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	function zvg_acf_chosen_share_networks() {
		$zvg_acf_networks = zvg_acf_share_networks();
		$zvg_acf_chosen   = zvg_acf_post_option( 'post_share_networks' );

		if ( ! is_array( $zvg_acf_chosen ) ) {
			return array();
		}

		return array_intersect_key( $zvg_acf_networks, array_flip( $zvg_acf_chosen ) );
	}
endif;

if ( ! function_exists( 'zvg_acf_render_share_links' ) ) :

	/**
	 * Print the share links of an entry.
	 *
	 * @param int $post_id Entry to share.
	 */
	function zvg_acf_render_share_links( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || ! zvg_acf_post_option( 'post_share_show' ) ) {
			return;
		}

		$zvg_acf_url = (string) get_permalink( $post_id );

		if ( '' === $zvg_acf_url ) {
			return;
		}

		$zvg_acf_networks = zvg_acf_chosen_share_networks();
		$zvg_acf_copy     = (bool) zvg_acf_post_option( 'post_share_copy' );

		if ( empty( $zvg_acf_networks ) && ! $zvg_acf_copy ) {
			return;
		}

		$zvg_acf_title = get_the_title( $post_id );
		$zvg_acf_label = trim( (string) zvg_acf_post_option( 'post_share_label' ) );
		?>
<div class="zvg-acf-share">
		<?php if ( '' !== $zvg_acf_label ) { ?>
	<p class="zvg-acf-share__label"><?php echo esc_html( $zvg_acf_label ); ?></p>
		<?php } ?>

	<ul class="zvg-acf-share__list">
		<?php foreach ( $zvg_acf_networks as $zvg_acf_network ) { ?>
		<li class="zvg-acf-share__item">
			<a class="zvg-acf-share__link" href="<?php echo esc_url( sprintf( $zvg_acf_network['url'], rawurlencode( $zvg_acf_url ), rawurlencode( $zvg_acf_title ) ) ); ?>" target="_blank" rel="noopener noreferrer">
				<svg class="zvg-acf-share__icon<?php echo empty( $zvg_acf_network['stroke'] ) ? '' : ' is-stroked'; ?>" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr( $zvg_acf_network['icon'] ); ?>" /></svg>
				<?php echo esc_html( $zvg_acf_network['name'] ); ?>
				<span class="screen-reader-text"><?php echo esc_html_x( '(opens in a new tab)', 'Share link', 'zvg-acf' ); ?></span>
			</a>
		</li>
		<?php } ?>

		<?php if ( $zvg_acf_copy ) { ?>
		<li class="zvg-acf-share__item">
			<button class="zvg-acf-share__link zvg-acf-share__copy" type="button" data-share-copy="<?php echo esc_url( $zvg_acf_url ); ?>" data-share-done="<?php echo esc_attr_x( 'Link copied', 'Share links', 'zvg-acf' ); ?>">
				<svg class="zvg-acf-share__icon is-stroked" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="<?php echo esc_attr( ZVG_ACF_SHARE_COPY_ICON ); ?>" /></svg>
				<span class="zvg-acf-share__copy-label" aria-live="polite"><?php echo esc_html_x( 'Copy link', 'Share links', 'zvg-acf' ); ?></span>
			</button>
		</li>
		<?php } ?>
	</ul>
</div>
		<?php
	}
endif;
