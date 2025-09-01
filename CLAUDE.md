# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

HuCommerce (surbma-magyar-woocommerce) is a WordPress plugin that provides Hungarian WooCommerce extensions and enhancements. The plugin offers both free and Pro versions with various modules for e-commerce functionality tailored to Hungarian businesses.

**Key Details:**
- Plugin Name: HuCommerce | Magyar WooCommerce kiegészítések
- Version: 2025.1.6
- Text Domain: surbma-magyar-woocommerce
- Main Plugin File: surbma-magyar-woocommerce.php
- Requires WooCommerce 4.6+, tested up to 9.7
- WordPress compatibility: 5.3+, tested up to 6.7
- PHP requirement: 7.4+

## Architecture & Code Structure

### Core Files Structure
```
surbma-magyar-woocommerce.php    # Main plugin file with basic setup
lib/
├── start.php                    # Main initialization and includes
├── admin.php                    # Admin interface functionality
├── license.php                  # Pro license management
└── modules.php                  # Module loading logic

modules/                         # General modules (free & Pro)
modules-hu/                      # Hungary-specific modules
pages/                          # Admin settings pages
assets/                         # CSS, JS, and images
cps-sdk/                        # Cherry Pick Studios SDK
translations/                   # Premium plugin translations
woocommerce/                    # WooCommerce template overrides
```

### Module System
The plugin uses a modular architecture where functionality is split into separate modules:

1. **Hungary-specific modules** (modules-hu/):
   - `hu-format-fix.php` - Name order fixes for Hungarian locale
   - `no-county.php` - Hide county field
   - `autofill-city.php` - Auto-fill city based on postal code
   - `mask-checkout-fields.php` - Field masking/validation
   - `product-price-history.php` - Price history tracking (EU regulation compliance)
   - `translations.php` - Hungarian translations

2. **General modules** (modules/):
   - `tax-number.php` - Tax number field functionality
   - `checkout.php` - Checkout page modifications
   - `plus-minus-buttons.php` - Quantity increment/decrement buttons
   - `catalog-mode.php` - Catalog mode (disable purchasing)
   - `smtp.php` - SMTP configuration
   - Many others for various WooCommerce enhancements

### Global Configuration
- Plugin options stored in `surbma_hc_fields` WordPress option
- Global variable `$hc_gems_options` contains all settings
- Pro functionality controlled by `SURBMA_HC_PREMIUM` constant

## Development Commands

This plugin doesn't use a build system like npm, composer, webpack, or gulp. It's a traditional WordPress plugin with direct PHP files.

**No build/compile process needed** - changes to PHP files are immediately active.

**Testing:**
- No automated test suite present
- Manual testing required in WordPress/WooCommerce environment
- Test with Hungarian locale settings for full functionality

**Code Quality:**
- Uses WordPress Coding Standards
- Follows PHP_CodeSniffer rules
- No automated linting setup found

## Key Constants & Globals

```php
SURBMA_HC_PLUGIN_VERSION    # Plugin version
SURBMA_HC_PLUGIN_DIR        # Plugin directory path
SURBMA_HC_PLUGIN_URL        # Plugin URL
SURBMA_HC_PLUGIN_FILE       # Main plugin file path
SURBMA_HC_PREMIUM           # Pro license status
$hc_gems_options            # Global settings array
```

## Important Development Notes

1. **WooCommerce Dependency**: Plugin requires WooCommerce to be active
2. **Hungarian Locale**: Many features only activate when site language is Hungarian
3. **Pro Features**: Many modules require Pro license (SURBMA_HC_PREMIUM constant)
4. **HPOS/COT Compatibility**: Plugin declares compatibility with WooCommerce High-Performance Order Storage
5. **Block Incompatibility**: Declares incompatibility with WooCommerce Cart & Checkout blocks

## Module Loading Logic

Modules are conditionally loaded in `lib/modules.php` based on:
- Individual module settings in `$hc_gems_options`
- Pro license status (`SURBMA_HC_PREMIUM`)
- Legacy user compatibility checks
- Admin vs frontend context

## Template System

Plugin can override WooCommerce templates through the filter system in `lib/start.php`. Custom templates are stored in the `woocommerce/` directory.

## Admin Interface

Admin functionality is handled by `lib/admin.php` and various page files in `pages/` directory. Uses the Cherry Pick Studios SDK for consistent admin UI.