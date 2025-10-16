# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-09-03-turnstile-integration/spec.md

> Created: 2025-09-03
> Version: 1.0.0

## Technical Requirements

### Core Module Implementation

**New Module File**: `modules/turnstile.php`
- Follow HuCommerce module architecture patterns
- Conditional loading based on user settings in `$hc_gems_options`
- WordPress hooks integration for WooCommerce forms
- Secure API key management with WordPress options

**Module Structure**:
```php
// Module initialization check
if (!defined('ABSPATH')) exit;

// Main class following HuCommerce patterns
class SurbmaHcTurnstile {
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Hook registrations
        // Settings integration
        // Script enqueueing
    }
}
```

### Admin Settings Integration

**Cherry Pick Studios SDK Integration**:
- Add Turnstile settings section to existing admin interface
- Follow SDK field patterns used in other HuCommerce modules
- Settings stored in global `$hc_gems_options` array

**Required Settings Fields**:
- Site Key (text input, required)
- Secret Key (password input, required, stored hashed)
- Enable/Disable toggle for each form type
- Theme selection (light/dark/auto)
- Size selection (normal/compact)
- Form placement options

### WordPress Integration Architecture

**Hook Implementation**:
```php
// Checkout form integration
add_action('woocommerce_review_order_before_submit', 'add_turnstile_widget');
add_action('woocommerce_checkout_process', 'validate_turnstile_checkout');

// Registration form integration
add_action('woocommerce_register_form', 'add_turnstile_widget');
add_action('woocommerce_registration_errors', 'validate_turnstile_registration');

// Login form integration
add_action('woocommerce_login_form', 'add_turnstile_widget');
add_action('wp_authenticate_user', 'validate_turnstile_login');
```

**Script Enqueueing**:
- Conditional loading only on forms where Turnstile is enabled
- Cloudflare Turnstile API script: `https://challenges.cloudflare.com/turnstile/v0/api.js`
- Custom JavaScript for widget initialization and form handling

### JavaScript Integration Specifications

**Widget Rendering**:
```javascript
window.turnstile.render('.cf-turnstile', {
    sitekey: hc_turnstile.site_key,
    theme: hc_turnstile.theme,
    size: hc_turnstile.size,
    callback: function(token) {
        // Handle successful verification
        document.querySelector('[name="cf-turnstile-response"]').value = token;
    },
    'error-callback': function() {
        // Handle verification failure
        showTurnstileError();
    }
});
```

**Form Validation Integration**:
- Prevent form submission until Turnstile validation completes
- Display user-friendly error messages for validation failures
- Graceful degradation when JavaScript is disabled

### Security Implementation

**API Key Storage**:
- Site Key: Stored as plain text (public by design)
- Secret Key: Stored using WordPress `wp_hash()` for security
- Keys retrieved only during validation process
- No keys exposed in frontend JavaScript

**Nonce Security**:
```php
// Add nonce to forms
wp_nonce_field('hc_turnstile_verify', 'hc_turnstile_nonce');

// Verify nonce during validation
if (!wp_verify_nonce($_POST['hc_turnstile_nonce'], 'hc_turnstile_verify')) {
    return false;
}
```

**Token Validation Process**:
1. Receive Turnstile response token from frontend
2. Verify nonce for security
3. Make server-side API call to Cloudflare validation endpoint
4. Cache successful validations using WordPress transients
5. Block form submission on validation failure

### Performance Optimization

**Caching Strategy**:
```php
// Cache successful validations for 5 minutes
$cache_key = 'hc_turnstile_' . wp_hash($token . $user_ip);
set_transient($cache_key, true, 5 * MINUTE_IN_SECONDS);
```

**Script Loading Optimization**:
- Conditional script loading only on relevant pages
- Async/defer attributes for Turnstile API script
- Minified custom JavaScript for production

**Fallback Handling**:
- Graceful degradation when Turnstile service unavailable
- Configurable fallback behavior (block/allow submissions)
- User notification for temporary service issues

## Functionality Specifics

### Widget Rendering Flow

1. **Form Detection**: Identify target forms based on admin settings
2. **Widget Insertion**: Add Turnstile container div before submit button
3. **Script Loading**: Enqueue Cloudflare Turnstile API script
4. **Widget Initialization**: Initialize widget with configured theme/size
5. **Response Handling**: Capture validation token in hidden form field

### Validation Flow

1. **Frontend Validation**: JavaScript prevents submission without valid token
2. **Backend Verification**: Server-side API call to Cloudflare endpoint
3. **Cache Check**: Check transient cache for recent successful validations
4. **Result Processing**: Block/allow form submission based on validation result
5. **Error Handling**: Display appropriate user feedback for failures

### API Integration Details

**Validation Endpoint**: `https://challenges.cloudflare.com/turnstile/v0/siteverify`

**Request Parameters**:
- `secret`: Secret key from admin settings
- `response`: Token from frontend widget
- `remoteip`: User's IP address (optional but recommended)

