# Spec-lite: Withdrawal Request Module

EU 2023/2673 online withdrawal button as a HuCommerce module.

- **Data model (DEV-234):** dedicated CPT `cps_hc_gems_withdrawal`, statuses
  `wd_pending → wd_accepted/wd_rejected → wd_refunded`, meta `_order_id`, `_scope`
  (whole/partial), `_items`, `_reason`, `_requested_at`, `_processed_at`,
  `_refund_status`.
- **Access (DEV-236):** per-order HMAC token, public endpoint `/elallas/?order=&key=`,
  valid 14 days from delivery date (fixed in MVP), login-free.
- **Identification + flow (DEV-235):** 3 entry paths — (a) tokenized email link,
  (b) logged-in → `select` of own in-window orders, (c) guest → order#+email lookup.
  Then 2 steps: select items (per-item checkboxes **or** whole order) → explicit
  confirm. Partial withdrawal is in the MVP.
- **Email (DEV-237):** `CPS_HC_Gems_Withdrawal_Email extends WC_Email`, sent
  immediately on confirm via WC mailer.
- **Order email link (DEV-238):** inject tokenized button into processing/completed
  order emails while window open.
- **Admin (DEV-239):** CPT list with columns + status transitions; order-edit metabox.
- Module registered in `cps_hc_gems_get_modules_config()` (`module-withdrawalrequest`,
  type `free_hu` — MVP free; Pro features in DEV-240). WPCS + HPOS-safe.
