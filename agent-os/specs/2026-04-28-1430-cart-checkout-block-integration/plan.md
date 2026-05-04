# Spec: Integrate Cart & Checkout features with the new block layout

## Context

A HuCommerce plugin jelenleg `false`-szal deklarálja a WooCommerce Cart & Checkout Blocks kompatibilitást (`cart_checkout_blocks`). Minden cart/checkout modul klasszikus WordPress hookokat és jQuery-t használ, ami a block alapú checkout/cart esetén nem működik. A cél: minden érintett modult kompatibilissé tenni a WooCommerce Cart & Checkout blokk alapú renderelésével.

**Linear projekt:** "Integrate Cart & Checkout features with the new block layout" (HC, In Progress)
**Technikai megközelítés:** WooCommerce Additional Fields API (PHP + vanilla JS, build rendszer nélkül)
**Megvalósítás elve:** modulonként haladunk, csak akkor lépünk tovább, ha az adott modul teljesen kompatibilis. Globális segédfüggvények előre kerülnek kialakításra, hogy a modulok ezekre épüljenek.
**Aktuális állapot:** A projekt implementációja újraindult. Csak a Task 1 tekintendő késznek, a folytatás a Task 2-től történik.

**Linear frissítés:** Minden task elvégzése után frissíteni kell a kapcsolódó Linear issue-t és a projektet. Szabályok:
- **Nyelv:** Minden Linear tartalom angolul írandó (comment, leírás, dokumentum).
- **Comment:** Az elvégzett munkák összefoglalója, döntések, eltérések a spectől.
- **Státusz:** Ha a task elvégzésre kerül, az issue státuszát "In Review"-ra kell állítani.
- **Leírás módosítása:** Ha a terv közben változott, a specben és/vagy az issue leírásában is frissíteni kell.
- **Projekt dokumentum:** A projekt szintű haladást dokumentumban kell nyomon követni a Linear projektben.

---

## Task 1: Save spec documentation ✅

Létrehozva: `agent-os/specs/2026-04-28-1430-cart-checkout-block-integration/`

---

## Task 2: Infrastruktúra — block integration bootstrap

**Cél:** Globális helper függvények és integration class létrehozása, amit minden modul használhat.

### Új fájlok

**`lib/blocks.php`** — block detection helpers
```php
function cps_hc_gems_is_block_checkout(): bool {
    $checkout_page_id = wc_get_page_id( 'checkout' );
    return $checkout_page_id && has_block( 'woocommerce/checkout', $checkout_page_id );
}

function cps_hc_gems_is_block_cart(): bool {
    $cart_page_id = wc_get_page_id( 'cart' );
    return $cart_page_id && has_block( 'woocommerce/cart', $cart_page_id );
}
```

**`lib/class-blocks-integration.php`** — `CPS_HC_Gems_Blocks_Integration` implementáció
- Implementálja: `Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface`
- Regisztrálja a modul-specifikus frontend JS-t a checkout/cart block számára
- Metódusok: `get_name()`, `get_script_handles()`, `get_editor_script_handles()`, `get_script_data()`

### Módosítandó fájlok

**`surbma-magyar-woocommerce.php`** (line 91)
- `false` → `true` a `cart_checkout_blocks` deklarációban (csak akkor, amikor minden modul kész)

**`lib/start.php`**
- `lib/blocks.php` és `lib/class-blocks-integration.php` betöltése
- `woocommerce_blocks_checkout_block_registration` hook regisztrálása

### WooCommerce verzió minimum check
```php
// Additional Fields API: WooCommerce 8.6+
if ( version_compare( WC()->version, '8.6.0', '>=' ) ) {
    // register additional fields
}
```

---

## Task 3: HC-26 — Block integration: Tax number field

**Linear:** https://linear.app/surbma/issue/HC-26

**Modul fájl:** [`modules/tax-number.php`](../../../modules/tax-number.php)

### Tervezett field id és hookok (for implementation)

