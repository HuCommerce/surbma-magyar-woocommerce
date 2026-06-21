# Cart & Checkout Block Integration — Shaping Notes

## Scope

Minden HuCommerce cart és checkout modul kompatibilissé tétele a WooCommerce Cart Block és Checkout Block alapú renderelésével. A plugin jelenleg `false`-szal deklarálja a blocks kompatibilitást — ennek `true`-ra váltása a végső cél, miután minden érintett modul teljesen kompatibilis.

**Linear projekt:** [\[HuCommerce\] Kosár & Pénztár funkciók integrálása a blokk szerkesztővel](https://linear.app/surbma/project/hucommerce-kosar-and-penztar-funkciok-integralasa-a-blokk-373a83cf3c97) (DEV team)

**Érintett modulok (Linear issue-k):**
- [DEV-26](https://linear.app/surbma/issue/DEV-26/block-integration-tax-number-field): Tax number field
- [DEV-219](https://linear.app/surbma/issue/DEV-219/block-integration-check-field-formats): Check field formats (Pro)
- [DEV-220](https://linear.app/surbma/issue/DEV-220/block-integration-check-field-values): Check field values (Pro)
- [DEV-221](https://linear.app/surbma/issue/DEV-221/block-integration-empty-cart-button): Empty Cart button (Pro)
- [DEV-222](https://linear.app/surbma/issue/DEV-222/block-integration-limit-payment-methods): Limit Payment Methods (Pro)
- [DEV-223](https://linear.app/surbma/issue/DEV-223/block-integration-legal-compliance): Legal compliance
- [DEV-224](https://linear.app/surbma/issue/DEV-224/block-integration-hide-county-field-if-country-is-hungary): Hide County field if Country is Hungary
- [DEV-225](https://linear.app/surbma/issue/DEV-225/block-integration-autofill-city-after-postcode-is-given): Autofill City after Postcode
- [DEV-226](https://linear.app/surbma/issue/DEV-226/block-integration-checkout-page-customizations): Checkout page customizations
- [DEV-227](https://linear.app/surbma/issue/DEV-227/block-integration-coupon-field-customizations): Coupon field customizations
- [DEV-228](https://linear.app/surbma/issue/DEV-228/block-integration-automatic-cart-update): Automatic Cart update
- [DEV-229](https://linear.app/surbma/issue/DEV-229/block-integration-continue-shopping-buttons): Continue shopping buttons
- [DEV-230](https://linear.app/surbma/issue/DEV-230/block-integration-hide-shipping-methods): Hide shipping methods

## Decisions

- **Technikai megközelítés:** WooCommerce Additional Fields API (PHP + vanilla JS). Nem szükséges build rendszer (npm/webpack), illeszkedik a plugin jelenlegi architektúrájához.
- **Globális infrastruktúra először:** Mielőtt bármely modul megvalósul, létrejönnek a `lib/blocks.php` (helper függvények) és `lib/class-blocks-integration.php` (IntegrationInterface) fájlok.
- **Modulonkénti haladás:** Egy modul csak akkor kerül lezárásra (Linear issue zárva), ha klasszikus ÉS block checkout/cart esetén is teljesen működőképes.
- **WooCommerce verzió minimum:** Az Additional Fields API WC 8.6+ szükséges; version check kerül be minden regisztrációs hívás elé.
- **DEV-228 (Automatic Cart update):** Várható eredmény N/A — a Cart block natívan auto-frissül, a modul letiltja önmagát block cart esetén.
- **`cart_checkout_blocks` kompatibilitás:** Csak az utolsó modul elkészülte után vált `true`-ra a főplugin fájlban.
- **Implementáció sorrend:**
  1. Infrastruktúra (lib/blocks.php, class-blocks-integration.php)
  2. DEV-26 Tax number (legmagasabb prioritás, folyamatban)
  3. DEV-223 Legal compliance (jogilag kritikus)
  4. DEV-224 Hide County
  5. DEV-225 Autofill City
  6. DEV-226 Checkout customizations
  7. DEV-219/220 Field formats/values (Pro)
  8. DEV-221/222/227/228/229/230 Cart features

## Context

- **Vizuális referenciák:** Nincsenek — a klasszikus checkout megjelenése az iránymutató.
- **Referencia implementációk:** Jelenlegi `modules/tax-number.php` és `modules/legal-checkout.php` a mintapéldák (PHP hook + JS alapú megközelítés).
- **Product alignment:** A WooCommerce egyre inkább a block alapú checkout felé tolja a fejlesztést; a kompatibilitás hiánya hosszú távon a plugin versenyképességét veszélyezteti. A "Legal Compliance First" core value miatt DEV-223 kiemelt prioritás.
- **Jelenlegi állapot (2026-06-21):** Task 1–2 kész. DEV-26 részben implementálva: mező megjelenik és mentődik block checkouton, de validáció, mező pozíció és billing-only megjelenítés hiányzik.

## Standards Applied

- WordPress Coding Standards (PHP)
- Security: nonce verification ahol szükséges (Store API saját nonce mechanizmust használ)
- Sanitization: `sanitize_text_field()`, `wp_kses_post()` minden user input esetén
- Escaping: `esc_html()`, `esc_attr()` minden output esetén
- Module pattern: azonos struktúra a meglévő modulokkal
- No build system: csak vanilla JS / jQuery, nincs npm/webpack
