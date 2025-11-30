# Global Options Access Refactor

> **Status: 📋 BACKLOGGED**  
> *Deprioritized on 2025-11-30 - "nice to have" code quality improvement with no significant performance or security gains.*

## Summary
Refactor the global `$cps_hc_gems_options` variable access pattern to use a more modern, WordPress-recommended approach for managing plugin settings.

## Current State
- Global variable `$cps_hc_gems_options` is initialized in `lib/start.php` on `init` action (priority 0)
- Uses `get_option( 'surbma_hc_fields', array() )` to load settings
- **82+ usages** across **32+ files** requiring `global $cps_hc_gems_options;` declaration

## Problem
- Requires repetitive `global $cps_hc_gems_options;` in every function
- Pollutes global namespace
- Not type-safe
- Difficult to test/mock
- Against modern PHP best practices

## Analysis (2025-11-30)

### Benefits
- ✅ Cleaner, more maintainable code (remove 82+ global declarations)
- ✅ Follows modern PHP best practices
- ✅ Better IDE autocomplete support
- ✅ Easier to test/mock

### Costs
- ❌ No meaningful performance improvement
- ❌ No security improvement
- ~2-3 hours development time
- Risk of introducing bugs during refactor

### Conclusion
Low priority - only pursue when actively developing/refactoring related code.

## Proposed Solution (for future reference)
Static helper function with caching:

```php
function cps_hc_gems_get_option( $key = null, $default = null ) {
    static $options = null;
    if ( $options === null ) {
        $options = get_option( 'surbma_hc_fields', [] );
        if ( !is_array( $options ) ) {
            $options = [];
        }
    }
    if ( $key === null ) {
        return $options;
    }
    return $options[ $key ] ?? $default;
}
```