- **Additional field id:** `cps-hc-gems/billing-tax-number` (not `hc/billing-tax-number`).
- **Registration (planned):** `woocommerce_init` → `woocommerce_register_additional_checkout_field()` (WC 8.6+). Skip when WooCommerce **Company** field is `hidden`.
- **Placeholder (planned):** when option `taxnumberplaceholder` is enabled, set `attributes['placeholder']` on the additional field (same label string as classic).
- **Validation (planned):** `woocommerce_blocks_validate_location_address_fields` for **billing and shipping groups**; require tax number when WC company setting is `required` or the current address group `company` is non-empty.
- **Classic parity note:** Block checkout first iteration can stay server-side equivalent; full hide/show pairing parity can follow Task 4 decision on checkbox behavior.
- **Persistence (planned):** `woocommerce_store_api_checkout_update_order_from_request` → `_billing_tax_number` + logged-in `billing_tax_number` user meta.
- **Guest prefill note:** Store API guest prefill parity depends on available WooCommerce hooks and may need explicit limitation note.
- **Frontend JS (planned):** [`assets/js/blocks-tax-number.js`](../../../assets/js/blocks-tax-number.js) moves the tax field after Company in block address forms. Register via `CPS_HC_Gems_Blocks_Integration` and expose through `get_script_handles()` when the Tax number module is enabled (`taxnumber` option).

### Jelenlegi működés (klasszikus checkout)

- `woocommerce_billing_fields` → field hozzáadása
- `woocommerce_checkout_process` → validáció
- `woocommerce_checkout_update_user_meta` → user meta mentés
- `wp_footer` inline JS → field láthatóság kezelés (company mező alapján)

### Linear comment template (after implementation, copy-paste)

```
HC-26 Tax number + Checkout Blocks: implemented and ready for review.

Implementation:
- Additional field id: cps-hc-gems/billing-tax-number (woocommerce_register_additional_checkout_field on woocommerce_init, WC 8.6+).
- Block validation: woocommerce_blocks_validate_location_address_fields — billing and shipping groups; required when company setting is required or the current address group company is non-empty.
- Save: woocommerce_store_api_checkout_update_order_from_request → _billing_tax_number + user meta for logged-in customers.
- Placeholder: optional attribute when taxnumberplaceholder is on.
- JS: blocks-tax-number.js registered via CPS_HC_Gems_Blocks_Integration (get_script_handles when taxnumber module enabled).

Decisions vs implementation: D3 simplified UX on blocks (no classic pairing jQuery parity); D5 guest session prefill not mirrored on Store API; D6 uses IntegrationInterface script handles.

Please regression-test block + shortcode checkout.
```

**Tesztelési kritériumok:**

- [ ] Mező megjelenik billing address szekcióban (block checkout)
- [ ] Validáció működik (billing vagy shipping company kitöltve / WC company required → tax number kötelező)
- [ ] Order meta mentés működik
- [ ] Klasszikus checkout változatlanul működik

---

## Task 4: HC-226 — Block integration: Checkout page customizations

**Linear:** https://linear.app/surbma/issue/HC-226
**Modul fájl:** `modules/checkout.php`

Modul részletes átnézése szükséges implementáció előtt. Érintett funkciók:
- Névrend csere, company checkbox, mező sorrend, e-mail mező előre hozása

---

## Task 5: HC-223 — Block integration: Legal compliance

**Linear:** https://linear.app/surbma/issue/HC-223
**Modul fájl:** `modules/legal-checkout.php`

### Block checkout implementáció

**Checkboxok regisztrálása:**
```php
woocommerce_register_additional_checkout_field( array(
    'id'       => 'hc/accept-tos',
    'label'    => $accepttosValue,
    'location' => 'order',
    'required' => true,
    'type'     => 'checkbox',
) );
// accept_pp, accept_custom1, accept_custom2 hasonlóan
```

**Validáció** (`woocommerce_blocks_validate_additional_field_{id}`):
- Kötelező checkboxoknál: ha nincs bepipálva → error

