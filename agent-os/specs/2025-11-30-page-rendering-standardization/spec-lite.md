# Spec Lite: Page Rendering Standardization

## Overview
Eliminate separate `page-*.php` files by creating a unified page rendering function with a centralized configuration array, following the same pattern used for module standardization in `lib/modules.php`.

## Current State Analysis

### Existing Page Files (to be eliminated)
- `page-modules.php`
- `page-directory.php`
- `page-license.php`
- `page-information.php`
- `page-news.php` (currently disabled)
- `page-offers.php` (currently disabled)

### Current Structure (Identical Pattern)
Each page file follows this exact template with only 5 variable elements:
1. **Title**: Translatable string (e.g., "HuCommerce Directory")
2. **Description**: Meta text explaining the page purpose
3. **Menu file**: `menu-{page}.php` (content renderer)
4. **Renderer function**: `cps_hc_gems_render_menu_{page}()`
5. **Icon**: UIkit icon name for sidebar navigation

## Technical Decisions

### 1. Configuration Approach
- **Decision**: Create new `lib/pages.php` file with centralized configuration array
- **Pattern**: Follow `lib/modules.php` structure

### 2. Menu File Loading Strategy
- **Decision**: Keep menu files separate, loaded on-demand when page is rendered
- **Rationale**: Better memory performance - only load what's needed

### 3. Page Registration
- **Decision**: Auto-generate all WordPress admin menu items from config
- **Structure**: 
  - First item in array = main/parent menu
  - All following items = submenu items
  - Array order = sidebar display order

### 4. Status Parameter (Three Options)
| Status | Behavior |
|--------|----------|
| `active` | Registered and visible in sidebar navigation |
| `inactive` | Completely hidden - not registered in WordPress admin at all |
| `hidden` | Registered (accessible via direct URL) but hidden from sidebar |

### 5. Card Styling
- **Decision**: Standardized - all pages use identical card styling
- **Note**: The `uk--card-large` class on offers page was deprecated and removed

### 6. Backward Compatibility
- **Decision**: Update menu registration to use new unified function directly
- **Note**: No wrapper functions needed

### 7. Navigation Functions Refactoring
- **Decision**: Refactor `pages-global-functions.php` navigation functions to use pages config
- **Scope**: Includes sidebar navigation, active state detection, and icon rendering

### 8. Icons
- **Decision**: Icons are part of the pages config array
- **Format**: UIkit icon names (e.g., `thumbnails`, `list`, `lock`, `info`)

## Proposed Configuration Structure

```php
// lib/pages.php
function cps_hc_gems_get_pages_config() {
    return [
        // First item = main menu parent
        'modules' => [
            'title' => __( 'Modules', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Modules', 'surbma-magyar-woocommerce' ),
            'description' => __( 'Manage and configure HuCommerce modules', 'surbma-magyar-woocommerce' ),
            'icon' => 'thumbnails',
            'menu_slug' => 'cps_hc_gems_modules',
            'menu_file' => 'menu-modules.php',
            'renderer' => 'cps_hc_gems_render_menu_modules',
            'status' => 'active', // active|inactive|hidden
        ],
        'offers' => [
            'title' => __( 'Offers', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Offers', 'surbma-magyar-woocommerce' ),
            'description' => 'Ajánlatok csak a HuCommerce felhasználóknak...',
            'icon' => 'star',
            'menu_slug' => 'cps_hc_gems_offers',
            'menu_file' => 'menu-offers.php',
            'renderer' => 'cps_hc_gems_render_menu_offers',
            'status' => 'inactive',
        ],
        'directory' => [
            'title' => __( 'Directory', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Directory', 'surbma-magyar-woocommerce' ),
            'description' => 'Hasznos linkek minden WooCommerce webáruház tulajdonosnak...',
            'icon' => 'list',
            'menu_slug' => 'cps_hc_gems_directory',
            'menu_file' => 'menu-directory.php',
            'renderer' => 'cps_hc_gems_render_menu_directory',
            'status' => 'active',
        ],
        // ... etc
    ];
}
```

## Implementation Scope

### Files to Create
- `lib/pages.php` - Pages configuration and unified rendering function

### Files to Modify
- `lib/admin.php` - Replace manual menu registration with config-based auto-generation
- `pages/pages-global-functions.php` - Refactor navigation functions to use pages config

### Files to Delete (after migration)
- `pages/page-modules.php`
- `pages/page-directory.php`
- `pages/page-license.php`
- `pages/page-information.php`
- `pages/page-news.php`
- `pages/page-offers.php`

### Files to Keep (unchanged)
- `pages/menu-*.php` - Content renderer files (loaded on-demand)
- `pages/settings.php`
- `pages/settings-*.php`

## Performance Benefits

1. **Reduced file I/O**: Single config file vs multiple page files
2. **On-demand loading**: Menu files only loaded when their page is accessed
3. **Centralized maintenance**: Single source of truth for all page definitions
4. **Easier future additions**: Add new pages by adding config entry only

## Related Files Reference
- `lib/modules.php` - Reference implementation for config-based approach
- `lib/admin.php` - Current page registration code
- `pages/pages-global-functions.php` - Navigation helper functions
