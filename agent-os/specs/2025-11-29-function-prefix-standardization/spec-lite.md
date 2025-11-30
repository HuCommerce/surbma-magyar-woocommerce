# Function Prefix Standardization Spec

## Goal
Standardize all PHP function prefixes in the codebase to use `cps_hc_gems_` prefix.

## Current State Analysis

### Prefixes Currently in Use

| Prefix | Count | Location(s) | Action |
|--------|-------|-------------|--------|
| `cps_hc_gems_` | 2 | lib/, modules/ | ✅ Already correct |
| `cps_hc_wcgems_` | 15 | pages/settings-functions.php, modules/ | 🔄 Rename |
| `surbma_hc_` | 31 | lib/, pages/, modules/, modules-hu/ | 🔄 Rename |
| `cps_wcgems_hc_` | 3 | modules/, modules-hu/ | 🔄 Rename |
| `hc_` | 6 | lib/modules.php | 🔄 Rename |
| `cps_` | 8 | cps-sdk/ | ⏭️ Exclude (SDK) |

### Total Functions to Rename: ~55

## Scope Decisions

### ✅ In Scope
- All functions with prefixes: `surbma_hc_`, `cps_wcgems_hc_`, `cps_hc_wcgems_`, `hc_`
- All WordPress hook/filter registrations (`add_action`, `add_filter`) referencing renamed functions
- All function calls throughout the codebase

### ❌ Out of Scope
- **CPS-SDK functions** (`cps-sdk/` folder) - Plugin-agnostic shared code
- **JavaScript functions** inside PHP files - Client-side only, no conflict risk
- **Third-party vendor code** (`cps-sdk/vendors/`) - External dependencies

## Files Affected

### Functions to Rename by File

**lib/modules.php** (6 functions)
- `hc_get_modules_config` → `cps_hc_gems_get_modules_config`
- `hc_get_new_module_keys` → `cps_hc_gems_get_new_module_keys`
- `hc_sort_modules_for_display` → `cps_hc_gems_sort_modules_for_display`
- `hc_get_tag_translations` → `cps_hc_gems_get_tag_translations`
- `hc_is_pro_module_type` → `cps_hc_gems_is_pro_module_type`
- `hc_is_free_module_type` → `cps_hc_gems_is_free_module_type`

**lib/start.php** (1 function)
- `surbma_hc_woocommerce_version_check` → `cps_hc_gems_woocommerce_version_check`

**lib/license.php** (3 functions)
- `surbma_hc_license_create_url` → `cps_hc_gems_license_create_url`
- `surbma_hc_license_status_update` → `cps_hc_gems_license_status_update`
- `surbma_hc_license_api_manager_action` → `cps_hc_gems_license_api_manager_action`

**lib/admin.php** (2 functions)
- `cps_wcgems_hc_allowed_post_tags` → `cps_hc_gems_allowed_post_tags`
- `surbma_hc_dashboard` → `cps_hc_gems_dashboard`

**pages/settings-functions.php** (12 functions)
- `cps_hc_wcgems_nav_item_header` → `cps_hc_gems_nav_item_header`
- `cps_hc_wcgems_module_nav_item` → `cps_hc_gems_module_nav_item`
- `cps_hc_wcgems_form_accordion_title` → `cps_hc_gems_form_accordion_title`
- `cps_hc_wcgems_module_card_more` → `cps_hc_gems_module_card_more`
- `cps_hc_wcgems_form_field_main` → `cps_hc_gems_form_field_main`
- `cps_hc_wcgems_form_modal` → `cps_hc_gems_form_modal`
- `cps_hc_wcgems_form_field_checkbox` → `cps_hc_gems_form_field_checkbox`
- `cps_hc_wcgems_form_field_select` → `cps_hc_gems_form_field_select`
- `cps_hc_wcgems_form_field_text` → `cps_hc_gems_form_field_text`
- `cps_hc_wcgems_form_field_number` → `cps_hc_gems_form_field_number`
- `cps_hc_wcgems_form_field_password` → `cps_hc_gems_form_field_password`
- `cps_hc_wcgems_form_field_textarea` → `cps_hc_gems_form_field_textarea`

