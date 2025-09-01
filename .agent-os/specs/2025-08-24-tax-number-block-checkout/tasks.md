# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-08-24-tax-number-block-checkout/spec.md

> Created: 2025-08-24
> Status: Ready for Implementation

## Tasks

### 1. Block Checkout Field Registration System

1.1 Write tests for WooCommerce Additional Checkout Fields API integration
1.2 Implement checkout type detection utility function in tax-number.php module
1.3 Create field registration configuration array with proper WooCommerce block parameters
1.4 Register tax number field using `woocommerce_register_additional_checkout_field` action
1.5 Implement conditional field registration based on checkout type detection
1.6 Configure field metadata including location (billing), type, label, and validation parameters
1.7 Ensure consistent field ordering with shortcode checkout implementation
1.8 Verify all tests pass for field registration system

### 2. Dynamic Field Behavior Implementation

2.1 Write tests for client-side JavaScript integration with WooCommerce blocks
2.2 Create block-compatible JavaScript file for real-time tax number validation
2.3 Implement field masking and formatting using WooCommerce Blocks JavaScript APIs
2.4 Integrate with WooCommerce Blocks checkout extension points
2.5 Develop proper error message display integration with block checkout UI
2.6 Implement Hungarian tax number format validation (11-digit) on client-side
2.7 Ensure cross-browser compatibility and mobile responsiveness
2.8 Verify all tests pass for dynamic field behavior

### 3. Validation System Integration

3.1 Write tests for server-side validation hooks integration
3.2 Hook into `woocommerce_store_api_validate_add_to_cart` for cart-level validation
3.3 Implement `woocommerce_store_api_checkout_update_order_from_request` validation hook
3.4 Apply existing Hungarian tax number validation logic to block checkout submissions
3.5 Ensure validation error messages match existing shortcode checkout formatting
3.6 Create shared validation logic between shortcode and block checkout systems
3.7 Implement proper error handling and user feedback mechanisms
3.8 Verify all tests pass for validation system integration

### 4. Data Processing and Storage

4.1 Write tests for order data consistency between checkout types
4.2 Ensure identical order meta key usage (`_billing_tax_number`) for both checkout types
4.3 Implement proper data sanitization for block checkout tax number submissions
4.4 Maintain existing admin order display functionality without modifications
4.5 Preserve current email template and invoice integration behavior
4.6 Test data retrieval and display in WordPress admin order details
4.7 Verify order meta storage consistency across shortcode and block checkouts
4.8 Verify all tests pass for data processing and storage

### 5. Compatibility and Testing

5.1 Write comprehensive integration tests for dual checkout system
5.2 Update plugin header to declare WooCommerce Blocks compatibility
5.3 Remove existing block incompatibility declarations from plugin metadata
5.4 Add required feature support declarations for checkout blocks
5.5 Implement automatic migration of existing shortcode settings to block checkout
5.6 Test compatibility across WooCommerce versions (8.6+ for block support, 4.6+ for shortcode)
5.7 Verify HPOS/COT compatibility is maintained alongside new block support
5.8 Verify all tests pass for complete compatibility system