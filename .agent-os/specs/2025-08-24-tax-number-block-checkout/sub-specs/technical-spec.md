# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-08-24-tax-number-block-checkout/spec.md

> Created: 2025-08-24
> Version: 1.0.0

## Technical Requirements

### WooCommerce Additional Checkout Fields API Integration

1. **Field Registration with Block Checkout**
   - Utilize `woocommerce_register_additional_checkout_field` action to register tax number field for block checkout
   - Define field configuration array with type, label, location, and validation parameters
   - Ensure field appears in billing section alongside existing billing fields
   - Maintain consistent field ordering with shortcode checkout implementation

2. **Block-Specific Field Registration System**
   - Implement conditional field registration based on checkout type detection
   - Use `woocommerce_blocks_loaded` action hook to initialize block-specific functionality
   - Register field with proper namespace and identifier for WooCommerce blocks system
   - Configure field metadata including required status, validation rules, and sanitization

3. **Server-Side Validation Hooks for Block Checkout**
   - Hook into `woocommerce_store_api_validate_add_to_cart` for cart-level validation
   - Implement `woocommerce_store_api_checkout_update_order_from_request` for order processing validation
   - Apply existing Hungarian tax number validation logic (11-digit format) to block checkout submissions
   - Ensure validation error messages match existing shortcode checkout error formatting

4. **Client-Side JavaScript for Dynamic Field Behavior in Blocks**
   - Enqueue block-compatible JavaScript for real-time validation
   - Utilize WooCommerce Blocks' checkout extension points for client-side integration
   - Implement field masking and formatting using block checkout's JavaScript APIs
   - Ensure validation messages appear in block checkout's error display system

5. **Dual Compatibility System (Shortcode + Block Checkout Detection)**
   - Create checkout type detection utility function
   - Implement conditional loading of shortcode vs block checkout specific code
   - Maintain shared validation and processing logic between both systems
   - Ensure settings and configuration work identically for both checkout types

6. **Plugin Compatibility Declaration Updates**
   - Update plugin header to declare WooCommerce Blocks compatibility
   - Remove or modify existing block incompatibility declarations
   - Add required feature support declarations for checkout blocks
   - Ensure HPOS/COT compatibility is maintained alongside block support

### Implementation Architecture

1. **Module Structure Enhancement**
   - Extend existing `modules/tax-number.php` with block checkout functionality
   - Create helper functions for checkout type detection and conditional logic
   - Separate shortcode-specific code from shared validation/processing logic
   - Implement feature flags for gradual rollout and testing

2. **Data Handling Consistency**
   - Ensure identical order meta key usage between shortcode and block checkout (`_billing_tax_number`)
   - Maintain existing admin order display functionality without modifications
   - Preserve current email template and invoice integration behavior
   - Use consistent data sanitization and validation across both checkout types

3. **Settings Integration**
   - Extend existing HuCommerce plugin settings to include block checkout options
   - Maintain backward compatibility with existing tax number field settings
   - Add admin notices for users about block checkout compatibility
   - Implement automatic migration of existing shortcode settings to block checkout

## Approach

### Phase 1: Block Registration and Display
1. Implement WooCommerce Additional Checkout Fields API integration
2. Register tax number field with proper block checkout configuration
3. Test field display and basic functionality in block checkout

### Phase 2: Validation and Processing
1. Implement server-side validation hooks for block checkout
2. Integrate existing Hungarian tax number validation logic
3. Ensure proper error handling and message display

### Phase 3: Client-Side Enhancement
1. Develop block-compatible JavaScript for real-time validation
2. Implement field masking and user experience improvements
3. Test cross-browser compatibility and mobile responsiveness

### Phase 4: Integration and Compatibility
1. Update plugin compatibility declarations
2. Implement dual checkout system with automatic detection
3. Comprehensive testing across different WooCommerce and WordPress versions

## External Dependencies

**No new external dependencies required.** This implementation will utilize:

1. **Built-in WooCommerce Blocks APIs:**
   - WooCommerce Additional Checkout Fields API (WooCommerce 8.6+)
   - WooCommerce Blocks checkout extension system
   - Store API validation and processing hooks

2. **Existing Plugin Dependencies:**
   - Current WooCommerce version compatibility (4.6+)
   - Existing Hungarian tax number validation logic
   - Current plugin settings and configuration system

3. **WordPress Core APIs:**
   - Action/filter hook system for integration
   - JavaScript enqueue system for client-side functionality
   - Admin settings API for configuration management

**Minimum Version Requirements:**
- WooCommerce 8.6+ (for Additional Checkout Fields API)
- WooCommerce Blocks plugin (bundled with WooCommerce core)
- WordPress 5.3+ (existing requirement)
- PHP 7.4+ (existing requirement)