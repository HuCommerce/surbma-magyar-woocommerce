# Tasks: Withdrawal Request Module

Dependency-ordered build (A → B → C → D). Each task is independently testable
and sized for execution by a smaller model following spec.md. Linear mapping:
issue IDs in brackets. The 6 tasks with no existing Linear issue at plan-writing
time (Linear MCP was unavailable then) got new issues created 2026-07-08:
A4 → DEV-241, A5 → DEV-242, B4 → DEV-243, D1 → DEV-244, D2 → DEV-245,
D3 → DEV-246.

Read `spec.md` §-references before starting a task. Conventions: function
prefix `cps_hc_gems_withdrawal_`, text domain `surbma-magyar-woocommerce`,
WPCS (`phpcs.xml`), all order access via WC CRUD (HPOS), every `$wpdb` query
prepared, every output escaped.

## A. Foundation

### A1 — Cleanup of the CPT iteration [DEV-234]
Files: `modules/withdrawal-request.php`, `lib/withdrawal/cpt.php` (delete),
`lib/withdrawal/admin-order.php` (delete; superseded by C3),
`lib/withdrawal/frontend.php` (strip data-layer calls only if needed to keep
the plugin loadable — full rewrite comes in B2).
- [x] Delete `lib/withdrawal/cpt.php`; remove its `require_once` and the whole
      `CPS_HC_GEMS_WITHDRAWAL_DEBUG_LEVEL` scaffold from the module bootstrap.
- [x] Delete `lib/withdrawal/admin-order.php` and its `require_once`.
- [x] Bootstrap keeps: constants, slug/button-label helpers,
      `require_once` of `token.php`, `order-lookup.php`, `email-link.php`,
      email class, `frontend.php` (`!is_admin()`).
- Acceptance: plugin activates with the module on; no fatals; no references to
  `cps_hc_gems_withdraw`, `wd_pending|wd_accepted|wd_rejected|wd_refunded`,
  `register_post_type`/`register_post_status` remain under `lib/withdrawal/`.

### A2 — Schema + install [DEV-234]
File: `lib/withdrawal/schema.php` (new), bootstrap hook-in.
- [ ] Table-name helpers `cps_hc_gems_withdrawal_cases_table()`,
      `..._case_items_table()`, `..._events_table()`, `..._documents_table()`
      returning `$wpdb->prefix . 'cps_hc_gems_wd_*'`.
- [ ] `cps_hc_gems_withdrawal_install_schema()`: `dbDelta()` with the exact
      CREATE TABLE definitions from spec §3.2 (columns/defaults/indexes
      verbatim); sets option `cps_hc_gems_wd_db_version` to the code constant
      (`1.0.0`).
- [ ] `admin_init` check: run install when the option is missing/older.
- Acceptance: enabling the module and loading any admin page creates the 4
  tables (verify with `SHOW CREATE TABLE`); re-loading does not alter them;
  works on a fresh multisite sub-site.

### A3 — Data layer (CRUD + audit) [DEV-234]
File: `lib/withdrawal/data.php` (new).
- [ ] `cps_hc_gems_withdrawal_create_case( $data )` → inserts case row
      (status `received`, `created_at`/`updated_at` GMT) + item rows + a
      `case_created` event; updates order meta (`_cps_hc_gems_wd_has_case`,
      `_cps_hc_gems_wd_case_ids`, `_cps_hc_gems_wd_deadline_status`); returns
      case id.
- [ ] `..._confirm_case( $case_id )` → sets `confirmed_at`, status per spec
      §3.3 (`auto_confirmed`/`manual_review` by deadline), `case_confirmed`
      event, fires `cps_hc_gems_withdrawal_confirmed`.
