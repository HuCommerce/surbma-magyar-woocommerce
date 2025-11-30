# Tasks: Inline Module Card Functions

## Spec Reference

- **Spec ID:** 2025-11-30-inline-module-card-more-link
- **Spec File:** [spec.md](./spec.md)
- **Complexity:** Low
- **Estimated Tasks:** 4

---

## Task Overview

| # | Task | File | Status |
|---|------|------|--------|
| 1 | Add global declaration | `pages/settings-nav-modules.php` | ✅ Complete |
| 2 | Inline "Read more" link | `pages/settings-nav-modules.php` | ✅ Complete |
| 3 | Inline activation toggle | `pages/settings-nav-modules.php` | ✅ Complete |
| 4 | Delete unused functions | `pages/settings-functions.php` | ✅ Complete |

---

## Task Details

### Task 1: Add Global Declaration

**Status:** ✅ Complete

**File:** `pages/settings-nav-modules.php`

**Description:**
Add the `$cps_hc_gems_options` global declaration to make it available in the file scope for the inlined toggle logic.

**Location:** After line 23 (with other global declarations)

**Implementation:**

Add this line after the existing global declarations:

```php
global $cps_hc_gems_options;
```

**Context - Current globals section (lines 13-23):**

```php
global $couponfieldposition_options;
global $returntoshopcartposition_options;
global $returntoshopcheckoutposition_options;
global $shippingmethodstohide_options;
global $legalconfirmationsposition_options;
global $smtpport_options;
global $smtpsecure_options;
global $emptycartbutton_cartpage_options;
global $emptycartbutton_checkoutpage_options;
global $productpricehistory_statisticslinkdisplay_options;
global $catalogmode_productpricedisplay_options;
```

**Acceptance Criteria:**
- [x] `global $cps_hc_gems_options;` added after line 23
- [x] Variable is accessible within the module cards loop

---

### Task 2: Inline "Read more" Link

**Status:** ✅ Complete

**File:** `pages/settings-nav-modules.php`

**Description:**
Replace the `cps_hc_gems_module_card_more()` function call with inline HTML that renders the "Read more" link directly.

**Location:** Line 253 (inside the module cards loop)

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

**Acceptance Criteria:**
- [x] Function call replaced with inline HTML
- [x] Link URL uses `$module['doc_slug']` with proper escaping
- [x] Conditional rendering preserved (only show when `doc_slug` exists)
- [x] All CSS classes and attributes match original output

---

### Task 3: Inline Activation Toggle

**Status:** ✅ Complete

**File:** `pages/settings-nav-modules.php`

**Description:**
Replace the `cps_hc_gems_form_field_main()` function call with inline PHP/HTML that renders the activation toggle directly.

**Location:** Line 257 (inside the module cards loop, in card footer)

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

**Key Variables:**
- `$is_free` - Already available in loop (computed earlier)
- `$module['option_key']` - From module config
- `$cps_hc_gems_options` - Global (added in Task 1)

**Acceptance Criteria:**
- [x] Function call replaced with inline PHP/HTML
- [x] Disabled state logic preserved exactly
- [x] Uses existing `$is_free` variable from loop
- [x] Form field names and IDs match original output
- [x] Toggle checked state computed correctly

---

### Task 4: Delete Unused Functions

**Status:** ✅ Complete

**File:** `pages/settings-functions.php`

**Description:**
Remove the two functions that are no longer used after inlining.

**Functions to Delete:**

1. **`cps_hc_gems_module_card_more()`** - Lines 50-52
2. **`cps_hc_gems_form_field_main()`** - Lines 54-75

**Before (lines 50-75):**

```php
function cps_hc_gems_module_card_more( $href ) {
	echo '<a class="cps-more uk-button uk-button-text uk-button-small uk-padding-remove-horizontal uk-animation-toggle" href="https://www.hucommerce.hu/modul/' . esc_attr( $href ) . '/" target="_blank">' . esc_html__( 'Read more', 'surbma-magyar-woocommerce' ) . ' <span class="uk-animation-slide-left-small" uk-icon="icon: arrow-right"></span></a>';
}

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

**After:**

Both functions completely removed from file.

**Acceptance Criteria:**
- [x] `cps_hc_gems_module_card_more()` function deleted
- [x] `cps_hc_gems_form_field_main()` function deleted
- [x] No syntax errors in file after deletion
- [x] Surrounding code unaffected

---

## Verification Checklist

After all tasks are complete, verify:

### Visual Verification
- [x] Module cards display correctly with all labels and badges
- [x] "Read more" links appear only for modules with `doc_slug`
- [x] "Read more" links open correct documentation URLs in new tab
- [x] Arrow animation works on hover
- [x] Activation toggles display correctly
- [x] Disabled toggles appear grayed out

### Functional Verification
- [x] Toggle state saves correctly when form is submitted
- [x] Free modules can be toggled without Pro license
- [x] Pro modules are disabled without Pro license
- [x] Previously enabled Pro modules remain toggleable

### Regression Testing
- [x] No PHP errors or warnings in error log
- [x] Form submission works correctly
- [x] Module settings are preserved after save

---

## Implementation Order

**Recommended execution order:**

1. ✅ **Task 1** first - Ensures global variable is available
2. ✅ **Task 2** second - Simple inline replacement
3. ✅ **Task 3** third - More complex inline replacement (depends on Task 1)
4. ✅ **Task 4** last - Only after Tasks 2 & 3 are verified working

**Note:** Tasks 2 and 3 can be done in either order, but both must complete before Task 4.

---

## Rollback Plan

If issues are encountered:

1. Revert changes to `pages/settings-nav-modules.php`
2. Revert changes to `pages/settings-functions.php`
3. Functions will be restored and original behavior maintained

Since this is a pure refactoring with no database changes, rollback is straightforward via git revert.