**Mentés** (`woocommerce_store_api_checkout_update_order_from_request`):
- `$order->add_meta_data()` minden checkbox értékéhez

**Tesztelési kritériumok:**
- [ ] Checkboxok megjelennek "Order" szekcióban
- [ ] Kötelező checkboxok nélkül nem lehet rendelni
- [ ] Order meta mentés működik
- [ ] Admin oldalon láthatók az elfogadások

---

## Task 6: HC-224 — Block integration: Hide County field

**Linear:** https://linear.app/surbma/issue/HC-224

### Block checkout implementáció
- JS (vanilla, enqueue via `IntegrationInterface`)
- Ország dropdown változásakor: ha HU → state/county mező elrejtése
- Alternatíva: `woocommerce_get_country_locale` filter vizsgálandó

**Tesztelési kritériumok:**
- [ ] HU ország → County mező eltűnik
- [ ] Más ország → County mező visszajön

---

## Task 7: HC-225 — Block integration: Autofill City after Postcode

**Linear:** https://linear.app/surbma/issue/HC-225
**JS fájl:** `assets/js/autofill.js`

### Block checkout implementáció
- `autofill.js` kiterjesztése block checkout DOM selectorokkal
- Event listener: `input` a postcode mezőn → city kitöltése
- Enqueue: `IntegrationInterface::get_script_handles()`

**Tesztelési kritériumok:**
- [ ] Irányítószám → város automatikusan kitöltődik (block checkout)

---

## Task 8: HC-219/220 — Block integration: Check field formats/values (Pro)

**HC-219:** https://linear.app/surbma/issue/HC-219
**HC-220:** https://linear.app/surbma/issue/HC-220

### Block checkout implementáció
- `woocommerce_store_api_checkout_validate_order_from_request`
- `woocommerce_store_api_checkout_order_processed`

---

## Task 9: HC-221/222/227/228/229/230 — Cart block features

| Issue | Funkció | Megközelítés |
|-------|---------|--------------|
| HC-221 | Empty Cart button | JS slot fill vagy DOM manipulation |
| HC-222 | Limit Payment Methods | `woocommerce_available_payment_gateways` filter (vizsgálandó) |
| HC-227 | Coupon field customizations | Block-specifikus hook vizsgálandó |
| HC-228 | Automatic Cart update | N/A — Cart block auto-frissül, modul letiltja magát |
| HC-229 | Continue shopping buttons | JS DOM manipulation |
| HC-230 | Hide shipping methods | `woocommerce_package_rates` filter (vizsgálandó) |

---

## Kritikus fájlok

| Fájl | Szerepe |
|------|---------|
| `surbma-magyar-woocommerce.php:88-94` | blocks kompatibilitás deklaráció |
| `lib/start.php` | bootstrap, új fájlok betöltési helye |
| `lib/modules.php` | modulok konfigurációja |
| `modules/tax-number.php` | tax number modul |
| `modules/legal-checkout.php` | legal compliance modul |
| `modules/checkout.php` | checkout customizations modul |
| `assets/js/autofill.js` | city autofill JS |
| `lib/blocks.php` | **ÚJ** — block detection helpers |
| `lib/class-blocks-integration.php` | **ÚJ** — IntegrationInterface implementáció |

---

## Ellenőrzés és tesztelés

### Minden modulnál
1. Klasszikus checkout/cart: változatlanul működik
2. Block checkout/cart: funkció megfelelően működik
3. Order meta: adatok elmentődnek, admin oldalon láthatók
4. Edge case-ek: guest checkout, bejelentkezett user, mobilnézet

### Technikai ellenőrzés
- `WP_DEBUG` bekapcsolt: nincs PHP hiba/warning
- Browser console: nincs JS hiba
- Network tab: Store API hívások (`/wp-json/wc/store/v1/checkout`) megfelelő response

### A végén
- `cart_checkout_blocks` kompatibilitás `true`-ra állítása
- WooCommerce admin "Status" oldalon: kompatibilitás zöld
