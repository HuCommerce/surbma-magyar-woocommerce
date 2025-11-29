# Specification: Dynamic Module Cards

## 1. Overview

### 1.1 Purpose
Centralize all module configuration data in `lib/modules.php` to enable dynamic generation of module cards in the admin area, eliminating the need for hard-coded HTML in `pages/settings-nav-modules.php`.

### 1.2 Problem Statement
Currently, adding a new module requires editing two separate files:
1. `lib/modules.php` - Module loading configuration
2. `pages/settings-nav-modules.php` - Hard-coded HTML card (lines 204-623)

This creates maintenance overhead and potential inconsistencies between the module configuration and its UI representation.

### 1.3 Solution
Extend the modules array in `lib/modules.php` with all UI-related properties, then replace the hard-coded card grid with a PHP loop that dynamically generates cards from this single source of truth.

### 1.4 Scope
- **In scope**: Module card grid (lines 204-623 in `settings-nav-modules.php`)
- **Out of scope**: Module settings pages (lines 627-1102) - planned for future project

---

## 2. Technical Specification

### 2.1 Files to Modify

| File | Changes |
|------|---------|
| `lib/modules.php` | Add UI properties to each module in the `$modules` array |
| `pages/settings-nav-modules.php` | Replace hard-coded cards (lines 204-623) with dynamic loop |

### 2.2 Module Array Structure

#### Current Structure (lib/modules.php)
```php
'mask-checkout-fields' => [
    'option_key' => 'maskcheckoutfields',
    'type' => 'legacy_hu',
    'directory' => 'modules-hu',
    'file' => 'mask-checkout-fields.php', // optional
    'frontend_only' => true,              // optional
    'force_enable' => true,               // optional
],
```

#### New Structure (with UI properties)
```php
'mask-checkout-fields' => [
    // Existing properties (unchanged)
    'option_key' => 'maskcheckoutfields',
    'type' => 'legacy_hu',
    'directory' => 'modules-hu',
    
    // New UI properties
    'title' => __( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ),
    'description' => __( 'Masking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ),
    'tags' => ['checkout', 'conversion'],
    'doc_slug' => 'mezok-formatumanak-ellenorzese-maszkolas',
    'version_added' => '2.0.0', // optional
],
```

### 2.3 New Properties Reference

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `title` | string | Yes | Translated module title (use `__()` function) |
| `description` | string | Yes | Translated module description (use `__()` function) |
| `tags` | array | Yes | Category tags for filtering |
| `doc_slug` | string | No | Documentation URL slug (omit to hide link) |
| `version_added` | string | No | Plugin version when module was added |

### 2.4 Available Tags
```php
['product', 'cart', 'checkout', 'payments', 'legal', 'conversion', 'other']
```

---

## 3. License/Type Mapping

### 3.1 Data-License Attribute Mapping
The `data-license` HTML attribute for filtering should be derived from the module `type`:

| Module Type | data-license | Label Color |
|-------------|--------------|-------------|
| `pro` | "pro" | `uk-label-danger` (red) |
| `pro_hu` | "pro" | `uk-label-danger` (red) |
| `legacy` | "pro" | `uk-label-danger` (red) |
| `legacy_hu` | "pro" | `uk-label-danger` (red) |
| `free` | "free" | `uk-label-success` (green) |
| `free_hu` | "free" | `uk-label-success` (green) |

### 3.2 Free Module Detection
For the `cps_hc_wcgems_form_field_main()` function's third parameter:
- Pass `true` for types: `free`, `free_hu`
- Pass `false` (or omit) for types: `pro`, `pro_hu`, `legacy`, `legacy_hu`

---

## 4. "New" Badge Logic

### 4.1 Implementation
The "New" badge should be automatically determined based on the `version_added` property:

1. Collect all `version_added` values from modules that have this property
2. Find the highest version using `version_compare()`
3. All modules with that highest version receive the "New" badge
4. Modules without `version_added` never receive the badge

