# Specification: Inline Module Card Functions

## Overview

| Attribute | Value |
|-----------|-------|
| **Spec ID** | 2025-11-30-inline-module-card-more-link |
| **Status** | Ready for Implementation |
| **Created** | 2025-11-30 |
| **Complexity** | Low |
| **Risk Level** | Low |

## 1. Executive Summary

This specification details the refactoring of two single-use helper functions (`cps_hc_gems_module_card_more()` and `cps_hc_gems_form_field_main()`) by inlining their code directly into the module cards loop in `settings-nav-modules.php`. After inlining, both functions will be deleted from `settings-functions.php`.

### Goals

1. **Consolidate rendering logic**: All module card UI code resides in one location
2. **Reduce function call overhead**: Eliminate unnecessary function calls for simple echo operations
3. **Improve maintainability**: Easier to understand and modify card layout when code is together
4. **Code cleanup**: Remove unused functions as part of broader refactoring effort

## 2. Current State Analysis

### 2.1 Functions to Refactor

#### `cps_hc_gems_module_card_more()`

| Property | Value |
|----------|-------|
| **Location** | `pages/settings-functions.php:50-52` |
| **Lines of Code** | 3 |
| **Usage Count** | 1 (only in module cards loop) |
| **Complexity** | Very simple - single echo statement |

**Current Implementation:**

```php
function cps_hc_gems_module_card_more( $href ) {
	echo '<a class="cps-more uk-button uk-button-text uk-button-small uk-padding-remove-horizontal uk-animation-toggle" href="https://www.hucommerce.hu/modul/' . esc_attr( $href ) . '/" target="_blank">' . esc_html__( 'Read more', 'surbma-magyar-woocommerce' ) . ' <span class="uk-animation-slide-left-small" uk-icon="icon: arrow-right"></span></a>';
}
```

#### `cps_hc_gems_form_field_main()`

| Property | Value |
|----------|-------|
| **Location** | `pages/settings-functions.php:54-75` |
| **Lines of Code** | ~20 |
| **Usage Count** | 1 (only in module cards loop) |
| **Complexity** | Medium - includes disabled state logic |

**Current Implementation:**

```php
function cps_hc_gems_form_field_main( $field_label, $field_option, $field_free = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$field = '';
	$disabled = $field_free || SURBMA_HC_PREMIUM || ( isset( $cps_hc_gems_options[$field_option] ) && 1 == $cps_hc_gems_options[$field_option] ) ? '' : ' disabled';

	?>
	<div class="cps-form-module cps-form-horizontal cps-form-checkbox<?php echo esc_html( $disabled ); ?>">
		<div class="uk-form-label uk-text-bold"><span><?php esc_html_e( $field_label, 'surbma-magyar-woocommerce' ); ?>:</span></div><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
		<div class="uk-form-controls">
			<div class="switch-wrap">
				<label class="switch">
					<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : 0; ?>
					<input id="<?php echo esc_attr( $field_option ); ?>" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" type="checkbox" value="1" <?php checked( '1', $optionValue ); ?><?php echo esc_html( $disabled ); ?> />
					<span class="slider round"></span>
				</label>
			</div>
		</div>
	</div>
	<?php
}
```

### 2.2 Current Call Site

**Location:** `pages/settings-nav-modules.php:252-258`

```php
<?php if ( ! empty( $module['doc_slug'] ) ) : ?>
	<p class="uk-margin-auto-top uk-margin-remove-bottom"><?php cps_hc_gems_module_card_more( $module['doc_slug'] ); ?></p>
<?php endif; ?>
</div>
<div class="uk-card-footer uk-background-muted">
	<?php cps_hc_gems_form_field_main( __( 'Activate module', 'surbma-magyar-woocommerce' ), $module['option_key'], $is_free ); ?>
</div>
```

### 2.3 Variables Available in Loop Context

| Variable | Source | Purpose |
|----------|--------|---------|
| `$module['doc_slug']` | Module config | Documentation URL slug |
| `$module['option_key']` | Module config | Settings option key for checkbox |
| `$is_free` | `cps_hc_gems_is_free_module_type()` | Already computed - determines if module is free |
| `$cps_hc_gems_options` | Global | **Needs to be declared** - plugin settings array |

## 3. Technical Specification

### 3.1 Files to Modify

| File | Action | Description |
|------|--------|-------------|
| `pages/settings-nav-modules.php` | Modify | Add global declaration + inline both functions |
| `pages/settings-functions.php` | Modify | Delete both functions |

### 3.2 Implementation Details

#### Step 1: Add Global Declaration

**File:** `pages/settings-nav-modules.php`
**Location:** After line 23 (with other global declarations)

Add the following line:

```php
global $cps_hc_gems_options;
```

#### Step 2: Inline `cps_hc_gems_module_card_more()`

**File:** `pages/settings-nav-modules.php`
**Location:** Replace line 253

**Before:**

```php
<?php if ( ! empty( $module['doc_slug'] ) ) : ?>
	<p class="uk-margin-auto-top uk-margin-remove-bottom"><?php cps_hc_gems_module_card_more( $module['doc_slug'] ); ?></p>
<?php endif; ?>
```

**After:**

