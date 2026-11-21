# Cart & Checkout Block Integration — Shaping Notes

## Scope

Minden HuCommerce cart és checkout modul kompatibilissé tétele a WooCommerce Cart Block és Checkout Block alapú renderelésével. A plugin jelenleg `false`-szal deklarálja a blocks kompatibilitást — ennek `true`-ra váltása a végső cél, miután minden érintett modul teljesen kompatibilis.

**Érintett modulok (Linear issue-k):**
- HC-26: Tax number field
- HC-219: Check field formats (Pro)
- HC-220: Check field values (Pro)
- HC-221: Empty Cart button (Pro)
- HC-222: Limit Payment Methods (Pro)
- HC-223: Legal compliance
- HC-224: Hide County field if Country is Hungary
- HC-225: Autofill City after Postcode
- HC-226: Checkout page customizations
- HC-227: Coupon field customizations
- HC-228: Automatic Cart update
- HC-229: Continue shopping buttons
- HC-230: Hide shipping methods

## Decisions

- **Technikai megközelítés:** WooCommerce Additional Fields API (PHP + vanilla JS). Nem szükséges build rendszer (npm/webpack), illeszkedik a plugin jelenlegi architektúrájához.
- **Globális infrastruktúra először:** Mielőtt bármely modul megvalósul, létrejönnek a `lib/blocks.php` (helper függvények) és `lib/class-blocks-integration.php` (IntegrationInterface) fájlok.
- **Modulonkénti haladás:** Egy modul csak akkor kerül lezárásra (Linear issue zárva), ha klasszikus ÉS block checkout/cart esetén is teljesen működőképes.
- **WooCommerce verzió minimum:** Az Additional Fields API WC 8.6+ szükséges; version check kerül be minden regisztrációs hívás elé.
- **HC-228 (Automatic Cart update):** Várható eredmény N/A — a Cart block natívan auto-frissül, a modul letiltja önmagát block cart esetén.
- **`cart_checkout_blocks` kompatibilitás:** Csak az utolsó modul elkészülte után vált `true`-ra a főplugin fájlban.
- **Implementáció sorrend:**
  1. Infrastruktúra (lib/blocks.php, class-blocks-integration.php)
  2. HC-26 Tax number (legmagasabb prioritás, már folyamatban)
  3. HC-223 Legal compliance (jogilag kritikus)
  4. HC-224 Hide County
  5. HC-225 Autofill City
  6. HC-226 Checkout customizations
  7. HC-219/220 Field formats/values (Pro)
  8. HC-221/222/227/228/229/230 Cart features

## Context

- **Vizuális referenciák:** Nincsenek — a klasszikus checkout megjelenése az iránymutató.
- **Referencia implementációk:** Jelenlegi `modules/tax-number.php` és `modules/legal-checkout.php` a mintapéldák (PHP hook + JS alapú megközelítés).
- **Product alignment:** A WooCommerce egyre inkább a block alapú checkout felé tolja a fejlesztést; a kompatibilitás hiánya hosszú távon a plugin versenyképességét veszélyezteti. A "Legal Compliance First" core value miatt HC-223 kiemelt prioritás.
- **Jelenlegi állapot:** `modules/tax-number.php` és `modules/legal-checkout.php` már módosítva van (git status), az implementáció elkezdődött.

## Standards Applied

- WordPress Coding Standards (PHP)
- Security: nonce verification ahol szükséges (Store API saját nonce mechanizmust használ)
- Sanitization: `sanitize_text_field()`, `wp_kses_post()` minden user input esetén
- Escaping: `esc_html()`, `esc_attr()` minden output esetén
- Module pattern: azonos struktúra a meglévő modulokkal
- No build system: csak vanilla JS / jQuery, nincs npm/webpack
