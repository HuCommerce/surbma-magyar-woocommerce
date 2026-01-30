# Plan: Translations single-level config

## Overview

Refactor [modules/translations.php](modules/translations.php) to use two simple arrays (plugins and themes) exposed via a getter, so the same config can be used by other code (e.g. menu-modules.php, settings-validate.php). Option keys and file paths are derived dynamically. Scope: modules/translations.php only.

## Implementation

### 1. Getter: `cps_hc_gems_get_translation_domains()`

Returns an array with keys `'plugins'` and `'themes'`, each value an array of domain strings. Single source of truth; callable from anywhere.

- **plugins:** `'restrict-content-pro'`, `'woocommerce-api-manager'`, `'woocommerce-memberships'`, `'woocommerce-subscriptions'`
- **themes:** empty array (optional comment e.g. `// e.g. 'storefront'`)

### 2. Helper: `cps_hc_gems_translation_domain_to_option_key( $domain )`

Returns `'translations-' . str_replace( '-', '', $domain )`. Keeps the option-key rule in one place for filter and future consumers.

### 3. Refactored `load_translation_file` callback

- Get domains via getter; use `$plugin_translations` and `$theme_translations`.
- Early return when no translation is active: loop both arrays, derive option key per domain, check `$cps_hc_gems_options[ $option_key ]`; if none active, return `$file`.
- Resolve type for requested domain: `in_array( $domain, $plugin_translations )` → `'plugins'`, else `in_array( $domain, $theme_translations )` → `'themes'`, else return `$file`.
- Check option for this domain; if empty, return `$file`.
- Derive paths: `$base = CPS_HC_GEMS_DIR . '/translations/' . $folder . '/' . $domain;` then `$php_file` and `$mo_file` from `$base`, `$domain`, `$locale`.
- Return custom file if present: prefer `.l10n.php`, then `.mo`, else return `$file`.

### 4. Placement

Define getter and helper **above** the `add_filter( 'load_translation_file', ... )` call. Callback remains `static function( $file, $domain, $locale )` with `global $cps_hc_gems_options;`. Priority and arg count: `10, 3`.

## Result

- Single source of truth for domain lists; add a domain in one place.
- Reusable option key via helper.
- No changes to pages/menu-modules.php or settings/settings-validate.php in this task; they can later use the getter and helper.