- [ ] `..._get_case( $id )`, `..._get_case_items( $case_id )`,
      `..._get_cases_by_order( $order_id, $statuses = null )`,
      `..._get_cases_by_customer( $user_id )`,
      `..._query_cases( $args )` (status/search/paging for the admin list),
      `..._set_case_status( $case_id, $status, $message, $actor_id )`
      (validates enum, writes `status_changed` event, fires
      `cps_hc_gems_withdrawal_status_changed`),
      `..._add_event()`, `..._get_events( $case_id )` (append-only: no update
      or delete function may exist for events).
- [ ] `..._get_statuses()` / `..._get_status_label()` with the 10 localized
      status labels; `..._is_terminal_status()`.
- Acceptance: a short WP-CLI `eval-file` smoke script (create → confirm →
  status change → query) leaves consistent rows in all touched tables and an
  event trail of `case_created, case_confirmed, status_changed`.

### A4 — Security primitives [DEV-241]
File: `lib/withdrawal/security.php` (new). Follow spec §3.9 / §4 exactly —
derivations must be byte-identical to the reference for importer compat.
- [ ] `cps_hc_gems_withdrawal_encrypt()` / `..._decrypt()`: AES-256-GCM, key
      `hash_hmac('sha256','elallas:cipher',wp_salt('auth'),true)`, payload
      `base64( iv(12) . tag(16) . ciphertext )`; graceful `''` on failure.
- [ ] `..._hash( $value )`: `hash_hmac('sha256', strtolower(trim($value)),
      hash_hmac('sha256','elallas:hmac',wp_salt('auth')))`.
- [ ] Rate limiter (transients): per-IP 10/600 s, per-order 20/3600 s;
      `..._rate_limit_check()` + `..._rate_limit_hit()`.
- [ ] Honeypot: rotating field name + signed timestamp per spec §4;
      `..._honeypot_fields()` (render) + `..._honeypot_passed()` (validate).
- Acceptance: encrypt→decrypt round-trip; `..._hash('  Foo@Bar.com ')` equals
  hash of `foo@bar.com`; 11th identify attempt from one IP inside 10 min is
  rejected; sub-2-second form submit is rejected.

### A5 — Deadline + case numbers [DEV-242]
File: `lib/withdrawal/deadline.php` (new).
- [ ] `cps_hc_gems_withdrawal_deadline_start( $order )` per option
      `withdrawalrequest-deadlinestart` (spec §3.4 fallback chain, delivery
      meta `_cps_hc_gems_wd_delivery_date` + filter
      `cps_hc_gems_withdrawal_delivery_date`).
- [ ] `..._deadline_status( $order )` → `within|expired|unknown` using
      `withdrawalrequest-deadlinedays` (filter
      `cps_hc_gems_withdrawal_deadline_days`). Pure marking — no caller may
      block on it.
- [ ] `..._next_case_number()`: `EL-%04d-%06d`, per-year counter in option
      `cps_hc_gems_wd_case_counter` (read-increment-write via
      `get_option`/`update_option`, race acceptable at this volume;
      `# ponytail:` note the UNIQUE key on case_number is the backstop).
- Acceptance: unit-style checks for the 4 start modes incl. missing dates →
  `unknown`; two consecutive numbers in the same year increment; year rollover
  starts at `000001`.

## B. Frontend

### B1 — Order lookup rework [DEV-235]
File: `lib/withdrawal/order-lookup.php` (rework).
- [ ] Eligible statuses from option `withdrawalrequest-eligiblestatuses`
      (comma-separated, default `processing,completed`), filter kept.
- [ ] Remove every window-based rejection: eligibility = status only; the
      deadline is attached as a flag (A5), never a gate.
- [ ] Guest lookup + logged-in resolution use `security.php` rate limiter and
      return `null` on any mismatch (callers render the single neutral view).
- [ ] Logged-in picker: own orders (customer_id + billing email), max 20,
      newest first; ownership check that a logged-in user can never resolve
      another customer's order even with correct order#+email.
