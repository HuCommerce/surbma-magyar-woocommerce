# References for Translations single-level config

## Similar implementations

### modules/translations.php (current)

- **Location:** [modules/translations.php](modules/translations.php), lines 97–147
- **Relevance:** Current implementation with nested `$translations` array (domain => option_key, php_file, mo_file). Refactor replaces this with getter + derived paths.
- **Key pattern:** Option key = `'translations-' . str_replace( '-', '', $domain )`; paths = `CPS_HC_GEMS_DIR . '/translations/plugins/' . $domain . '/' . $domain . '-' . $locale . '.l10n.php'` (and .mo). Prefer .l10n.php then .mo.

### pages/menu-modules.php (future consumer)

- **Location:** [pages/menu-modules.php](pages/menu-modules.php)
- **Relevance:** Renders checkboxes per translation; currently hardcodes option keys and labels. Can later loop `cps_hc_gems_get_translation_domains()` and use `cps_hc_gems_translation_domain_to_option_key( $domain )` for the option key.

### settings/settings-validate.php (future consumer)

- **Location:** [settings/settings-validate.php](settings/settings-validate.php)
- **Relevance:** Validates translation option keys; currently hardcodes each key. Can later loop the same getter and validate only those option keys.
