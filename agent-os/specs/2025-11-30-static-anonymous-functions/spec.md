# Specification: Static Anonymous Functions Conversion

## 1. Overview

### 1.1 Purpose
Convert all eligible anonymous functions (closures) to static anonymous functions throughout the HuCommerce plugin codebase for improved performance, reduced memory usage, and alignment with PHP best practices.

### 1.2 Background
Static anonymous functions in PHP explicitly prevent binding to `$this` and the outer scope, resulting in:
- Lower memory footprint
- Faster execution
- Clearer code intent
- Compatibility with static analysis tools (PHPStan, Psalm)

The codebase currently has 4 functions already using `static function()` pattern. This spec extends that pattern to all eligible closures.

### 1.3 Scope Summary

| Metric | Value |
|--------|-------|
| Total anonymous functions | ~195 |
| Already converted | 4 |
| Cannot be converted | 3 |
| **To be converted** | **~188** |
| Estimated files affected | ~33 |

---

## 2. Technical Specification

### 2.1 Conversion Pattern

**Before:**
```php
add_action( 'hook_name', function( $param ) {
    // function body
} );
```

**After:**
```php
add_action( 'hook_name', static function( $param ) {
    // function body
} );
```

### 2.2 Files and Directories in Scope

#### Phase 1: `lib/` Directory
| File | Est. Closures |
|------|---------------|
| `lib/admin.php` | ~10 |
| `lib/license.php` | ~8 |
| `lib/modules.php` | ~2 |
| `lib/start.php` | ~3 |
| `lib/pages.php` | ~2 (excluding 2 with `use()`) |

#### Phase 2: `modules/` Directory
| File | Est. Closures |
|------|---------------|
| `modules/catalog-mode.php` | ~3 |
| `modules/checkout.php` | ~10 (2 already done) |
| `modules/coupon.php` | ~4 |
| `modules/custom-addtocart-button.php` | TBD |
| `modules/empty-cart-button.php` | ~4 |
| `modules/free-shipping-notice.php` | ~3 |
| `modules/global-info.php` | ~14 |
| `modules/hide-shipping-methods.php` | ~2 |
| `modules/legal-checkout.php` | ~12 |
| `modules/limit-payment-methods.php` | TBD |
| `modules/login-registration-redirect.php` | ~2 |
| `modules/one-product-in-cart.php` | ~1 |
| `modules/plus-minus-buttons.php` | TBD |
| `modules/product-price-additions.php` | ~3 |
| `modules/product-settings.php` | TBD |
| `modules/redirect-cart.php` | TBD |
| `modules/return-to-shop.php` | ~2 |
| `modules/smtp.php` | ~1 |
| `modules/tax-number.php` | ~12 (2 already done) |
| `modules/translations.php` | ~4 (excluding 1 with `use()`) |
| `modules/update-cart.php` | TBD |

#### Phase 3: `modules-hu/` Directory
| File | Est. Closures |
|------|---------------|
| `modules-hu/autofill-city.php` | TBD |
| `modules-hu/hu-format-fix.php` | ~7 |
| `modules-hu/mask-checkout-fields.php` | ~2 |
| `modules-hu/no-county.php` | TBD |
| `modules-hu/product-price-history-display.php` | TBD |
| `modules-hu/product-price-history.php` | ~3 |
| `modules-hu/translations.php` | TBD |
| `modules-hu/validate-checkout-fields.php` | TBD |

#### Phase 4: `pages/` Directory
| File | Est. Closures |
|------|---------------|
| `pages/settings.php` | ~1 |
| Other page files | ~4 |

### 2.3 Exclusions (MUST NOT Convert)

The following 3 functions use the `use()` keyword to capture outer scope variables and **cannot** be converted to static:

#### 1. `lib/pages.php:97`
```php
// Inside cps_hc_gems_get_pages_by_status()
return array_filter( $pages, function( $page ) use ( $statuses ) {
    return in_array( $page['status'], $statuses, true );
});
```
**Reason:** Captures `$statuses` from outer scope.

#### 2. `lib/pages.php:143`
```php
// Inside cps_hc_gems_get_page_callback()
return function() use ( $page_key ) {
    cps_hc_gems_render_page( $page_key );
};
```
**Reason:** Captures `$page_key` from outer scope.

#### 3. `modules/translations.php:126`
```php
$active_translations = array_filter( $translations, function( $translation ) use ( $cps_hc_gems_options ) {
    return !empty( $cps_hc_gems_options[$translation['option_key']] );
} );
```
**Reason:** Captures `$cps_hc_gems_options` from outer scope.

### 2.4 Already Converted (Reference Examples)

These 4 functions are already using `static function` and serve as examples:

1. `modules/checkout.php:88` - `woocommerce_checkout_update_order_review`
2. `modules/checkout.php:98` - `default_checkout_billing_company_check`
3. `modules/tax-number.php:104` - `woocommerce_checkout_update_order_review`
4. `modules/tax-number.php:114` - `default_checkout_billing_tax_number`

