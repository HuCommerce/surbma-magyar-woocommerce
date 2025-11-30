# Spec: Inline Module Card Functions

## Status: Ready for Spec Writing

## Summary

Inline two single-use functions directly into the module cards loop in `settings-nav-modules.php`, then delete the original functions from `settings-functions.php`. This consolidates all module card rendering logic in one place.

## Scope

### Functions to Inline and Remove

| Function | Location | Lines | Usage Count |
|----------|----------|-------|-------------|
| `cps_hc_gems_module_card_more()` | `settings-functions.php:50-52` | 3 | 1 (only in loop) |
| `cps_hc_gems_form_field_main()` | `settings-functions.php:54-75` | ~20 | 1 (only in loop) |

### Files to Modify

1. **`pages/settings-nav-modules.php`**
   - Add `global $cps_hc_gems_options;` to existing global declarations (around line 13-23)
   - Replace `cps_hc_gems_module_card_more()` call with inline HTML (line 253)
   - Replace `cps_hc_gems_form_field_main()` call with inline HTML/PHP (line 257)

2. **`pages/settings-functions.php`**
   - Delete `cps_hc_gems_module_card_more()` function (lines 50-52)
   - Delete `cps_hc_gems_form_field_main()` function (lines 54-75)

## Requirements Gathered

### Q1: What should happen to original functions?
**A: Delete entirely** - Both functions are single-use and should be removed after inlining.

### Q2: What's driving this change?
**A: Multiple factors:**
- Code simplification - having all card rendering logic in one place
- Reducing function call overhead for simple operations
- Part of a broader refactoring effort

### Q3: Link configuration behavior?
**A: Keep as-is** - Only show "Read more" link when `doc_slug` exists (conditional rendering).

### Q4: Disabled state logic for form field?
**A: Keep exact same logic** - Reuse existing `$is_free` variable already computed in the loop.

### Q5: Global variable handling?
**A: Ensure availability** - Add `global $cps_hc_gems_options;` declaration to `settings-nav-modules.php` alongside other global declarations.

### Q6: Label handling for activate toggle?
**A: Fixed translated string** - Keep as `__( 'Activate module', 'surbma-magyar-woocommerce' )` for all modules.

## Current Implementation Reference

### `cps_hc_gems_module_card_more()` (to be inlined)

```php
function cps_hc_gems_module_card_more( $href ) {
	echo '<a class="cps-more uk-button uk-button-text uk-button-small uk-padding-remove-horizontal uk-animation-toggle" href="https://www.hucommerce.hu/modul/' . esc_attr( $href ) . '/" target="_blank">' . esc_html__( 'Read more', 'surbma-magyar-woocommerce' ) . ' <span class="uk-animation-slide-left-small" uk-icon="icon: arrow-right"></span></a>';
}
```

### `cps_hc_gems_form_field_main()` (to be inlined)

```php
function cps_hc_gems_form_field_main( $field_label, $field_option, $field_free = false ) {
	global $cps_hc_gems_options;

	$field = '';
	$disabled = $field_free || SURBMA_HC_PREMIUM || ( isset( $cps_hc_gems_options[$field_option] ) && 1 == $cps_hc_gems_options[$field_option] ) ? '' : ' disabled';

	?>
	<div class="cps-form-module cps-form-horizontal cps-form-checkbox<?php echo esc_html( $disabled ); ?>">
		<div class="uk-form-label uk-text-bold"><span><?php esc_html_e( $field_label, 'surbma-magyar-woocommerce' ); ?>:</span></div>
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

### Current Loop Call Site (lines 252-258)

```php
<?php if ( ! empty( $module['doc_slug'] ) ) : ?>
	<p class="uk-margin-auto-top uk-margin-remove-bottom"><?php cps_hc_gems_module_card_more( $module['doc_slug'] ); ?></p>
<?php endif; ?>
</div>
<div class="uk-card-footer uk-background-muted">
	<?php cps_hc_gems_form_field_main( __( 'Activate module', 'surbma-magyar-woocommerce' ), $module['option_key'], $is_free ); ?>
</div>
```

## Variables Available in Loop Context

- `$module['doc_slug']` - Documentation URL slug
- `$module['option_key']` - Settings option key
- `$is_free` - Already computed via `cps_hc_gems_is_free_module_type( $module['type'] )`
- `$cps_hc_gems_options` - Needs to be declared as global

## Motivation

- **Code simplification**: All module card rendering logic consolidated in one file
- **Reduced overhead**: Eliminates function call overhead for simple echo operations
- **Maintainability**: Easier to understand and modify card layout when all code is together
- **Broader refactoring**: Part of ongoing effort to optimize module system code
