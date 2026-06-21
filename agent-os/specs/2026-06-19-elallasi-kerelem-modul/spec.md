# Specification: Withdrawal Request Module (Elállási kérelem)

> Linear project: [HuCommerce] Modul: Elállási kérelem (team DEV)
> Branch: `feature/withdrawal-request` (off `develop`)
> Legal basis: EU Directive 2023/2673 (online withdrawal button)
> Data-model decision: DEV-234 — dedicated CPT `cps_hc_gems_withdrawal`

## 1. Overview

### 1.1 Purpose
Add a self-contained HuCommerce module that lets a consumer exercise their statutory
right of withdrawal entirely online, without logging in, and that records each
request as a first-class, status-tracked entity. This implements the six technical
requirements of EU Directive 2023/2673 ("withdrawal button").

### 1.2 Goals
- **Compliance**: meet all six directive requirements (clear label, no login,
  two-step, immediate automated confirmation, available for the full 14-day window,
  reachable from the order confirmation email).
- **Auditability**: every request is a `cps_hc_gems_withdrawal` post with a tracked
  lifecycle (`pending → accepted/rejected → refunded`).
- **Convention-fit**: ship as a standard module registered in
  `cps_hc_gems_get_modules_config()`, gated by an option, following the existing
  `cps_hc_gems_` prefix, `$cps_hc_gems_options` settings, WPCS, and HPOS-safe order
  access.

### 1.3 Non-Goals
- Automated refund execution in the payment gateway (status `refunded` is set
  manually by the shop in v1; gateway automation is a later iteration).
- Integration with external return services (that is the parked DEV-232 track).
- Cart & Checkout Blocks UI (plugin uses traditional templates).

> **Note:** Per-line-item (partial) withdrawal **is in the MVP** — see §3.4. Only the
> automated gateway refund of the selected items is deferred.

## 2. Current State Analysis

### 2.1 Relevant conventions (from the existing codebase)
| Concern | Existing pattern | Source |
|---------|------------------|--------|
| Module registration | entry in `cps_hc_gems_get_modules_config()` (`option_key`, `type`, `directory`, `title`, `description`, `tags`, `doc_slug`) | `lib/modules.php` |
| Module loading | `include_once CPS_HC_GEMS_DIR . '/' . directory . '/' . file` when option enabled; `frontend_only` / `is_admin()` gating | `lib/modules.php` |
| Settings | global `$cps_hc_gems_options` array keyed by `option_key` | all modules |
| Hooks | static anonymous functions, named `cps_hc_gems_*` for reusable functions | `modules/*.php` |
| Order meta (admin display) | `woocommerce_admin_order_data_after_billing_address`, `$order->get_meta()` | `modules/legal-checkout.php` |
| Order meta (write) | `$order->add_meta_data()` on `woocommerce_checkout_create_order` | `modules/legal-checkout.php` |
| Nonces | `check_ajax_referer()` before reads of `$_POST` | `modules/legal-checkout.php` |
| Text domain | `surbma-magyar-woocommerce` | all |
| HPOS | declared compatible; use CRUD (`wc_get_order`, `$order->get_meta`) | `agent-os/product/tech-stack.md` |

### 2.2 New ground (no internal precedent)
This is the first module to introduce a **custom post type**, **custom post
statuses**, a **transactional `WC_Email` subclass**, and a **login-free front-end
endpoint**. These are standard WordPress/WooCommerce APIs; the spec defines the
patterns the module establishes for the plugin.

## 3. Architecture

### 3.1 Files
```
modules/withdrawal-request.php          # module entry: option-gated bootstrap
lib/withdrawal/cpt.php                   # CPT + custom statuses + admin columns
lib/withdrawal/token.php                 # tokenized-link generation + validation (14-day window)
lib/withdrawal/frontend.php              # endpoint + identification (link/login/guest) + item-select + submit
lib/withdrawal/order-lookup.php          # logged-in order list + guest order#+email lookup (window-filtered)
lib/withdrawal/email-link.php            # inject withdrawal button into order emails
lib/withdrawal/class-wc-email-withdrawal.php  # WC_Email subclass (confirmation)
lib/withdrawal/admin-order.php           # order-edit metabox showing linked withdrawals
templates/withdrawal/form-step-1.php     # identify + per-item checkboxes / whole-order
templates/withdrawal/form-step-2.php     # explicit confirmation (scope summary)
templates/withdrawal/confirmation.php    # post-submit thank-you
assets/css/withdrawal.css                # minimal front-end styling
```
The single `modules/withdrawal-request.php` is the only file the loader includes; it
`require_once`s the `lib/withdrawal/*` parts. This keeps the module-registry contract
intact while allowing internal separation.

### 3.2 Data model (DEV-234)
Custom post type **`cps_hc_gems_withdrawal`** (`public => false`, `show_ui => true`,
`capability_type => shop_order`-style restricted, no front-end single view).

| Meta key | Meaning |
|----------|---------|
| `_order_id` | linked WooCommerce order ID |
| `_scope` | `whole` or `partial` |
| `_items` | for `partial`: map of `order_item_id => qty` being withdrawn (empty/all for `whole`) |
| `_reason` | optional consumer-stated reason |
| `_requested_at` | submission timestamp (GMT) |
| `_processed_at` | timestamp of accept/reject |
| `_refund_status` | free/short status string for the refund step |

