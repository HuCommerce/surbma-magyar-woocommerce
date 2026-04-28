# Spec: Integrate Cart & Checkout features with the new block layout

## Context

A HuCommerce plugin jelenleg `false`-szal deklarálja a WooCommerce Cart & Checkout Blocks kompatibilitást (`cart_checkout_blocks`). Minden cart/checkout modul klasszikus WordPress hookokat és jQuery-t használ, ami a block alapú checkout/cart esetén nem működik. A cél: minden érintett modult kompatibilissé tenni a WooCommerce Cart & Checkout blokk alapú renderelésével.

**Linear projekt:** "Integrate Cart & Checkout features with the new block layout" (HC, In Progress)
**Technikai megközelítés:** WooCommerce Additional Fields API (PHP + vanilla JS, build rendszer nélkül)
**Megvalósítás elve:** modulonként haladunk, csak akkor lépünk tovább, ha az adott modul teljesen kompatibilis. Globális segédfüggvények előre kerülnek kialakításra, hogy a modulok ezekre épüljenek.

---

## Task 1: Save spec documentation ✅

Létrehozva: `agent-os/specs/2026-04-28-1430-cart-checkout-block-integration/`

---

## Task 2: Infrastruktúra — block integration bootstrap ✅

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
**Modul fájl:** `modules/tax-number.php`

### Jelenlegi működés (klasszikus checkout)
- `woocommerce_billing_fields` → field hozzáadása
- `woocommerce_checkout_process` → validáció
- `woocommerce_checkout_update_user_meta` → user meta mentés
- `wp_footer` inline JS → field láthatóság kezelés (company mező alapján)

### Block checkout implementáció

**Field regisztráció** (`woocommerce_init` hook):
```php
woocommerce_register_additional_checkout_field( array(
    'id'         => 'hc/billing-tax-number',
    'label'      => __( 'Tax number', 'surbma-magyar-woocommerce' ),
    'location'   => 'address',
    'required'   => false,
    'attributes' => array( 'autocomplete' => 'off' ),
) );
```

**Validáció** (`woocommerce_blocks_validate_additional_field_hc/billing-tax-number`):
- Ha company ki van töltve vagy company checkbox be van pipálva: kötelező

**Mentés** (`woocommerce_store_api_checkout_update_order_from_request`):
- `$order->update_meta_data( '_billing_tax_number', $value )`

**Meglévő hookok, amik változatlanul működnek:**
- Admin order oldal, thank you page, My Account, user profil

**Tesztelési kritériumok:**
- [ ] Mező megjelenik billing address szekcióban (block checkout)
- [ ] Validáció működik (company kitöltve → tax number kötelező)
- [ ] Order meta mentés működik
- [ ] Klasszikus checkout változatlanul működik

---

## Task 4: HC-223 — Block integration: Legal compliance

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

## Task 5: HC-224 — Block integration: Hide County field

**Linear:** https://linear.app/surbma/issue/HC-224

### Block checkout implementáció
- JS (vanilla, enqueue via `IntegrationInterface`)
- Ország dropdown változásakor: ha HU → state/county mező elrejtése
- Alternatíva: `woocommerce_get_country_locale` filter vizsgálandó

**Tesztelési kritériumok:**
- [ ] HU ország → County mező eltűnik
- [ ] Más ország → County mező visszajön

---

## Task 6: HC-225 — Block integration: Autofill City after Postcode

**Linear:** https://linear.app/surbma/issue/HC-225
**JS fájl:** `assets/js/autofill.js`

### Block checkout implementáció
- `autofill.js` kiterjesztése block checkout DOM selectorokkal
- Event listener: `input` a postcode mezőn → city kitöltése
- Enqueue: `IntegrationInterface::get_script_handles()`

**Tesztelési kritériumok:**
- [ ] Irányítószám → város automatikusan kitöltődik (block checkout)

---

## Task 7: HC-226 — Block integration: Checkout page customizations

**Linear:** https://linear.app/surbma/issue/HC-226
**Modul fájl:** `modules/checkout.php`

Modul részletes átnézése szükséges implementáció előtt. Érintett funkciók:
- Névrend csere, company checkbox, mező sorrend, e-mail mező előre hozása

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
