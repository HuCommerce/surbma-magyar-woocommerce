# Function Prefix Standardization Specification

## Overview

This specification defines the process for standardizing all PHP function prefixes across the HuCommerce plugin codebase to use a consistent `cps_hc_gems_` prefix. This refactoring ensures naming consistency, improves code maintainability, and reduces the risk of function name collisions with other plugins.

## Goals

1. **Standardize function naming** - All non-anonymous PHP functions should use the `cps_hc_gems_` prefix
2. **Maintain functionality** - No changes to business logic; pure refactoring
3. **Update all references** - Function definitions, calls, and WordPress hook registrations must all be updated
4. **Preserve SDK isolation** - Keep CPS-SDK functions unchanged for cross-plugin compatibility

## Non-Goals

- Refactoring function logic or parameters
- Changing JavaScript function names (client-side only)
- Modifying third-party vendor code
- Restructuring file organization

## Background

### Current State

The codebase currently has **6 different function prefixes** in use:

| Prefix | Count | Origin | Status |
|--------|-------|--------|--------|
| `cps_hc_gems_` | 2 | Current standard | ✅ Keep |
| `cps_hc_wcgems_` | 15 | Previous iteration | 🔄 Rename |
| `surbma_hc_` | 31 | Legacy naming | 🔄 Rename |
| `cps_wcgems_hc_` | 3 | Alternate format | 🔄 Rename |
| `hc_` | 6 | Abbreviated | 🔄 Rename |
| `cps_` | 8 | SDK functions | ⏭️ Exclude |

### Why This Change

- **Consistency**: A single prefix makes the codebase easier to navigate and maintain
- **Collision Prevention**: The longer, unique prefix reduces risk of conflicts with other plugins
- **Code Standard**: Establishes a clear naming convention documented in README.md

## Technical Approach

### Prefix Transformation Rules

```
surbma_hc_*       → cps_hc_gems_*
cps_wcgems_hc_*   → cps_hc_gems_*
cps_hc_wcgems_*   → cps_hc_gems_*
hc_*              → cps_hc_gems_*
```

### Search & Replace Strategy

For each function, update:
1. **Function definition**: `function old_name(` → `function new_name(`
2. **Function calls**: `old_name(` → `new_name(`
3. **Hook callbacks**: `add_action( 'hook', 'old_name' )` → `add_action( 'hook', 'new_name' )`
4. **Filter callbacks**: `add_filter( 'filter', 'old_name' )` → `add_filter( 'filter', 'new_name' )`

### Files to Modify

#### Core Library (`lib/`)

| File | Functions | Changes Required |
|------|-----------|------------------|
| `lib/modules.php` | 6 | Rename `hc_*` functions |
| `lib/start.php` | 1 | Rename `surbma_hc_*` function |
| `lib/license.php` | 3 | Rename `surbma_hc_*` functions |
| `lib/admin.php` | 2 | Rename `surbma_hc_*` and `cps_wcgems_hc_*` functions |

#### Admin Pages (`pages/`)

| File | Functions | Changes Required |
|------|-----------|------------------|
| `pages/settings-functions.php` | 12 | Rename `cps_hc_wcgems_*` functions |
| `pages/settings-validate.php` | 2 | Rename `surbma_hc_*` functions |
| `pages/settings-nav-license.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-globals.php` | 10 | Rename `surbma_hc_*` functions |
| `pages/page-offers.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-news.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-modules.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-license.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-information.php` | 1 | Rename `surbma_hc_*` function |
| `pages/page-directory.php` | 1 | Rename `surbma_hc_*` function |

#### Modules (`modules/`)

| File | Functions | Changes Required |
|------|-----------|------------------|
| `modules/product-settings.php` | 6 | Rename `surbma_hc_*` functions |
| `modules/tax-number.php` | 1 | Rename `cps_wcgems_hc_*` function |
| `modules/checkout.php` | 1 | Rename `cps_wcgems_hc_*` function |
| `modules/limit-payment-methods.php` | 2 | Rename `cps_hc_wcgems_*` functions |
| `modules/custom-addtocart-button.php` | 1 | Rename `cps_hc_wcgems_*` function |

#### Hungarian Modules (`modules-hu/`)

| File | Functions | Changes Required |
|------|-----------|------------------|
| `modules-hu/product-price-history.php` | 3 | Rename `surbma_hc_*` functions |
| `modules-hu/validate-checkout-fields.php` | 1 | Rename `cps_wcgems_hc_*` function |

## Detailed Function Mapping

### lib/modules.php (6 functions)