- Acceptance: guest with wrong email, wrong order#, and rate-limited IP all
  produce the identical neutral outcome; logged-in user resolving a foreign
  order id fails; `?order=ID` preselects own order.

### B2 — Stateless 3-step flow + templates [DEV-235]
Files: `lib/withdrawal/frontend.php` (rewrite), `templates/withdrawal/
identify.php, select.php, confirm.php, success.php, denied.php` (rework/new;
delete `form-step-1.php`, `form-step-2.php`, `confirmation.php`),
`assets/css/withdrawal.css` (extend).
- [ ] Flow per spec §3.5: hidden-field state, full re-validation per step,
      nonce `cps_hc_gems_withdrawal`, honeypot fields on every form.
- [ ] Step 1: per-item quantity inputs limited to remaining withdrawable qty
      (ordered − qty in non-terminal cases; duplicate guard) + whole-order
      shortcut; expired/unknown deadline renders a visible warning banner but
      never blocks.
- [ ] Step 2: selection summary, legal declaration (option w/ built-in
      default), 3 required consents, optional bank account + note.
- [ ] Confirm handler: snapshot rows via order item data (name, SKU, qty,
      `get_total()`, `get_total_tax()`), `SubmissionContext`-equivalent array
      (email hash/encrypt, IP/UA per privacy options, source_url, language,
      identify method in event metadata) → `A3 create + confirm`; render
      `success.php` with case number + receipt timestamp.
- [ ] Template loading stays the internal loader; keep templates escaping all
      output.
- Acceptance: full guest flow creates a correct case (rows + snapshot +
  events + order meta); partial selection yields `withdrawal_type = partial`;
  re-submitting the same items is prevented by the duplicate guard; missing
  consent re-renders step 2 with an error; expired order still submits and
  lands in `manual_review`.

### B3 — Page, shortcodes, token-link entry [DEV-236]
Files: `lib/withdrawal/frontend.php`, `lib/withdrawal/token.php` (kept, minor).
- [ ] Shortcodes `[elallas_form]` (renders the flow anywhere) and
      `[elallas_button label=""]` (links to page from
      `withdrawalrequest-pageid`, label fallback chain: att → option →
      default; page id through `wpml_object_id`).
- [ ] Keep the rewrite-endpoint entry (`withdrawalrequest-slug`, default now
      `elallas`) AND make the page+shortcode the primary documented entry.
- [ ] Token link (`?order=&key=`) resolves the order and skips identify;
      remove the "window closed → refuse" branch (flag instead).
- [ ] `?order=ID` (no key): preselect in picker (logged-in) / prefill order
      number (guest); never auto-resolves without email or token.
- Acceptance: a page containing `[elallas_form]` runs the full flow; the
  button shortcode renders the labelled link; an expired-window token link
  still opens the form with the warning banner.

### B4 — My Account endpoint + order button [DEV-243]
File: `lib/withdrawal/my-account.php` (new),
`templates/withdrawal/my-account.php` (new).
- [ ] Endpoint `withdrawals` (`add_rewrite_endpoint`, WC query var, menu item
      after Orders, label "Elállás"), gated by option
      `withdrawalrequest-displayaccount`; flush handling via the existing
      module flush-flag mechanism.
- [ ] Endpoint content: the customer's cases (case number, order, status
      label, submitted date) via `..._get_cases_by_customer()`; empty-state
      text; (PDF download column arrives with the round-2 PDF project).
- [ ] Order-view button (`woocommerce_order_details_after_order_table`, option
      `withdrawalrequest-displayorderbutton`): links to the withdrawal page
      with `?order=ID`.
- Acceptance: logged-in customer sees only own cases at
  `/my-account/withdrawals/`; the order page shows the button and it lands on
  the form with that order preselected; both toggles hide their feature.

## C. Emails + admin