**Response Handling**:
```php
$response = wp_remote_post($validation_url, array(
    'body' => $validation_data,
    'timeout' => 10,
    'user-agent' => 'HuCommerce-Turnstile/1.0'
));

if (is_wp_error($response)) {
    // Handle connection failure
    return apply_filters('hc_turnstile_fallback_allow', false);
}
```

## UI/UX Specifications

### Admin Settings Interface

**Settings Page Integration**:
- Add new "Security" or "Turnstile" tab to existing HuCommerce admin
- Use Cherry Pick Studios SDK field types for consistency
- Real-time validation of API keys with test button
- Preview widget with different theme/size combinations

**Field Specifications**:
```php
// Example SDK field configuration
'turnstile_site_key' => array(
    'type' => 'text',
    'title' => __('Site Key', 'surbma-magyar-woocommerce'),
    'description' => __('Your Cloudflare Turnstile Site Key', 'surbma-magyar-woocommerce'),
    'required' => true
),
'turnstile_secret_key' => array(
    'type' => 'password',
    'title' => __('Secret Key', 'surbma-magyar-woocommerce'),
    'description' => __('Your Cloudflare Turnstile Secret Key (stored securely)', 'surbma-magyar-woocommerce'),
    'required' => true
)
```

### Form Integration Design

**Widget Placement**:
- Checkout: Above "Place Order" button
- Registration: Above "Register" button  
- Login: Above "Log in" button
- Consistent spacing and alignment with form styling

**Visual Integration**:
- Match WooCommerce theme styling where possible
- Responsive design for mobile devices
- Clear error messaging positioned near widget
- Loading states during validation

**Accessibility Considerations**:
- Proper ARIA labels for screen readers
- Keyboard navigation support
- High contrast mode compatibility
- Clear focus indicators

## Integration Requirements

### WooCommerce Hooks Integration

**Checkout Process**:
```php
// Add widget to checkout form
add_action('woocommerce_review_order_before_submit', array($this, 'render_turnstile_widget'), 10);

// Validate during checkout processing
add_action('woocommerce_after_checkout_validation', array($this, 'validate_checkout_turnstile'), 10, 2);
```

**Account Forms**:
```php
// Registration form
add_action('woocommerce_register_form', array($this, 'render_turnstile_widget'), 20);
add_filter('woocommerce_registration_errors', array($this, 'validate_registration_turnstile'), 10, 4);

// Login form
add_action('woocommerce_login_form', array($this, 'render_turnstile_widget'), 20);
add_filter('wp_authenticate_user', array($this, 'validate_login_turnstile'), 10, 2);
```

### WordPress Core Integration

**Options API Integration**:
- Settings stored in existing `surbma_hc_fields` option
- Seamless integration with HuCommerce settings system
- Database schema compatibility with existing structure

**Transients API Usage**:
```php
// Cache validation results
$cache_key = 'hc_turnstile_' . md5($token . $ip);
set_transient($cache_key, array(
    'success' => true,
    'timestamp' => time(),
    'ip' => $ip
), 300); // 5 minute cache
```

**Localization Support**:
- All user-facing strings wrapped in `__()` functions
- Integration with existing `surbma-magyar-woocommerce` text domain
- Support for Hungarian and English languages

### Module Loading Integration

**Conditional Loading Logic**:
```php
// In lib/modules.php - add to existing module loading logic
if (!empty($hc_gems_options['turnstile']) && $hc_gems_options['turnstile'] == '1') {
    include_once(SURBMA_HC_PLUGIN_DIR . '/modules/turnstile.php');
}
```

**Settings Integration**:
- Add Turnstile options to global `$hc_gems_options` array
- Maintain compatibility with existing settings structure
- Proper sanitization and validation of all inputs

## Performance Criteria

### Caching Requirements

**Validation Caching**:
- Successful validations cached for 5 minutes maximum
- Cache keys based on token hash and user IP
- Automatic cache cleanup for expired entries
- Memory-efficient storage using WordPress transients

**Script Loading Performance**:
- Cloudflare Turnstile API script loaded asynchronously
- Custom JavaScript minified for production
- No render-blocking resources
- Conditional loading only on required pages

### Fallback Handling

**Service Unavailability**:
```php
// Graceful fallback when Turnstile service is down
public function is_turnstile_available() {
    $test_response = wp_remote_get('https://challenges.cloudflare.com/turnstile/v0/api.js', array(
        'timeout' => 5
    ));
    
    return !is_wp_error($test_response);
}
```

**Configuration Options**:
- Admin setting to allow/block submissions during service outages
- Automatic retry mechanism for temporary failures
- User-friendly error messages for service issues
- Fallback to alternative validation methods if configured

### Error Handling

**API Failure Scenarios**:
1. **Network Timeout**: Configurable fallback behavior
2. **Invalid Credentials**: Clear admin notification
3. **Rate Limiting**: Temporary service degradation notice
4. **Invalid Token**: User-friendly re-verification prompt

**Performance Monitoring**:
- Response time tracking for API calls
- Success/failure rate monitoring
- Automatic fallback activation for poor performance
- Admin dashboard metrics for service health