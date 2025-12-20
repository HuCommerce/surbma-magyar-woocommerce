# Task Breakdown: Tax Number Field - Block Checkout Support

## Overview

**Total Estimated Effort:** 14-18 hours
**Implementation Approach:** PHP-only, no build system, manual testing
**Core Challenge:** Maintaining feature parity between shortcode and block checkout while adapting to React-rendered DOM

## Strategic Phases

### Phase 1: Research & Validation
**Duration:** 2-3 hours
**Dependencies:** None
**Goal:** Verify WooCommerce Block Checkout API availability and document technical constraints

#### Task 1.1: Verify WooCommerce Additional Checkout Fields API
**File:** `modules/tax-number.php`
**Estimated Effort:** 0.5 hours

**Actions:**
- Add version detection function to check if WooCommerce 8.9+ is active
- Use `version_compare( WC()->version, '8.9.0', '>=' )` for detection
- Verify `woocommerce_register_additional_checkout_field` function exists
- Create utility function `cps_hc_gems_has_block_checkout_support()` for reuse

**Deliverables:**
- Utility function that returns boolean for block checkout API availability
- Comment documenting minimum WooCommerce version requirement

**Acceptance Criteria:**
- Function correctly identifies WooCommerce 8.9+ installations
- Returns false for older versions without throwing errors
- Can be called from multiple locations in codebase

---

#### Task 1.2: Document Block Checkout Field Registration Requirements
**File:** New comment block in `modules/tax-number.php`
**Estimated Effort:** 1 hour

**Actions:**
- Research WooCommerce Additional Checkout Fields API documentation
- Document required parameters: location, type, label, required, sanitize_callback, validate_callback
- Identify how to override default meta key from `_wc_billing_tax_number` to `_billing_tax_number`
- Document StoreApi namespace integration requirements
- Note any known limitations or gotchas from WooCommerce documentation

**Deliverables:**
- Inline code comments documenting API parameters and behavior
- Reference links to WooCommerce developer documentation
- Notes on meta key customization approach

**Acceptance Criteria:**
- All API parameters are documented with explanations
- Meta key override approach is identified and documented
- Comments serve as implementation guide for next phase

---

#### Task 1.3: Review Existing Shortcode Implementation
**File:** `modules/tax-number.php` (lines 11-265)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Extract field configuration from lines 23-29 (label, required, class, priority, clear)
- Identify all plugin settings used: `taxnumberplaceholder`, `billingcompanycheck`, `companytaxnumberpair`
- Map validation logic from `cps_hc_gems_billing_tax_number_check()` function (lines 72-87)
- Document JavaScript behavior requirements (lines 192-265)
- List all WordPress/WooCommerce hooks currently in use

**Deliverables:**
- Commented list of all features that must be replicated for block checkout
- Mapping of shortcode features to required block checkout implementations
- Identification of shared code opportunities

**Acceptance Criteria:**
- Complete feature inventory created
- No shortcode functionality is missed in the mapping
- Clear understanding of what needs to be implemented vs. what can be reused

---

### Phase 2: DOM Structure Research
**Duration:** 2-3 hours
**Dependencies:** Phase 1 complete
**Goal:** Identify block checkout DOM selectors and React component lifecycle for JavaScript adaptation

#### Task 2.1: Set Up Test Environment with Block Checkout
**File:** WordPress admin (WooCommerce settings)
**Estimated Effort:** 0.5 hours

**Actions:**
- Navigate to WooCommerce > Settings > Advanced > Features
- Enable "Cart and Checkout Blocks" feature flag if needed
- Create or use existing test page with Block Checkout block
- Verify block checkout renders correctly on frontend
- Ensure test data is available for checkout process

**Deliverables:**
- Working block checkout test environment
- Test page URL documented in code comments
- Sample test data for manual testing

**Acceptance Criteria:**
- Block checkout page renders without errors
- Can complete test checkout with existing fields
- Browser developer tools accessible for DOM inspection

---

#### Task 2.2: Document Block Checkout DOM Structure
**File:** New comment block in `modules/tax-number.php`
**Estimated Effort:** 1-1.5 hours

**Actions:**
- Use browser DevTools to inspect block checkout billing section HTML
- Document field wrapper class structure (look for `.wp-block-woocommerce-*` classes)
- Identify Company field selector for dynamic behavior integration
- Document billing section container selectors
- Note any React component data attributes or IDs
- Compare with shortcode checkout DOM structure (from lines 210-260 selectors)

