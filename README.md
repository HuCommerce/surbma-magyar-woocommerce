HuCommerce - Magyar kiegészítések WooCommerce webáruházakhoz
============================================================

Hasznos javítások és kiegészítések a magyar WooCommerce webáruházakhoz.

## Project Overview

HuCommerce (surbma-magyar-woocommerce) is a WordPress plugin that provides Hungarian WooCommerce extensions and enhancements. The plugin offers both free and Pro versions with various modules for e-commerce functionality tailored to Hungarian businesses.

## Project Standards

### PHP Coding Standards

- Use the `cps_hc_gems_` prefix for all non-anonymous functions.

## Developer Guide

### Adding New Admin Pages

The plugin uses a centralized page configuration system. To add a new admin page:

**Step 1:** Create a menu file at `pages/menu-{pagename}.php` with the content renderer function:

```php
<?php
defined( 'ABSPATH' ) || exit;

function cps_hc_gems_render_menu_{pagename}() {
    ?>
    <!-- Your page content here -->
    <?php
}
```

**Step 2:** Add the page configuration to `lib/pages.php` in the `cps_hc_gems_get_pages_config()` function:

```php
'{pagename}' => [
    'title' => __( 'Menu Title', 'surbma-magyar-woocommerce' ),
    'page_title' => __( 'HuCommerce Page Title', 'surbma-magyar-woocommerce' ),
    'card_title' => __( 'Card Header Title', 'surbma-magyar-woocommerce' ),
    'description' => 'Description text below the card title.',
    'icon' => 'star',  // UIkit icon name
    'menu_slug' => 'cps_hc_gems_{pagename}',
    'menu_file' => 'menu-{pagename}.php',
    'renderer' => 'cps_hc_gems_render_menu_{pagename}',
    'status' => 'active',  // active | inactive | hidden
],
```

**Step 3:** Done! The page will automatically appear in the admin menu.

#### Page Status Options

| Status | Menu Registered | Visible in Sidebar | Accessible via URL |
|--------|-----------------|--------------------|--------------------|
| `active` | ✅ Yes | ✅ Yes | ✅ Yes |
| `inactive` | ❌ No | ❌ No | ❌ No |
| `hidden` | ✅ Yes | ❌ No | ✅ Yes |

#### Configuration Keys Reference

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `title` | string | Yes | Menu item title in sidebar |
| `page_title` | string | Yes | Browser/WordPress page title |
| `card_title` | string | Yes | Card header h3 title |
| `description` | string | Yes | Meta description below card title |
| `icon` | string | Yes | UIkit icon name (e.g., `star`, `list`, `info`) |
| `icon_dynamic` | bool | No | Set `true` if icon changes based on state |
| `menu_slug` | string | Yes | WordPress menu slug |
| `menu_file` | string | Yes | Content file relative to `/pages/` |
| `renderer` | string | Yes | Function name to call for content |
| `status` | string | Yes | `active`, `inactive`, or `hidden` |

## Project Mission

See [mission.md](./agent-os/product/mission.md) for detailed Project Mission documentation.

## Project Roadmap

See [roadmap.md](./agent-os/product/roadmap.md) for detailed Project Roadmap documentation.

## Project Tech Stack

See [tech-stack.md](./agent-os/product/tech-stack.md) for detailed Project Tech Stack documentation.
