# Specification: Withdrawal Request Module (Elállási kérelem)

> Linear project: [HuCommerce] Modul: Elállási kérelem (team DEV)
> Branch: `cursor/withdrawal-request-storage-3941` (off `develop`)
> Legal basis: EU Directive 2023/2673 (online withdrawal button)
> Reference implementation (logic/structure only, NO code copying):
> https://github.com/uptools-io/elallas-for-woo (v1.0.12, inspected 2026-07-08)
> Data-model decision (2026-07-08, supersedes DEV-234 CPT decision): **custom DB
> tables** with own `cps_hc_gems_wd_*` prefix, column-identical to the reference
> schema. The previous CPT (`cps_hc_gems_withdraw`) data layer is removed.

## 1. Overview

### 1.1 Purpose
A self-contained HuCommerce module that lets a consumer exercise their statutory
right of withdrawal entirely online, without logging in, and records each request
as a first-class, status-tracked **case** with an order snapshot, an append-only
audit log and privacy controls. Implements the six technical requirements of EU
Directive 2023/2673 and is designed for painless migration from the reference
plugin "Elállás for WooCommerce".

### 1.2 Goals (MVP / round 1)
- **Compliance**: clear label ("Elállás a szerződéstől"), no login required,
  two-step flow with explicit confirmation, immediate automated durable-medium
  email, available for the full withdrawal window, reachable from the order
  confirmation email.
- **Case management**: dedicated withdrawal page + `[elallas_form]` /
  `[elallas_button]` shortcodes, My Account `withdrawals` endpoint, order-page
  button, `?order=ID` preselection, tokenized email link.
- **Full / partial / per-item / per-quantity withdrawal** with an order snapshot
  (names, SKUs, quantities, totals frozen at submission).
- **Deadline marking, never blocking**: the window (default 14 days) is computed
  and flagged (`within` / `expired` / `unknown`); expired or unknown cases are
  still accepted and routed to manual review — the merchant decides.
- **Neutral identification**: wrong order number / email / rate-limit all return
  one identical generic message (no enumeration).
- **Admin case manager** under WooCommerce: filterable list, detail view
  (summary incl. encrypted bank account, declaration, snapshot, audit log,
  decision), bulk actions, CSV export, order-edit panel.
- **Privacy controls**: IP/UA full|hash|off, email hash + optional encryption,
  encrypted bank account (AES-256-GCM), configurable retention with scheduled
  anonymization.
- **Competing-plugin guard**: detect installed withdrawal plugins (first:
  elallas-for-woo) and warn **even while this module is disabled**; mutual
  exclusion with a one-click deactivate button when both are active.
- Multilingual-ready (translatable option strings, translated page ID),
  HPOS-compatible (plugin already declares compatibility; all order access via
  CRUD).

### 1.3 Non-Goals (round 2 — separate projects, roughly weekly cadence)
- PDF withdrawal statement (dompdf, SHA-256, token-protected download) — the
  `documents` table ships now but stays empty; the My Account PDF column appears
  with this round.
- Gutenberg block + Elementor widget (both delegate to `[elallas_form]`).
- B2B detection and product/category/tag withdrawal exceptions.
- Onboarding wizard (auto-create the withdrawal page etc.).
- REST API endpoints.
- Billing (Számlázz.hu / Billingo / NAV) and carrier delivery-date integrations.
- Importer from other withdrawal plugins (`lw_elallas_*` tables → our tables;
  by design a plain row copy, see §3.9).
- Automated gateway refunds; Cart/Checkout Blocks UI.

## 2. Current State Analysis

The branch contains a working CPT-based first iteration. Disposition:

| Existing file | Disposition |
|---|---|
| `lib/withdrawal/cpt.php` | **Delete.** Replaced by `schema.php` + `data.php` (custom tables). |
| `modules/withdrawal-request.php` | **Rewrite.** Remove the `CPS_HC_GEMS_WITHDRAWAL_DEBUG_LEVEL` bisection scaffold; add guard check + schema check. |
| `lib/withdrawal/token.php` | **Keep.** HMAC email-link token (the reference has no equivalent; this is our extra directive-compliance path). Meta key `_cps_hc_gems_withdrawal_token` unchanged. |
| `lib/withdrawal/order-lookup.php` | **Rework.** Neutral errors, option-driven eligible statuses, never-block window handling. |
| `lib/withdrawal/frontend.php` | **Rewrite.** Stateless 3-step flow (identify → select → confirm), consents, bank account, honeypot; writes to the new data layer. |
| `lib/withdrawal/email-link.php` | **Keep** (minor: read new settings). |
| `lib/withdrawal/class-wc-email-withdrawal.php` | **Extend.** Becomes the customer confirmation; two new email classes added (admin notification, status update). |
| `lib/withdrawal/admin-order.php` | **Replace** with `admin.php` (WP_List_Table on custom tables). The CPT columns/metabox code goes away; the order-edit panel concept stays. |
| `templates/withdrawal/*`, `assets/css/withdrawal.css` | **Rework/extend** (new steps + my-account template). |
| Settings keys in `settings-defaults.php` / `settings-validate.php` / `pages/menu-modules.php` | **Extend** per §6. Default slug changes `cps-hc-gems-withdraw` → `elallas`. |

Removed behaviors: "window closed → no record" hard block (now: never block,
flag + manual review); CPT statuses `wd_*`; the stale Pro-notice on the module
card (module is `free_hu`).

## 3. Architecture

### 3.1 Files
```
modules/withdrawal-request.php        # bootstrap: guard check, schema check, require parts
lib/withdrawal/guard.php              # competing-plugin detection + mutual exclusion (loaded ALWAYS in admin, see §3.8)
lib/withdrawal/schema.php             # table names, dbDelta SQL, install/upgrade (db-version option)
lib/withdrawal/data.php               # case/item/event/document CRUD ($wpdb, prepared statements)
lib/withdrawal/security.php           # encrypt/decrypt/hash + rate limiter + honeypot
lib/withdrawal/deadline.php           # deadline calculation (never blocks) + case-number generator
lib/withdrawal/token.php              # KEPT: per-order HMAC token for the email link
lib/withdrawal/order-lookup.php       # identification helpers (login/guest), eligibility
lib/withdrawal/frontend.php           # page rendering, shortcodes, stateless 3-step flow
lib/withdrawal/my-account.php         # NEW: `withdrawals` My Account endpoint + order-page button
lib/withdrawal/emails.php             # registers the 3 WC_Email classes + trigger wiring
lib/withdrawal/class-wc-email-withdrawal.php         # customer confirmation (id cps_hc_gems_withdrawal)
lib/withdrawal/class-wc-email-withdrawal-admin.php   # admin notification
lib/withdrawal/class-wc-email-withdrawal-status.php  # status update
lib/withdrawal/email-link.php         # KEPT: tokenized button in order emails
lib/withdrawal/admin.php              # cases list (WP_List_Table), case detail, bulk, CSV, order-edit panel
lib/withdrawal/privacy.php            # retention cron + anonymization
templates/withdrawal/identify.php     # entry: prefilled email / order picker / guest lookup
templates/withdrawal/select.php       # step 1: per-item qty selection or whole order
templates/withdrawal/confirm.php      # step 2: summary + 3 consents + bank account + note
templates/withdrawal/success.php      # case number + received-at confirmation
templates/withdrawal/denied.php       # single neutral error view
templates/withdrawal/my-account.php   # case list for the endpoint
templates/withdrawal/email-*.php      # HTML+plain templates for the 3 emails
assets/css/withdrawal.css             # front-end styling (kept, extended)
```
`modules/withdrawal-request.php` is the only file the module loader includes
(registry contract intact); it `require_once`s the parts, splitting on
`is_admin()` where appropriate. Function prefix stays `cps_hc_gems_withdrawal_`.

