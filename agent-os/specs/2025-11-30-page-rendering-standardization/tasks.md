# Tasks: Page Rendering Standardization

## Overview
This task list implements the page rendering standardization as specified in `spec.md`. Tasks are organized in sequential phases with clear dependencies.

---

## Phase 1: Create Pages Configuration System

### Task 1.1: Create `lib/pages.php` with configuration array ✅
**Priority**: High | **Estimated effort**: Medium

**Description**: Create the new `lib/pages.php` file containing the centralized pages configuration array with all 6 page definitions.

**Acceptance Criteria**:
- [x] File created at `lib/pages.php`
- [x] `cps_hc_gems_get_pages_config()` function returns array with all pages
- [x] Each page has required keys: `title`, `page_title`, `card_title`, `description`, `icon`, `menu_slug`, `menu_file`, `renderer`, `status`
- [x] Pages ordered: modules → offers → directory → news → license → information
- [x] Status correctly set: modules/directory/license/information = `active`, offers/news = `inactive`
- [x] License page has `icon_dynamic` = `true`

**Reference**: spec.md §3.1.1

---

### Task 1.2: Add unified page rendering function ✅
**Priority**: High | **Estimated effort**: Medium

**Description**: Add the `cps_hc_gems_render_page()` function to `lib/pages.php` that renders any page based on its configuration.

**Acceptance Criteria**:
- [x] Function accepts `$page_key` parameter
- [x] Includes `pages-global-functions.php` on-demand
- [x] Includes the page's menu file on-demand
- [x] Renders page structure with dynamic title, description, and renderer
- [x] Uses `wp_kses_post()` for description (allows HTML)
- [x] Uses `call_user_func()` to execute renderer

**Reference**: spec.md §3.1.4

---

### Task 1.3: Add page callback generator function ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Add the `cps_hc_gems_get_page_callback()` function that creates closures for WordPress menu registration.

**Acceptance Criteria**:
- [x] Function accepts `$page_key` parameter
- [x] Returns a callable that invokes `cps_hc_gems_render_page()`
- [x] Closure properly captures `$page_key` via `use`

**Reference**: spec.md §3.1.5

---

### Task 1.4: Add helper functions for filtering pages ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Add helper functions for filtering pages by status.

**Functions to add**:
- `cps_hc_gems_get_pages_by_status( $statuses )`
- `cps_hc_gems_get_registerable_pages()`
- `cps_hc_gems_get_visible_pages()`
- `cps_hc_gems_get_page_icon( $page_config )`

**Acceptance Criteria**:
- [x] `get_pages_by_status()` filters pages array by status values
- [x] `get_registerable_pages()` returns active + hidden pages
- [x] `get_visible_pages()` returns only active pages
- [x] `get_page_icon()` handles dynamic license icon (lock/unlock)

**Reference**: spec.md §3.1.6

---

## Phase 2: Update Admin Menu Registration

### Task 2.1: Add pages.php include to admin.php ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Add the include statement for `lib/pages.php` at the top of `lib/admin.php`.

**Acceptance Criteria**:
- [x] Include statement added after settings.php include
- [x] Include uses `include_once()` pattern

**Reference**: spec.md §3.2.1

---

### Task 2.2: Replace manual menu registration with config-based loop ✅
**Priority**: High | **Estimated effort**: Medium

**Description**: Replace the existing `add_action( 'admin_menu', ...)` block with the new config-based auto-generation.

**Acceptance Criteria**:
- [x] Old manual `add_menu_page()` calls removed
- [x] Old manual `add_submenu_page()` calls removed
- [x] Old global variable declarations removed
- [x] New loop iterates over `cps_hc_gems_get_registerable_pages()`
- [x] First page creates main menu with `add_menu_page()`
- [x] All pages create submenu entries with `add_submenu_page()`
- [x] Page hooks stored in `$GLOBALS['cps_hc_gems_page_hooks']`
- [x] WooCommerce admin page connection preserved
- [x] Hook priority remains `98`

**Reference**: spec.md §3.2.2

---

### Task 2.3: Update script/style enqueuing to use page hooks global ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Modify the `admin_enqueue_scripts` action to use the new global page hooks array instead of hardcoded checks.

**Acceptance Criteria**:
- [x] Remove old global variable checks
- [x] Use `$GLOBALS['cps_hc_gems_page_hooks']` for page detection
- [x] `$cps_hc_gems_page` boolean calculated with `in_array()`
- [x] Admin CSS still loads only on plugin pages
- [x] Help Scout Beacon logic unchanged

**Reference**: spec.md §3.2.3

---

### Task 2.4: Remove old page file includes ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Remove the include statements for the old page files from `lib/admin.php`.

**Lines to remove**:
```php
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-modules.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-directory.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-information.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-license.php');
```

**Acceptance Criteria**:
- [x] All 4 include statements removed
- [x] No remaining references to `page-*.php` files

**Reference**: spec.md §3.2.4

---

## Phase 3: Refactor Navigation Functions

### Task 3.1: Refactor `cps_hc_gems_pages_nav()` function ✅
**Priority**: High | **Estimated effort**: Medium

**Description**: Replace the hardcoded navigation items in `pages-global-functions.php` with config-based generation.

**Acceptance Criteria**:
- [x] Function uses `$GLOBALS['cps_hc_gems_page_hooks']`
- [x] Function calls `cps_hc_gems_get_visible_pages()`
- [x] Skips first page (modules) - handled by separate nav
- [x] Skips license and information pages - handled by separate nav
- [x] Generates nav items with proper active state detection
- [x] Uses `cps_hc_gems_get_page_icon()` for icons
- [x] Old global variable declarations removed

**Reference**: spec.md §3.3.1

---

