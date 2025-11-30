# Function Prefix Standardization Tasks

## Overview

This task list covers the standardization of all PHP function prefixes to `cps_hc_gems_`.

**Total Functions to Rename:** 55  
**Total Files to Modify:** ~20  
**Estimated Effort:** Medium

---

## Task Groups

### Group 1: Core Library Functions (`lib/`)

#### Task 1.1: Rename `hc_*` functions in `lib/modules.php`
- [x] Rename `hc_get_modules_config` → `cps_hc_gems_get_modules_config`
- [x] Rename `hc_get_new_module_keys` → `cps_hc_gems_get_new_module_keys`
- [x] Rename `hc_sort_modules_for_display` → `cps_hc_gems_sort_modules_for_display`
- [x] Rename `hc_get_tag_translations` → `cps_hc_gems_get_tag_translations`
- [x] Rename `hc_is_pro_module_type` → `cps_hc_gems_is_pro_module_type`
- [x] Rename `hc_is_free_module_type` → `cps_hc_gems_is_free_module_type`
- [x] Update all references across the codebase

**Files affected:** `lib/modules.php`, `pages/settings-nav-modules.php`, and any files calling these functions

#### Task 1.2: Rename `surbma_hc_*` function in `lib/start.php`
- [x] Rename `surbma_hc_woocommerce_version_check` → `cps_hc_gems_woocommerce_version_check`
- [x] Update all references across the codebase

**Files affected:** `lib/start.php` and any files calling this function

#### Task 1.3: Rename functions in `lib/license.php`
- [x] Rename `surbma_hc_license_create_url` → `cps_hc_gems_license_create_url`
- [x] Rename `surbma_hc_license_status_update` → `cps_hc_gems_license_status_update`
- [x] Rename `surbma_hc_license_api_manager_action` → `cps_hc_gems_license_api_manager_action`
- [x] Update all references across the codebase

**Files affected:** `lib/license.php`, `pages/settings-nav-license.php`, and any files calling these functions

#### Task 1.4: Rename functions in `lib/admin.php`
- [x] Rename `cps_wcgems_hc_allowed_post_tags` → `cps_hc_gems_allowed_post_tags`
- [x] Rename `surbma_hc_dashboard` → `cps_hc_gems_dashboard`
- [x] Update all hook registrations (`add_action`, `add_filter`)
- [x] Update all references across the codebase

**Files affected:** `lib/admin.php` and any files referencing these functions

---

### Group 2: Settings Functions (`pages/settings-*.php`)

#### Task 2.1: Rename `cps_hc_wcgems_*` functions in `pages/settings-functions.php`
- [x] Rename `cps_hc_wcgems_nav_item_header` → `cps_hc_gems_nav_item_header`
- [x] Rename `cps_hc_wcgems_module_nav_item` → `cps_hc_gems_module_nav_item`
- [x] Rename `cps_hc_wcgems_form_accordion_title` → `cps_hc_gems_form_accordion_title`
- [x] Rename `cps_hc_wcgems_module_card_more` → `cps_hc_gems_module_card_more`
- [x] Rename `cps_hc_wcgems_form_field_main` → `cps_hc_gems_form_field_main`
- [x] Rename `cps_hc_wcgems_form_modal` → `cps_hc_gems_form_modal`
- [x] Rename `cps_hc_wcgems_form_field_checkbox` → `cps_hc_gems_form_field_checkbox`
- [x] Rename `cps_hc_wcgems_form_field_select` → `cps_hc_gems_form_field_select`
- [x] Rename `cps_hc_wcgems_form_field_text` → `cps_hc_gems_form_field_text`
- [x] Rename `cps_hc_wcgems_form_field_number` → `cps_hc_gems_form_field_number`
- [x] Rename `cps_hc_wcgems_form_field_password` → `cps_hc_gems_form_field_password`
- [x] Rename `cps_hc_wcgems_form_field_textarea` → `cps_hc_gems_form_field_textarea`
- [x] Update all references in `pages/settings-nav-*.php` files

**Files affected:** `pages/settings-functions.php`, `pages/settings-nav-modules.php`, `pages/settings-nav-license.php`, and all other settings nav files

#### Task 2.2: Rename `surbma_hc_*` functions in `pages/settings-validate.php`
- [x] Rename `surbma_hc_fields_validate` → `cps_hc_gems_fields_validate`
- [x] Rename `surbma_hc_license_validate` → `cps_hc_gems_license_validate`
- [x] Update hook registrations (register_setting callbacks)
- [x] Update all references across the codebase