### 3.2 Data model — 4 custom tables
Names via `$wpdb->prefix`:
`cps_hc_gems_wd_cases`, `cps_hc_gems_wd_case_items`, `cps_hc_gems_wd_events`,
`cps_hc_gems_wd_documents`. Columns, types, defaults and indexes are
**column-identical to the reference schema** (so the round-2 importer is a row
copy):

**`cps_hc_gems_wd_cases`**
```
id BIGINT(20) UNSIGNED AUTO_INCREMENT PK
case_number VARCHAR(32) NOT NULL, UNIQUE KEY
order_id BIGINT(20) UNSIGNED NOT NULL, KEY
order_number VARCHAR(64) NOT NULL DEFAULT ''
customer_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0, KEY
customer_email_hash CHAR(64) NOT NULL DEFAULT ''
customer_email_encrypted TEXT NULL
status VARCHAR(32) NOT NULL DEFAULT 'received', KEY
withdrawal_type VARCHAR(10) NOT NULL DEFAULT 'full'
submitted_at DATETIME NULL
confirmed_at DATETIME NULL
deadline_status VARCHAR(16) NOT NULL DEFAULT 'unknown', KEY
order_created_at DATETIME NULL
order_completed_at DATETIME NULL
delivery_date DATETIME NULL
ip_hash VARCHAR(64) NOT NULL DEFAULT ''
user_agent_hash VARCHAR(64) NOT NULL DEFAULT ''
source_url VARCHAR(255) NOT NULL DEFAULT ''
language VARCHAR(12) NOT NULL DEFAULT ''
assigned_admin_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0
customer_note TEXT NULL
bank_account_encrypted TEXT NULL
created_at DATETIME NOT NULL
updated_at DATETIME NOT NULL
```

**`cps_hc_gems_wd_case_items`** — the order snapshot:
```
id PK; case_id BIGINT UNSIGNED NOT NULL, KEY
order_item_id, product_id, variation_id BIGINT UNSIGNED DEFAULT 0
product_name_snapshot VARCHAR(255) DEFAULT ''
sku_snapshot VARCHAR(100) DEFAULT ''
qty_ordered INT DEFAULT 0; qty_withdrawn INT DEFAULT 0
line_total_snapshot DECIMAL(19,4) DEFAULT 0
tax_total_snapshot DECIMAL(19,4) DEFAULT 0
eligibility_flag VARCHAR(20) DEFAULT 'eligible'
eligibility_note VARCHAR(255) DEFAULT ''
```

**`cps_hc_gems_wd_events`** — append-only audit log (no UPDATE/DELETE ever):
```
id PK; case_id KEY; event_type VARCHAR(50) NOT NULL
actor_type VARCHAR(20) DEFAULT 'system'   # system|customer|admin
actor_id BIGINT UNSIGNED DEFAULT 0
message TEXT NULL; metadata_json LONGTEXT NULL; created_at DATETIME NOT NULL
```
Event types in MVP: `case_created`, `case_confirmed`, `status_changed`,
`anonymized`.

**`cps_hc_gems_wd_documents`** — created now, populated in round 2 (PDF):
```
id PK; case_id KEY; document_type VARCHAR(50) DEFAULT 'withdrawal_statement'
file_path VARCHAR(255) DEFAULT ''; file_hash CHAR(64) DEFAULT ''
token VARCHAR(64) DEFAULT ''; created_at DATETIME NOT NULL
```

**Install/upgrade**: `dbDelta()` in `schema.php`, run from `admin_init` when
option `cps_hc_gems_wd_db_version` (standalone `wp_options` row) is missing or
older than the code constant (start at `1.0.0`). No activation hook is available
(modules toggle via settings), so the version check is the install trigger.
Multisite: `$wpdb->prefix` is per-site; tables are created per site on first
admin load with the module enabled.

