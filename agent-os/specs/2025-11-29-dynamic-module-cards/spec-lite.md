# Spec Lite: Dynamic Module Cards

## Overview
Enhance module management by centralizing all module configuration data in `lib/modules.php`, enabling dynamic generation of module cards in the admin area instead of hard-coding them in `pages/settings-nav-modules.php`.

## Current State
- Module cards (lines 204-623 in `settings-nav-modules.php`) are hard-coded HTML (28 cards)
- Module loading logic in `lib/modules.php` contains partial configuration (28 modules)
- Adding new modules requires editing two separate files

## Proposed Change
- Extend the modules array in `lib/modules.php` to include all data needed for UI rendering
- Replace hard-coded module cards (lines 204-623) with a loop that reads from the centralized configuration
- The modules array becomes the **single source of truth**

## Scope
- **In scope**: Module card grid (lines 204-623 in `settings-nav-modules.php`)
- **Out of scope**: Module settings pages (lines 627-1102) - future project

## Key Decisions

### 1. Module Card Display
- ALL modules in the array will have cards displayed
- No display-only modules (all cards represent actual modules)
- Modules with `force_enable: true` still show cards (card controls other functions)

### 2. Display Order
- PRO modules displayed first, then Free modules
- Maintain current order within those groups
- No `priority` property needed yet

### 3. License/Type Mapping for `data-license` Attribute
- **"pro"**: `pro`, `pro_hu`, `legacy`, `legacy_hu`
- **"free"**: `free`, `free_hu`
- Legacy modules displayed identically to others (no deprecated badge)

### 4. Translation Handling
- Store translations inline in the array:
  ```php
  'title' => __( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' )
  ```

### 5. Documentation Links
- Store as `doc_slug` property
- Display link only if `doc_slug` is provided
- If no `doc_slug`, skip the documentation link element

### 6. "New" Badge Logic (Automatic)
- Use `version_added` property (optional):
  ```php
  'version_added' => '3.5.0'
  ```
- System finds the highest `version_added` value among all modules
- All modules with that highest version get the "new" badge
- When a newer module is added, previous "new" modules automatically lose the badge
- If `version_added` is not set, module never gets "new" badge

## New Module Array Properties

Each module will need these additional properties for card rendering:

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `title` | string | Yes | Translated module title for card heading |
| `description` | string | Yes | Translated module description |
| `tags` | array | Yes | Category tags: `['checkout', 'conversion']` |
| `doc_slug` | string | No | Documentation URL slug |
| `version_added` | string | No | Plugin version when module was added (for "new" badge) |

### Existing Properties (unchanged)
- `option_key` - Settings key for activation toggle
- `type` - Module type (free, pro, legacy, etc.)
- `directory` - Module file directory
- `file` - Custom filename (optional)
- `frontend_only` - Load only on frontend (optional)
- `force_enable` - Always load module (optional)

## Available Tags
Based on current UI filters:
- `product`
- `cart`
- `checkout`
- `payments`
- `legal`
- `conversion`
- `other`

## Status
- [x] Spec initialized
- [x] Requirements gathered
- [x] Spec document written
- [x] Tasks created
- [x] Implementation complete
- [x] Verification complete
