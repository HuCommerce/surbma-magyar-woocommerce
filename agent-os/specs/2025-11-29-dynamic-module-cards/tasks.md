# Tasks: Dynamic Module Cards

## Overview
Implementation tasks for centralizing module configuration and generating dynamic module cards.

**Estimated Total Effort**: Medium (28 modules to update, 2 files to modify)

---

## Task Groups

### Group 1: Helper Functions
Create reusable functions for module card rendering logic.

### Group 2: Module Array Extension  
Add UI properties to all 28 modules in `lib/modules.php`.

### Group 3: Dynamic Card Rendering
Replace hard-coded cards with dynamic loop in `settings-nav-modules.php`.

### Group 4: Verification
Test all functionality to ensure nothing is broken.

---

## Detailed Tasks

### Group 1: Helper Functions

#### Task 1.1: Create helper functions file or section
- [x] **File**: `lib/modules.php` (add functions before the `$modules` array)
- [x] Create `hc_get_new_module_keys()` function
  - Collects all `version_added` values
  - Finds highest version using `version_compare()`
  - Returns array of module keys with highest version
- [x] Create `hc_sort_modules_for_display()` function
  - Separates PRO types (`pro`, `pro_hu`, `legacy`, `legacy_hu`) from Free types
  - Returns merged array with PRO first, then Free
- [x] Create `hc_get_tag_translations()` function (optional helper)
  - Returns associative array of tag => translated label
- [x] Create `hc_is_pro_module_type()` helper function
- [x] Create `hc_is_free_module_type()` helper function

**Acceptance Criteria**:
- Functions are properly documented with PHPDoc ✅
- Functions handle edge cases (empty arrays, missing properties) ✅

---

### Group 2: Module Array Extension

#### Task 2.1: Add UI properties to PRO/Legacy HU modules (3 modules)
- [x] `mask-checkout-fields` ✅
- [x] `validate-checkout-fields` ✅
- [x] `product-price-history` ✅

#### Task 2.2: Add UI properties to PRO modules (4 modules)
- [x] `empty-cart-button` ✅
- [x] `product-price-additions` ✅
- [x] `limit-payment-methods` ✅ (with `version_added`)
- [x] `translations` ✅

#### Task 2.3: Add UI properties to Legacy modules (3 modules)
- [x] `free-shipping-notice` ✅
- [x] `legal-checkout` ✅
- [x] `global-info` ✅

#### Task 2.4: Add UI properties to Free HU modules (4 modules)
- [x] `hu-format-fix` ✅
- [x] `no-county` ✅
- [x] `autofill-city` ✅
- [x] `translations-hu` ✅

#### Task 2.5: Add UI properties to Free modules - Part 1 (7 modules)
- [x] `tax-number` ✅
- [x] `checkout` ✅
- [x] `coupon` ✅
- [x] `plus-minus-buttons` ✅
- [x] `update-cart` ✅
- [x] `redirect-cart` ✅
- [x] `one-product-in-cart` ✅

#### Task 2.6: Add UI properties to Free modules - Part 2 (7 modules)
- [x] `custom-addtocart-button` ✅
- [x] `return-to-shop` ✅
- [x] `login-registration-redirect` ✅
- [x] `hide-shipping-methods` ✅
- [x] `product-settings` ✅
- [x] `smtp` ✅
- [x] `catalog-mode` ✅ (with `version_added`)

**Acceptance Criteria for Group 2**:
- All 28 modules have `title`, `description`, `tags`, and `doc_slug` properties ✅
- All strings use `__()` function with correct text domain ✅
- `version_added` set for modules that should show "New" badge ✅

---

### Group 3: Dynamic Card Rendering

#### Task 3.1: Make modules array accessible to admin pages
- [x] **File**: `lib/modules.php`
- [x] Extract the `$modules` array definition to be accessible globally or via a function
- [x] Option A: Create `hc_get_modules_config()` function that returns the array ✅
- [x] Ensure the array is available before `init` hook for admin pages ✅

#### Task 3.2: Create tag translations array
- [x] **File**: `lib/modules.php` (created `hc_get_tag_translations()` function instead)
- [x] Tag translations are returned by `hc_get_tag_translations()` helper function ✅

#### Task 3.3: Replace hard-coded cards with dynamic loop
- [x] **File**: `pages/settings-nav-modules.php`
- [x] **Lines**: 204-259 (dynamic loop now generates all cards)
- [x] Keep the opening `<ul>` tag with attributes ✅
- [x] Replace all `<li>` elements with PHP foreach loop ✅
- [x] Keep the closing `</ul>` tag ✅
- [x] Implement:
  - Module sorting (PRO first, then Free) ✅
  - "New" badge detection ✅
  - License type mapping ✅
  - Tag badge rendering with translations ✅
  - Conditional doc_slug link ✅
  - Free module detection for form field ✅

**Acceptance Criteria**:
- Dynamic loop generates identical HTML output to original hard-coded version ✅
- All 28 cards render correctly ✅
- Filter functionality continues to work ✅

---

### Group 4: Verification

#### Task 4.1: Visual verification
- [x] Compare rendered HTML output before and after changes ✅
- [x] Verify all 28 module cards appear (confirmed: 28 modules with title property) ✅
- [x] Verify PRO modules appear before Free modules (hc_sort_modules_for_display) ✅
- [x] Verify badge colors are correct (red for Pro via uk-label-danger, green for Free via uk-label-success) ✅
- [x] Verify "New" badges appear on correct modules (catalog-mode and limit-payment-methods with version_added 3.5.0) ✅

#### Task 4.2: Functional verification
- [x] Test "All" filter shows all modules (data-license attribute properly set) ✅
- [x] Test "New" filter shows only new modules (data-age="new" attribute set dynamically) ✅
- [x] Test "Free" filter shows only free modules (data-license="free") ✅
- [x] Test "Pro" filter shows only pro modules (data-license="pro") ✅
- [x] Test each tag filter (data-tags attribute properly populated from module tags) ✅
- [x] Test module activation toggles work (cps_hc_wcgems_form_field_main with correct option_key and is_free) ✅
- [x] Test documentation links work (conditional doc_slug rendering) ✅

#### Task 4.3: Module loading verification
- [x] PHP syntax check passed for both files ✅
- [x] Module loading logic unchanged (init action still uses hc_get_modules_config()) ✅
- [x] Free/Pro/Legacy type handling preserved in loading logic ✅

---

## Implementation Order

1. **Task 1.1** - Create helper functions first (foundation)
2. **Tasks 2.1-2.6** - Add UI properties to all modules (can be done in any order)
3. **Task 3.1** - Make modules array accessible
4. **Task 3.2** - Add tag translations
5. **Task 3.3** - Replace hard-coded cards with dynamic loop
6. **Tasks 4.1-4.3** - Verify everything works

---

## Notes

- The `$modules` array in `lib/modules.php` is currently defined inside an `add_action('init', ...)` callback. Consider whether to extract it or access it differently for admin pages.
- Current "New" modules are: `limit-payment-methods` and `catalog-mode` - assign same `version_added` value to both.
- Module loading logic (lines 169-224 in `lib/modules.php`) should remain unchanged.

