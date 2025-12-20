# Constant Prefix Standardization

## Summary

Standardize the naming convention for PHP constants in the HuCommerce plugin, similar to the function and variable prefix standardization that was previously completed.

## Current State

The plugin currently uses `SURBMA_HC_*` prefixed constants:

### Plugin Constants (in `surbma-magyar-woocommerce.php`)
- `SURBMA_HC_PLUGIN_VERSION`
- `SURBMA_HC_PLUGIN_DIR`
- `SURBMA_HC_PLUGIN_URL`
- `SURBMA_HC_PLUGIN_FILE`

### License Constants (in `lib/license.php`)
- `SURBMA_HC_PLUGIN_LICENSE`
- `SURBMA_HC_PREMIUM`
- `SURBMA_HC_PRO_USER`

## Target State

### Plugin Constants → CPS_HC_GEMS_*
| Current | New |
|---------|-----|
| `SURBMA_HC_PLUGIN_VERSION` | `CPS_HC_GEMS_VERSION` |
| `SURBMA_HC_PLUGIN_DIR` | `CPS_HC_GEMS_DIR` |
| `SURBMA_HC_PLUGIN_URL` | `CPS_HC_GEMS_URL` |
| `SURBMA_HC_PLUGIN_FILE` | `CPS_HC_GEMS_FILE` |
| `SURBMA_HC_PLUGIN_LICENSE` | `HC_LICENSE` |

### License Status Constants → HC_*
| Current | New |
|---------|-----|
| `SURBMA_HC_PREMIUM` | `HC_PREMIUM` |
| `SURBMA_HC_PRO_USER` | `HC_PRO_USER` |

## Scope

- **7 constants** to rename
- **97 usages** across **18 PHP files**
- All usages must be updated to maintain functionality

## Files Affected

1. `surbma-magyar-woocommerce.php` - Main plugin file (definitions + usages)
2. `lib/license.php` - License constants (definitions + usages)
3. `lib/start.php` - Usages
4. `lib/admin.php` - Usages
5. `lib/modules.php` - Usages
6. `lib/pages.php` - Usages
7. `lib/pages-global-functions.php` - Usages
8. `lib/settings.php` - Usages
9. `settings/settings-functions.php` - Usages
10. `settings/settings-validate.php` - Usages
11. `modules/*.php` - Various module files
12. `modules-hu/*.php` - Various HU module files
13. `pages/*.php` - Admin page files

## Out of Scope

- CPS SDK constants (`CPS_SDK_VERSION`, `CPS_DIR`, `CPS_URL`, `CPS_UIKIT_VERSION`) - these already follow the correct naming convention
- Documentation files in `agent-os/specs/` - these are historical references

