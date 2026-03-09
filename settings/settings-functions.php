<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

function cps_hc_gems_nav_item_header( $nav_item_title ) {
	?>
	<div class="uk-padding-small uk-padding-remove-vertical uk-padding-remove-left uk-clearfix">
		<div class="uk-margin-remove">
			<h4 class="uk-margin-remove uk-float-left"><?php echo esc_html( $nav_item_title ); ?></h4>
		</div>
	</div>
	<hr>
	<?php
}

function cps_hc_gems_module_nav_item( $module_title, $module_option ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$module = '';
	$moduleValue = $cps_hc_gems_options[$module_option] ?? 0;
	$module_class = 1 == $moduleValue ? '' : 'uk-hidden';

	echo '<li class="' . esc_attr( $module_class ) . '"><a class="uk-offcanvas-close uk-modal-close-default"><span class="uk-margin-small-right" style="width: 100%;max-width: 20px;" uk-icon="icon: triangle-right; ratio: 1"></span> ' . esc_html( $module_title ) . '</a></li>';
}

function cps_hc_gems_form_accordion_title( $module_title, $module_option, $module_free = false, $module_new = false, $module_beta = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$module = '';
	$new = $module_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';
	$beta = $module_beta ? ' <span class="uk-badge">' . __( 'Beta', 'surbma-magyar-woocommerce' ) . '</span>' : '';
	$pro = $module_free ? '' : ' <span class="uk-badge" style="background: #fff;color: #ffd700 !important;font-weight: bold;">PRO</span>';

	$moduleValue = $cps_hc_gems_options[$module_option] ?? 0;
	$module_indicator = 1 == $moduleValue ? '<span class="module-indicator module-on" uk-tooltip="title: ' . esc_attr( __( 'Aktív modul', 'surbma-magyar-woocommerce' ) ) . '"></span>' : '<span class="module-indicator module-off" uk-tooltip="title: ' . esc_attr( __( 'Kikapcsolt modul', 'surbma-magyar-woocommerce' ) ) . '"></span>';

	$allowed_html = array(
		'span' => array(
			'class' => true,
			'uk-tooltip' => true
		)
	);

	echo '<a class="uk-accordion-title" href="#">' . wp_kses( $module_indicator, $allowed_html ) . ' ' . esc_html( $module_title ) . wp_kses_post( $new ) . wp_kses_post( $pro ) . wp_kses_post( $beta ) . '</a>';
}

function cps_hc_gems_form_modal( $modal_title, $modal_content, $modal_id ) {
	?>
	<div id="modal-<?php echo esc_attr( $modal_id ); ?>" class="uk-flex-top" uk-modal>
		<div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">
			<button class="uk-modal-close-default" type="button" uk-close></button>
			<h2 class="uk-modal-title uk-text-default uk-text-bold"><?php echo esc_html( $modal_title ); ?></h2>
			<p><?php echo esc_html( $modal_content ); ?></p>
		</div>
	</div>
	<?php
}

function cps_hc_gems_form_field_checkbox( $field_label, $field_option, $field_info = false, $field_new = false, $field_free = false, $field_default = 0, $field_li_attrs = [] ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
	);

	$li_attrs = '';
	if ( ! empty( $field_li_attrs ) && is_array( $field_li_attrs ) ) {
		foreach ( $field_li_attrs as $attr_name => $attr_value ) {
			$li_attrs .= ' ' . esc_attr( $attr_name ) . '="' . esc_attr( (string) $attr_value ) . '"';
		}
	}

	?>
	<li class="cps-form-checkbox cps-form-horizontal<?php echo esc_html( $disabled ); ?>"<?php echo $li_attrs; ?>>
		<div class="uk-form-label"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></div>
		<div class="uk-form-controls">
			<div class="switch-wrap">
				<label class="switch">
					<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default; ?>
					<input id="<?php echo esc_attr( $field_option ); ?>" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" type="checkbox" value="1" <?php checked( '1', $optionValue ); ?><?php echo esc_html( $disabled ); ?> />
					<span class="slider round"></span>
				</label>
			</div>
		</div>
	</li>
	<?php
}

function cps_hc_gems_form_field_select( $field_label, $field_option, $field_options, $field_default, $field_info = false, $field_new = false, $field_free = false ) {
	global $couponfieldposition_options;
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;
	global $shippingmethodstohide_options;
	global $legalconfirmationsposition_options;
	global $smtpport_options;
	global $smtpsecure_options;
	global $emptycartbutton_cartpage_options;
	global $emptycartbutton_checkoutpage_options;

	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
	);

	?>
	<li class="cps-form-select cps-form-horizontal cps-form-horizontal-large<?php echo esc_html( $disabled ); ?>">
		<div class="uk-form-label"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></div>
		<div class="uk-form-controls">
			<select class="uk-select" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"<?php echo esc_html( $disabled ); ?>>
				<?php
				$selected = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default;
				$p = '';
				$r = '';

				foreach ( $field_options as $option ) {
					$label = $option['label'];
					// Make default first in list
					if ( $selected == $option['value'] ) {
						$p = PHP_EOL . '<option style="padding-right: 10px;" selected="selected" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
					} else {
						$r .= PHP_EOL . '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
					}
				}
				echo wp_kses( $p, $allowed_html ) . wp_kses( $r, $allowed_html );
				?>
			</select>
		</div>
	</li>
	<?php
}

