# Specification: Page Rendering Standardization

## 1. Overview

### 1.1 Purpose
Eliminate the need for separate `page-*.php` files by creating a unified page rendering system with a centralized configuration array. This follows the successful pattern established in `lib/modules.php` for module standardization.

### 1.2 Goals
- **DRY Principle**: Remove duplicate boilerplate code across 6 page files
- **Maintainability**: Single source of truth for all page definitions
- **Developer Experience**: Add new pages by simply adding a config entry
- **Performance**: On-demand loading of menu content files
- **Flexibility**: Support active/inactive/hidden page states

### 1.3 Non-Goals
- Changing the visual appearance of pages
- Modifying the content renderer files (`menu-*.php`)
- Altering the settings system (`settings.php`, `settings-*.php`)

---

## 2. Current State Analysis

### 2.1 Existing Page Files
| File | Status | Function Name |
|------|--------|---------------|
| `page-modules.php` | Active | `cps_hc_gems_modules_page()` |
| `page-directory.php` | Active | `cps_hc_gems_directory_page()` |
| `page-license.php` | Active | `cps_hc_gems_license_page()` |
| `page-information.php` | Active | `cps_hc_gems_information_page()` |
| `page-news.php` | Disabled | `cps_hc_gems_news_page()` |
| `page-offers.php` | Disabled | `cps_hc_gems_offers_page()` |

### 2.2 Current Page Template Structure
Every page file follows this identical pattern:

```php
function cps_hc_gems_{page}_page() {
    include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');
    include_once( SURBMA_HC_PLUGIN_DIR . '/pages/menu-{page}.php');

    cps_hc_gems_page_header();
    ?>
    <div id="cps-settings">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-medium uk-visible@m">
                <?php cps_hc_gems_page_sidebar(); ?>
            </div>
            <div class="uk-width-expand">
                <?php cps_hc_gems_page_notifications(); ?>
                <div class="cps-card uk-card uk-card-default uk-card-hover uk-margin-bottom">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom">{TITLE}</h3>
                                <p class="uk-text-meta uk-margin-remove-top">{DESCRIPTION}</p>
                            </div>
                            <?php cps_hc_gems_page_mobile_nav(); ?>
                        </div>
                    </div>
                    <div class="uk-card-body uk-background-muted">
                        <?php cps_hc_gems_render_menu_{page}(); ?>
                    </div>
                    <?php cps_hc_gems_page_card_footer(); ?>
                </div>
                <?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
            </div>
        </div>
    </div>
    <?php
    cps_hc_gems_page_footer();
}
```

### 2.3 Variable Elements Per Page
Only 5 elements differ between pages:
1. **Title** - Card header title (translatable)
2. **Description** - Meta text below title
3. **Menu file** - Content file to include (`menu-{page}.php`)
4. **Renderer function** - Function to call for content
5. **Icon** - UIkit icon for sidebar navigation

### 2.4 Current Menu Registration (lib/admin.php)
```php
add_action( 'admin_menu', function() {
    global $cps_hc_gems_main_page;
    global $cps_hc_gems_modules_page;
    // ... more globals ...

    $cps_hc_gems_main_page = add_menu_page(
        'HuCommerce',
        'HuCommerce',
        'manage_options',
        'cps_hc_gems_modules',
        'cps_hc_gems_modules_page',
        'dashicons-welcome-widgets-menus',
        '58'
    );

    $cps_hc_gems_modules_page = add_submenu_page(
        'cps_hc_gems_modules',
        __( 'HuCommerce Modules', 'surbma-magyar-woocommerce' ),
        __( 'Modules', 'surbma-magyar-woocommerce' ),
        'manage_options',
        'cps_hc_gems_modules',
        'cps_hc_gems_modules_page'
    );
    // ... more manual registrations ...
}, 98 );
```

### 2.5 Current Navigation Functions (pages-global-functions.php)
Navigation functions use global variables to track page hooks and determine active states:

```php
function cps_hc_gems_pages_nav() {
    $screen = get_current_screen();
    global $cps_hc_gems_offers_page;
    global $cps_hc_gems_directory_page;
    // ...
    
    $active_offers_menu = $cps_hc_gems_offers_page == $screen->base ? 'uk-active' : '';
    // ... hardcoded menu items with icons ...
}
```

---

## 3. Technical Design

### 3.1 New File: `lib/pages.php`