### 4.2 Helper Function
```php
/**
 * Determine which modules should show the "New" badge
 * 
 * @param array $modules The modules array
 * @return array Array of module keys that should show "New" badge
 */
function hc_get_new_module_keys( $modules ) {
    $versions = [];
    
    // Collect all version_added values
    foreach ( $modules as $key => $module ) {
        if ( isset( $module['version_added'] ) && ! empty( $module['version_added'] ) ) {
            $versions[ $key ] = $module['version_added'];
        }
    }
    
    if ( empty( $versions ) ) {
        return [];
    }
    
    // Find the highest version
    $highest_version = '0.0.0';
    foreach ( $versions as $version ) {
        if ( version_compare( $version, $highest_version, '>' ) ) {
            $highest_version = $version;
        }
    }
    
    // Return keys of modules with the highest version
    $new_modules = [];
    foreach ( $versions as $key => $version ) {
        if ( version_compare( $version, $highest_version, '==' ) ) {
            $new_modules[] = $key;
        }
    }
    
    return $new_modules;
}
```

---

## 5. Display Order

### 5.1 Sorting Logic
Modules should be displayed in this order:
1. **PRO modules first** (types: `pro`, `pro_hu`, `legacy`, `legacy_hu`)
2. **Free modules second** (types: `free`, `free_hu`)

Within each group, maintain the original array order (no additional sorting needed).

### 5.2 Implementation
```php
/**
 * Sort modules for display: PRO first, then Free
 * 
 * @param array $modules The modules array
 * @return array Sorted modules array
 */
function hc_sort_modules_for_display( $modules ) {
    $pro_types = ['pro', 'pro_hu', 'legacy', 'legacy_hu'];
    
    $pro_modules = [];
    $free_modules = [];
    
    foreach ( $modules as $key => $module ) {
        if ( in_array( $module['type'], $pro_types, true ) ) {
            $pro_modules[ $key ] = $module;
        } else {
            $free_modules[ $key ] = $module;
        }
    }
    
    return array_merge( $pro_modules, $free_modules );
}
```

---

## 6. Card HTML Template

### 6.1 Current Card Structure
```html
<li data-license="pro" data-age="new" data-tags="checkout conversion">
    <div class="cps-card uk-card uk-card-default uk-card-small uk-card-hover">
        <div class="uk-card-body">
            <!-- "New" badge (if applicable) -->
            <span class="uk-label uk-label-default">New</span>
            <!-- License badge -->
            <span class="uk-label uk-label-danger">Pro</span>
            <!-- Tag badges -->
            <span class="uk-label uk-label-warning">Checkout</span>
            <span class="uk-label uk-label-warning">Conversion</span>
            <!-- Title -->
            <h5 class="uk-text-bold uk-margin-top uk-margin-remove-bottom">Module Title</h5>
            <!-- Description -->
            <p class="uk-margin-small-top uk-margin-remove-bottom">Module description text.</p>
            <!-- Documentation link (if doc_slug exists) -->
            <p class="uk-margin-small-top uk-margin-remove-bottom">
                <?php cps_hc_wcgems_module_card_more( 'doc-slug' ); ?>
            </p>
        </div>
        <div class="uk-card-footer uk-background-muted">
            <?php cps_hc_wcgems_form_field_main( 'Activate module', 'option_key', true ); ?>
        </div>
    </div>
</li>
```

