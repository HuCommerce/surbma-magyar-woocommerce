# References for Dynamic translation settings UI and validation

## Similar implementations

### pages/menu-modules.php (current translations block)

- **Location:** pages/menu-modules.php, ~lines 502–553
- **Relevance:** Current “Translations for premium plugins & themes” section: hardcoded checkboxes and static “Supported softwares” table. Replace with dynamic loop and scrollable lists.
- **Key pattern:** `cps_hc_gems_form_field_checkbox( $field_label, $field_option )`; section uses `<h3>`, `<h5>`, `<ul class="cps-form-fields uk-list uk-list-divider">`.

### settings/settings-validate.php (translation keys)

- **Location:** settings/settings-validate.php, lines 111–114
- **Relevance:** Four hardcoded translation option keys. Replace with a loop over `cps_hc_gems_get_translation_domains()` and `cps_hc_gems_translation_domain_to_option_key( $domain )`.
- **Key pattern:** `$input['option-key'] = isset( $input['option-key'] ) && 1 == $input['option-key'] ? 1 : 0;`

### settings/settings-functions.php (checkbox helper)

- **Location:** settings/settings-functions.php, `cps_hc_gems_form_field_checkbox`
- **Relevance:** Signature is `( $field_label, $field_option, $field_info = false, $field_new = false, $field_free = false, $field_default = 0 )`. Use domain as label and `cps_hc_gems_translation_domain_to_option_key( $domain )` as option.

### modules/translations.php (getter and helper)

- **Location:** modules/translations.php
- **Relevance:** `cps_hc_gems_get_translation_domains()` returns `['plugins' => [...], 'themes' => [...]]`. `cps_hc_gems_translation_domain_to_option_key( $domain )` returns e.g. `translations-woocommercesubscriptions`. Single source of truth for UI and validation.

### agent-os/specs/2026-01-30-1200-translations-single-level-config/

- **Location:** agent-os/specs/2026-01-30-1200-translations-single-level-config/
- **Relevance:** Spec that introduced the getter and option-key helper; references.md and shape.md already recommend looping this getter in menu-modules and settings-validate. This spec implements those next steps.