#### 3.1.1 Pages Configuration Function
```php
/**
 * Get the pages configuration array
 *
 * This is the single source of truth for all admin page definitions.
 * The first item in the array is the main/parent menu.
 * All subsequent items are submenu items.
 * Array order determines sidebar display order.
 *
 * @return array The pages configuration array
 */
function cps_hc_gems_get_pages_config() {
    return [
        // First item = main menu parent
        'modules' => [
            'title' => __( 'Modules', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Modules', 'surbma-magyar-woocommerce' ),
            'card_title' => 'HuCommerce ' . __( 'Modules', 'surbma-magyar-woocommerce' ),
            'description' => __( 'Manage and configure HuCommerce modules', 'surbma-magyar-woocommerce' ),
            'icon' => 'thumbnails',
            'menu_slug' => 'cps_hc_gems_modules',
            'menu_file' => 'menu-modules.php',
            'renderer' => 'cps_hc_gems_render_menu_modules',
            'status' => 'active',
        ],
        'offers' => [
            'title' => __( 'Offers', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Offers', 'surbma-magyar-woocommerce' ),
            'card_title' => __( 'Offers', 'surbma-magyar-woocommerce' ),
            'description' => 'Ajánlatok csak a HuCommerce felhasználóknak válogatott partnerektől.',
            'icon' => 'star',
            'menu_slug' => 'cps_hc_gems_offers',
            'menu_file' => 'menu-offers.php',
            'renderer' => 'cps_hc_gems_render_menu_offers',
            'status' => 'inactive',
        ],
        'directory' => [
            'title' => __( 'Directory', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Directory', 'surbma-magyar-woocommerce' ),
            'card_title' => 'HuCommerce ' . __( 'Directory', 'surbma-magyar-woocommerce' ),
            'description' => 'Hasznos linkek minden WooCommerce webáruház tulajdonosnak. <strong>FIGYELEM!</strong> A linkek partner linkek, amik után jutalékot kaphatunk.',
            'icon' => 'list',
            'menu_slug' => 'cps_hc_gems_directory',
            'menu_file' => 'menu-directory.php',
            'renderer' => 'cps_hc_gems_render_menu_directory',
            'status' => 'active',
        ],
        'news' => [
            'title' => __( 'Latest News', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Latest News', 'surbma-magyar-woocommerce' ),
            'card_title' => __( 'Latest News', 'surbma-magyar-woocommerce' ),
            'description' => 'Legújabb híreink a HuCommerce bővítménnyel kapcsolatban.',
            'icon' => 'rss',
            'menu_slug' => 'cps_hc_gems_news',
            'menu_file' => 'menu-news.php',
            'renderer' => 'cps_hc_gems_render_menu_news',
            'status' => 'inactive',
        ],
        'license' => [
            'title' => __( 'License management', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce License Management', 'surbma-magyar-woocommerce' ),
            'card_title' => __( 'License management', 'surbma-magyar-woocommerce' ),
            'description' => 'HuCommerce Pro aktiválása az API kulccsal.',
            'icon' => 'lock', // Dynamic: 'unlock' when active license
            'icon_dynamic' => true,
            'menu_slug' => 'cps_hc_gems_license',
            'menu_file' => 'menu-license.php',
            'renderer' => 'cps_hc_gems_render_menu_license',
            'status' => 'active',
        ],
        'information' => [
            'title' => __( 'Information', 'surbma-magyar-woocommerce' ),
            'page_title' => __( 'HuCommerce Information', 'surbma-magyar-woocommerce' ),
            'card_title' => __( 'Information', 'surbma-magyar-woocommerce' ),
            'description' => 'Fontos információk a HuCommerce bővítménnyel és az installációval kapcsolatban.',
            'icon' => 'info',
            'menu_slug' => 'cps_hc_gems_information',
            'menu_file' => 'menu-information.php',
            'renderer' => 'cps_hc_gems_render_menu_information',
            'status' => 'active',
        ],
    ];
}
```

#### 3.1.2 Configuration Schema

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `title` | string | Yes | Menu item title in sidebar |
| `page_title` | string | Yes | Browser/WordPress page title |
| `card_title` | string | Yes | Card header h3 title |
| `description` | string | Yes | Meta description below card title |
| `icon` | string | Yes | UIkit icon name |
| `icon_dynamic` | bool | No | If true, icon may change based on state |
| `menu_slug` | string | Yes | WordPress menu slug |
| `menu_file` | string | Yes | Content file to include (relative to `/pages/`) |
| `renderer` | string | Yes | Function name to call for content |
| `status` | string | Yes | `active`, `inactive`, or `hidden` |

