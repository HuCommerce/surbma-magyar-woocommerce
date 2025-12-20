# HuCommerce Technical Stack

## Overview

HuCommerce is built as a traditional WordPress plugin using PHP, following WordPress coding standards and best practices. The plugin does not use modern build tools or frameworks, maintaining simplicity and compatibility.

---

## Core Technologies

### Backend

#### PHP
- **Version**: PHP 7.4+ (minimum requirement)
- **Usage**: Core plugin logic, module functionality, admin interface
- **Standards**: WordPress Coding Standards
- **Code Quality**: PHP_CodeSniffer compliance

#### WordPress
- **Minimum Version**: WordPress 5.3+
- **Tested Up To**: WordPress 6.8
- **APIs Used**:
  - Plugin API (hooks and filters)
  - Options API (settings storage)
  - Settings API (admin interface)
  - Template system (WooCommerce template overrides)
  - Translation API (i18n/l10n)

#### WooCommerce
- **Minimum Version**: WooCommerce 4.6+
- **Tested Up To**: WooCommerce 10.1
- **Compatibility**:
  - HPOS (High-Performance Order Storage) compatible
  - Cart & Checkout Blocks: Declared incompatible (uses traditional templates)
- **APIs Used**:
  - Checkout API
  - Cart API
  - Product API
  - Order API
  - Template system

---

## Frontend Technologies

### JavaScript
- **Libraries**:
  - jQuery (WordPress bundled version)
  - jQuery Mask Plugin (for field masking)
  - Custom JavaScript for:
    - City auto-fill (`autofill.js`)
    - Field validation
    - Cart updates
    - Checkout enhancements

### CSS
- **Approach**: Traditional CSS (no preprocessors)
- **Files**:
  - `assets/css/admin.css` - Admin interface styles
  - Inline styles for specific modules
- **Framework**: None (vanilla CSS)

---

## Architecture

### Plugin Structure

```
surbma-magyar-woocommerce/
├── surbma-magyar-woocommerce.php  # Main plugin file
├── lib/                            # Core library files
│   ├── start.php                  # Initialization
│   ├── admin.php                  # Admin interface
│   ├── license.php                # Pro license management
│   └── modules.php                # Module loading system
├── modules/                        # General modules
├── modules-hu/                     # Hungary-specific modules
├── pages/                          # Admin settings pages
├── assets/                         # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── cps-sdk/                        # Cherry Pick Studios SDK
├── translations/                   # Premium plugin translations
└── woocommerce/                    # WooCommerce template overrides
```

### Module System

**Architecture**: Modular plugin architecture with conditional loading

**Module Types**:
- `free` - Available to all users
- `free_hu` - Free Hungary-specific modules
- `pro` - Requires Pro license
- `pro_hu` - Pro Hungary-specific modules
- `legacy` - Legacy modules (for backward compatibility)

**Loading Logic**:
- Modules are loaded conditionally based on:
  - User settings (`$hc_gems_options`)
  - Pro license status (`HC_LICENSE` constant)
  - Legacy user compatibility
  - Admin vs frontend context

**Module Configuration**:
- Defined in `lib/modules.php`
- Each module has:
  - Option key (settings identifier)
  - Type (free/pro/legacy)
  - Directory location
  - Optional file name override
  - Optional frontend-only flag

---

## Dependencies

### WordPress Core Dependencies
- WordPress Plugin API
- WordPress Options API
- WordPress Settings API
- WordPress Translation API
- WordPress Admin UI

### WooCommerce Dependencies
- WooCommerce core plugin (required)
- WooCommerce template system
- WooCommerce hooks and filters

### Third-Party Libraries

#### Cherry Pick Studios SDK
- **Location**: `cps-sdk/`
- **Purpose**: Admin interface framework
- **Version**: 8.15.3
- **Components**:
  - UIkit CSS framework
  - UIkit JavaScript
  - UIkit Icons
  - Persist Admin Notices Dismissal (PAND)

#### jQuery Mask Plugin
- **Location**: `assets/js/jquery.mask.js`
- **Purpose**: Input field masking (phone, tax number, postal code)
- **Usage**: Field validation and formatting

---

## Data Storage

### WordPress Options API
- **Primary Option**: `surbma_hc_fields`
- **Global Variable**: `$hc_gems_options`
- **Storage**: WordPress `wp_options` table
- **Structure**: Associative array of module settings

### Custom Database Tables
- **Product Price History**: Custom table for price tracking (EU compliance)
- **Purpose**: Store historical price data for consumer protection

---