**Standalone options**: `cps_hc_gems_wd_db_version`,
`cps_hc_gems_wd_case_counter` (array `[ 'YYYY' => int ]`).

**Order meta** (all via WC CRUD, HPOS-safe): `_cps_hc_gems_wd_has_case` (`yes`),
`_cps_hc_gems_wd_case_ids` (int[]), `_cps_hc_gems_wd_deadline_status`,
`_cps_hc_gems_wd_delivery_date` (deadline input), plus the kept
`_cps_hc_gems_withdrawal_token`.

### 3.3 Case lifecycle
Statuses (same string enums as the reference): `received`, `auto_confirmed`,
`manual_review`, `accepted`, `rejected`, `awaiting_return`, `goods_received`,
`refund_pending`, `closed`, `cancelled`. Terminal: `closed`, `rejected`,
`cancelled`.

- Create → `received`; on successful customer confirmation → `auto_confirmed`
  when `deadline_status = within`, else `manual_review`.
- Admin may set any valid status (no rigid transition matrix); each change
  writes a `status_changed` event and fires
  `do_action( 'cps_hc_gems_withdrawal_status_changed', $case_id, $old, $new, $message )`.
- `withdrawal_type`: `full` when every order item's full quantity is selected,
  else `partial`.
- Case number: `EL-%04d-%06d` (year, per-year sequence from
  `cps_hc_gems_wd_case_counter`) — same format as the reference so imported and
  native case numbers form one continuous, unique series.

### 3.4 Deadline — marks, never blocks
`deadline_status` ∈ `within|expired|unknown`.
Start date per option `withdrawalrequest-deadlinestart`:
`order_created` → order created date; `order_completed` (default) → completed
date ?? paid date ?? created date; `delivery` → order meta
`_cps_hc_gems_wd_delivery_date` passed through filter
`cps_hc_gems_withdrawal_delivery_date`; `manual` → null (→ `unknown`).
`deadline = start + days*86400` (days from option
`withdrawalrequest-deadlinedays`, default 14, filter
`cps_hc_gems_withdrawal_deadline_days`). No start date or unparseable →
`unknown`.

**Never blocks** (per brief; deliberately simpler than the reference's
`expired_handling` option): expired/unknown submissions are accepted, flagged
in the UI ("A 14 napos elállási határidő ellenőrzést igényel"), and land in
`manual_review` instead of `auto_confirmed`. The email link (§3.7) is likewise
always rendered while the module is on; the flag communicates, the merchant
decides.

### 3.5 Identification & front-end flow (stateless, 3 steps)
Entry points: the withdrawal page (option `withdrawalrequest-pageid`) rendering
`[elallas_form]`; `[elallas_button]` (label from option, links to the page);
My Account endpoint; order-page button; tokenized email link; `?order=ID`
preselection.

**Statelessness**: no session/transient. Every step re-POSTs the full state in
hidden fields and the handler **re-validates everything** (order resolution,
eligibility, remaining quantities) on each step. Nonce action
`cps_hc_gems_withdrawal` on every POST; honeypot + signed-timestamp field;
rate limiting per §4.

