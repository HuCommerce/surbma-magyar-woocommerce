# Spec Requirements: Include to Function Refactor

## Initial Description

Put all included code into a function and call that function from the related "page-" files.

## Requirements Discussion

### First Round Questions (Original Spec)

**Q1:** I assume the function naming should follow the existing codebase pattern with the `cps_hc_gems_` prefix (e.g., `cps_hc_gems_render_settings_nav_modules()`). Is that correct, or would you prefer a different naming convention?
**Answer:** Yes, correct. Follow the existing codebase pattern.

**Q2:** I'm thinking the `settings-nav-*.php` files should be included once at a central location (perhaps in `settings-functions.php` or loaded via `lib/admin.php`), so the functions are defined before the `page-*.php` files call them. Should we: A) Include all from one central file, or B) Keep each `page-*.php` file responsible for including its own file before calling the function?
**Answer:** B) Keep each `page-*.php` file responsible for including its own file before calling the function.

**Q3:** The `settings-nav-modules.php` file currently uses several global variables (lines 13-26). Should we: A) Keep the global variables as-is inside the function, B) Pass them as function parameters, or C) Refactor global access to a settings object?
**Answer:** A) Keep the global variables as-is inside the function.

**Q4:** I assume we should refactor all 6 `settings-nav-*.php` files for consistency. Is that correct, or are there specific files you'd like to prioritize?
**Answer:** Correct. And also rename them: replace the "settings-nav-" prefix with "menu-" prefix to be more clear about these files' functions.

**Q5:** Should the wrapped functions return any value or just output HTML directly (void functions)?
**Answer:** Just output the HTML.

**Q6:** Is there anything that should be explicitly excluded from this refactoring? For example, should `settings-functions.php`, `settings-validate.php`, or `settings-select-options.php` remain as includes?
**Answer:** Yes, the `settings-functions.php`, `settings-validate.php` and `settings-select-options.php` remain as includes. We only refactor the files that are called from the "page-*.php" files.

### Additional Requirements Questions (2025-11-30)

**Q1:** I assume the include should be placed at the very beginning of each page function (e.g., the first line inside `cps_hc_gems_modules_page()`), before any calls to functions from `pages-global-functions.php`. Is that correct?
**Answer:** Yes, correct.

**Q2:** After this refactor, I'm thinking `pages/pages.php` should only include the `page-*.php` files, and each page file will handle its own include. Should we: A) Keep `pages.php` but remove the `pages-global-functions.php` include from it, or B) Remove `pages.php` entirely and include the `page-*.php` files directly from wherever `pages.php` is currently loaded?
**Answer:** B) Remove `pages.php` entirely and include the `page-*.php` files directly from `lib/admin.php`.

**Q3:** Should we still add the `pages-global-functions.php` include to those files for consistency, or only update the active ones (page-offers.php and page-news.php are commented out)?
**Answer:** Add the include to those files also, that are commented out now.

**Q4:** Include order in the function?
**Answer:** Add the includes at the very top of the function, so first include all files needed for the function to run.

### Existing Code to Reference

**Similar Features Identified:**
- Feature: Existing page functions - Path: `pages/page-modules.php` (uses functions like `cps_hc_gems_page_header()`, `cps_hc_gems_page_sidebar()`, etc.)
- Components to potentially reuse: Follow same function pattern as existing `cps_hc_gems_*` functions
- Backend logic to reference: `pages/settings-functions.php` for function definitions pattern
- Pattern to follow: `menu-*.php` includes inside page functions (already implemented)

### Follow-up Questions

No follow-up questions needed - all requirements are clear.

## Visual Assets

### Files Provided:
No visual assets provided.

### Visual Insights:
N/A - This is a code refactoring task with no visual changes.

## Requirements Summary

### Functional Requirements

**Part 1: File Renaming (Completed)**
- ✅ Rename `settings-nav-directory.php` → `menu-directory.php`
- ✅ Rename `settings-nav-information.php` → `menu-information.php`
- ✅ Rename `settings-nav-license.php` → `menu-license.php`
- ✅ Rename `settings-nav-modules.php` → `menu-modules.php`
- ✅ Rename `settings-nav-news.php` → `menu-news.php`
- ✅ Rename `settings-nav-offers.php` → `menu-offers.php`

