# Tasks: Withdrawal Request Module

Build order is dependency-driven. Linear issue IDs in brackets.

## 0. Scaffold
- [ ] Create `modules/withdrawal-request.php` (option-gated bootstrap, `require_once` parts).
- [ ] Register module in `cps_hc_gems_get_modules_config()` (`option_key` `module-withdrawalrequest`, type `free_hu`, title/description/tags/doc_slug).
- [ ] Add module settings to the settings system + `wpml-config.xml` entries.

## 1. Data model [DEV-234 done → implement]
- [ ] `lib/withdrawal/cpt.php`: register CPT `cps_hc_gems_withdrawal` (non-public, admin UI).
- [ ] Register custom statuses `wd_pending/wd_accepted/wd_rejected/wd_refunded` with localized labels.
- [ ] Define meta keys `_order_id`, `_reason`, `_requested_at`, `_processed_at`, `_refund_status` (+ email, IP, window-end audit fields).

## 2. Tokenized access [DEV-236]
- [ ] `lib/withdrawal/token.php`: generate per-order HMAC token, store `_cps_hc_gems_withdrawal_token`.
- [ ] Token validation + 14-day window check (delivery date → fallback completion date).
- [ ] Register rewrite endpoint (default slug `elallas`); flush rules on module enable/disable.

## 3. Identification + front-end flow [DEV-235]
- [ ] `lib/withdrawal/order-lookup.php`: (a) logged-in customer's own in-window orders for a `select`; (b) guest order#+email lookup (window-filtered, rate-limited, nonce, generic error on mismatch).
- [ ] `lib/withdrawal/frontend.php` + `templates/withdrawal/form-step-1.php`: resolve order via link/login/guest; render order line items with per-item checkboxes (+ qty) and a "whole order" option; optional reason. Login-free for the link/guest paths.
- [ ] `templates/withdrawal/form-step-2.php`: explicit confirmation with scope/items summary, nonce-protected.
- [ ] Submit handler: re-validate identification+window, compute `_scope`/`_items`, create CPT post (`wd_pending`), persist meta, duplicate guard (offer only not-yet-withdrawn items), render `confirmation.php`.
- [ ] `assets/css/withdrawal.css` minimal styling.

## 4. Confirmation email [DEV-237]
- [ ] `lib/withdrawal/class-wc-email-withdrawal.php`: `CPS_HC_Gems_Withdrawal_Email extends WC_Email`, register via `woocommerce_email_classes`.
- [ ] Fire immediately+synchronously on confirm; configurable subject/heading; optional admin copy.

## 5. Order-email link [DEV-238]
- [ ] `lib/withdrawal/email-link.php`: inject tokenized button into `customer_processing_order`/`customer_completed_order` emails while window open.

## 6. Admin / persistence [DEV-239]
- [ ] CPT admin list columns (order, consumer, requested_at, status) + status-transition actions writing `_processed_at`/`_refund_status`.
- [ ] `lib/withdrawal/admin-order.php`: order-edit metabox listing linked withdrawals.

## 7. Verification
- [ ] PHPCS clean (`phpcs.xml`).
- [ ] Manual: full flow on multisite.local — order → email link → endpoint (logged out) → 2-step → confirmation email → CPT record → admin status transitions.
- [ ] Expired-window and duplicate-request paths show correct messages.
- [ ] HPOS on: order meta + CRUD verified.

## Decisions (resolved 2026-06-19)
- Module `type` = `free_hu` (MVP free; Pro features tracked in DEV-240).
- Endpoint slug default `elallas`; window = 14 days from **delivery date**, fixed in MVP.
- No admin notification copy in MVP (the CPT log captures it).
- Identification: 3 paths (link / logged-in order-select / guest order#+email). Partial (per-item) withdrawal is **in the MVP**.
- Configurable window, admin copy, gateway-auto refund of selected items, register export, reminders → Pro (DEV-240).
