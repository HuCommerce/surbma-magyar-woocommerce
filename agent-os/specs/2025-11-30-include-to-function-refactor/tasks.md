# Task Breakdown: Include to Function Refactor

## Overview
Total Tasks: 9

## Task List

### Page Files Update

#### Task Group 1: Add pages-global-functions.php Include to Active Page Files
**Dependencies:** None

- [x] 1.0 Complete active page files update
  - [x] 1.1 Update `pages/page-modules.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_modules_page()`
    - Place before existing `include_once` for `menu-modules.php`
    - Keep all other function content unchanged
  - [x] 1.2 Update `pages/page-directory.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_directory_page()`
    - Place before existing `include_once` for `menu-directory.php`
    - Keep all other function content unchanged
  - [x] 1.3 Update `pages/page-information.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_information_page()`
    - Place before existing `include_once` for `menu-information.php`
    - Keep all other function content unchanged
  - [x] 1.4 Update `pages/page-license.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_license_page()`
    - Place before existing `include_once` for `menu-license.php`
    - Keep all other function content unchanged

**Acceptance Criteria:**
- ✅ All 4 active page files have `pages-global-functions.php` include at top of their main function
- ✅ Include is placed before any other includes or function calls
- ✅ No other changes to file content

#### Task Group 2: Add pages-global-functions.php Include to Inactive Page Files
**Dependencies:** None (can run parallel to Task Group 1)

- [x] 2.0 Complete inactive page files update
  - [x] 2.1 Update `pages/page-offers.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_offers_page()`
    - Place before existing `include_once` for `menu-offers.php`
    - Keep all other function content unchanged
  - [x] 2.2 Update `pages/page-news.php`
    - Add `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');` as first line inside `cps_hc_gems_news_page()`
    - Place before existing `include_once` for `menu-news.php`
    - Keep all other function content unchanged

**Acceptance Criteria:**
- ✅ Both inactive page files have `pages-global-functions.php` include at top of their main function
- ✅ Include is placed before any other includes or function calls
- ✅ No other changes to file content

### Admin Loader Update

#### Task Group 3: Update lib/admin.php and Delete pages.php
**Dependencies:** Task Groups 1 and 2

- [x] 3.0 Complete admin loader refactoring
  - [x] 3.1 Update `lib/admin.php` includes
    - Replace line 24 `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages.php');`
    - Add direct includes for each page file:
      - `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-modules.php');`
      - `// include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-offers.php');`
      - `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-directory.php');`
      - `// include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-news.php');`
      - `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-information.php');`
      - `include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-license.php');`
    - Maintain comment "// Initialize pages" before the includes
  - [x] 3.2 Delete `pages/pages.php`
    - Remove the file entirely from the codebase
    - This file is no longer needed after direct includes in admin.php

**Acceptance Criteria:**
- ✅ `lib/admin.php` includes page files directly (not via pages.php)
- ✅ `page-offers.php` and `page-news.php` remain commented out
- ✅ `pages/pages.php` is deleted
- ✅ Includes are in same location (after settings.php, before admin_menu hook)

### Verification

#### Task Group 4: Manual Verification
**Dependencies:** Task Group 3

- [x] 4.0 Verify refactoring works correctly
  - [x] 4.1 Test HuCommerce Modules page
    - Navigate to WP Admin → HuCommerce → Modules
    - Verify page loads without errors
    - Verify all content displays correctly
  - [x] 4.2 Test HuCommerce Directory page
    - Navigate to WP Admin → HuCommerce → Directory
    - Verify page loads without errors
    - Verify all content displays correctly
  - [x] 4.3 Test HuCommerce License page
    - Navigate to WP Admin → HuCommerce → License management
    - Verify page loads without errors
    - Verify all content displays correctly
  - [x] 4.4 Test HuCommerce Information page
    - Navigate to WP Admin → HuCommerce → Information
    - Verify page loads without errors
    - Verify all content displays correctly

**Acceptance Criteria:**
- ✅ All 4 active admin pages load without PHP errors
- ✅ All page content, navigation, and functionality work identically to before
- ✅ No visible changes to UI or behavior
- ✅ Lazy loading benefit achieved (pages-global-functions.php only loaded on page render)

## Execution Order

Recommended implementation sequence:
1. ✅ Task Group 1: Update active page files (page-modules, page-directory, page-information, page-license)
2. ✅ Task Group 2: Update inactive page files (page-offers, page-news) - can run parallel to Group 1
3. ✅ Task Group 3: Update lib/admin.php and delete pages.php
4. ✅ Task Group 4: Manual verification of all admin pages

## Implementation Complete! 🎉

All tasks have been completed and verified successfully.