| Old Name | New Name |
|----------|----------|
| `hc_get_modules_config` | `cps_hc_gems_get_modules_config` |
| `hc_get_new_module_keys` | `cps_hc_gems_get_new_module_keys` |
| `hc_sort_modules_for_display` | `cps_hc_gems_sort_modules_for_display` |
| `hc_get_tag_translations` | `cps_hc_gems_get_tag_translations` |
| `hc_is_pro_module_type` | `cps_hc_gems_is_pro_module_type` |
| `hc_is_free_module_type` | `cps_hc_gems_is_free_module_type` |

### lib/start.php (1 function)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_woocommerce_version_check` | `cps_hc_gems_woocommerce_version_check` |

### lib/license.php (3 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_license_create_url` | `cps_hc_gems_license_create_url` |
| `surbma_hc_license_status_update` | `cps_hc_gems_license_status_update` |
| `surbma_hc_license_api_manager_action` | `cps_hc_gems_license_api_manager_action` |

### lib/admin.php (2 functions)

| Old Name | New Name |
|----------|----------|
| `cps_wcgems_hc_allowed_post_tags` | `cps_hc_gems_allowed_post_tags` |
| `surbma_hc_dashboard` | `cps_hc_gems_dashboard` |

### pages/settings-functions.php (12 functions)

| Old Name | New Name |
|----------|----------|
| `cps_hc_wcgems_nav_item_header` | `cps_hc_gems_nav_item_header` |
| `cps_hc_wcgems_module_nav_item` | `cps_hc_gems_module_nav_item` |
| `cps_hc_wcgems_form_accordion_title` | `cps_hc_gems_form_accordion_title` |
| `cps_hc_wcgems_module_card_more` | `cps_hc_gems_module_card_more` |
| `cps_hc_wcgems_form_field_main` | `cps_hc_gems_form_field_main` |
| `cps_hc_wcgems_form_modal` | `cps_hc_gems_form_modal` |
| `cps_hc_wcgems_form_field_checkbox` | `cps_hc_gems_form_field_checkbox` |
| `cps_hc_wcgems_form_field_select` | `cps_hc_gems_form_field_select` |
| `cps_hc_wcgems_form_field_text` | `cps_hc_gems_form_field_text` |
| `cps_hc_wcgems_form_field_number` | `cps_hc_gems_form_field_number` |
| `cps_hc_wcgems_form_field_password` | `cps_hc_gems_form_field_password` |
| `cps_hc_wcgems_form_field_textarea` | `cps_hc_gems_form_field_textarea` |

### pages/settings-validate.php (2 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_fields_validate` | `cps_hc_gems_fields_validate` |
| `surbma_hc_license_validate` | `cps_hc_gems_license_validate` |

### pages/settings-nav-license.php (1 function)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_mask` | `cps_hc_gems_mask` |

### pages/page-globals.php (10 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_page_modules_nav` | `cps_hc_gems_page_modules_nav` |
| `surbma_hc_pages_nav` | `cps_hc_gems_pages_nav` |
| `surbma_hc_page_license_nav` | `cps_hc_gems_page_license_nav` |
| `surbma_hc_page_social_nav` | `cps_hc_gems_page_social_nav` |
| `surbma_hc_page_header` | `cps_hc_gems_page_header` |
| `surbma_hc_page_notifications` | `cps_hc_gems_page_notifications` |
| `surbma_hc_page_sidebar` | `cps_hc_gems_page_sidebar` |
| `surbma_hc_page_mobile_nav` | `cps_hc_gems_page_mobile_nav` |
| `surbma_hc_page_card_footer` | `cps_hc_gems_page_card_footer` |
| `surbma_hc_page_footer` | `cps_hc_gems_page_footer` |

### pages/page-*.php (6 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_offers_page` | `cps_hc_gems_offers_page` |
| `surbma_hc_news_page` | `cps_hc_gems_news_page` |
| `surbma_hc_modules_page` | `cps_hc_gems_modules_page` |
| `surbma_hc_license_page` | `cps_hc_gems_license_page` |
| `surbma_hc_information_page` | `cps_hc_gems_information_page` |
| `surbma_hc_directory_page` | `cps_hc_gems_directory_page` |

### modules/product-settings.php (6 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_get_product_settings` | `cps_hc_gems_get_product_settings` |
| `surbma_hc_register_product_metabox` | `cps_hc_gems_register_product_metabox` |
| `surbma_hc_product_metabox` | `cps_hc_gems_product_metabox` |
| `surbma_hc_save_product_metabox` | `cps_hc_gems_save_product_metabox` |
| `surbma_hc_add_product_subtitle` | `cps_hc_gems_add_product_subtitle` |
| `surbma_hc_product_subtitle_styles` | `cps_hc_gems_product_subtitle_styles` |

### modules/tax-number.php (1 function)

| Old Name | New Name |
|----------|----------|
| `cps_wcgems_hc_billing_tax_number_check` | `cps_hc_gems_billing_tax_number_check` |

