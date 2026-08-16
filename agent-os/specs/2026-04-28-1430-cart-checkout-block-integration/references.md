# References for Cart & Checkout Block Integration

## Hasonló implementációk a kódbázisban

### Tax number modul (jelenlegi implementáció)

- **Helye:** `modules/tax-number.php`
- **Relevancia:** Ez a legkomplexebb checkout field modul — a block integráció alapmintája
- **Kulcs minták:**
  - `woocommerce_billing_fields` → billing field hozzáadása (klasszikus)
  - `woocommerce_checkout_process` → szerver oldali validáció (klasszikus)
  - `woocommerce_checkout_update_user_meta` → user meta mentés (mindkét módban működik)
  - `wp_footer` inline JS → field láthatóság kezelés (jQuery, csak klasszikusban)
  - `woocommerce_order_formatted_billing_address` → order display (mindkét módban)
  - `woocommerce_admin_billing_fields` → admin order mező (mindkét módban)

### Legal checkout modul (jelenlegi implementáció)

- **Helye:** `modules/legal-checkout.php`
- **Relevancia:** Checkbox alapú extra fields a checkout-on — a block checkbox integráció mintapéldája
- **Kulcs minták:**
  - `$legalconfirmationsposition` hook → checkboxok renderelése (klasszikus)
  - `woocommerce_checkout_process` → checkbox validáció (klasszikus)
  - `woocommerce_checkout_create_order` → order meta mentés (klasszikus)
  - `woocommerce_admin_order_data_after_billing_address` → admin display (mindkét módban)
  - `woocommerce_register_form` → regisztrációs oldal checkbox (nem checkout, marad)

### Autofill JS

- **Helye:** `assets/js/autofill.js`
- **Relevancia:** Irányítószám → város automatikus kitöltés — block checkout DOM selectorokat is kezelni kell
- **Kulcs minták:**
  - jQuery `#billing_postcode` selector → block esetén más selector szükséges
  - AJAX hívás a city lookup-hoz

### Modul betöltési rendszer

- **Helye:** `lib/modules.php`
- **Relevancia:** Így épül fel minden modul konfigurációja és töltődik be feltételesen
- **Kulcs minták:**
  - `option_key` → settings kulcs
  - `type` → free/pro/legacy
  - `force_enable` → mindig betöltött modulok
  - `frontend_only` → csak frontend contexten töltődik be

### Plugin bootstrap

- **Helye:** `lib/start.php`
- **Relevancia:** Ide kerül be az új `lib/blocks.php` és `lib/class-blocks-integration.php` betöltése
- **Kulcs minták:**
  - `global $cps_hc_gems_options` inicializálása
  - Core fájlok `include_once`-szal töltve be

### Blocks kompatibilitás deklaráció

- **Helye:** `surbma-magyar-woocommerce.php:88-94`
- **Relevancia:** Ez az a sor, ami `true`-ra vált, amikor minden modul kész
```php
\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
    'cart_checkout_blocks',
    __FILE__,
    false  // → true lesz a végén
);
```

---

## Külső referenciák

### WooCommerce Block Development dokumentáció

- **URL:** https://github.com/woocommerce/woocommerce/tree/trunk/docs/block-development
- **Forrás:** Linear projekt resource (hozzáadva 2026-04-28)
- **Relevancia:** Hivatalos WC blocks fejlesztési útmutató

### WooCommerce Additional Fields API

- **Összefoglaló:** WC 8.6+-ban bevezetett API extra checkout mezők regisztrálásához
- **Fő függvény:** `woocommerce_register_additional_checkout_field()`
- **Fő hookok:**
  - `woocommerce_blocks_validate_additional_field_{id}` — validáció
  - `woocommerce_store_api_checkout_update_order_from_request` — mentés

### WooCommerce IntegrationInterface

- **Namespace:** `Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface`
- **Regisztrálás:** `woocommerce_blocks_checkout_block_registration` action
- **Célja:** JS szkriptek regisztrálása a checkout/cart block számára, PHP adatok átadása JS-nek
