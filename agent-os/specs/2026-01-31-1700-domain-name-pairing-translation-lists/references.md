# References for Domain–name pairing for translation lists

## Similar implementations

### pages/menu-modules.php (translations block)

- **Location:** pages/menu-modules.php, ~lines 501–558
- **Relevance:** "Translations for premium plugins & themes" section: dynamic checkboxes from `cps_hc_gems_get_translation_domains()`, search input, scrollable lists. Extend to use **name** as label and `data-domain` on list items; update filter to match name + domain.
- **Key pattern:** `cps_hc_gems_form_field_checkbox( $field_label, $field_option )`; section uses `<h3>`, `<h5>`, `<ul class="cps-form-fields uk-list uk-list-divider">`, inline script for filter.

### modules/translations.php (getter and helper)

- **Location:** modules/translations.php
- **Relevance:** `cps_hc_gems_get_translation_domains()` returns `['plugins' => [...], 'themes' => [...]]`. `cps_hc_gems_translation_domain_to_option_key( $domain )` returns e.g. `translations-woocommercesubscriptions`. Add `cps_hc_gems_get_translation_domain_names()` returning `domain => name` with fallback for missing entries.
- **Key pattern:** Single source of truth for domains; names as second getter for display only.

### settings/settings-validate.php (translation validation)

- **Location:** settings/settings-validate.php, lines 111–115
- **Relevance:** Loop over `cps_hc_gems_get_translation_domains()`; for each domain set `$input[ cps_hc_gems_translation_domain_to_option_key( $domain ) ] = 1 or 0`. No change; validation stays domain-based.
- **Key pattern:** `$input[ $option_key ] = isset( $input[ $option_key ] ) && 1 == $input[ $option_key ] ? 1 : 0;`

### settings/settings-functions.php (checkbox helper)

- **Location:** settings/settings-functions.php, `cps_hc_gems_form_field_checkbox`
- **Relevance:** Signature `( $field_label, $field_option, $field_info = false, $field_new = false, $field_free = false, $field_default = 0 )`. First parameter is the visible label; option key stays from domain. Optional: add parameter for extra `<li>` attributes (e.g. `data-domain`) for translation items.
- **Key pattern:** Outputs `<li class="cps-form-checkbox ...">` with label and checkbox; filter script uses label text and (after change) `data-domain`.

### agent-os/specs/2026-01-31-1430-dynamic-translation-settings-ui-validation/

- **Location:** agent-os/specs/2026-01-31-1430-dynamic-translation-settings-ui-validation/
- **Relevance:** Spec that implemented dynamic checkboxes and validation from the domains array. Recommended next steps (already done): use domains array to render checkboxes, use domains array for validation. This spec adds the **names** array for display and uses it in the lists; validation remains driven only by the domains array.