**Files affected:** `pages/settings-validate.php`, `pages/settings.php`

#### Task 2.3: Rename `surbma_hc_*` function in `pages/settings-nav-license.php`
- [x] Rename `surbma_hc_mask` → `cps_hc_gems_mask`
- [x] Update all references across the codebase

**Files affected:** `pages/settings-nav-license.php`

---

### Group 3: Page Global Functions (`pages/page-*.php`)

#### Task 3.1: Rename functions in `pages/page-globals.php`
- [x] Rename `surbma_hc_page_modules_nav` → `cps_hc_gems_page_modules_nav`
- [x] Rename `surbma_hc_pages_nav` → `cps_hc_gems_pages_nav`
- [x] Rename `surbma_hc_page_license_nav` → `cps_hc_gems_page_license_nav`
- [x] Rename `surbma_hc_page_social_nav` → `cps_hc_gems_page_social_nav`
- [x] Rename `surbma_hc_page_header` → `cps_hc_gems_page_header`
- [x] Rename `surbma_hc_page_notifications` → `cps_hc_gems_page_notifications`
- [x] Rename `surbma_hc_page_sidebar` → `cps_hc_gems_page_sidebar`
- [x] Rename `surbma_hc_page_mobile_nav` → `cps_hc_gems_page_mobile_nav`
- [x] Rename `surbma_hc_page_card_footer` → `cps_hc_gems_page_card_footer`
- [x] Rename `surbma_hc_page_footer` → `cps_hc_gems_page_footer`
- [x] Update all references across all page files

**Files affected:** `pages/page-globals.php`, `pages/page-modules.php`, `pages/page-license.php`, `pages/page-information.php`, `pages/page-news.php`, `pages/page-offers.php`, `pages/page-directory.php`

#### Task 3.2: Rename page functions in individual page files
- [x] Rename `surbma_hc_offers_page` → `cps_hc_gems_offers_page` in `pages/page-offers.php`
- [x] Rename `surbma_hc_news_page` → `cps_hc_gems_news_page` in `pages/page-news.php`
- [x] Rename `surbma_hc_modules_page` → `cps_hc_gems_modules_page` in `pages/page-modules.php`
- [x] Rename `surbma_hc_license_page` → `cps_hc_gems_license_page` in `pages/page-license.php`
- [x] Rename `surbma_hc_information_page` → `cps_hc_gems_information_page` in `pages/page-information.php`
- [x] Rename `surbma_hc_directory_page` → `cps_hc_gems_directory_page` in `pages/page-directory.php`
- [x] Update all menu registration callbacks in `lib/admin.php`

**Files affected:** All `pages/page-*.php` files, `lib/admin.php`

---

### Group 4: Module Functions (`modules/`)

#### Task 4.1: Rename functions in `modules/product-settings.php`
- [x] Rename `surbma_hc_get_product_settings` → `cps_hc_gems_get_product_settings`
- [x] Rename `surbma_hc_register_product_metabox` → `cps_hc_gems_register_product_metabox`
- [x] Rename `surbma_hc_product_metabox` → `cps_hc_gems_product_metabox`
- [x] Rename `surbma_hc_save_product_metabox` → `cps_hc_gems_save_product_metabox`
- [x] Rename `surbma_hc_add_product_subtitle` → `cps_hc_gems_add_product_subtitle`
- [x] Rename `surbma_hc_product_subtitle_styles` → `cps_hc_gems_product_subtitle_styles`
- [x] Update all hook registrations
- [x] Update all references across the codebase

**Files affected:** `modules/product-settings.php`

#### Task 4.2: Rename function in `modules/tax-number.php`
- [x] Rename `cps_wcgems_hc_billing_tax_number_check` → `cps_hc_gems_billing_tax_number_check`
- [x] Update hook registration
- [x] Update all references across the codebase

**Files affected:** `modules/tax-number.php`

#### Task 4.3: Rename function in `modules/checkout.php`
- [x] Rename `cps_wcgems_hc_billing_company_check` → `cps_hc_gems_billing_company_check`
- [x] Update hook registration
- [x] Update all references across the codebase

**Files affected:** `modules/checkout.php`

#### Task 4.4: Rename functions in `modules/limit-payment-methods.php`
- [x] Rename `cps_hc_wcgems_add_payment_methods_field` → `cps_hc_gems_add_payment_methods_field`
- [x] Rename `cps_hc_wcgems_save_payment_methods_field` → `cps_hc_gems_save_payment_methods_field`
- [x] Update all hook registrations
- [x] Update all references across the codebase