Additional stored fields for the audit trail: consumer email (from order),
submission IP, the identification method used (link / logged-in / guest-lookup), and
the order's withdrawal-window end date.

Custom post statuses (via `register_post_status`):
`wd_pending` → `wd_accepted` / `wd_rejected` → `wd_refunded`.
(Internal slugs are prefixed to avoid collisions; UI labels are localized.)

### 3.3 Tokenized access (DEV-236)
- On order creation (or first email render), generate a per-order token:
  `hash_hmac('sha256', $order_id . '|' . $order->get_date_created(), wp_salt('auth'))`,
  stored as order meta `_cps_hc_gems_withdrawal_token` (stable, regenerable).
- Public URL: front-end rewrite endpoint, e.g. `/{slug}/?order={id}&key={token}`
  (`slug` configurable, default `elallas`). Resolved on `template_redirect`.
- **Validity window**: 14 days measured from the **delivery date** (fixed in the
  MVP). Configurable window length/start is a Pro feature (DEV-240). Expired tokens
  render a clear "withdrawal period ended" message; no record is created.

### 3.4 Front-end flow & identification (DEV-235)

**Identification — three entry paths, all resolving to one order + its items:**
1. **Tokenized email link** (§3.3): the order is pre-identified by `order` + `key`;
   no further lookup needed. Works for guests and logged-in users.
2. **Logged-in customer**: on the withdrawal page, a `select` lists the current
   user's own orders that are still inside the withdrawal window; the customer picks
   one. (Restricted to the user's own orders.)
3. **Guest customer**: order number + billing email act as the identification; on
   match (and within window) the order's items are shown. Rate-limited + nonce to
   resist enumeration; generic error on mismatch.

**Step 1 — select what to withdraw:** show the order's line items, each with a
checkbox (and quantity where >1), plus a clearly-labelled **"Withdraw from the whole
order"** option. Optional reason textarea. Clear labelling throughout
("Elállás a szerződéstől"). At least one item (or whole-order) must be selected.

**Step 2 — explicit confirmation:** a distinct confirmation screen summarizing the
chosen scope/items, with a single unambiguous confirm action (no silent/auto submit).
Nonce-protected POST.

**On confirm:** re-validate identification + window, compute `_scope`/`_items`, create
the `cps_hc_gems_withdrawal` post (`wd_pending`), persist meta, fire the confirmation
email, show the thank-you template. Duplicate guard: one open request per order; if a
partial request already exists, only the not-yet-withdrawn items are offered.

### 3.5 Confirmation email (DEV-237)
- `CPS_HC_Gems_Withdrawal_Email extends WC_Email`, registered via
  `woocommerce_email_classes`, recipient = consumer. No admin copy in the MVP (the
  CPT log captures the request); an admin notification copy is a Pro idea (DEV-240).
- Sent **immediately and synchronously** on successful confirmation, using the WC
  mailer so it inherits the shop's email template/branding. Subject/heading
  configurable.

### 3.6 Link in order confirmation email (DEV-238)
- Hook `woocommerce_email_order_details` (or `woocommerce_email_after_order_table`)
  for `customer_processing_order` / `customer_completed_order`.
- Inject the clearly-labelled, tokenized withdrawal button, only while the 14-day
  window is open.

### 3.7 Admin (DEV-239)
- CPT admin list with custom columns: order (link), consumer, requested_at, scope
  (whole/partial), status.
- Status transitions from the list/edit screen (accept/reject/refunded), writing
  `_processed_at` / `_refund_status`.
- Metabox on the order edit screen
  (`woocommerce_admin_order_data_after_order_details`) listing linked withdrawals
  and, for partial requests, the specific withdrawn items + quantities.

### 3.8 Settings (`$cps_hc_gems_options`)
`option_key` base `module-withdrawalrequest` plus: button label text, endpoint slug
(default `elallas`), which order statuses receive the email link, email
subject/heading. Window length is fixed at 14 days from delivery in the MVP
(configurability + admin-notification toggle are Pro — DEV-240).

## 4. Security & Compliance
- Token is HMAC-derived, not guessable; validated on every request; bound to order
  and window.
- All `$_POST`/`$_GET` reads sanitized; all output escaped (WPCS clean, `phpcs.xml`).
- Nonce on the confirm POST in addition to the token (defense in depth).
- Capability checks (`edit_shop_orders`) on all admin actions.
- No PII beyond what the order already holds; IP stored only for the audit trail.
- WPML: register strings; `wpml-config.xml` updated for new options.

## 5. Module type (decided)
`type => 'free_hu'`. The MVP withdrawal feature is **free** (EU/HU legal-compliance,
consistent with the free `legal-checkout` module). Pro features are tracked
separately in DEV-240 and ship later.

## 6. Out-of-scope / later iterations (Pro — DEV-240)
- Configurable withdrawal window, admin notification copy, gateway-automated refunds
  of the selected items, CSV export of the withdrawal register, reminder emails.
- External-service integration is the parked DEV-232 track.
- (Partial / per-item withdrawal is **in the MVP**, see §3.4 — only the automated
  gateway refund of those items is deferred.)

## 7. Acceptance (directive mapping)
| Directive requirement | Covered by |
|-----------------------|-----------|
| Clearly labelled | §3.4 / §3.6 |
| No login required | §3.3 token + §3.4 |
| Two-step process | §3.4 |
| Immediate automated confirmation | §3.5 |
| Available for full 14-day period | §3.3 window |
| Reachable from order confirmation email | §3.6 |