- **Identify**: logged-in users get their email prefilled and a `<select>` of
  their own eligible orders (max 20, newest first; ownership enforced — a
  logged-in user can never act on another account's order), and may always type
  an order number + email manually instead (covers guest orders placed with
  another address). Guests: order number + billing email. Tokenized link
  (`order` + `key`, validated via `token.php`) skips this step entirely.
  `?order=ID` preselects the order in the picker / prefills the field.
- **Step 1 — select**: the order's line items with a quantity input per item
  (remaining withdrawable qty = ordered − already covered by non-terminal
  cases; duplicate guard) plus a "whole order" shortcut. ≥1 unit required.
- **Step 2 — confirm**: summary of the selection, the legal declaration text,
  **three required consent checkboxes** (data accuracy, withdrawal intent,
  data-processing consent), optional bank account / IBAN (encrypted at rest)
  and optional free-text note. Single explicit confirm button.
- **On confirm**: re-validate, build the snapshot rows, insert case
  (`received`) + items + `case_created` event, then confirm → status per §3.3,
  `case_confirmed` event, `confirmed_at = current_time('mysql', true)`, order
  meta updated, `do_action( 'cps_hc_gems_withdrawal_confirmed', $case_id )`
  (fires the emails), render `success.php` with case number + exact receipt
  timestamp.
- **Neutral errors**: any failure to identify (bad order#, bad email, rate
  limit, ineligible status) renders the same `denied.php` with one generic
  message. Never reveal which field was wrong.

### 3.6 Emails (durable medium)
Three `WC_Email` subclasses registered via `woocommerce_email_classes`
(instantiated lazily inside the registration callback, current pattern kept),
`template_base = CPS_HC_GEMS_DIR . '/templates/withdrawal/'` (theme-overridable
via `wc_get_template_html`), placeholders `{case_number}`, `{order_number}`:

| Class | id | Recipient | Trigger |
|---|---|---|---|
| `CPS_HC_Gems_Withdrawal_Email` | `cps_hc_gems_withdrawal` | customer | `cps_hc_gems_withdrawal_confirmed` |
| `CPS_HC_Gems_Withdrawal_Email_Admin` | `cps_hc_gems_withdrawal_admin` | admin (option, fallback default) | `cps_hc_gems_withdrawal_confirmed` |
| `CPS_HC_Gems_Withdrawal_Email_Status` | `cps_hc_gems_withdrawal_status` | customer | `cps_hc_gems_withdrawal_status_changed` (+ optional admin message) |

Customer confirmation is sent immediately and synchronously on confirm, contains
the full withdrawal data + the exact receipt timestamp, and appends the
merchant-editable extra text (option `withdrawalrequest-emailextra`). PDF
attachment slot arrives in round 2.

### 3.7 Order-email link & order-page button
- `email-link.php` (kept): tokenized button injected via
  `woocommerce_email_after_order_table` into `customer_processing_order` /
  `customer_completed_order` (per-email options). Change from current code: do
  **not** suppress the button when the window has expired (never-block); the
  link stays valid, the form flags the deadline.
- Order-page button (`my-account.php`): a "Elállás a szerződéstől" button on the
  My Account order view (`woocommerce_order_details_after_order_table`) linking
  to the withdrawal page with `?order=ID` — reachable within two clicks.

### 3.8 Admin
- **Guard** (`guard.php`): included unconditionally from `lib/modules.php` when
  `is_admin()` (NOT via the option-gated module loader — it must run while the
  module is off). Behavior:
  - Competing plugin active (filterable list
    `cps_hc_gems_withdrawal_competing_plugins`, initially
    `[ 'elallas-for-woo/elallas-for-woo.php' ]`) + module **off** → dismissible
    `admin_notices` recommending the HuCommerce module and stating the other
    plugin must be deactivated.
  - Competing plugin active + module **on** → `modules/withdrawal-request.php`
    loads guard only and returns (no module features run); non-dismissible
    warning with a one-click **Deactivate** button
    (`admin_post_cps_hc_gems_withdrawal_deactivate_competitor`, nonce,
    `current_user_can( 'activate_plugins' )`, `deactivate_plugins()`).
- **Cases list**: `add_submenu_page( 'woocommerce', …, 'cps-hc-gems-withdrawals',
  … )`, cap `manage_woocommerce`, custom `WP_List_Table` over
  `cps_hc_gems_wd_cases`. Columns: case number (→ detail), order (HPOS-safe
  `$order->get_edit_order_url()`), customer, type, deadline badge, status,
  submitted. Status filter views + search (case number / order number). Bulk
  actions: `mark_review`, `mark_accepted`, `mark_rejected`, `mark_closed`,
  `cancel`. (The old CPT admin-menu breakage does not apply: no CPT is
  registered anymore, this is a plain submenu page.)
- **Case detail** (`?page=cps-hc-gems-withdrawals&view=case&case_id=N`):
  sections — summary (status, deadline, timestamps, identification method,
  decrypted bank account shown on demand to `manage_woocommerce`), declaration
  + consents, order snapshot (items, qty, totals, SKU), audit log (read-only),
  admin decision (status select + optional message → event + status email),
  documents (placeholder until round 2).
- **CSV export**: `admin_post_cps_hc_gems_withdrawal_export_csv` (nonce, cap),
  respects current list filters; columns `case_number, order_number, status,
  withdrawal_type, deadline_status, submitted_at`.
- **Order-edit panel**: `woocommerce_admin_order_data_after_order_details` —
  lists the order's cases (link, status, submitted) built on `data.php`.

### 3.9 Reference compatibility & migration posture
Own table/option/hook names everywhere, but three things are kept
**byte-compatible** with the reference so the round-2 importer is a plain row
copy with working lookups afterwards:
1. **Schema**: identical columns/types/defaults/enums (§3.2, §3.3).
2. **Hash derivations**: `hash( $v ) = hash_hmac( 'sha256',
   strtolower( trim( $v ) ), hash_hmac( 'sha256', 'elallas:hmac',
   wp_salt( 'auth' ) ) )` for `customer_email_hash` and for IP/UA in `hash`
   mode. Same site → same `wp_salt('auth')` → imported hashes keep matching.
3. **Encryption**: AES-256-GCM, key `hash_hmac( 'sha256', 'elallas:cipher',
   wp_salt( 'auth' ), true )`, payload `base64( iv(12) . tag(16) . ciphertext )`
   — imported `bank_account_encrypted` / `customer_email_encrypted` blobs stay
   decryptable.
Public identifiers are shared by design (both plugins can never run
simultaneously): shortcodes `[elallas_form]` / `[elallas_button]`, My Account
endpoint slug `withdrawals` — existing pages/links keep working after a switch.
Case-number format shared (§3.3); the importer must bump
`cps_hc_gems_wd_case_counter` past imported sequences.

## 4. Security & Privacy
- **Nonces** on every state-changing request (front: `cps_hc_gems_withdrawal`;
  admin: per-action nonces). Capabilities: `manage_woocommerce` for all admin
  pages/actions; `activate_plugins` for the deactivate button.
- **SQL**: every query through `$wpdb->prepare()`; table names from
  `schema.php` helpers only. WPCS clean per `phpcs.xml`.
- **Rate limiting** (transients): per-IP `10 attempts / 600 s` on identify;
  per-order `20 / 3600 s` global — both on identify and confirm.
- **Honeypot**: daily-rotating field name
  (`cps_hc_gems_wd_hp_` + `substr( hash_hmac( 'sha256', gmdate( 'Y-m-d' ),
  wp_salt( 'auth' ) ), 0, 12 )`) + signed timestamp field; reject fill-times
  < 2 s or > 24 h or bad signature — silently render the neutral `denied` view.
- **PII**: email stored as HMAC hash + optionally encrypted; IP/UA per mode
  `full|hash|off`; bank account always encrypted; token in email link is
  HMAC-derived, order-bound, `hash_equals()`-compared.
- **Retention** (`privacy.php`): option `withdrawalrequest-retentiondays`
  (0 = keep forever); daily cron `cps_hc_gems_withdrawal_retention` blanks
  `customer_email_hash`, `customer_email_encrypted`, `ip_hash`,
  `user_agent_hash`, `source_url`, `customer_note`, `bank_account_encrypted`
  on cases older than the limit (cases/items/events survive) and logs an
  `anonymized` event. Cron scheduled on module bootstrap, cleared when the
  module option turns off.
- Output escaping everywhere; templates receive pre-built data arrays.
- Module disable/uninstall never drops the tables in MVP (data retention).

## 5. Module registration
Existing entry kept: `cps_hc_gems_get_modules_config()['withdrawal-request']`,
`option_key` `module-withdrawalrequest`, `type => 'free_hu'`, directory
`modules`, doc_slug `elallasi-kerelem`. The module card's Pro-notice is removed.

## 6. Settings (`$cps_hc_gems_options`, prefix `withdrawalrequest-`)
Existing keys kept: `module-withdrawalrequest` (0), `-emailprocessing` (1),
`-emailcompleted` (1), `-buttonlabel` (''), `-emailsubject` (''),
`-emailheading` (''), `-slug` (**default changes to `elallas`**).

New keys (defaults in parentheses; checkboxes 0/1, selects validated against
whitelists, texts `wp_filter_nohtml_kses`, textareas `wp_kses_post`):
`-pageid` (0), `-confirmlabel` (''), `-displayaccount` (1),
`-displayorderbutton` (1), `-deadlinedays` (14),
`-deadlinestart` (`order_completed` | `order_created` | `delivery` | `manual`),
`-eligiblestatuses` (`processing,completed`, comma-separated),
`-storeip` (`hash` | `full` | `off`), `-storeua` (`hash` | `full` | `off`),
`-encryptemail` (1), `-retentiondays` (0), `-emailcustomer` (1),
`-emailadmin` (1), `-emailstatus` (1), `-emailadminrecipient` (''),
`-emailextra` (''), `-legaldeclaration` (''), `-legalconfirmation` ('').
Empty label/legal options fall back to built-in translatable defaults
(default button label: "Elállás a szerződéstől"; confirm: "Elállás
megerősítése"; declaration/confirmation texts defined in `frontend.php`).

WPML: add the text keys (`buttonlabel`, `confirmlabel`, `emailsubject`,
`emailheading`, `emailextra`, `legaldeclaration`, `legalconfirmation`) to
`wpml-config.xml`; resolve `-pageid` through `apply_filters( 'wpml_object_id',
…, 'page', true )` so WPML/Polylang/TranslatePress serve the translated page.

## 7. Acceptance

### 7.1 EU 2023/2673 directive mapping
| Requirement | Covered by |
|---|---|
| Clearly labelled button/function | §3.5 entry points, §3.7, default labels §6 |
| No login required | §3.5 guest path + tokenized link §3.7 |
| Two-step process with explicit confirmation | §3.5 steps 1–2 + consents |
| Immediate automated confirmation on durable medium | §3.6 customer email with receipt timestamp |
| Available for the full withdrawal period | §3.4 never-block (link + form always available) |
| Reachable from the order confirmation email | §3.7 email link |

### 7.2 Brief (first-round bullets) → spec mapping
| Brief bullet | Spec |
|---|---|
| Online page + button, shortcode, account endpoint, order button | §3.5, §3.7 (block/Elementor → round 2) |
| Guest-friendly identification, `?order=ID`, own-orders picker, cross-account protection | §3.5 |
| My Account self-service (case list; PDF link in round 2) | §3.8 endpoint via `my-account.php` |
| Two-step flow, consents, IBAN encrypted, note | §3.5, §4 |
| Durable-medium email + timestamp + extra text (PDF attach → round 2) | §3.6 |
| Full/partial/per-item/per-quantity | §3.5 step 1, §3.3 `withdrawal_type` |
| Deadline marking, never blocks | §3.4 |
| Order snapshot | §3.2 `case_items` |
| Append-only audit log | §3.2 `events` |
| Admin case manager under WooCommerce | §3.8 |
| CSV export (PDF → round 2) | §3.8 |
| Neutral identification | §3.5, §4 |
| Privacy controls (IP/UA, email hash/encrypt, bank account, retention) | §4 |
| Multilingual | §6 WPML block |
| HPOS-compatible | plugin-wide declaration + CRUD-only order access |
| Competing-plugin detection + one-click deactivate + mutual exclusion | §3.8 guard |