```php
<?php if ( ! empty( $module['doc_slug'] ) ) : ?>
	<p class="uk-margin-auto-top uk-margin-remove-bottom"><a class="cps-more uk-button uk-button-text uk-button-small uk-padding-remove-horizontal uk-animation-toggle" href="https://www.hucommerce.hu/modul/<?php echo esc_attr( $module['doc_slug'] ); ?>/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span class="uk-animation-slide-left-small" uk-icon="icon: arrow-right"></span></a></p>
<?php endif; ?>
```

#### Step 3: Inline `cps_hc_gems_form_field_main()`

**File:** `pages/settings-nav-modules.php`
**Location:** Replace line 257

**Before:**

```php
<div class="uk-card-footer uk-background-muted">
	<?php cps_hc_gems_form_field_main( __( 'Activate module', 'surbma-magyar-woocommerce' ), $module['option_key'], $is_free ); ?>
</div>
```

**After:**

```php
<div class="uk-card-footer uk-background-muted">
	<?php
	$disabled = $is_free || SURBMA_HC_PREMIUM || ( isset( $cps_hc_gems_options[ $module['option_key'] ] ) && 1 == $cps_hc_gems_options[ $module['option_key'] ] ) ? '' : ' disabled';
	$optionValue = isset( $cps_hc_gems_options[ $module['option_key'] ] ) ? $cps_hc_gems_options[ $module['option_key'] ] : 0;
	?>
	<div class="cps-form-module cps-form-horizontal cps-form-checkbox<?php echo esc_html( $disabled ); ?>">
		<div class="uk-form-label uk-text-bold"><span><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?>:</span></div>
		<div class="uk-form-controls">
			<div class="switch-wrap">
				<label class="switch">
					<input id="<?php echo esc_attr( $module['option_key'] ); ?>" name="surbma_hc_fields[<?php echo esc_attr( $module['option_key'] ); ?>]" type="checkbox" value="1" <?php checked( '1', $optionValue ); ?><?php echo esc_html( $disabled ); ?> />
					<span class="slider round"></span>
				</label>
			</div>
		</div>
	</div>
</div>
```

#### Step 4: Delete Functions from `settings-functions.php`

**File:** `pages/settings-functions.php`
**Action:** Delete lines 50-75 (both functions)

Functions to delete:
- `cps_hc_gems_module_card_more()` (lines 50-52)
- `cps_hc_gems_form_field_main()` (lines 54-75)

## 4. Behavior Requirements

### 4.1 "Read More" Link Behavior

| Requirement | Specification |
|-------------|---------------|
| Conditional rendering | Only show when `$module['doc_slug']` is not empty |
| URL format | `https://www.hucommerce.hu/modul/{doc_slug}/` |
| Target | Open in new tab (`target="_blank"`) |
| Link text | Translated "Read more" string |
| Animation | Arrow icon with slide animation on hover |

### 4.2 Module Activation Toggle Behavior

| Requirement | Specification |
|-------------|---------------|
| Label | Fixed translated string: "Activate module" |
| Disabled state logic | `$is_free || SURBMA_HC_PREMIUM || (option already enabled)` |
| Form field name | `surbma_hc_fields[{option_key}]` |
| Input ID | `{option_key}` |
| Checked state | Based on stored option value |

### 4.3 Disabled State Logic Explanation

The toggle is **enabled** (not disabled) when ANY of these conditions is true:
1. `$is_free` - Module is a free module type
2. `SURBMA_HC_PREMIUM` - User has Pro license
3. Option is already set to `1` - Preserve existing enabled state for legacy users

Otherwise, the toggle is **disabled** (Pro module, no license, not previously enabled).

## 5. Testing Requirements

### 5.1 Visual Verification

- [ ] Module cards display correctly with all labels and badges
- [ ] "Read more" links appear only for modules with `doc_slug`
- [ ] "Read more" links open correct documentation URLs in new tab
- [ ] Arrow animation works on hover
- [ ] Activation toggles display correctly
- [ ] Disabled toggles appear grayed out

### 5.2 Functional Verification

- [ ] Toggle state saves correctly when form is submitted
- [ ] Free modules can be toggled without Pro license
- [ ] Pro modules are disabled without Pro license
- [ ] Previously enabled Pro modules remain toggleable (legacy behavior)

### 5.3 Regression Testing

- [ ] All existing module cards render identically to before refactoring
- [ ] No PHP errors or warnings in error log
- [ ] Form submission works correctly
- [ ] Module settings are preserved after save

## 6. Migration Notes

### 6.1 Breaking Changes

**None** - This is a pure refactoring with no external API changes.

### 6.2 Backward Compatibility

- No changes to stored options
- No changes to module configuration format
- No changes to HTML output structure or CSS classes

## 7. Estimated Impact

| Metric | Before | After | Change |
|--------|--------|-------|--------|
| Function count | 2 | 0 | -2 functions |
| Lines in `settings-functions.php` | ~392 | ~366 | -26 lines |
| Lines in `settings-nav-modules.php` | ~745 | ~760 | +15 lines (net) |
| Total lines | ~1137 | ~1126 | -11 lines |

## 8. Definition of Done

- [ ] Global `$cps_hc_gems_options` declared in `settings-nav-modules.php`
- [ ] `cps_hc_gems_module_card_more()` inlined and function deleted
- [ ] `cps_hc_gems_form_field_main()` inlined and function deleted
- [ ] All module cards render correctly
- [ ] No PHP errors or linting issues
- [ ] Form saves work correctly
- [ ] Visual verification passed