function cps_hc_gems_form_field_text( $field_label, $field_option, $field_default = '', $field_info = false, $field_new = false, $field_free = false, $field_description = false, $field_icon = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
		'code' => array(
		),
	);

	?>
	<li class="cps-form-text cps-form-horizontal cps-form-horizontal-large<?php echo esc_html( $disabled ); ?>">
		<label class="uk-form-label" for="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></label>
		<div class="uk-form-controls">
			<div class="uk-inline uk-width-1-1">
				<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default; ?>
				<?php if ( $field_icon ) { ?>
				<span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: <?php esc_attr( $field_icon ); ?>"></span>
				<?php } ?>
				<input id="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" class="uk-input" type="text" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" value="<?php echo esc_attr( wp_unslash( $optionValue ) ); ?>"<?php echo esc_html( $disabled ); ?> />
				<?php if ( $field_description ) { ?>
				<div class="uk-text-meta"><?php echo wp_kses( $field_description, $allowed_html ); ?></div>
				<?php } ?>
			</div>
		</div>
	</li>
	<?php
}

function cps_hc_gems_form_field_number( $field_label, $field_option, $field_default, $field_info = false, $field_new = false, $field_free = false, $field_description = false, $field_icon = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
		'code' => array(
		),
	);

	?>
	<li class="cps-form-number cps-form-horizontal<?php echo esc_html( $disabled ); ?>">
		<label class="uk-form-label" for="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></label>
		<div class="uk-form-controls">
			<div class="uk-inline uk-width-1-1">
				<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default; ?>
				<?php if ( $field_icon ) { ?>
				<span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: <?php esc_attr( $field_icon ); ?>"></span>
				<?php } ?>
				<input id="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" class="uk-input" type="number" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" value="<?php echo esc_attr( wp_unslash( $optionValue ) ); ?>"<?php echo esc_html( $disabled ); ?> />
				<?php if ( $field_description ) { ?>
				<div class="uk-text-meta"><?php echo wp_kses( $field_description, $allowed_html ); ?></div>
				<?php } ?>
			</div>
		</div>
	</li>
	<?php
}

function cps_hc_gems_form_field_password( $field_label, $field_option, $field_default, $field_info = false, $field_new = false, $field_free = false, $field_description = false, $field_icon = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
		'code' => array(
		),
	);

	?>
	<li class="cps-form-text cps-form-horizontal cps-form-horizontal-large<?php echo esc_html( $disabled ); ?>">
		<label class="uk-form-label" for="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></label>
		<div class="uk-form-controls">
			<div class="uk-inline uk-width-1-1">
				<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default; ?>
				<?php if ( $field_icon ) { ?>
				<span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: <?php esc_attr( $field_icon ); ?>"></span>
				<?php } ?>
				<input id="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" class="uk-input" type="password" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" value="<?php echo esc_attr( wp_unslash( $optionValue ) ); ?>"<?php echo esc_html( $disabled ); ?> />
				<?php if ( $field_description ) { ?>
				<div class="uk-text-meta"><?php echo wp_kses( $field_description, $allowed_html ); ?></div>
				<?php } ?>
			</div>
		</div>
	</li>
	<?php
}

function cps_hc_gems_form_field_textarea( $field_label, $field_option, $field_default, $field_info = false, $field_new = false, $field_free = false, $field_description = false ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$info = $field_info ? ' <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: ' . esc_attr( $field_info ) . '; pos: right"></span>' : '';
	$disabled = '';
	$new = $field_new ? ' <span class="uk-badge">' . __( 'New', 'surbma-magyar-woocommerce' ) . '</span>' : '';

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
			'uk-icon' => true,
			'uk-toggle' => true,
			'uk-tooltip' => true,
		),
		'code' => array(
		),
	);

	?>
	<li class="cps-form-textarea cps-form-horizontal cps-form-horizontal-large<?php echo esc_html( $disabled ); ?>">
		<label class="uk-form-label" for="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"><span><?php echo esc_html( $field_label ); ?><?php echo wp_kses( $info, $allowed_html ); ?><?php echo wp_kses_post( $new ); ?></span></label>
		<div class="uk-form-controls">
			<?php $optionValue = isset( $cps_hc_gems_options[$field_option] ) ? $cps_hc_gems_options[$field_option] : $field_default; ?>
			<textarea id="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[<?php echo esc_attr( $field_option ); ?>]"<?php echo esc_html( $disabled ); ?>><?php echo esc_html( wp_unslash( $optionValue ) ); ?></textarea>
			<div class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?> <?php echo wp_kses( $field_description, $allowed_html ); ?></div>
		</div>
	</li>
	<?php
}