#### 3.1.3 Status Behavior

| Status | Menu Registered | Visible in Sidebar | Accessible via URL |
|--------|-----------------|--------------------|--------------------|
| `active` | ✅ Yes | ✅ Yes | ✅ Yes |
| `inactive` | ❌ No | ❌ No | ❌ No |
| `hidden` | ✅ Yes | ❌ No | ✅ Yes |

#### 3.1.4 Unified Page Rendering Function
```php
/**
 * Render a page based on its configuration
 *
 * @param string $page_key The page key from the config array
 * @return void
 */
function cps_hc_gems_render_page( $page_key ) {
    $pages = cps_hc_gems_get_pages_config();
    
    if ( ! isset( $pages[ $page_key ] ) ) {
        return;
    }
    
    $page = $pages[ $page_key ];
    
    // Load required files
    include_once SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php';
    include_once SURBMA_HC_PLUGIN_DIR . '/pages/' . $page['menu_file'];
    
    cps_hc_gems_page_header();
    ?>
    <div id="cps-settings">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-medium uk-visible@m">
                <?php cps_hc_gems_page_sidebar(); ?>
            </div>
            <div class="uk-width-expand">
                <?php cps_hc_gems_page_notifications(); ?>
                <div class="cps-card uk-card uk-card-default uk-card-hover uk-margin-bottom">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom"><?php echo esc_html( $page['card_title'] ); ?></h3>
                                <p class="uk-text-meta uk-margin-remove-top"><?php echo wp_kses_post( $page['description'] ); ?></p>
                            </div>
                            <?php cps_hc_gems_page_mobile_nav(); ?>
                        </div>
                    </div>
                    <div class="uk-card-body uk-background-muted">
                        <?php
                        if ( function_exists( $page['renderer'] ) ) {
                            call_user_func( $page['renderer'] );
                        }
                        ?>
                    </div>
                    <?php cps_hc_gems_page_card_footer(); ?>
                </div>
                <?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
            </div>
        </div>
    </div>
    <?php
    cps_hc_gems_page_footer();
}
```

#### 3.1.5 Page Callback Generator
```php
/**
 * Generate a page callback function for WordPress menu registration
 *
 * @param string $page_key The page key from config
 * @return callable The callback function
 */
function cps_hc_gems_get_page_callback( $page_key ) {
    return function() use ( $page_key ) {
        cps_hc_gems_render_page( $page_key );
    };
}
```

#### 3.1.6 Helper Functions
```php
/**
 * Get pages filtered by status
 *
 * @param array $statuses Array of status values to include
 * @return array Filtered pages config
 */
function cps_hc_gems_get_pages_by_status( $statuses = ['active'] ) {
    $pages = cps_hc_gems_get_pages_config();
    return array_filter( $pages, function( $page ) use ( $statuses ) {
        return in_array( $page['status'], $statuses, true );
    });
}

/**
 * Get registerable pages (active + hidden)
 *
 * @return array Pages that should be registered in WordPress admin
 */
function cps_hc_gems_get_registerable_pages() {
    return cps_hc_gems_get_pages_by_status( ['active', 'hidden'] );
}

/**
 * Get visible pages for sidebar navigation
 *
 * @return array Pages that should appear in sidebar
 */
function cps_hc_gems_get_visible_pages() {
    return cps_hc_gems_get_pages_by_status( ['active'] );
}

/**
 * Get the icon for a page, handling dynamic icons
 *
 * @param array $page_config The page configuration
 * @return string The icon name
 */
function cps_hc_gems_get_page_icon( $page_config ) {
    // Handle dynamic license icon
    if ( isset( $page_config['icon_dynamic'] ) && $page_config['icon_dynamic'] ) {
        if ( $page_config['menu_slug'] === 'cps_hc_gems_license' ) {
            return 'active' === SURBMA_HC_PLUGIN_LICENSE ? 'unlock' : 'lock';
        }
    }
    return $page_config['icon'];
}
```

---

### 3.2 Modifications to `lib/admin.php`

#### 3.2.1 Include Pages Configuration
Add at the top with other includes:
```php
include_once( SURBMA_HC_PLUGIN_DIR . '/lib/pages.php');
```

#### 3.2.2 Replace Manual Menu Registration
Replace the current `add_action( 'admin_menu', ...)` block with:

```php
// Admin options menu - auto-generated from pages config
add_action( 'admin_menu', function() {
    $pages = cps_hc_gems_get_registerable_pages();
    $page_hooks = [];
    $is_first = true;
    $parent_slug = '';
    
    foreach ( $pages as $page_key => $page ) {
        if ( $is_first ) {
            // First page becomes the main menu
            $page_hooks[ $page_key ] = add_menu_page(
                'HuCommerce',
                'HuCommerce',
                'manage_options',
                $page['menu_slug'],
                cps_hc_gems_get_page_callback( $page_key ),
                'dashicons-welcome-widgets-menus',
                '58'
            );
            $parent_slug = $page['menu_slug'];
            $is_first = false;
        }
        
        // All pages (including first) get a submenu entry
        $page_hooks[ $page_key ] = add_submenu_page(
            $parent_slug,
            $page['page_title'],
            $page['title'],
            'manage_options',
            $page['menu_slug'],
            cps_hc_gems_get_page_callback( $page_key )
        );
    }
    
    // Store page hooks globally for navigation and script loading
    $GLOBALS['cps_hc_gems_page_hooks'] = $page_hooks;
    
    // WooCommerce admin page connection
    if ( function_exists( 'wc_admin_connect_page' ) ) {
        wc_admin_connect_page(
            array(
                'id'        => 'cps_hc_gems_modules',
                'screen_id' => 'woocommerce_page_cps_hc_gems_modules',
                'title'     => 'HuCommerce'
            )
        );
    }
}, 98 );
```

#### 3.2.3 Update Script/Style Enqueuing
Replace the hardcoded page hook checks with:

```php
add_action( 'admin_enqueue_scripts', function( $hook ) {
    $page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
    $cps_hc_gems_page = in_array( $hook, $page_hooks, true );
    
    // Load plugin scripts & styles for plugin pages
    if ( $cps_hc_gems_page ) {
        add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
        wp_enqueue_style( 'surbma-hc-admin', SURBMA_HC_PLUGIN_URL . '/assets/css/admin.css', array(), SURBMA_HC_PLUGIN_VERSION );
    }
    
    // ... rest of Help Scout Beacon code ...
} );
```

#### 3.2.4 Remove Old Includes
Remove these lines:
```php
// Remove these:
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-modules.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-directory.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-information.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-license.php');
```

---

### 3.3 Modifications to `pages/pages-global-functions.php`

#### 3.3.1 Refactor `cps_hc_gems_pages_nav()`
Replace the current function with:

```php
/**
 * Render the pages navigation items in sidebar
 * Uses pages config for dynamic generation
 */
function cps_hc_gems_pages_nav() {
    $screen = get_current_screen();
    $page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
    $pages = cps_hc_gems_get_visible_pages();
    
    // Skip first page (modules) as it has its own nav function
    $is_first = true;
    foreach ( $pages as $page_key => $page ) {
        if ( $is_first ) {
            $is_first = false;
            continue; // Skip modules page
        }
        
        // Skip license and information - they have their own nav section
        if ( in_array( $page_key, ['license', 'information'], true ) ) {
            continue;
        }
        
        $hook = $page_hooks[ $page_key ] ?? '';
        $active_class = ( $hook === $screen->base ) ? 'uk-active' : '';
        $icon = cps_hc_gems_get_page_icon( $page );
        
        printf(
            '<li class="%s"><a href="%s"><span class="uk-margin-small-right" uk-icon="icon: %s"></span> %s</a></li>',
            esc_attr( $active_class ),
            esc_url( admin_url( 'admin.php?page=' . $page['menu_slug'] ) ),
            esc_attr( $icon ),
            esc_html( $page['card_title'] )
        );
    }
}
```

#### 3.3.2 Refactor `cps_hc_gems_page_license_nav()`
Replace with:

```php
/**
 * Render the license and information navigation items
 */
function cps_hc_gems_page_license_nav() {
    $screen = get_current_screen();
    $page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
    $pages = cps_hc_gems_get_pages_config();
    
    $nav_pages = ['license', 'information'];
    
    foreach ( $nav_pages as $page_key ) {
        if ( ! isset( $pages[ $page_key ] ) || $pages[ $page_key ]['status'] !== 'active' ) {
            continue;
        }
        
        $page = $pages[ $page_key ];
        $hook = $page_hooks[ $page_key ] ?? '';
        $active_class = ( $hook === $screen->base ) ? 'uk-active' : '';
        $icon = cps_hc_gems_get_page_icon( $page );
        
        printf(
            '<li class="%s"><a href="%s"><span class="uk-margin-small-right" uk-icon="icon: %s"></span> %s</a></li>',
            esc_attr( $active_class ),
            esc_url( admin_url( 'admin.php?page=' . $page['menu_slug'] ) ),
            esc_attr( $icon ),
            esc_html( $page['title'] )
        );
    }
}
```