**pages/settings-validate.php** (2 functions)
- `surbma_hc_fields_validate` → `cps_hc_gems_fields_validate`
- `surbma_hc_license_validate` → `cps_hc_gems_license_validate`

**pages/settings-nav-license.php** (1 function)
- `surbma_hc_mask` → `cps_hc_gems_mask`

**pages/page-globals.php** (10 functions)
- `surbma_hc_page_modules_nav` → `cps_hc_gems_page_modules_nav`
- `surbma_hc_pages_nav` → `cps_hc_gems_pages_nav`
- `surbma_hc_page_license_nav` → `cps_hc_gems_page_license_nav`
- `surbma_hc_page_social_nav` → `cps_hc_gems_page_social_nav`
- `surbma_hc_page_header` → `cps_hc_gems_page_header`
- `surbma_hc_page_notifications` → `cps_hc_gems_page_notifications`
- `surbma_hc_page_sidebar` → `cps_hc_gems_page_sidebar`
- `surbma_hc_page_mobile_nav` → `cps_hc_gems_page_mobile_nav`
- `surbma_hc_page_card_footer` → `cps_hc_gems_page_card_footer`
- `surbma_hc_page_footer` → `cps_hc_gems_page_footer`

**pages/page-*.php** (6 functions)
- `surbma_hc_offers_page` → `cps_hc_gems_offers_page`
- `surbma_hc_news_page` → `cps_hc_gems_news_page`
- `surbma_hc_modules_page` → `cps_hc_gems_modules_page`
- `surbma_hc_license_page` → `cps_hc_gems_license_page`
- `surbma_hc_information_page` → `cps_hc_gems_information_page`
- `surbma_hc_directory_page` → `cps_hc_gems_directory_page`

**modules/product-settings.php** (6 functions)
- `surbma_hc_get_product_settings` → `cps_hc_gems_get_product_settings`
- `surbma_hc_register_product_metabox` → `cps_hc_gems_register_product_metabox`
- `surbma_hc_product_metabox` → `cps_hc_gems_product_metabox`
- `surbma_hc_save_product_metabox` → `cps_hc_gems_save_product_metabox`
- `surbma_hc_add_product_subtitle` → `cps_hc_gems_add_product_subtitle`
- `surbma_hc_product_subtitle_styles` → `cps_hc_gems_product_subtitle_styles`

**modules/tax-number.php** (1 function)
- `cps_wcgems_hc_billing_tax_number_check` → `cps_hc_gems_billing_tax_number_check`

**modules/checkout.php** (1 function)
- `cps_wcgems_hc_billing_company_check` → `cps_hc_gems_billing_company_check`

**modules/limit-payment-methods.php** (2 functions)
- `cps_hc_wcgems_add_payment_methods_field` → `cps_hc_gems_add_payment_methods_field`
- `cps_hc_wcgems_save_payment_methods_field` → `cps_hc_gems_save_payment_methods_field`

**modules/custom-addtocart-button.php** (1 function)
- `cps_hc_wcgems_custom_addtocart_button` → `cps_hc_gems_custom_addtocart_button`

**modules-hu/product-price-history.php** (3 functions)
- `surbma_hc_update_product_price_history` → `cps_hc_gems_update_product_price_history`
- `surbma_hc_show_termekartortenet_single` → `cps_hc_gems_show_termekartortenet_single`
- `surbma_hc_show_termekartortenet_variation` → `cps_hc_gems_show_termekartortenet_variation`

**modules-hu/validate-checkout-fields.php** (1 function)
- `cps_wcgems_hc_validate_checkout_fields` → `cps_hc_gems_validate_checkout_fields`

## Status
- [x] Scope defined
- [x] Requirements gathered
- [x] Spec written