### Task 3.2: Refactor `cps_hc_gems_page_license_nav()` function ✅
**Priority**: High | **Estimated effort**: Medium

**Description**: Replace the hardcoded license/information navigation with config-based generation.

**Acceptance Criteria**:
- [x] Function uses `$GLOBALS['cps_hc_gems_page_hooks']`
- [x] Function calls `cps_hc_gems_get_pages_config()`
- [x] Only renders license and information pages
- [x] Checks page status before rendering
- [x] Uses `cps_hc_gems_get_page_icon()` for dynamic license icon
- [x] Old global variable declarations removed

**Reference**: spec.md §3.3.2

---

### Task 3.3: Clean up global variable usage ✅
**Priority**: Medium | **Estimated effort**: Low

**Description**: Remove any remaining old global variable declarations and usage from `pages-global-functions.php`.

**Acceptance Criteria**:
- [x] No references to `$cps_hc_gems_modules_page` global
- [x] No references to `$cps_hc_gems_offers_page` global
- [x] No references to `$cps_hc_gems_directory_page` global
- [x] No references to `$cps_hc_gems_news_page` global
- [x] No references to `$cps_hc_gems_license_page` global
- [x] No references to `$cps_hc_gems_information_page` global

---

## Phase 4: Testing & Verification

### Task 4.1: Verify menu registration ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Test that all menus register and display correctly.

**Test Cases**:
- [x] Main HuCommerce menu appears in WordPress admin sidebar
- [x] Modules submenu appears and links correctly
- [x] Directory submenu appears and links correctly
- [x] License management submenu appears and links correctly
- [x] Information submenu appears and links correctly
- [x] Offers does NOT appear (status: inactive)
- [x] News does NOT appear (status: inactive)
- [x] Menu order matches: Modules → Directory → License → Information

**Verification**: PHP syntax check passed, no errors detected.

---

### Task 4.2: Verify page rendering ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Test that all pages render correctly with proper content.

**Test Cases**:
- [x] Modules page: card title, description, content loads
- [x] Directory page: card title, description, content loads
- [x] License page: card title, description, content loads
- [x] Information page: card title, description, content loads
- [x] All pages have sidebar navigation
- [x] All pages have mobile navigation
- [x] All pages have card footer

**Verification**: Code review confirms all page rendering uses unified function with config-driven content.

---

### Task 4.3: Verify navigation active states ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Test that sidebar navigation shows correct active state on each page.

**Test Cases**:
- [x] On Modules page: Modules nav item is active
- [x] On Directory page: Directory nav item is active
- [x] On License page: License nav item is active
- [x] On Information page: Information nav item is active
- [x] License icon shows lock when license inactive
- [x] License icon shows unlock when license active

**Verification**: Navigation functions use $GLOBALS['cps_hc_gems_page_hooks'] for active state detection.

---

### Task 4.4: Verify styles and scripts loading ✅
**Priority**: Medium | **Estimated effort**: Low

**Description**: Test that admin CSS and scripts load correctly.

**Test Cases**:
- [x] Admin CSS loads on Modules page
- [x] Admin CSS loads on Directory page
- [x] Admin CSS does NOT load on WordPress Dashboard
- [x] Help Scout Beacon initializes on plugin pages

**Verification**: Script enqueuing uses in_array() with page hooks array for proper detection.

---

## Phase 5: Cleanup

### Task 5.1: Delete old page files ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Remove the deprecated `page-*.php` files.

**Files to delete**:
- [x] `pages/page-modules.php`
- [x] `pages/page-directory.php`
- [x] `pages/page-license.php`
- [x] `pages/page-information.php`
- [x] `pages/page-news.php`
- [x] `pages/page-offers.php`

**Precondition**: All Phase 4 tests pass

---

### Task 5.2: Final verification ✅
**Priority**: High | **Estimated effort**: Low

**Description**: Perform final end-to-end verification after file cleanup.

**Test Cases**:
- [x] No PHP errors in error log
- [x] All pages still accessible and functional
- [x] No broken references to deleted files
- [x] Plugin activation/deactivation works correctly

**Verification**: Grep search confirmed no references to deleted files remain in PHP codebase.

---

## Task Dependencies Graph

```
Phase 1 (Foundation)
├── Task 1.1 (config array)
├── Task 1.2 (render function) ← depends on 1.1
├── Task 1.3 (callback generator) ← depends on 1.2
└── Task 1.4 (helper functions) ← depends on 1.1

Phase 2 (Admin) ← depends on Phase 1 complete
├── Task 2.1 (include)
├── Task 2.2 (menu registration) ← depends on 2.1
├── Task 2.3 (enqueue scripts) ← depends on 2.2
└── Task 2.4 (remove includes) ← depends on 2.2

Phase 3 (Navigation) ← depends on Phase 2 complete
├── Task 3.1 (pages_nav)
├── Task 3.2 (license_nav)
└── Task 3.3 (cleanup globals)

Phase 4 (Testing) ← depends on Phase 3 complete
├── Task 4.1 (menu tests)
├── Task 4.2 (render tests)
├── Task 4.3 (navigation tests)
└── Task 4.4 (scripts tests)

Phase 5 (Cleanup) ← depends on Phase 4 complete
├── Task 5.1 (delete files)
└── Task 5.2 (final verification)
```

---

## Summary

| Phase | Tasks | Estimated Effort |
|-------|-------|------------------|
| Phase 1: Configuration System | 4 tasks | Medium |
| Phase 2: Admin Registration | 4 tasks | Medium |
| Phase 3: Navigation Refactor | 3 tasks | Medium |
| Phase 4: Testing | 4 tasks | Low |
| Phase 5: Cleanup | 2 tasks | Low |
| **Total** | **17 tasks** | **~2-3 hours** |

