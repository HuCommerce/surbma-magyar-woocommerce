# Specification: Tax Number Field - Block Checkout Support

## Goal

Enable the tax number field module to work with WooCommerce block-based checkout while maintaining full backward compatibility with traditional shortcode checkout, using a PHP-only implementation approach.

## User Stories

- As a store administrator, I want to enable block-based checkout while maintaining tax number field functionality so that I can use modern WooCommerce features without losing critical business requirements
- As a Hungarian business customer, I want to enter my tax number on any checkout type so that I receive proper tax invoices regardless of which checkout interface the store uses

## Specific Requirements

**Block Checkout Field Registration**
- Register tax number field using WooCommerce Additional Checkout Fields API (`woocommerce_register_additional_checkout_field`)
- Field must appear in billing section of block checkout with same positioning as shortcode checkout (after Company field)
- Field label, placeholder, and validation rules must match current shortcode implementation
- Field must be stored with existing meta key `_billing_tax_number` (not the API default `_wc_billing_tax_number`)
- Use `StoreApi` namespace integration for proper block checkout compatibility

**Dynamic Field Behavior (Block Checkout)**
- Tax number field required/optional state must respond to Company field presence (same as shortcode)
- Field visibility must toggle based on Company field value when `companytaxnumberpair` setting is disabled
- Required indicator (*) must appear/disappear dynamically based on Company field state
- Behavior must respect plugin settings: `billingcompanycheck`, `companytaxnumberpair`, `woocommerce_checkout_company_field`
- JavaScript must use block checkout DOM selectors (research required to identify correct selectors)

**Validation Parity**
- Hungarian tax number format validation (11 digits) must apply to both checkout types
- Same validation logic from `cps_hc_gems_billing_tax_number_check()` function must execute for block checkout
- Error messages must match shortcode checkout formatting and translation
- Validation must integrate with WooCommerce Store API validation hooks

**Version Detection and Routing**
- Detect WooCommerce version at runtime to determine API availability (8.9+ for blocks)
- Conditionally load block registration code only when Additional Checkout Fields API is available
- Maintain shortcode checkout hooks regardless of WooCommerce version for backward compatibility
- Use `WC()->version` comparison or feature detection to route implementation

**Settings Integration**
- Block checkout must honor all existing plugin settings from `$cps_hc_gems_options` global
- `taxnumberplaceholder` setting must apply placeholder text to block checkout field
- Company-Tax Number pairing logic must work identically to shortcode implementation
- No new settings required - reuse existing configuration infrastructure

**JavaScript Adaptation**
- Existing jQuery code (lines 192-265 of tax-number.php) must be adapted for block checkout DOM
- Research block checkout field selectors (`.wp-block-woocommerce-*` classes expected)
- Add conditional JavaScript loading based on detected checkout type (shortcode vs block)
- Ensure field manipulation works with React-rendered fields (timing considerations)
- Test that dynamic required/optional toggling works during checkout updates

**Data Storage Consistency**
- Both checkout types must save to identical meta key `_billing_tax_number`
- Order meta storage must use same format and location for both implementations
- User meta must update correctly for logged-in users on both checkout types
- Session handling must work for guest checkouts on both implementations
- Admin order display must show tax number identically regardless of checkout type used

**Backward Compatibility**
- Shortcode checkout functionality must remain 100% unchanged
- WooCommerce 4.6+ installations must continue working with shortcode checkout
- No breaking changes to existing filters, actions, or function signatures
- Existing validation, session handling, and user meta code must remain functional

**Plugin Compatibility Declaration**
- Update `cart_checkout_blocks` compatibility declaration from `false` to `true` in main plugin file
- Change occurs in `surbma-magyar-woocommerce.php` line 79
- Add conditional logic to only declare compatibility when block integration is implemented
- Document version when compatibility was added

**DOM Structure Research**
- Identify exact DOM structure of block checkout billing fields section
- Document React component hierarchy and field wrapper classes
- Test jQuery selector compatibility with block checkout markup
- Verify Company field selector for dynamic behavior integration
- Ensure JavaScript timing works with React component lifecycle

## Visual Design

No visual mockups provided. Block checkout field should match native WooCommerce block checkout styling automatically through API integration.

## Existing Code to Leverage

**`modules/tax-number.php` (lines 11-32): Field Registration Hook**
- Reuse existing `woocommerce_billing_fields` filter logic for shortcode checkout
- Extract field configuration array for reuse in block registration
- Field definition includes label, required state, class, priority, and clear properties
- Company field value checking logic can be shared between implementations

**`modules/tax-number.php` (lines 72-87): Validation Function**
- `cps_hc_gems_billing_tax_number_check()` contains core validation logic
- Checks Company field presence, billing_company_check flag, and tax number requirement
- Generates WooCommerce-compatible error notices
- Function can be called from both shortcode and block validation hooks

**`modules/tax-number.php` (lines 192-265): Dynamic Behavior JavaScript**
- jQuery code handles field show/hide based on Company field
- Manages required/optional indicator toggling
- Respects `billingcompanycheck` and `companytaxnumberpair` settings
- Logic must be adapted for block checkout selectors but algorithm remains same

**`modules/checkout.php`: Similar Billing Field Modifications**
- Review existing billing field customizations for pattern examples
- Check if other modules have block checkout integration to follow
- Use same global settings access pattern (`$cps_hc_gems_options`)

**`lib/modules.php`: Module Loading System**
- Conditional module loading based on settings and license status
- Frontend-only loading flags for performance
- Use existing infrastructure for block checkout feature detection

## Out of Scope

- React or TypeScript implementation (plugin has no build system)
- npm, webpack, or any build tool setup
- Changes to admin order display (already working correctly)
- Modifications to email templates or invoice display (already working)
- Changes to My Account address editing (already supported)
- New validation rules beyond existing Hungarian 11-digit format
- Automated test infrastructure setup
- Changes to existing shortcode checkout implementation
- New plugin settings or configuration options
- Translation updates (use existing strings)
- Visual redesign of checkout fields
