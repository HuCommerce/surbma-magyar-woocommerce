# Initial Idea

## User's Description

Put all included code into a function and call that function from the related "page-" files.

## Context

Currently, the plugin uses `include_once()` to include settings navigation files into page files:

- `page-directory.php` includes `settings-nav-directory.php`
- `page-information.php` includes `settings-nav-information.php`
- `page-license.php` includes `settings-nav-license.php`
- `page-modules.php` includes `settings-nav-modules.php`
- `page-news.php` includes `settings-nav-news.php`
- `page-offers.php` includes `settings-nav-offers.php`

The goal is to refactor these `settings-nav-*.php` files to wrap their code in functions, and call those functions from the corresponding `page-*.php` files instead of using direct includes.

## Additional Requirement (2025-11-30)

Move the `pages-global-functions.php` include from the central `pages/pages.php` file into each `page-*.php` file's main function, so the global functions will be included only on actual page loads (lazy loading).

Current state in `pages/pages.php`:
```php
// Initialize global functions of pages
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');
```

This include should be moved into each page function (e.g., `cps_hc_gems_modules_page()`) so that the global functions are only loaded when an admin page is actually rendered, improving performance.