**Deliverables:**
- Inline comments with exact CSS selectors for:
  - Billing section container
  - Company field input and wrapper
  - Field wrapper template for custom fields
  - Required indicator elements
  - Validation message containers
- Side-by-side comparison of shortcode vs block selectors

**Acceptance Criteria:**
- All required selectors are documented with exact class names
- Company field can be reliably targeted with documented selector
- Field wrapper structure matches WooCommerce block patterns
- Selectors are specific enough to avoid conflicts

---

#### Task 2.3: Test jQuery Compatibility with Block Checkout
**File:** Browser console testing (document results in comments)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Open block checkout page in browser
- Test jQuery selector queries against documented selectors
- Verify timing: run selectors on DOMContentLoaded vs. delayed execution
- Test field manipulation: show/hide, add/remove classes, modify attributes
- Check if WooCommerce block checkout uses jQuery or vanilla JS
- Document any timing issues or selector failures

**Deliverables:**
- Test results documented in code comments
- Recommended jQuery ready state or timing approach
- Notes on any compatibility issues discovered
- Alternative selector strategies if primary approach fails

**Acceptance Criteria:**
- Selectors successfully target block checkout elements
- Field manipulation works without breaking React rendering
- Timing approach identified that works reliably
- No JavaScript console errors during testing

---

### Phase 3: Core Block Integration
**Duration:** 3-4 hours
**Dependencies:** Phases 1-2 complete
**Goal:** Register tax number field with WooCommerce Block Checkout API

#### Task 3.1: Implement Block Field Registration Function
**File:** `modules/tax-number.php` (add after line 32)
**Estimated Effort:** 2-2.5 hours

**Actions:**
- Create new function `cps_hc_gems_register_block_tax_number_field()`
- Check `cps_hc_gems_has_block_checkout_support()` before proceeding
- Get plugin settings from `$cps_hc_gems_options` global
- Build field configuration array matching shortcode field properties:
  - `location`: 'billing'
  - `type`: 'text'
  - `label`: Same as shortcode (line 24)
  - `required`: false (dynamic requirement handled by JavaScript)
  - `priority`: 30 (same as shortcode, line 27)
  - `show_in_checkout`: true
  - `show_in_order`: true
- Implement meta key override to use `_billing_tax_number` instead of default `_wc_billing_tax_number`
- Add placeholder using `taxnumberplaceholder` setting if enabled
- Call `woocommerce_register_additional_checkout_field()` with configuration

**Deliverables:**
- Complete field registration function
- Proper meta key override implementation
- Settings integration matching shortcode behavior

**Acceptance Criteria:**
- Function runs without errors on WooCommerce 8.9+
- Field appears in block checkout billing section
- Field position matches shortcode checkout (after Company field)
- Meta key saves as `_billing_tax_number`
- Placeholder text applies correctly when setting enabled

---

#### Task 3.2: Hook Block Field Registration to WordPress Init
**File:** `modules/tax-number.php` (add after new function)
**Estimated Effort:** 0.5 hours

**Actions:**
- Add action hook: `add_action( 'woocommerce_blocks_loaded', 'cps_hc_gems_register_block_tax_number_field' )`
- Ensure hook only fires when block checkout is available
- Verify shortcode checkout hooks remain unchanged
- Test that both systems can coexist without conflicts

**Deliverables:**
- Action hook properly registered
- No interference with existing shortcode checkout

**Acceptance Criteria:**
- Block field registration runs at correct timing
- Shortcode checkout continues working unchanged
- Both checkout types can be used on same installation
- No PHP errors or warnings in debug log

---

#### Task 3.3: Manual Testing - Field Rendering
**File:** Browser testing (document results)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Clear all caches (WordPress, WooCommerce, browser)
- Load block checkout page
- Verify tax number field appears in billing section
- Check field position relative to Company field
- Verify placeholder text when setting enabled
- Test field input accepts text
- Submit checkout and verify order saves tax number
- Check admin order page for tax number display
- Test with shortcode checkout to ensure no regression

**Deliverables:**
- Test checklist with pass/fail results
- Screenshots or notes on any visual issues
- Confirmation that basic field rendering works

**Acceptance Criteria:**
- Tax number field renders correctly in block checkout
- Field appears after Company field
- Placeholder setting is respected
- Order meta saves correctly
- No errors in browser console or PHP logs
- Shortcode checkout still works perfectly

---