## Configuration & Constants

### Plugin Constants
```php
CPS_HC_GEMS_VERSION         // Plugin version
CPS_HC_GEMS_DIR             // Plugin directory path
CPS_HC_GEMS_URL             // Plugin URL
CPS_HC_GEMS_FILE            // Main plugin file path
CPS_HC_GEMS_DIRNAME         // Plugin folder 
CPS_HC_GEMS_PLUGIN_NAME     // Plugin name
CPS_HC_GEMS_PLUGIN_URL      // Plugin URL
HC_LICENSE                  // License status (active, inactive, invalid, free)
```

### Global Variables
```php
$hc_gems_options            // Global settings array
```

---

## Build System & Development Tools

### Build System
- **Status**: None (traditional WordPress plugin)
- **Reason**: Direct PHP file execution, no compilation needed
- **Deployment**: Direct file upload or WordPress plugin installer

### Development Tools
- **Code Quality**: PHP_CodeSniffer
- **Standards**: WordPress Coding Standards
- **Version Control**: Git
- **Testing**: Manual testing (no automated test suite currently)

### No Build Tools Used
- ❌ npm/yarn
- ❌ Composer (for dependencies)
- ❌ Webpack/Gulp/Grunt
- ❌ Sass/Less
- ❌ TypeScript

---

## Internationalization (i18n)

### Translation System
- **Text Domain**: `surbma-magyar-woocommerce`
- **Domain Path**: `/languages/`
- **Primary Language**: Hungarian (hu_HU)
- **Translation Files**: `.po` and `.mo` files (standard WordPress)

### Premium Plugin Translations
- **Location**: `translations/`
- **Format**: `.l10n.php` (WordPress 6.5+ format)
- **Supported Plugins**:
  - Restrict Content Pro
  - WooCommerce API Manager (Kestrel)
  - WooCommerce Memberships
  - WooCommerce Subscriptions

### Multilingual Support
- **WPML**: Compatible
- **Polylang**: Compatible
- **Translation Management**: WordPress.org translation system

---

## Security

### Security Practices
- Direct access prevention (`defined( 'ABSPATH' ) || exit`)
- Data sanitization (WordPress functions)
- Data validation (input/output)
- Nonce verification for forms
- Capability checks for admin functions
- Escaping output (WordPress escaping functions)

### License Management
- Pro license validation via API
- License status stored securely
- DEV mode for development environments

---

## Performance Considerations

### Optimization Strategies
- Conditional module loading (only active modules)
- Frontend-only module loading (skip in admin)
- Efficient database queries
- Minimal JavaScript dependencies
- CSS optimization (no unused styles)

### Caching Compatibility
- WordPress object cache compatible
- Compatible with popular caching plugins
- No aggressive caching of dynamic content

---

## Compatibility

### WordPress Compatibility
- **Minimum**: 5.3
- **Tested Up To**: 6.8
- **Future**: Continuous compatibility testing

### WooCommerce Compatibility
- **Minimum**: 4.6
- **Tested Up To**: 10.1
- **HPOS**: Declared compatible
- **Blocks**: Cart & Checkout blocks declared incompatible

### PHP Compatibility
- **Minimum**: PHP 7.4
- **Recommended**: PHP 8.0+
- **Future**: PHP 8.1+ compatibility maintained

### Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers supported
- Responsive design considerations

---

## Development Environment

### Local Development
- **Requirements**:
  - WordPress 5.3+
  - WooCommerce 4.6+
  - PHP 7.4+
  - MySQL/MariaDB
  - Hungarian locale (for full functionality)

### DEV Mode
- **Purpose**: Development and testing without Pro license
- **Activation**: Automatic on dev/local subdomains
- **Features**: Full Pro functionality in development

---

## Future Technical Considerations

### Potential Improvements
- Automated testing suite (PHPUnit)
- Composer for dependency management
- Modern JavaScript (ES6+)
- CSS preprocessor (Sass)
- Build system for asset optimization
- REST API endpoints
- Webhook support

### Migration Path
- Maintain backward compatibility
- Gradual adoption of modern tools
- No breaking changes for existing users

---

## Summary

HuCommerce follows a **traditional WordPress plugin architecture** with:
- ✅ Simple, maintainable codebase
- ✅ No build system complexity
- ✅ Direct PHP execution
- ✅ WordPress-native patterns
- ✅ Modular architecture
- ✅ Strong compatibility focus

This approach ensures:
- Easy maintenance
- Quick deployment
- Broad compatibility
- Low barrier to entry for contributors
- Reliable performance

