# Static Anonymous Functions Conversion

## Overview

Convert all anonymous functions (closures) to static anonymous functions throughout the codebase for improved performance and reduced memory usage.

## Initial Research Findings

### Current State Analysis

| Metric | Count |
|--------|-------|
| Total anonymous functions | ~195 |
| Already using `static` | 4 |
| Using `use()` (cannot be fully static) | 3 |
| Eligible for conversion | ~188 |

### Functions That CANNOT Be Converted to Static

The following functions use the `use()` keyword to reference outer scope variables. These **cannot** become `static function` because static closures cannot access variables from the outer scope:

1. **`lib/pages.php:97`** - `cps_hc_gems_get_pages_by_status()`
   ```php
   return array_filter( $pages, function( $page ) use ( $statuses ) {
       return in_array( $page['status'], $statuses, true );
   });
   ```

2. **`lib/pages.php:143`** - `cps_hc_gems_get_page_callback()`
   ```php
   return function() use ( $page_key ) {
       cps_hc_gems_render_page( $page_key );
   };
   ```

3. **`modules/translations.php:126`** - Inside translation loading filter
   ```php
   $active_translations = array_filter( $translations, function( $translation ) use ( $cps_hc_gems_options ) {
       return !empty( $cps_hc_gems_options[$translation['option_key']] );
   } );
   ```

### Functions Already Using Static

4 functions are already converted (good examples to follow):

- `modules/checkout.php:88` - `woocommerce_checkout_update_order_review` action
- `modules/checkout.php:98` - `default_checkout_billing_company_check` filter
- `modules/tax-number.php:104` - `woocommerce_checkout_update_order_review` action  
- `modules/tax-number.php:114` - `default_checkout_billing_tax_number` filter

### No `$this` References Found

✅ Good news: No anonymous functions in the codebase use `$this`, which means all non-`use()` functions can safely be converted.

## Benefits of Static Anonymous Functions

### 1. **Memory Efficiency**
- Non-static closures capture `$this` by reference (in class context)
- Static closures explicitly prevent binding, reducing memory footprint
- Garbage collection happens earlier since there's no reference to outer object

### 2. **Performance Improvement**
- No overhead of creating scope bindings
- PHP doesn't need to track outer scope variables
- Slightly faster execution due to simpler closure structure

### 3. **Code Clarity & Intent**
- `static function` clearly signals "this closure is self-contained"
- Prevents accidental access to `$this` in class methods
- Makes code review easier - reviewer knows no hidden dependencies

### 4. **Future-Proofing**
- If code is later moved into a class, static prevents unintended `$this` binding
- Reduces debugging time - no mysterious `$this` references

### 5. **Best Practice Alignment**
- Recommended by PHP static analysis tools (PHPStan, Psalm)
- Modern PHP coding standards encourage explicit static when no binding needed

## Scope Estimate

### Files Requiring Changes

| Directory | Files | Est. Functions |
|-----------|-------|----------------|
| `modules/` | 18+ files | ~90 |
| `modules-hu/` | 7+ files | ~30 |
| `lib/` | 5 files | ~25 |
| `pages/` | 3+ files | ~5 |
| **Total** | ~33 files | ~150+ |

## Risk Assessment

- **Low Risk**: Simple find-and-replace with validation
- **Testing Required**: Ensure no regressions after conversion
- **Reversible**: Easy to revert if issues arise

## Date Initialized

2025-11-30

