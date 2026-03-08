# Options Storage Optimization Plan

## Summary

Reduce the size of `surbma_hc_fields` option by not saving values that equal their defaults. This includes ~200+ disabled translation checkboxes and any other fields matching default values.

## Approach

- **Don't save default values** - Only store values that differ from defaults
- **Translations** - Only save enabled translations (`1`). Missing key = disabled (default)
- **Future saves only** - No migration; existing data cleaned naturally when users save

## Tasks

### Task 1: Save Spec Documentation

Create `agent-os/specs/2026-01-31-options-storage-optimization/` with:
- `plan.md` - This plan
- `shape.md` - Shaping decisions and context

### Task 2: Create Centralized Defaults File

Create `settings/settings-defaults.php` with a function that returns all default values.

Note: Translation keys (`translations-*`) are NOT included in defaults - their absence means disabled.

### Task 3: Modify Validation Function

Update `settings/settings-validate.php`:

1. Include the new defaults file
2. At the end of `cps_hc_gems_fields_validate()`, before returning:
   - Get defaults via `cps_hc_gems_get_defaults()`
   - Remove any key from `$input` where value equals default
   - Remove any `translations-*` key where value is `0`

### Task 4: Verify Reading Code

Check that option reading handles missing keys correctly:

**Files to verify:**
- `settings/settings-functions.php` - Form field rendering already uses defaults
- `modules/*.php` - All module files that read options
- `lib/start.php` - Global option loading

### Task 5: Update Form Field Functions

Update `settings/settings-functions.php` to use centralized defaults.

## Files to Modify

| File | Change |
|------|--------|
| `settings/settings-defaults.php` | **NEW** - Centralized defaults |
| `settings/settings-validate.php` | Filter out default values before saving |
| `settings/settings-functions.php` | Use centralized defaults for rendering |

## Verification

1. **Before optimization**: Note the current size of `surbma_hc_fields` in database
2. **After implementation**:
   - Open plugin settings, make no changes, click Save
   - Check database - option should be significantly smaller
   - Verify all settings still work correctly in UI
   - Test that enabled translations still load
   - Test that disabled translations remain disabled

## Expected Impact

- **Before**: Option stores all ~200+ translation keys + all fields = large serialized array
- **After**: Only stores non-default values = much smaller array
- **Typical savings**: 80-90% reduction if user hasn't customized many settings