---

## 3. Implementation Strategy

### 3.1 Phased Approach

Execute conversion in 4 phases to ensure quality and catch issues early:

```
Phase 1: lib/          → Test → Verify
Phase 2: modules/      → Test → Verify
Phase 3: modules-hu/   → Test → Verify
Phase 4: pages/        → Test → Verify
```

### 3.2 Conversion Process per File

For each PHP file:

1. **Identify** all `function(` patterns
2. **Skip** functions that:
   - Already use `static function`
   - Use `use()` keyword (outer scope binding)
3. **Convert** eligible functions by adding `static` keyword
4. **Verify** no syntax errors introduced

### 3.3 Search Pattern

Use this regex to find convertible functions:
```regex
(?<!static\s)function\s*\([^)]*\)\s*(?!use\s*\()
```

Or simpler approach - find all `function(` then manually skip:
- Lines containing `static function`
- Lines containing `use (`

---

## 4. Testing & Verification

### 4.1 Automated Verification

After each phase, run:

1. **PHP Syntax Check**
   ```bash
   find . -name "*.php" -exec php -l {} \;
   ```

2. **Grep Verification** - Confirm conversions applied:
   ```bash
   # Count static functions (should increase)
   grep -r "static function" --include="*.php" | wc -l
   
   # Count non-static (should decrease, minus exclusions)
   grep -r "function(" --include="*.php" | grep -v "static function" | wc -l
   ```

3. **Exclusion Verification** - Confirm excluded functions unchanged:
   ```bash
   grep -n "use ( \$statuses )" lib/pages.php
   grep -n "use ( \$page_key )" lib/pages.php
   grep -n "use ( \$cps_hc_gems_options )" modules/translations.php
   ```

### 4.2 Manual Verification

- Load WordPress admin panel - check for PHP errors
- Navigate to HuCommerce settings pages
- Test checkout flow if applicable modules were modified
- Check browser console for JavaScript errors (indirect impact)

### 4.3 Acceptance Criteria

| Criteria | Validation Method |
|----------|-------------------|
| All eligible functions converted | Grep count matches expectations |
| No PHP syntax errors | `php -l` passes on all files |
| Excluded functions unchanged | Grep confirms `use()` patterns intact |
| No functional regressions | Manual testing + no error logs |

---

## 5. Benefits Summary

### 5.1 Performance
- Reduced memory allocation per closure
- No scope binding overhead
- Earlier garbage collection eligibility

### 5.2 Code Quality
- Explicit intent: "this closure is self-contained"
- Prevents accidental `$this` binding
- Compatible with PHPStan/Psalm strict mode

### 5.3 Maintainability
- Clear visual indicator of closure scope
- Easier code review
- Future-proof for OOP refactoring

---

## 6. Risk Assessment

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Syntax errors | Low | High | PHP lint check after each file |
| Missed conversion | Low | Low | Automated grep verification |
| Wrong function converted | Very Low | High | Skip all `use()` patterns |
| Functional regression | Very Low | Medium | Manual testing per phase |

**Overall Risk Level:** LOW

The conversion is mechanical and easily reversible. Static closures are a strict subset of regular closures (they do less, not more).

---

## 7. Timeline

| Phase | Scope | Estimated Duration |
|-------|-------|-------------------|
| Phase 1 | `lib/` (5 files, ~25 functions) | 15 minutes |
| Phase 2 | `modules/` (18 files, ~90 functions) | 45 minutes |
| Phase 3 | `modules-hu/` (7 files, ~30 functions) | 20 minutes |
| Phase 4 | `pages/` (3 files, ~5 functions) | 10 minutes |
| Verification | All phases | 15 minutes |
| **Total** | | **~2 hours** |

---

## 8. Success Metrics

| Metric | Before | After |
|--------|--------|-------|
| Static closures | 4 | ~192 |
| Non-static closures | ~191 | 3 (excluded) |
| PHP errors | 0 | 0 |
| Functional regressions | N/A | 0 |

---

## 9. Appendix

### A. Why Static Closures Can't Use `use()`

Static closures explicitly opt out of scope binding. The `use()` keyword is a form of scope binding - it captures variables from the outer scope. These two features are mutually exclusive by design in PHP.

```php
// This is INVALID and will cause a parse error:
static function() use ($var) { }
```

### B. Reference: PHP Manual

From [PHP Anonymous Functions](https://www.php.net/manual/en/functions.anonymous.php):

> "As of PHP 5.4, when declared in the context of a class, the current class is automatically bound to it, making `$this` available inside of the function's scope. If this automatic binding of the current class is not wanted, then static anonymous functions may be used instead."

---

**Spec Version:** 1.0  
**Created:** 2025-11-30  
**Status:** Ready for Implementation

