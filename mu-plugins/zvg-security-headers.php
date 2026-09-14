<?php
/**
 * Plugin Name: ZVG Security Headers
 * Description: Sends the baseline security response headers on every request of every build.
 * Version: 1.0.0
 * Author: ZVGrys
 * Network: true
 *
 * The same headers are set for static assets in the root .htaccess. They are repeated here
 * because mod_fastcgi does not run mod_headers over a PHP response, so on this stack the
 * .htaccess block reaches stylesheets, fonts and images but never a rendered page.
 *
 * @package ZVG
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'wp_headers', 'zvg_security_headers' );

/**
 * The headers every response carries.
 *
 * The content security policy is sent as `Content-Security-Policy-Report-Only`: the three builds
 * load inline styles and scripts from core, Elementor and Contact Form 7, so the policy reports
 * what it would block until an audit of those sources shows it can be enforced.
 *
 * @param array<string, string> $headers Headers WordPress is about to send.
 *
 * @return array<string, string>
 */
function zvg_security_headers( $headers ) {
	$headers['X-Content-Type-Options']              = 'nosniff';
	$headers['X-Frame-Options']                     = 'SAMEORIGIN';
	$headers['Referrer-Policy']                     = 'strict-origin-when-cross-origin';
	$headers['Cross-Origin-Opener-Policy']          = 'same-origin';
	$headers['Permissions-Policy']                  = 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()';
	$headers['Content-Security-Policy-Report-Only'] = zvg_security_headers_csp();

	return $headers;
}

add_filter( 'wp_inline_script_attributes', 'zvg_security_headers_nonce_inline_script' );

/**
 * The nonce this response allows inline scripts with.
 *
 * @return string
 */
function zvg_security_headers_nonce() {
	static $nonce = null;

	if ( null === $nonce ) {
		$nonce = base64_encode( random_bytes( 18 ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Encoding random bytes for a CSP nonce.
	}

	return $nonce;
}

/**
 * Give every inline script printed through core the response nonce.
 *
 * @param array<string, string|bool> $attributes Script tag attributes.
 *
 * @return array<string, string|bool>
 */
function zvg_security_headers_nonce_inline_script( $attributes ) {
	if ( ! is_admin() ) {
		$attributes['nonce'] = zvg_security_headers_nonce();
	}

	return $attributes;
}

/**
 * Hashes of the inline scripts that plugins print by hand, outside core's script API.
 *
 * `elementor/modules/lazyload/module.php` prints the background lazy-load observer straight into
 * `wp_footer`. A plugin update that changes that script changes its hash and shows up as a
 * violation again.
 *
 * @return string[]
 */
function zvg_security_headers_script_hashes() {
	return array(
		"'sha256-GM+by+4ISzNYVDO4JNTxeYFeCxSAROUXmwbFZAz/8ro='",
	);
}

/**
 * The content security policy the builds are audited against.
 *
 * Scripts are held to the site itself, the response nonce and known hashes. Styles allow
 * `'unsafe-inline'`: block supports write `style` attributes, which neither a nonce nor a hash
 * can cover.
 *
 * @return string
 */
function zvg_security_headers_csp() {
	$directives = array(
		'default-src'     => "'self'",
		'script-src'      => "'self' 'nonce-" . zvg_security_headers_nonce() . "' " . implode( ' ', zvg_security_headers_script_hashes() ),
		'style-src'       => "'self' 'unsafe-inline'",
		'img-src'         => "'self' data:",
		'worker-src'      => "'self'",
		'font-src'        => "'self'",
		'connect-src'     => "'self'",
		'frame-src'       => "'self'",
		'object-src'      => "'none'",
		'base-uri'        => "'self'",
		'form-action'     => "'self'",
		'frame-ancestors' => "'self'",
	);

	/**
	 * Filter the content security policy directives.
	 *
	 * @param array<string, string> $directives Source list keyed by directive.
	 */
	$directives = apply_filters( 'zvg_security_headers_csp', $directives );

	$policy = array();

	foreach ( $directives as $directive => $sources ) {
		$policy[] = trim( $directive . ' ' . $sources );
	}

	return implode( '; ', $policy );
}

add_action( 'send_headers', 'zvg_security_headers_drop_server_hints', 1 );

/**
 * Drop the headers that only tell an attacker what to try.
 *
 * `X-Powered-By` comes from PHP itself, so `expose_php = Off` in php.ini is the real fix;
 * this covers the hosts where that file is not ours to edit.
 */
function zvg_security_headers_drop_server_hints() {
	if ( headers_sent() ) {
		return;
	}

	header_remove( 'X-Powered-By' );
	header_remove( 'X-Pingback' );
}
