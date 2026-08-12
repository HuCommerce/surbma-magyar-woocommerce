# Spec-lite: Withdrawal Request Module

EU 2023/2673 online withdrawal button as a HuCommerce module. Reference (logic
only, no code copy): uptools-io/elallas-for-woo. **Data model changed
2026-07-08: custom DB tables replace the CPT.**

- **Data:** 4 tables `{$wpdb->prefix}cps_hc_gems_wd_cases / _case_items /
  _events / _documents`, column-identical to the reference's `lw_elallas_*`
  schema. dbDelta install via option `cps_hc_gems_wd_db_version` on
  `admin_init`. Statuses `received → auto_confirmed | manual_review →
  accepted/rejected/awaiting_return/goods_received/refund_pending →
  closed/cancelled`. Case numbers `EL-YYYY-NNNNNN`
  (`cps_hc_gems_wd_case_counter`). Snapshot items, append-only audit log,
  documents table empty until the round-2 PDF project.
- **Compat for the round-2 importer:** hash (`elallas:hmac` HMAC label,
  lowercased/trimmed input) and AES-256-GCM encryption (`elallas:cipher` key
  label, `base64(iv12|tag16|cipher)`) derivations byte-identical to the
  reference; shared public names `[elallas_form]`, `[elallas_button]`,
  My Account endpoint `withdrawals`.
- **Flow:** stateless 3 steps (identify → select → confirm), full re-validation
  each step. Guest order#+email, logged-in picker (own orders only), tokenized
  email link (kept `token.php`), `?order=ID` preselect. Per-item/per-qty or
  whole order; 3 consent checkboxes; optional encrypted IBAN + note. Neutral
  single error message; honeypot + rate limits (10/600s IP, 20/3600s order).
- **Deadline:** marks, never blocks — `within|expired|unknown`; expired/unknown
  → `manual_review`. Start: option (`order_completed` default, or created /
  delivery meta / manual), days option (14).
- **Emails:** 3 WC_Email classes (customer confirmation w/ receipt timestamp +
  extra text; admin notification; status update) fired by
  `cps_hc_gems_withdrawal_confirmed` / `_status_changed`. Tokenized button in
  processing/completed order emails (never suppressed).
- **Admin:** WooCommerce submenu `cps-hc-gems-withdrawals`
  (`manage_woocommerce`): WP_List_Table + filters + bulk + CSV export; case
  detail (summary, decrypted bank account on demand, snapshot, audit log,
  decision + status email); order-edit panel.
- **Guard (always loaded in admin, module off included):** detect competing
  plugins (filterable list, first `elallas-for-woo/elallas-for-woo.php`) →
  notice recommending this module; if both active → module features do not run,
  warning + nonce-protected one-click `deactivate_plugins()` button.
- **Privacy:** IP/UA `full|hash|off`, email hash + optional encrypt, retention
  cron anonymization (`cps_hc_gems_withdrawal_retention`).
- Module `module-withdrawalrequest`, type `free_hu`; settings prefix
  `withdrawalrequest-` (slug default now `elallas`); WPML config for text keys;
  HPOS-safe CRUD only. Round 2 (separate projects): PDF/dompdf, block +
  Elementor, B2B/exceptions, wizard, REST, billing/carrier integrations,
  importer.
