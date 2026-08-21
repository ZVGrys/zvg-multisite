=== ZVG FSE ===

Contributors: ZVGrys
Theme URI: https://github.com/ZVGrys/zvg-multisite
Requires at least: 6.7
Tested up to: 7.1
Requires PHP: 7.4
Tested PHP: 7.4.33, 8.3.30, 8.4.17, 8.5.2
License: © 2026 ZVGrys. All rights reserved. Code is published for review purposes only.

== Description ==

A minimal WordPress block theme for Full Site Editing, and one of the three builds of the
same landing page that make up the 3-Build Lab comparison.

All design tokens live in theme.json; templates, parts and patterns are pure block markup.
Text that a static template cannot translate is pulled in from a PHP pattern instead, so the
theme carries no permanently English strings. Section stylesheets load only on the requests
that render them, and every asset is versioned by file mtime during development.

Nine server-rendered blocks ship with the theme: a build switcher, a statistics list, a token
flow diagram, a comparison table, a build chooser, a team member trigger and dialog, a share
row and a blockquote.

No third-party dependencies. WCAG 2.1 AA.

== Bundled resources ==

Fonts in assets/fonts/ are third-party and keep their own licences. Full texts ship beside
them.

* Space Grotesk (400, 600) — Florian Karsten, SIL Open Font License 1.1.
  https://github.com/floriankarsten/space-grotesk
  Licence: assets/fonts/LICENSE-space-grotesk.txt

* IBM Plex Mono (400, 600) — IBM, SIL Open Font License 1.1.
  https://github.com/IBM/plex
  Licence: assets/fonts/LICENSE-ibm-plex-mono.txt

Both are subset to latin and served locally; the theme makes no third-party request at
runtime.

== Changelog ==

= 1.0.0 =
* Initial release.
