# Specification: Include to Function Refactor

## Goal

Refactor admin page includes to use lazy loading by moving the `pages-global-functions.php` include from the central `pages/pages.php` into each `page-*.php` file's main function, then delete `pages.php` and update `lib/admin.php` to include page files directly.

## User Stories

- As a developer, I want the global functions file included only when admin pages actually render so that unnecessary code is not loaded on every admin request.
- As a maintainer, I want page files to be self-contained with explicit dependencies so that the code is easier to understand and maintain.

## Specific Requirements

**Delete pages/pages.php**
- Remove the file `pages/pages.php` entirely from the codebase
- This file currently serves only as a loader for `pages-global-functions.php` and the `page-*.php` files
- After refactoring, each page file will handle its own dependencies

**Update lib/admin.php Includes**
- Replace line 24 `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages.php');` with direct includes of each `page-*.php` file
- Include `page-modules.php`, `page-directory.php`, `page-information.php`, `page-license.php`
- Keep `page-offers.php` and `page-news.php` commented out (matching current state in `pages.php`)
- Place includes in same location (after settings.php include, before admin_menu hook)

**Add pages-global-functions.php Include to page-modules.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_modules_page()`
- Place before the existing `include_once` for `menu-modules.php`
- Keep all other function content unchanged

**Add pages-global-functions.php Include to page-directory.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_directory_page()`
- Place before the existing `include_once` for `menu-directory.php`
- Keep all other function content unchanged

**Add pages-global-functions.php Include to page-information.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_information_page()`
- Place before the existing `include_once` for `menu-information.php`
- Keep all other function content unchanged

**Add pages-global-functions.php Include to page-license.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_license_page()`
- Place before the existing `include_once` for `menu-license.php`
- Keep all other function content unchanged

**Add pages-global-functions.php Include to page-offers.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_offers_page()`
- Place before the existing `include_once` for `menu-offers.php`
- Include even though this file is currently commented out in admin.php

**Add pages-global-functions.php Include to page-news.php**
- Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as the first line inside `cps_hc_gems_news_page()`
- Place before the existing `include_once` for `menu-news.php`
- Include even though this file is currently commented out in admin.php

**Behavior Preservation**
- HTML output must remain exactly identical before and after refactoring
- No changes to functionality, styling, or logic
- Global functions from `pages-global-functions.php` will be loaded via `include_once` so multiple calls are safe

## Visual Design

No visual assets provided - this is a code refactoring task with no visual changes.

## Existing Code to Leverage

**`pages/page-modules.php` (Current Pattern)**
- Shows the include + function call pattern already implemented for `menu-modules.php`
- The `pages-global-functions.php` include should be added at the very top of `cps_hc_gems_modules_page()` before the existing `menu-modules.php` include
- Function calls like `cps_hc_gems_page_header()` depend on `pages-global-functions.php` being loaded

**`pages/pages.php` (To Be Deleted)**
- Shows the current structure: first includes `pages-global-functions.php`, then includes all `page-*.php` files
- This loading order must be preserved in the new structure (each page function includes global functions first)
- Commented out pages (`page-offers.php`, `page-news.php`) should remain commented out in `lib/admin.php`

**`lib/admin.php` Line 24**
- Currently includes `pages/pages.php` which acts as a loader
- After refactoring, will include each `page-*.php` file directly
- Location is between settings include (line 21) and admin_menu hook (line 27)

**`pages/pages-global-functions.php`**
- Contains functions used by all page files: `cps_hc_gems_page_header()`, `cps_hc_gems_page_sidebar()`, `cps_hc_gems_page_notifications()`, etc.
- Uses `include_once` so safe to include from multiple page functions
- Must be loaded before any page function calls these helper functions

## Out of Scope

- `settings-functions.php` - remains as include, not refactored
- `settings-validate.php` - remains as include, not refactored
- `settings-select-options.php` - remains as include, not refactored
- `settings.php` - not part of this refactoring
- Refactoring global variables to function parameters
- Any visual or UI changes
- Any functional behavior changes
- Moving pages-global-functions.php content into individual files
- Renaming pages-global-functions.php