### 6.2 Dynamic Card Generation
```php
<?php
// Get sorted modules and determine "new" badges
$sorted_modules = hc_sort_modules_for_display( $modules );
$new_module_keys = hc_get_new_module_keys( $modules );
$pro_types = ['pro', 'pro_hu', 'legacy', 'legacy_hu'];

foreach ( $sorted_modules as $module_key => $module ) :
    // Skip modules without required UI properties
    if ( ! isset( $module['title'] ) || ! isset( $module['description'] ) || ! isset( $module['tags'] ) ) {
        continue;
    }
    
    // Determine license type for data attribute
    $data_license = in_array( $module['type'], $pro_types, true ) ? 'pro' : 'free';
    
    // Determine if this is a "new" module
    $is_new = in_array( $module_key, $new_module_keys, true );
    
    // Build data-tags attribute
    $data_tags = implode( ' ', $module['tags'] );
    
    // Determine if this is a free module (for form field)
    $is_free = in_array( $module['type'], ['free', 'free_hu'], true );
?>
<li data-license="<?php echo esc_attr( $data_license ); ?>"<?php echo $is_new ? ' data-age="new"' : ''; ?> data-tags="<?php echo esc_attr( $data_tags ); ?>">
    <div class="cps-card uk-card uk-card-default uk-card-small uk-card-hover">
        <div class="uk-card-body">
            <?php if ( $is_new ) : ?>
                <span class="uk-label uk-label-default"><?php esc_html_e( 'New', 'surbma-magyar-woocommerce' ); ?></span>
            <?php endif; ?>
            
            <?php if ( $data_license === 'pro' ) : ?>
                <span class="uk-label uk-label-danger">Pro</span>
            <?php else : ?>
                <span class="uk-label uk-label-success"><?php esc_html_e( 'Free', 'surbma-magyar-woocommerce' ); ?></span>
            <?php endif; ?>
            
            <?php foreach ( $module['tags'] as $tag ) : ?>
                <span class="uk-label uk-label-warning"><?php echo esc_html( ucfirst( $tag ) ); ?></span>
            <?php endforeach; ?>
            
            <h5 class="uk-text-bold uk-margin-top uk-margin-remove-bottom"><?php echo esc_html( $module['title'] ); ?></h5>
            <p class="uk-margin-small-top uk-margin-remove-bottom"><?php echo esc_html( $module['description'] ); ?></p>
            
            <?php if ( ! empty( $module['doc_slug'] ) ) : ?>
                <p class="uk-margin-small-top uk-margin-remove-bottom"><?php cps_hc_wcgems_module_card_more( $module['doc_slug'] ); ?></p>
            <?php endif; ?>
        </div>
        <div class="uk-card-footer uk-background-muted">
            <?php cps_hc_wcgems_form_field_main( __( 'Activate module', 'surbma-magyar-woocommerce' ), $module['option_key'], $is_free ); ?>
        </div>
    </div>
</li>
<?php endforeach; ?>
```

---

## 7. Complete Module Data Reference

Below is the complete mapping of all 28 modules with their UI properties extracted from the current hard-coded cards:

### 7.1 PRO/Legacy Modules (displayed first)

| Module Key | Title | Tags | Doc Slug |
|------------|-------|------|----------|
| `mask-checkout-fields` | Check field formats (Masking) | checkout, conversion | mezok-formatumanak-ellenorzese-maszkolas |
| `validate-checkout-fields` | Check field values | checkout, conversion | mezok-ertekenek-ellenorzese |
| `free-shipping-notice` | Free shipping notification | cart, conversion | ingyenes-szallitas-ertesites |
| `empty-cart-button` | Empty Cart button | cart, checkout | kosar-uritese-gomb |
| `product-price-history` | Product price history | product, conversion, legal | termek-ar-tortenet |
| `product-price-additions` | Product price additions | product, conversion, legal | termek-ar-kiegeszitesek |
| `legal-checkout` | Legal compliance (GDPR, CCPA, ePrivacy) | checkout, conversion, legal | jogi-megfeleles |
| `limit-payment-methods` | Limit Payment Methods | checkout, payments | fizetesi-modok-korlatozasa |
| `global-info` | Global Information | other | globalis-adatok |
| `translations` | Translations for premium plugins & themes | other | forditasok |

### 7.2 Free Modules (displayed second)

