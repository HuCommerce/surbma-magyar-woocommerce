# Standards for Cart & Checkout Block Integration

Az `agent-os/standards/index.yml` jelenleg üres, ezért a projektre vonatkozó szabványok a meglévő kódbázisból és a product dokumentációból kerültek levezetésre.

---

## PHP kód minőség

**Forrás:** `agent-os/product/tech-stack.md`, meglévő modulok

- WordPress Coding Standards betartása
- PHP_CodeSniffer kompatibilitás
- Minden fájl elején: `defined( 'ABSPATH' ) || exit;`
- Static closure-k használata: `static function()` (nem kap `$this` referenciát)
- Típusdeklaráció PHP 7.4+ szintaxissal (`bool`, `string`, stb.)

---

## Biztonság

**Forrás:** `agent-os/product/tech-stack.md`

- **Nonce:** A WooCommerce Store API saját nonce mechanizmust használ — a block checkout esetén NEM kell `check_ajax_referer()` a Store API callbackekben (az API maga kezeli)
- **Sanitization:** Minden user input:
  - `sanitize_text_field()` szöveges mezőknél
  - `wp_kses_post()` HTML-t tartalmazó beállításoknál
  - `filter_var( $value, FILTER_VALIDATE_IP )` IP mentésnél
- **Escaping:** Minden output:
  - `esc_html()` szövegnél
  - `esc_attr()` HTML attribútumoknál
  - `wp_kses_post()` HTML tartalmaknál
- **Capability checks:** Admin funkcióknál `current_user_can()` ellenőrzés

---

## WooCommerce Blocks API szabályok

**Forrás:** WooCommerce Block Development dokumentáció

### Additional Fields API (WC 8.6+)
- Verzió ellenőrzés szükséges: `version_compare( WC()->version, '8.6.0', '>=' )`
- Field ID formátum: `namespace/field-name` (pl. `hc/billing-tax-number`)
- Lokációk: `address` (billing/shipping), `contact` (email felett), `order` (additional info)
- Checkbox típus: `'type' => 'checkbox'`
- HTML label engedélyezése: a `label` értéke `wp_kses_post()`-on átment HTML lehet

### IntegrationInterface
- `get_name()`: egyedi, kisbetűs, kötőjeles azonosító (pl. `hc-blocks-integration`)
- `get_script_handles()`: frontend script handles tömbje
- `get_editor_script_handles()`: editor script handles (rendszerint üres)
- `get_script_data()`: PHP → JS adatátadás tömbje

### Store API hookok (block checkout szerver oldali logikához)
- `woocommerce_store_api_checkout_update_order_from_request` — order frissítés
- `woocommerce_store_api_checkout_order_processed` — rendelés feldolgozás után
- `woocommerce_blocks_validate_additional_field_{field-id}` — egyedi validáció

---

## JavaScript szabályok

**Forrás:** meglévő JS fájlok (`assets/js/`)

- Vanilla JS vagy jQuery (WordPress bundled)
- Nincs ES6 module, nincs JSX, nincs npm build
- Minden JS fájl `wp_register_script()` + `wp_enqueue_script()` via WordPress asset API
- Block-specifikus JS: `IntegrationInterface::get_script_handles()` via enqueue
- Dependency: `wc-blocks-checkout` handle szükséges ha Store API JS-t használ

---

## Modul struktúra

**Forrás:** `lib/modules.php`, meglévő modulok

- Minden modul egy önálló PHP fájl: `modules/module-name.php`
- Hookok: statikus anonymous function-ök (`static function()`)
- Globális beállítások: `global $cps_hc_gems_options;` minden callbackben ahol szükséges
- Block checkout detektor: `hc_is_block_checkout()` / `hc_is_block_cart()` függvények (`lib/blocks.php`-ból)
- Klasszikus ÉS block: mindkét módnak működnie kell, nem kizárják egymást

---

## i18n (fordítás)

**Forrás:** `agent-os/product/tech-stack.md`

- Text domain: `surbma-magyar-woocommerce`
- WooCommerce saját string-ek: `// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch` kommenttel
- `__()`, `_x()`, `esc_html__()` függvények