### Phase 4: JavaScript Adaptation
**Duration:** 3-4 hours
**Dependencies:** Phase 3 complete
**Goal:** Adapt dynamic field behavior (required/optional toggle, show/hide) for block checkout DOM

#### Task 4.1: Create Block Checkout JavaScript Function
**File:** `modules/tax-number.php` (modify wp_footer action, lines 192-265)
**Estimated Effort:** 2-2.5 hours

**Actions:**
- Duplicate existing JavaScript logic structure
- Replace shortcode selectors with block checkout selectors from Phase 2
- Update field wrapper selector (change `#billing_tax_number_field` to block equivalent)
- Update Company field selector (change `#billing_company` to block equivalent)
- Update required indicator selector (change `.required` abbr to block equivalent)
- Test timing: may need to wait for React rendering before DOM manipulation
- Add checkout type detection to load appropriate JavaScript
- Consider using `document.addEventListener('DOMContentLoaded')` or block-specific events

**Key Selector Replacements:**
- `#billing_tax_number_field` → Block checkout field wrapper selector
- `#billing_tax_number` → Block checkout input selector
- `#billing_company` → Block checkout Company input selector
- `#billing_tax_number_field label abbr` → Block checkout required indicator
- `.validate-required` → Block checkout validation class

**Deliverables:**
- Block checkout JavaScript function with updated selectors
- Checkout type detection logic
- Conditional loading based on detected checkout type

**Acceptance Criteria:**
- JavaScript targets correct block checkout elements
- No console errors when script runs
- Field manipulation works without breaking React state
- Both shortcode and block JavaScript coexist without conflicts

---

#### Task 4.2: Implement Dynamic Required/Optional Toggle
**File:** `modules/tax-number.php` (within JavaScript section)
**Estimated Effort:** 1-1.5 hours

**Actions:**
- Adapt Company field `.keyup()` event handler for block checkout
- Ensure required indicator appears/disappears based on Company field state
- Respect `billingcompanycheck` setting (lines 203)
- Respect `companytaxnumberpair` setting (lines 204)
- Handle `woocommerce_checkout_company_field` option value (lines 193)
- Test show/hide behavior when `companytaxnumberpair` is disabled (lines 236-244)
- Add/remove `validate-required` class equivalent for blocks
- Ensure validation messages trigger correctly

**Settings-Based Behaviors:**
- If `companytaxnumberpair` disabled: Hide tax number when Company empty
- If `companytaxnumberpair` enabled: Keep visible, toggle required only
- If Company required: Always show tax number as required
- If `billingcompanycheck` enabled: Different behavior logic

**Deliverables:**
- Dynamic required/optional behavior working on block checkout
- All settings properly integrated
- Behavior matches shortcode checkout exactly

**Acceptance Criteria:**
- Required indicator (*) appears when Company has value
- Required indicator disappears when Company is empty (if settings allow)
- Field shows/hides correctly based on `companytaxnumberpair` setting
- Behavior identical to shortcode checkout experience
- Settings from `$cps_hc_gems_options` are respected

---

#### Task 4.3: Manual Testing - JavaScript Behavior
**File:** Browser testing (document results)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Test with Company field required setting enabled
- Test with Company field optional setting enabled
- Test with `companytaxnumberpair` setting enabled
- Test with `companytaxnumberpair` setting disabled
- Test with `billingcompanycheck` setting enabled
- Test typing in Company field triggers tax number changes
- Test clearing Company field removes required state
- Verify no JavaScript errors in console
- Test on mobile viewport for responsive behavior
- Compare side-by-side with shortcode checkout behavior

**Deliverables:**
- Testing matrix with all setting combinations
- Pass/fail results for each combination
- Notes on any behavior discrepancies
- Browser console screenshots showing no errors

**Acceptance Criteria:**
- All setting combinations work correctly
- Behavior matches shortcode checkout in all scenarios
- No JavaScript errors occur during interaction
- Mobile experience works correctly
- Field state changes are immediate and smooth

---

### Phase 5: Validation & Data Processing
**Duration:** 2-3 hours
**Dependencies:** Phase 4 complete
**Goal:** Ensure validation and data storage work identically for both checkout types

#### Task 5.1: Implement Block Checkout Validation Hook
**File:** `modules/tax-number.php` (add after line 87)
**Estimated Effort:** 1-1.5 hours