### C1 — Three transactional emails [DEV-237]
Files: `lib/withdrawal/emails.php` (new),
`class-wc-email-withdrawal.php` (extend),
`class-wc-email-withdrawal-admin.php`, `class-wc-email-withdrawal-status.php`
(new), `templates/withdrawal/email-*.php` (HTML+plain per email).
- [ ] Register the 3 classes per spec §3.6 (ids `cps_hc_gems_withdrawal`,
      `..._admin`, `..._status`), lazily inside the
      `woocommerce_email_classes` callback; enable toggles from options
      `-emailcustomer` / `-emailadmin` / `-emailstatus`.
- [ ] Customer confirmation: full withdrawal data + exact receipt timestamp +
      `-emailextra` appended; fired synchronously from
      `cps_hc_gems_withdrawal_confirmed`.
- [ ] Admin notification to `-emailadminrecipient` (fallback WC default);
      status update fired from `cps_hc_gems_withdrawal_status_changed` with
      the optional admin message.
- Acceptance: confirming a case delivers customer + admin emails (check
  mail-catcher on multisite.local); an admin status change with message
  delivers the status email containing it; toggles suppress each email.

### C2 — Order-email link rewire [DEV-238]
File: `lib/withdrawal/email-link.php` (kept).
- [ ] Keep injection into `customer_processing_order` /
      `customer_completed_order` per the two existing options.
- [ ] Remove the window-open condition (never-block): the button always
      renders while the module is enabled.
- Acceptance: both email types contain the working tokenized button; an
  expired order's email still links to a functioning (warning-flagged) form.

### C3 — Admin case manager [DEV-239]
File: `lib/withdrawal/admin.php` (new; replaces deleted `admin-order.php`).
- [ ] Submenu `add_submenu_page( 'woocommerce', …, 'cps-hc-gems-withdrawals' )`,
      cap `manage_woocommerce`; WP_List_Table over `..._query_cases()` with
      columns/filters/search/bulk actions per spec §3.8.
- [ ] Case detail view (`view=case&case_id=N`): summary (+ decrypted bank
      account behind an explicit reveal action), declaration + consents,
      snapshot table, read-only audit log, decision form (status select +
      message → `..._set_case_status()`), documents placeholder.
- [ ] CSV export `admin_post_cps_hc_gems_withdrawal_export_csv` (nonce + cap,
      respects filters, spec column list).
- [ ] Order-edit panel on `woocommerce_admin_order_data_after_order_details`
      listing linked cases (HPOS-safe edit URLs).
- Acceptance: list shows seeded cases with working filters/search; bulk
  accept works; detail shows all sections and a decision writes event + email;
  CSV downloads with correct rows; order edit screen lists its cases; nothing
  breaks the WooCommerce Orders submenu (regression from the CPT era).

### C4 — Settings + module card [DEV-239]
Files: `settings/settings-defaults.php`, `settings/settings-validate.php`,
`pages/menu-modules.php`, `wpml-config.xml`.
- [ ] Add every new key from spec §6 with defaults; validation: checkboxes
      0/1, selects whitelisted, numbers `absint`, texts
      `wp_filter_nohtml_kses`, legal textareas `wp_kses_post`,
      `-eligiblestatuses` sanitized to a known-status CSV, `-slug`
      `sanitize_title` (default `elallas`), `-pageid` `absint`.
- [ ] Module card: remove the Pro notice; group fields (display, deadline,
      privacy/retention, emails, legal texts); page-id as a page dropdown.
- [ ] `wpml-config.xml`: add the 7 text keys from spec §6.
- Acceptance: saving the settings page persists and re-renders every field;
  invalid select/CSV values fall back to defaults; WPML string screen lists
  the new keys.

## D. Guard, privacy, close-out

### D1 — Competing-plugin guard + mutual exclusion [DEV-244]
Files: `lib/withdrawal/guard.php` (new), include from `lib/modules.php`
(admin-only, unconditional), early-return in `modules/withdrawal-request.php`.
- [ ] `cps_hc_gems_withdrawal_competitor_active()` over the filterable list
      `cps_hc_gems_withdrawal_competing_plugins` (initial:
      `elallas-for-woo/elallas-for-woo.php`).