**Part 2: Function Wrapping (Completed)**
Each renamed file wraps its content in a function following the naming pattern:
- ✅ `menu-directory.php` → `cps_hc_gems_render_menu_directory()`
- ✅ `menu-information.php` → `cps_hc_gems_render_menu_information()`
- ✅ `menu-license.php` → `cps_hc_gems_render_menu_license()`
- ✅ `menu-modules.php` → `cps_hc_gems_render_menu_modules()`
- ✅ `menu-news.php` → `cps_hc_gems_render_menu_news()`
- ✅ `menu-offers.php` → `cps_hc_gems_render_menu_offers()`

**Part 3: Move pages-global-functions.php Include (NEW)**

1. **Remove `pages/pages.php`** - This file will be deleted entirely.

2. **Update `lib/admin.php`** - Replace the single include of `pages.php` with direct includes of each `page-*.php` file:
   ```php
   // OLD (line 24):
   include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages.php');
   
   // NEW:
   include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-modules.php');
   // include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-offers.php');
   include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-directory.php');
   // include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-news.php');
   include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-information.php');
   include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-license.php');
   ```

3. **Update each `page-*.php` file** - Add `pages-global-functions.php` include at the very top of each page function:
   ```php
   function cps_hc_gems_modules_page() {
       include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');
       include_once( SURBMA_HC_PLUGIN_DIR . '/pages/menu-modules.php');
       cps_hc_gems_page_header();
       // ... rest of function
   }
   ```

**Global Variables:**
- Keep existing `global` variable declarations inside the new functions
- No change to how global variables are accessed

### Reusability Opportunities

- Components that might exist already: Existing `cps_hc_gems_*` function patterns in `settings-functions.php`
- Backend patterns to investigate: N/A
- Similar features to model after: `cps_hc_gems_page_header()`, `cps_hc_gems_page_sidebar()`, etc.

### Scope Boundaries

**In Scope:**
- ✅ Rename 6 `settings-nav-*.php` files to `menu-*.php` (DONE)
- ✅ Wrap content of each file in a function (DONE)
- ✅ Update 6 `page-*.php` files to include the renamed files and call the functions (DONE)
- Delete `pages/pages.php`
- Update `lib/admin.php` to include `page-*.php` files directly
- Add `pages-global-functions.php` include to all 6 `page-*.php` files (including commented out ones)

**Out of Scope:**
- `settings-functions.php` - remains as include
- `settings-validate.php` - remains as include
- `settings-select-options.php` - remains as include
- `settings.php` - not affected
- Refactoring global variables to parameters
- Any visual/UI changes
- Any functional behavior changes

### Technical Considerations

- Integration points: `page-*.php` files are entry points for admin pages
- Existing system constraints: Must maintain exact same HTML output and behavior
- Technology preferences: Follow WordPress/PHP function patterns
- Similar code patterns to follow: `cps_hc_gems_` prefix for all function names
- Lazy loading benefit: `pages-global-functions.php` only loaded when admin page actually renders

### File Mapping Summary

| Current File | New File | New Function |
|--------------|----------|--------------|
| `settings-nav-directory.php` | `menu-directory.php` | `cps_hc_gems_render_menu_directory()` |
| `settings-nav-information.php` | `menu-information.php` | `cps_hc_gems_render_menu_information()` |
| `settings-nav-license.php` | `menu-license.php` | `cps_hc_gems_render_menu_license()` |
| `settings-nav-modules.php` | `menu-modules.php` | `cps_hc_gems_render_menu_modules()` |
| `settings-nav-news.php` | `menu-news.php` | `cps_hc_gems_render_menu_news()` |
| `settings-nav-offers.php` | `menu-offers.php` | `cps_hc_gems_render_menu_offers()` |

| Page File | Update Required |
|-----------|-----------------|
| `page-directory.php` | Add `pages-global-functions.php` include at top of function |
| `page-information.php` | Add `pages-global-functions.php` include at top of function |
| `page-license.php` | Add `pages-global-functions.php` include at top of function |
| `page-modules.php` | Add `pages-global-functions.php` include at top of function |
| `page-news.php` | Add `pages-global-functions.php` include at top of function |
| `page-offers.php` | Add `pages-global-functions.php` include at top of function |

| File | Action |
|------|--------|
| `pages/pages.php` | DELETE |
| `lib/admin.php` | Update includes (replace `pages.php` with direct `page-*.php` includes) |