**Actions:**
- Research WooCommerce Store API validation hooks
- Identify correct hook: likely `woocommerce_store_api_checkout_update_order_from_request`
- Create new function `cps_hc_gems_block_tax_number_validation()`
- Reuse validation logic from `cps_hc_gems_billing_tax_number_check()` (lines 72-87)
- Extract Company field value from Store API request data
- Extract tax number from Store API request data
- Apply same validation rules as shortcode checkout
- Use `wc_add_notice()` for error messages with same formatting
- Ensure error messages match shortcode checkout exactly

**Deliverables:**
- Block checkout validation function
- Store API hook integration
- Shared validation logic between checkout types

**Acceptance Criteria:**
- Validation runs when block checkout is submitted
- Empty tax number triggers error when Company has value
- Error message format matches shortcode checkout
- Validation respects all plugin settings
- No duplicate validation errors appear

---

#### Task 5.2: Verify Data Storage Consistency
**File:** `modules/tax-number.php` (review existing hooks, lines 90-124)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Verify block checkout saves to `_billing_tax_number` meta key (not `_wc_billing_tax_number`)
- Test user meta update for logged-in users (lines 90-101)
- Test session storage for guest users (lines 104-111)
- Test field pre-population from session (lines 114-124)
- Ensure no additional hooks are needed for block checkout
- Verify meta key override from Task 3.1 works correctly

**Test Cases:**
- Logged-in user completes block checkout → verify user meta saved
- Guest user completes block checkout → verify order meta saved
- Guest user updates checkout → verify session stores value
- Return to checkout → verify field pre-populates from session
- Admin order view → verify tax number displays correctly

**Deliverables:**
- Test results confirming data storage works for block checkout
- Documentation of any additional hooks needed
- Confirmation of meta key consistency

**Acceptance Criteria:**
- Both checkout types save to identical meta key
- User meta updates correctly for logged-in users
- Session handling works for guest users
- Order meta retrieval works in admin
- No data is lost between checkout types

---

#### Task 5.3: Manual Testing - Validation & Storage
**File:** Browser and admin testing (document results)
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Test validation: Submit checkout with Company filled, tax number empty
- Verify error message appears and matches shortcode format
- Test validation: Submit with both fields filled correctly
- Verify order completes successfully
- Check admin order page for tax number display
- Test as logged-in user: verify user meta saved
- Test as guest: verify order meta saved
- Test field pre-population on return to checkout
- Verify formatted address includes tax number (lines 147-179)
- Test on My Account > Addresses page (lines 162-166)

**Deliverables:**
- Validation test results
- Data storage verification
- Screenshots of admin order display
- User meta verification

**Acceptance Criteria:**
- Validation errors appear correctly
- Error messages match shortcode checkout
- Order meta saves with correct key
- Admin order display shows tax number
- User meta updates for logged-in users
- Address formatting includes tax number
- No data storage issues detected

---

### Phase 6: Compatibility & Polish
**Duration:** 2-3 hours
**Dependencies:** Phases 1-5 complete
**Goal:** Update plugin declarations, test cross-compatibility, and finalize implementation

#### Task 6.1: Update Plugin Compatibility Declaration
**File:** `surbma-magyar-woocommerce.php` (line 79)
**Estimated Effort:** 0.5 hours

**Actions:**
- Change `cart_checkout_blocks` compatibility from `false` to `true`
- Add conditional logic to only declare compatibility when WooCommerce 8.9+ detected
- Use same version check function from Task 1.1
- Add code comment documenting when compatibility was added
- Add plugin version number to comment

**Implementation:**
```php
add_action( 'before_woocommerce_init', static function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		// Determine compatibility based on WooCommerce version
		$is_compatible = cps_hc_gems_has_block_checkout_support();

		// Block checkout compatibility added in version X.X.X for WooCommerce 8.9+
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, $is_compatible );
	}
} );
```

**Deliverables:**
- Updated compatibility declaration
- Conditional logic based on WooCommerce version
- Documentation comment

**Acceptance Criteria:**
- Compatibility declared as true for WooCommerce 8.9+
- Compatibility declared as false for older versions
- Plugin header accurately reflects support status
- No errors when declaration runs

---

#### Task 6.2: Cross-Version Compatibility Testing
**File:** Test environment (document results in code comments)
**Estimated Effort:** 1-1.5 hours

**Actions:**
- Test with WooCommerce 8.9+ (block checkout available)
  - Verify block checkout works with tax number field
  - Verify shortcode checkout still works
  - Verify no JavaScript errors in either mode