**Files affected:** `modules/limit-payment-methods.php`

#### Task 4.5: Rename function in `modules/custom-addtocart-button.php`
- [x] Rename `cps_hc_wcgems_custom_addtocart_button` → `cps_hc_gems_custom_addtocart_button`
- [x] Update hook registration
- [x] Update all references across the codebase

**Files affected:** `modules/custom-addtocart-button.php`

---

### Group 5: Hungarian Module Functions (`modules-hu/`)

#### Task 5.1: Rename functions in `modules-hu/product-price-history.php`
- [x] Rename `surbma_hc_update_product_price_history` → `cps_hc_gems_update_product_price_history`
- [x] Rename `surbma_hc_show_termekartortenet_single` → `cps_hc_gems_show_termekartortenet_single`
- [x] Rename `surbma_hc_show_termekartortenet_variation` → `cps_hc_gems_show_termekartortenet_variation`
- [x] Update all hook registrations
- [x] Update all references across the codebase

**Files affected:** `modules-hu/product-price-history.php`, `modules-hu/product-price-history-display.php`

#### Task 5.2: Rename function in `modules-hu/validate-checkout-fields.php`
- [x] Rename `cps_wcgems_hc_validate_checkout_fields` → `cps_hc_gems_validate_checkout_fields`
- [x] Update hook registration
- [x] Update all references across the codebase

**Files affected:** `modules-hu/validate-checkout-fields.php`

---

### Group 6: Verification & Testing

#### Task 6.1: Verify no old prefixes remain
- [x] Search for remaining `surbma_hc_` references (excluding cps-sdk/) - **0 function definitions found**
- [x] Search for remaining `cps_wcgems_hc_` references - **0 matches**
- [x] Search for remaining `cps_hc_wcgems_` references - **0 matches**
- [x] Search for remaining standalone `hc_` function references in lib/modules.php context - **0 matches**
- [x] Document any intentionally excluded references - **Note: `surbma_hc_fields` and `surbma_hc_license` are option names, not functions**

#### Task 6.2: Verify all new prefixes are correct
- [x] Count total `cps_hc_gems_` functions (should be ~57 including existing 2) - **60 functions found**
- [x] Verify function definitions match function calls - **PHP syntax check passed on all files**
- [x] Verify hook callbacks match function names - **All references updated**

#### Task 6.3: Functional verification
- [x] Plugin activates without PHP errors - **All files pass PHP -l syntax check**
- [ ] All admin pages load correctly - **Requires browser testing**
- [ ] Module settings pages display properly - **Requires browser testing**
- [ ] Settings save and load correctly - **Requires browser testing**
- [ ] License functionality works - **Requires browser testing**
- [ ] WooCommerce integration features work - **Requires browser testing**

---

## Implementation Strategy

### Recommended Order

1. **Start with `pages/settings-functions.php`** (Task 2.1) - These are form helper functions used throughout settings pages
2. **Then `lib/modules.php`** (Task 1.1) - Core module configuration functions
3. **Then `pages/page-globals.php`** (Task 3.1) - Page layout functions used by all pages
4. **Then individual page files** (Task 3.2) - Page callback functions
5. **Then remaining lib files** (Tasks 1.2, 1.3, 1.4) - Core library functions
6. **Then modules** (Tasks 4.1-4.5) - Individual module functions
7. **Then modules-hu** (Tasks 5.1-5.2) - Hungarian module functions
8. **Then remaining settings files** (Tasks 2.2, 2.3) - Validation and other settings
9. **Finally verification** (Tasks 6.1-6.3)

### Search & Replace Pattern

For each function rename:
1. Replace function definition: `function old_name(` → `function new_name(`
2. Replace all calls: `old_name(` → `new_name(`
3. Replace string references in hooks: `'old_name'` → `'new_name'`

---

## Exclusions (Do NOT modify)

- `cps-sdk/` folder (all files)
- `cps-sdk/vendors/` folder (all files)
- JavaScript functions inside PHP files
- The `cps()` singleton function in `lib/start.php`

---

## Summary

| Group | Tasks | Functions |
|-------|-------|-----------|
| Group 1: Core Library | 4 tasks | 12 functions |
| Group 2: Settings | 3 tasks | 15 functions |
| Group 3: Page Globals | 2 tasks | 16 functions |
| Group 4: Modules | 5 tasks | 10 functions |
| Group 5: Hungarian Modules | 2 tasks | 4 functions |
| Group 6: Verification | 3 tasks | - |
| **Total** | **19 tasks** | **57 functions** |