| Module Key | Title | Tags | Doc Slug |
|------------|-------|------|----------|
| `hu-format-fix` | Fixes for Hungarian language | other | magyar-formatum-javitasok |
| `tax-number` | Tax number field | checkout, legal | adoszam-megjelenitese |
| `translations-hu` | Hungarian translation fixes | other | forditasi-hianyossagok-javitasa |
| `no-county` | Hide County field if Country is Hungary | checkout, conversion | megye-mezo-elrejtese-magyar-cim-eseten |
| `autofill-city` | Autofill City after Postcode is given | checkout, conversion | varos-automatikus-kitoltese-az-iranyitoszam-alapjan |
| `product-settings` | Product customizations | product, conversion | termek-modositasok |
| `checkout` | Checkout page customizations | checkout, conversion | penztar-oldal-modositasok |
| `plus-minus-buttons` | Plus/minus quantity buttons | product, cart | plusz-minusz-mennyisegi-gombok |
| `update-cart` | Automatic Cart update | cart | kosar-automatikus-frissitese-darabszam-modositas-utan |
| `return-to-shop` | Continue shopping buttons | cart, checkout | vasarlas-folytatasa-gombok |
| `login-registration-redirect` | Login and registration redirection | other | belepes-es-regisztracio-utani-atiranyitas |
| `coupon` | Coupon field customizations | checkout | kupon-mezo-modositasok |
| `redirect-cart` | Redirect Cart page to Checkout page | cart, checkout, conversion | kosar-atiranyitasa-a-penztar-oldalra |
| `one-product-in-cart` | One product per purchase | product, checkout | egy-termek-vasarlasonkent |
| `custom-addtocart-button` | Custom Add To Cart Button | product, conversion | egyedi-kosarba-teszem-gombok |
| `hide-shipping-methods` | Hide shipping methods | cart, checkout, conversion | szallitasi-modok-elrejtese |
| `smtp` | SMTP service | other | smtp-szolgaltatas |
| `catalog-mode` | Catalog mode | product, other | katalogus-mod |

---

## 8. Tag Translations

Tags need to be translated for display. The translation strings already exist in the codebase:

```php
$tag_translations = [
    'product' => __( 'Product', 'surbma-magyar-woocommerce' ),
    'cart' => __( 'Cart', 'surbma-magyar-woocommerce' ),
    'checkout' => __( 'Checkout', 'surbma-magyar-woocommerce' ),
    'payments' => __( 'Payments', 'surbma-magyar-woocommerce' ),
    'legal' => __( 'Legal', 'surbma-magyar-woocommerce' ),
    'conversion' => __( 'Conversion', 'surbma-magyar-woocommerce' ),
    'other' => __( 'Other', 'surbma-magyar-woocommerce' ),
];
```

---

## 9. Backward Compatibility

### 9.1 Considerations
- The modules array must maintain all existing properties for module loading functionality
- New UI properties are additive; they don't affect module loading logic
- Helper functions should be placed in a separate file or clearly separated from loading logic

### 9.2 Graceful Degradation
If a module is missing required UI properties (`title`, `description`, `tags`), it should be skipped in the card rendering loop but still function for module loading.

---

## 10. Testing Checklist

- [ ] All 28 module cards render correctly
- [ ] PRO modules appear before Free modules
- [ ] License badges show correct colors (red for Pro, green for Free)
- [ ] "New" badges appear only on modules with highest `version_added`
- [ ] Tag filtering works correctly (existing filter UI)
- [ ] Documentation links appear only when `doc_slug` is provided
- [ ] Module activation toggles work correctly
- [ ] Translations display properly in Hungarian locale
- [ ] Module loading functionality unchanged (all modules still load correctly)

---

## 11. Future Considerations

### 11.1 Planned for Next Phase
- Dynamic settings pages (lines 627-1102 in `settings-nav-modules.php`)
- Module dependency declarations
- Module ordering/priority system

### 11.2 Potential Enhancements
- Module search functionality
- Module usage statistics
- Module compatibility warnings