### modules/checkout.php (1 function)

| Old Name | New Name |
|----------|----------|
| `cps_wcgems_hc_billing_company_check` | `cps_hc_gems_billing_company_check` |

### modules/limit-payment-methods.php (2 functions)

| Old Name | New Name |
|----------|----------|
| `cps_hc_wcgems_add_payment_methods_field` | `cps_hc_gems_add_payment_methods_field` |
| `cps_hc_wcgems_save_payment_methods_field` | `cps_hc_gems_save_payment_methods_field` |

### modules/custom-addtocart-button.php (1 function)

| Old Name | New Name |
|----------|----------|
| `cps_hc_wcgems_custom_addtocart_button` | `cps_hc_gems_custom_addtocart_button` |

### modules-hu/product-price-history.php (3 functions)

| Old Name | New Name |
|----------|----------|
| `surbma_hc_update_product_price_history` | `cps_hc_gems_update_product_price_history` |
| `surbma_hc_show_termekartortenet_single` | `cps_hc_gems_show_termekartortenet_single` |
| `surbma_hc_show_termekartortenet_variation` | `cps_hc_gems_show_termekartortenet_variation` |

### modules-hu/validate-checkout-fields.php (1 function)

| Old Name | New Name |
|----------|----------|
| `cps_wcgems_hc_validate_checkout_fields` | `cps_hc_gems_validate_checkout_fields` |

## Exclusions

### CPS-SDK Functions (Unchanged)

The following functions in `cps-sdk/` will **NOT** be renamed:

- `cps_admin_scripts()` - `cps-sdk/start.php`
- `cps_admin_header()` - `cps-sdk/start.php`
- `cps_admin_footer()` - `cps-sdk/start.php`
- `cps_plugins_page()` - `cps-sdk/pages/plugins-page.php`
- `cps_add_menus()` - `cps-sdk/lib/admin.php`
- `cps_admin_enqueue_scripts()` - `cps-sdk/lib/admin.php`
- `cps_admin_custom_admin_head()` - `cps-sdk/lib/admin.php`
- `cps()` - `lib/start.php` (singleton accessor)

### JavaScript Functions (Unchanged)

Inline JavaScript functions will **NOT** be renamed:

- `drawChart()`, `copyJsonData()`, `copyCsvData()` - `modules-hu/product-price-history-display.php`
- `copyHuCommerceOptions()`, `copyWebsiteInformation()` - `pages/settings-nav-information.php`
- `showCompanyFields()`, `hideCompanyFields()`, `hideShowCompanyFields()` - `modules/checkout.php`
- `HCmaskcheckoutbillingfields()`, etc. - `modules-hu/mask-checkout-fields.php`

### Vendor Code (Unchanged)

- `cps-sdk/vendors/pand/persist-admin-notices-dismissal.php` - Third-party library

## Testing Strategy

### Pre-Implementation Verification

1. Create a full backup of the plugin
2. Document current function count with each prefix

### Post-Implementation Verification

1. **Syntax Check**: Run PHP linter on all modified files
2. **Function Count**: Verify no functions remain with old prefixes (except excluded)
3. **Reference Check**: Grep for any remaining old prefix references
4. **Functional Testing**:
   - Plugin activation/deactivation
   - Admin pages load correctly
   - Module settings save/load
   - License validation works
   - WooCommerce integration functions

### Verification Commands

```bash
# Check for remaining old prefixes (should return 0 in plugin files, excluding cps-sdk/)
grep -r "function surbma_hc_" --include="*.php" lib/ pages/ modules/ modules-hu/
grep -r "function cps_wcgems_hc_" --include="*.php" lib/ pages/ modules/ modules-hu/
grep -r "function cps_hc_wcgems_" --include="*.php" lib/ pages/ modules/ modules-hu/
grep -r "function hc_" --include="*.php" lib/modules.php

# Verify new prefix count
grep -r "function cps_hc_gems_" --include="*.php" | wc -l
```

## Risks and Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Missed function reference | Plugin crash | Comprehensive grep search for all old prefixes |
| Hook callback mismatch | Feature broken | Search for string references in add_action/add_filter |
| External code dependency | Third-party break | Document any known external references |

## Success Criteria

- [ ] All 55 functions renamed to `cps_hc_gems_*` prefix
- [ ] All function calls updated throughout codebase
- [ ] All WordPress hooks reference new function names
- [ ] No PHP errors on plugin activation
- [ ] All admin pages load without errors
- [ ] All module functionality works correctly
- [ ] No remaining old prefixes in non-excluded files

## Summary

| Metric | Count |
|--------|-------|
| Functions to Rename | 55 |
| Files to Modify | ~20 |
| Excluded Functions | 8 (SDK) |
| Excluded JS Functions | ~10 |

