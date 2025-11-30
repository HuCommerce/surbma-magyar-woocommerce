# Spec Lite: Static Anonymous Functions Conversion

## Goal
Convert ~188 anonymous functions to static anonymous functions for better performance and code clarity.

## Key Numbers
- **Total functions:** ~195
- **Already done:** 4
- **Cannot convert:** 3 (use `use()` keyword)
- **To convert:** ~188

## Phases
1. `lib/` - 5 files, ~25 functions
2. `modules/` - 18 files, ~90 functions
3. `modules-hu/` - 7 files, ~30 functions
4. `pages/` - 3 files, ~5 functions

## DO NOT Convert (3 exclusions)
1. `lib/pages.php:97` - uses `use ( $statuses )`
2. `lib/pages.php:143` - uses `use ( $page_key )`
3. `modules/translations.php:126` - uses `use ( $cps_hc_gems_options )`

## Pattern
```php
// Before
function( $param ) { }

// After
static function( $param ) { }
```

## Verification
```bash
# PHP syntax check
find . -name "*.php" -exec php -l {} \;

# Count static (should increase)
grep -r "static function" --include="*.php" | wc -l
```

## Est. Time: ~2 hours

