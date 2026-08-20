# zvg-multisite

A WordPress multisite where each site is a one-page landing built on a different
stack: Full Site Editing, ACF and Elementor.

Requires WordPress 6.7+ and PHP 7.4+.

## Layout

The repository is rooted at WordPress's `wp-content/`, so it drops straight into
an install. Only own code is tracked — core, bundled themes, third-party plugins
and uploads are excluded.

```
wp-content/
└── themes/
    ├── zvg-fse/         Full Site Editing
    ├── zvg-acf/         ACF          
    └── zvg-elementor/   Elementor    
```

## Licence

© 2026 ZVGrys. All rights reserved. Code is published for review purposes only.
