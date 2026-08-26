<?php
/**
 * Fatal error template, shared by all three builds.
 *
 * WordPress loads this drop-in from `WP_Fatal_Error_Handler::display_error_template()` when PHP
 * has died. No theme, no plugins and possibly no WordPress functions are available — core warns
 * the drop-in may run "very early in the bootstrap process" — so everything here is plain PHP,
 * the markup carries its own styles inline, and nothing is fetched over the network.
 *
 * The drop-in owns the status code; core does not set one when a custom template is present.
 *
 * `$error` (from `error_get_last()`) and `$handled` are in scope. Neither is printed: a stack
 * trace on a public page tells an attacker the paths, the PHP version and the failing extension.
 *
 * @package zvg
 */

/*
 * Discard anything already buffered.
 *
 * When php.ini has `display_errors` on — MAMP's default, and not unheard of on a live server —
 * PHP writes the fatal and its stack trace to the output buffer before WordPress's shutdown
 * handler reaches this template. Nothing has been flushed yet (the headers below still send),
 * so dropping every buffer level is what makes the promise "no stack trace" hold regardless of
 * how the server is configured, rather than only when it is configured correctly.
 */
while ( ob_get_level() > 0 ) {
	ob_end_clean();
}

if ( ! headers_sent() ) {
	http_response_code( 500 );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'Retry-After: 120' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	header( 'Cache-Control: no-store, max-age=0' );
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Something broke &mdash; 3-Build Lab</title>
<style>
	:root {
		color-scheme: dark;
		--surface: #14111a;
		--text: #ece8f0;
		--muted: #a79fb5;
		--accent: #ff8a65;
		--accent-ink: #1a1420;
		--border: #2a2536;
	}

	* { box-sizing: border-box; }

	body {
		margin: 0;
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 24px;
		background-color: var(--surface);
		color: var(--text);
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
		font-size: 17px;
		line-height: 1.6;
	}

	main { max-width: 560px; }

	.code {
		margin: 0 0 8px;
		font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
		font-size: 15px;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: var(--accent);
	}

	h1 {
		margin: 0 0 16px;
		font-size: 40px;
		font-weight: 600;
		line-height: 1.2;
		text-wrap: balance;
	}

	@media (max-width: 600px) {
		h1 { font-size: 32px; }
	}

	p {
		margin: 0 0 16px;
		color: var(--muted);
		text-wrap: pretty;
	}

	.actions {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		margin-top: 28px;
		padding-top: 28px;
		border-top: 1px solid var(--border);
	}

	a {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 44px;
		padding: 10px 24px;
		border: 1.5px solid var(--accent);
		border-radius: 4px;
		background-color: var(--accent);
		color: var(--accent-ink);
		font-size: 16px;
		font-weight: 600;
		text-decoration: none;
	}

	a + a {
		background-color: transparent;
		border-color: var(--border);
		color: var(--text);
	}

	a:hover,
	a:focus-visible { opacity: .88; }

	a:focus-visible {
		outline: 3px solid var(--accent);
		outline-offset: 2px;
	}
</style>
</head>
<body>
	<main>
		<p class="code">Error 500</p>
		<h1>Something broke on the server.</h1>
		<p>This one is not your fault &mdash; the site hit a fatal error before it could render the page. Nothing you did caused it, and nothing you sent was lost.</p>
		<p>It is usually brief. Try again in a minute.</p>
		<div class="actions">
			<a href="/">Back to the homepage</a>
			<a href="https://github.com/ZVGrys/zvg-multisite/issues">Report it</a>
		</div>
	</main>
</body>
</html>