- Test with WooCommerce 8.8 or earlier (block checkout unavailable)
  - Verify shortcode checkout works unchanged
  - Verify no errors from block checkout code
  - Verify version detection prevents block registration
- Test HPOS/COT compatibility maintained (line 71 of main plugin file)
- Test with different WordPress themes
- Test with different PHP versions (7.4+)

**Deliverables:**
- Compatibility test matrix with results
- Version-specific behavior documentation
- Notes on any compatibility issues

**Acceptance Criteria:**
- WooCommerce 8.9+ installations work with block checkout
- Older WooCommerce versions work with shortcode checkout
- No breaking changes to existing functionality
- HPOS compatibility maintained
- No PHP deprecation warnings

---

#### Task 6.3: Code Review & Documentation
**File:** `modules/tax-number.php` and main plugin file
**Estimated Effort:** 0.5-1 hour

**Actions:**
- Review all new code for WordPress coding standards
- Add inline comments explaining block-specific logic
- Document all new functions with PHPDoc blocks
- Add file header comments noting block checkout support
- Document minimum WooCommerce version requirement
- Add comments explaining version detection logic
- Ensure all magic numbers have explanatory comments
- Review and clean up any temporary test code

**PHPDoc Requirements:**
- Function descriptions
- Parameter types and descriptions
- Return types and descriptions
- Version information (@since tags)
- Related functions (@see tags)

**Deliverables:**
- Well-commented code following WordPress standards
- PHPDoc blocks for all new functions
- Inline comments for complex logic
- Documentation of version requirements

**Acceptance Criteria:**
- All new functions have PHPDoc blocks
- Complex logic has explanatory comments
- Code follows WordPress coding standards
- Version requirements are documented
- No TODO comments remain in code

---

#### Task 6.4: Final Integration Testing
**File:** Complete checkout flow testing (document results)
**Estimated Effort:** 1 hour

**Actions:**
- **Shortcode Checkout Tests:**
  - Complete checkout with tax number as logged-in user
  - Complete checkout with tax number as guest
  - Test Company field optional scenario
  - Test Company field required scenario
  - Verify all settings combinations work
  - Verify validation works correctly
- **Block Checkout Tests:**
  - Repeat all shortcode checkout tests with block checkout
  - Verify identical behavior and data storage
  - Test switching between checkout types doesn't lose data
- **Admin Verification:**
  - Verify order meta displays correctly from both checkout types
  - Verify email templates show tax number
  - Verify My Account addresses show tax number
- **Edge Cases:**
  - Test with WooCommerce 4.6 (oldest supported version)
  - Test with empty tax number (optional scenario)
  - Test with special characters in tax number
  - Test with exactly 11 digits (Hungarian format)

**Deliverables:**
- Complete test results matrix
- Screenshots of successful checkouts
- Admin order screenshots
- Edge case test results

**Acceptance Criteria:**
- All shortcode checkout tests pass
- All block checkout tests pass
- Feature parity confirmed between checkout types
- No data loss when switching checkout types
- Edge cases handled correctly
- No errors in any test scenario

---

## Execution Summary

**Recommended Implementation Sequence:**
1. Phase 1: Research & Validation (2-3 hours)
2. Phase 2: DOM Structure Research (2-3 hours)
3. Phase 3: Core Block Integration (3-4 hours)
4. Phase 4: JavaScript Adaptation (3-4 hours)
5. Phase 5: Validation & Data Processing (2-3 hours)
6. Phase 6: Compatibility & Polish (2-3 hours)

**Total Timeline:** 14-18 hours

**Key Success Metrics:**
- Zero breaking changes to existing shortcode checkout
- Complete feature parity between checkout types
- No additional plugin settings required
- All existing validation rules apply to both checkouts
- Identical data storage and retrieval for both checkout types
- Clean, well-documented code following WordPress standards

**Risk Mitigation:**
- Version detection ensures graceful degradation for older WooCommerce
- DOM structure research prevents brittle selector dependencies
- Reusing existing validation logic ensures consistency
- Extensive manual testing catches edge cases
- Backward compatibility prioritized throughout implementation

**Files Modified:**
- `/modules/tax-number.php` (primary implementation file)
- `/surbma-magyar-woocommerce.php` (compatibility declaration only)

**Files NOT Modified:**
- `/modules/checkout.php` (no changes needed)
- Email templates (already working)
- Admin order display (already working)
- Translation files (reusing existing strings)