- [ ] Module OFF + competitor active → dismissible notice (user-meta
      dismissal) recommending this module and stating the competitor must be
      deactivated.
- [ ] Module ON + competitor active → bootstrap loads guard only and returns;
      non-dismissible warning with the one-click Deactivate button
      (`admin_post_…_deactivate_competitor`, nonce,
      `current_user_can('activate_plugins')`, `deactivate_plugins()`, redirect
      back with a success notice).
- Acceptance: with elallas-for-woo active and module off, the notice shows on
  admin (module card too); turning the module on runs zero module hooks (no
  endpoint, no emails, no menu) but shows the warning; the button deactivates
  the competitor and the module starts working on the next load.

### D2 — Retention / anonymization [DEV-245]
File: `lib/withdrawal/privacy.php` (new).
- [ ] Daily cron `cps_hc_gems_withdrawal_retention` (scheduled at bootstrap if
      missing; cleared when the module option is off — check on
      `update_option_surbma_hc_fields`).
- [ ] When `withdrawalrequest-retentiondays > 0`: blank the 7 PII columns
      (spec §4) on cases older than the limit, keep rows/items/events, write
      one `anonymized` event per case.
- Acceptance: with retention 1 and a backdated case, running the cron via
  `wp cron event run` blanks exactly the PII columns and logs the event;
  retention 0 touches nothing.

### D3 — Multilingual pass [DEV-246]
- [ ] Verify all user-facing strings are translatable (text domain), option
      texts registered in `wpml-config.xml` (C4), page id resolved via
      `wpml_object_id` (B3), emails/PDF-ready strings use the case language
      column where available.
- Acceptance: `wp i18n make-pot` picks up the new strings; with WPML active
  the button label translates per language.

### D4 — Verification & close-out
- [ ] PHPCS clean (`vendor/bin/phpcs`).
- [ ] Manual E2E on multisite.local: guest flow, logged-in flow, token-link
      flow; partial + whole withdrawal; duplicate guard; expired-window
      warning + `manual_review`; all 3 emails; admin list/detail/bulk/CSV;
      order-edit panel; My Account endpoint; guard scenarios (D1); retention
      run (D2).
- [ ] HPOS on: order meta reads/writes verified.
- [ ] Update this file's checkboxes; summarize results for the Linear issues.

## Round 2 (separate projects — do NOT start; listed for context)
PDF statement (dompdf via Composer+Strauss, SHA-256, token download, My Account
column, email attachment) → B2B + product/category/tag exceptions → Gutenberg
block + Elementor widget → onboarding wizard → REST API → billing/carrier
integrations → importer from `lw_elallas_*` tables (row copy; bump
`cps_hc_gems_wd_case_counter` past imported sequences).

## Decisions
2026-06-19: module `type=free_hu`; canonical branch
`cursor/withdrawal-request-storage-3941`; endpoint slug default `elallas`;
3 identification paths; partial withdrawal in MVP.
2026-07-08 (supersedes the CPT decision): the existing uncommitted work is NOT
discarded — confirmed path is continue-on-branch with selective deletion per
task A1 (keep `token.php`, `email-link.php`, the WC_Email class,
`order-lookup.php`, settings integration, templates/CSS base; delete
`cpt.php`, `admin-order.php`, the DEBUG_LEVEL scaffold). Custom
`cps_hc_gems_wd_*` tables,
column-identical to the reference; hash/encryption derivations byte-compatible
(`elallas:hmac` / `elallas:cipher`) for the future importer; public names
shared with the reference (`[elallas_form]`, `[elallas_button]`,
`withdrawals`); block/Elementor, PDF/dompdf → round 2; deadline marks and
never blocks (no `block` option in MVP).