#### 3.3.3 Remove Global Variable Declarations
Remove the old global variable usage and replace with the new `$GLOBALS['cps_hc_gems_page_hooks']` approach.

---

## 4. File Changes Summary

### 4.1 Files to Create
| File | Purpose |
|------|---------|
| `lib/pages.php` | Pages configuration array and unified rendering functions |

### 4.2 Files to Modify
| File | Changes |
|------|---------|
| `lib/admin.php` | Include pages.php, replace manual menu registration, update script enqueuing |
| `pages/pages-global-functions.php` | Refactor navigation functions to use pages config |

### 4.3 Files to Delete
| File | Reason |
|------|--------|
| `pages/page-modules.php` | Replaced by unified rendering |
| `pages/page-directory.php` | Replaced by unified rendering |
| `pages/page-license.php` | Replaced by unified rendering |
| `pages/page-information.php` | Replaced by unified rendering |
| `pages/page-news.php` | Replaced by unified rendering |
| `pages/page-offers.php` | Replaced by unified rendering |

### 4.4 Files Unchanged
| File | Reason |
|------|--------|
| `pages/menu-*.php` | Content renderers remain separate (on-demand loading) |
| `pages/settings.php` | Settings system unchanged |
| `pages/settings-*.php` | Settings system unchanged |

---

## 5. Migration Strategy

### 5.1 Phase 1: Create New System
1. Create `lib/pages.php` with configuration and rendering functions
2. Test configuration array structure

### 5.2 Phase 2: Update Admin
1. Modify `lib/admin.php` to use new config-based registration
2. Remove old page file includes
3. Test menu registration works correctly

### 5.3 Phase 3: Update Navigation
1. Refactor `pages-global-functions.php` navigation functions
2. Test sidebar navigation and active states

### 5.4 Phase 4: Cleanup
1. Delete old `page-*.php` files
2. Final testing of all pages

---

## 6. Testing Checklist

### 6.1 Menu Registration
- [ ] Main HuCommerce menu appears in WordPress admin
- [ ] All active submenu items appear
- [ ] Inactive pages do not appear in menu
- [ ] Hidden pages accessible via direct URL but not in menu
- [ ] Menu order matches config array order

### 6.2 Page Rendering
- [ ] Modules page renders correctly
- [ ] Directory page renders correctly
- [ ] License page renders correctly
- [ ] Information page renders correctly
- [ ] Card titles display correctly
- [ ] Descriptions display correctly
- [ ] Content renderers execute correctly

### 6.3 Navigation
- [ ] Sidebar shows correct active state
- [ ] Icons display correctly
- [ ] License icon changes based on license status
- [ ] Mobile navigation works

### 6.4 Scripts/Styles
- [ ] Admin CSS loads on plugin pages
- [ ] Admin CSS does not load on other pages
- [ ] Help Scout Beacon loads correctly

### 6.5 Performance
- [ ] Menu files only loaded when page is accessed
- [ ] No errors in error log
- [ ] Page load times comparable to before

---

## 7. Future Considerations

### 7.1 Adding New Pages
To add a new page:
1. Create `pages/menu-{newpage}.php` with content renderer function
2. Add entry to `cps_hc_gems_get_pages_config()` array
3. Set `status` to `active`
4. Done - no other files need modification

### 7.2 Potential Enhancements
- Capability-based page visibility (different pages for different user roles)
- Page-specific scripts/styles configuration
- Conditional page visibility based on plugin settings

---

## 8. Dependencies

### 8.1 WordPress Functions
- `add_menu_page()`
- `add_submenu_page()`
- `get_current_screen()`
- `admin_url()`

### 8.2 Plugin Constants
- `SURBMA_HC_PLUGIN_DIR`
- `SURBMA_HC_PLUGIN_URL`
- `SURBMA_HC_PLUGIN_FILE`
- `SURBMA_HC_PLUGIN_VERSION`
- `SURBMA_HC_PLUGIN_LICENSE`

### 8.3 Existing Functions (unchanged)
- `cps_hc_gems_page_header()`
- `cps_hc_gems_page_footer()`
- `cps_hc_gems_page_sidebar()`
- `cps_hc_gems_page_notifications()`
- `cps_hc_gems_page_mobile_nav()`
- `cps_hc_gems_page_card_footer()`
- `cps_admin_footer()`

