# Translations single-level config — Shaping notes

## Scope

Refactor translations module: two arrays (plugin_translations / theme_translations) with domain-only values; getter `cps_hc_gems_get_translation_domains()` for shared use; helper `cps_hc_gems_translation_domain_to_option_key( $domain )`. Only [modules/translations.php](modules/translations.php) is modified; menu-modules and settings-validate are not changed in this task.

## Decisions

- **Single source of truth:** Domain lists live inside the getter so other code can call it and loop the same list.
- **Option key via helper:** Rule `'translations-' . str_replace( '-', '', $domain )` in one place; filter and future consumers use the helper.
- **No menu/validation changes:** This task only prepares the API in translations.php.

## Context

- **Visuals:** None.
- **References:** [modules/translations.php](modules/translations.php) (current implementation), [pages/menu-modules.php](pages/menu-modules.php), [settings/settings-validate.php](settings/settings-validate.php).
- **Product alignment:** No specific product goal referenced.

## Standards applied

- agent-os/standards/index.yml is empty; no standards referenced for this work.
