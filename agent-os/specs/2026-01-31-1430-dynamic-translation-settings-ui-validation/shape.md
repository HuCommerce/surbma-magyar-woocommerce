# Dynamic translation settings UI and validation — Shaping Notes

## Scope

- Use `cps_hc_gems_get_translation_domains()` as the single source of truth to drive:
  1. **Settings UI:** Render checkboxes for all translation domains (394 plugins + 9 themes) in the modules page, grouped by Plugins / Themes, in a scrollable list with search/filter.
  2. **Validation:** Persist all translation option keys by looping over the same getter in settings-validate.php (no hardcoded keys).

## Decisions

- **Single source of truth:** The getter in modules/translations.php; no duplicate lists in menu-modules or settings-validate.
- **Checkbox label:** Use the domain slug (e.g. `woocommerce-subscriptions`) as the visible label; no custom “friendly” names or table links in this iteration.
- **Validation:** One loop over merged plugins + themes; each option key from `cps_hc_gems_translation_domain_to_option_key( $domain )` is set to 1 or 0 based on request.
- **Search/filter:** One search input for the whole translation block; client-side, case-insensitive match on domain string; minimal vanilla JS or UIkit, no new dependencies.

## Context

- **Visuals:** None provided.
- **References:** menu-modules.php (current hardcoded translations block), settings-validate.php (four hardcoded translation keys), settings-functions.php (`cps_hc_gems_form_field_checkbox`), modules/translations.php (getter + option-key helper), agent-os/specs/2026-01-30-1200-translations-single-level-config/ (recommended next steps for UI/validation).
- **Product alignment:** User-centric (easy to find and toggle any translation); easy-to-use (search + grouped list).

## Standards Applied

- No project standards files in agent-os/standards/ apply; follow existing patterns in menu-modules.php and settings-validate.php.
