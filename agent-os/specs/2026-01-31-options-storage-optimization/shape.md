# Options Storage Optimization - Shaping Notes

## Scope

Refactor the plugin's options storage to reduce database footprint by not saving values that match their defaults. This is a performance optimization targeting the `surbma_hc_fields` option which stores all plugin settings as a serialized array.

## Problem Statement

The plugin currently saves ALL option values to the database, including:
- ~200+ translation checkboxes, most of which are disabled (value: 0)
- All other settings, even when they match default values

This results in a large serialized array in the `wp_options` table.

## Decisions

- **Only store non-default values** - If a value equals its default, don't include it in the saved array
- **Translations handling** - Don't save `translations-*` keys when value is `0` (disabled is the default)
- **Centralized defaults** - Create a new `settings/settings-defaults.php` file with all defaults in one place
- **No migration** - Existing data will be cleaned up naturally when users save settings
- **Filter in validation** - Perform the filtering in `cps_hc_gems_fields_validate()` before returning

## Context

- **Visuals:** None (backend refactor)
- **References:** Current implementation in `settings/settings-validate.php` and `settings/settings-functions.php`
- **Product alignment:** Supports HuCommerce's goal of being the "indispensable foundation" - a leaner, faster plugin

## Technical Details

### Current Architecture

1. Options loaded in `lib/start.php` into global `$cps_hc_gems_options`
2. Form fields rendered in `settings/settings-functions.php` with inline defaults
3. Validation in `settings/settings-validate.php` processes all input
4. WordPress saves via `update_option('surbma_hc_fields', $input)`

### New Architecture

1. Defaults defined in `settings/settings-defaults.php`
2. Form fields use centralized defaults
3. Validation filters out default values before returning
4. Only non-default values saved to database
5. Reading code handles missing keys by falling back to defaults

## Risks & Mitigations

| Risk | Mitigation |
|------|------------|
| Missing default causes unexpected behavior | Comprehensive defaults file, thorough testing |
| Breaking existing functionality | No data migration; values already in DB continue to work |
| Default changes in updates affect users | This is desired behavior - users get new defaults unless they explicitly set a value |
