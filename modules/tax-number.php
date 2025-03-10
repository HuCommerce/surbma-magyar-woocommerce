<?php

/**
 * Module: Tax number field
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Adding Tax number field
add_filter( 'woocommerce_billing_fields', function( $fields ) {
	if ( 'hidden' != get_option( 'woocommerce_checkout_company_field' ) ) {
		$fields['billing_tax_number'] = array(
			'label' 		=> __( 'Tax number', 'surbma-magyar-woocommerce' ),
			'required' 		=> false,
			'class' 		=> array( 'form-row-wide' ),
			'priority' 		=> 30,
			'clear' 		=> true
		);
	}
	return $fields;
} );

// Adding placeholder to Tax number field conditionally
add_filter( 'woocommerce_checkout_fields' , function( $fields ) {
	// Get the settings array
	global $hc_gems_options;

	$taxnumberplaceholderValue = $hc_gems_options['taxnumberplaceholder'] ?? 0;

	if ( 1 == $taxnumberplaceholderValue ) {
		$fields['billing']['billing_tax_number']['placeholder'] = __( 'Tax number', 'surbma-magyar-woocommerce' );
	}

	return $fields;
}, 20, 1 );

// Adding custom validation message for Tax number field on Checkout page
add_action( 'woocommerce_checkout_process', function() {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false );

	// Init the Billing Tax number check process
	cps_wcgems_hc_billing_tax_number_check();
} );

// Adding custom validation message for Tax number field on My Account -> Addresses page
add_action( 'woocommerce_after_save_address_validation', function( $user_id, $address_type ) {
	// Only proceed if this is the billing address form
	if ( 'billing' !== $address_type ) {
		return;
	}

	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce', false );

	// Init the Billing Tax number check process
	cps_wcgems_hc_billing_tax_number_check();
}, 10, 2 );

// Billing Tax number check process
function cps_wcgems_hc_billing_tax_number_check() {
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' ) != false ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';
	$billing_company = !empty( $_POST['billing_company'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_company'] ) ) : '';
	$billing_company_check = !empty( $_POST['billing_company_check'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_company_check'] ) ) : 0;
	$billing_tax_number = !empty( $_POST['billing_tax_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_tax_number'] ) ) : '';

	// Add error notice, if Tax number field is empty, but it should be filled out
	if ( 'hidden' != $woocommercecheckoutcompanyfieldValue && ( !empty( $billing_company ) || 1 == $billing_company_check || 'required' == $woocommercecheckoutcompanyfieldValue ) && empty( $billing_tax_number ) ) {
		$field_label = __( 'Tax number', 'surbma-magyar-woocommerce' );
		/* translators: %s: Field label */
		$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		/* translators: %s: Field label */
		$noticeError = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		wc_add_notice( $noticeError, 'error' );
	}
}

// Updating Tax number user meta on Checkout page
add_action( 'woocommerce_checkout_update_user_meta', function( $customer_id ) {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false );

	$billing_tax_number = !empty( $_POST['billing_tax_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_tax_number'] ) ) : '';
	update_user_meta( $customer_id, 'billing_tax_number', $billing_tax_number );
} );

// Adding editable Tax number field on edit order page
add_filter( 'woocommerce_admin_billing_fields' , function( $fields ) {
	global $the_order;

	$fields['tax_number'] = array(
		'label' => __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => '',
	);

	return $fields;
} );

// Replacement value for Billing & Shipping address on Thank you page.
// add_filter( 'woocommerce_get_order_address', function( $address, $type, $order ) {
// 	$address['tax_number'] = __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $order->get_meta( '_billing_tax_number' );
// 	return $address;
// }, 10, 3 );

// Adding {tax_number} as a new "replacement" field
add_filter( 'woocommerce_localisation_address_formats', function( $formats ) {
	foreach ( $formats as $key => &$format ) {
		$format .= "\n{tax_number}";
	}
	return $formats;
} );

// Replacement for the new {tax_number} field
add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ) {
	$taxnumber = isset( $args['tax_number'] ) ? $args['tax_number'] : '';
	$replacements['{tax_number}'] = $taxnumber;
	return $replacements;
}, 10, 2 );

// Adding Tax number to My Account -> Addresses page
add_filter( 'woocommerce_my_account_my_address_formatted_address', function( $address, $customer_id, $address_type ) {
	$taxnumber = get_user_meta( $customer_id, 'billing_tax_number', true );
	$address['tax_number'] = 'billing' == $address_type && '' != $taxnumber ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : '';
	return $address;
}, 10, 3 );

// Adding Tax number to Billing address on Thank you page and admin Preview
add_filter( 'woocommerce_order_formatted_billing_address', function( $address, $wc_order ) {
	$taxnumber = $wc_order->get_meta( '_billing_tax_number' );
	$address['tax_number'] = '' != $taxnumber ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : '';
	return $address;
}, 10, 2 );

// Removing Tax number from Shipping address on Thank you page
add_filter( 'woocommerce_order_formatted_shipping_address', function( $address ) {
	$address['tax_number'] = '';
	return $address;
} );

// Adding Tax number to user profile
add_filter( 'woocommerce_customer_meta_fields', function( $profileFieldArray ) {
	$fieldData = array(
		'label'			=> __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'description'   => ''
	);
	$profileFieldArray['billing']['fields']['billing_tax_number'] = $fieldData;
	return $profileFieldArray;
} );

// Custom JavaScript codes
add_action( 'wp_footer', function() {
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' ) != false ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';

	if ( 'hidden' == $woocommercecheckoutcompanyfieldValue || ( ! is_checkout() && ! is_wc_endpoint_url( 'edit-address' ) ) ) {
		return;
	}

	// Get the settings array
	global $hc_gems_options;

	$moduleCheckoutValue = $hc_gems_options['module-checkout'] ?? 0;
	$billingcompanycheckValue = 1 == $moduleCheckoutValue && isset( $hc_gems_options['billingcompanycheck'] ) ? $hc_gems_options['billingcompanycheck'] : 0;
	$companytaxnumberpairValue = 1 == $moduleCheckoutValue && isset( $hc_gems_options['companytaxnumberpair'] ) ? $hc_gems_options['companytaxnumberpair'] : 0;

	?>
<script id="cps-hc-wcgems-tax-number">
jQuery(document).ready(function($){
	// Add required sign and remove the "not required" text from billing_tax_number_field
	$('#billing_tax_number_field label').append( ' <abbr class="required" title="required">*</abbr>' );
	$('#billing_tax_number_field label span').hide();

	<?php if ( 0 == $billingcompanycheckValue && 1 == $companytaxnumberpairValue && 'optional' == $woocommercecheckoutcompanyfieldValue ) { ?>
		$('#billing_tax_number_field label abbr').hide();
		$('#billing_tax_number_field label span').show();
	<?php } ?>

	<?php if ( 'required' == $woocommercecheckoutcompanyfieldValue ) { ?>
		// Add required sign and remove the "not required" text from billing_tax_number_field
		$('#billing_tax_number_field').addClass('validate-required');
		$('#billing_tax_number_field label abbr').show();
		$('#billing_tax_number_field label span').hide();
	<?php } ?>

	// Fix for previous version, that saved '- N/A -'' value if billing_tax_number was empty
	if ( $('#billing_tax_number').val() == '- N/A -' ){
		$('#billing_tax_number').val('');
	}

	<?php if ( 0 == $billingcompanycheckValue && 'optional' == $woocommercecheckoutcompanyfieldValue ) { ?>
	// Check Company field value
	$('#billing_company').keyup(function() {
		if ( $(this).val().length == 0 ) {
			$('#billing_tax_number_field').removeClass('validate-required');
			<?php if ( 0 == $companytaxnumberpairValue ) { ?>
				// If Company is empty, hide Tax number
				$('#billing_tax_number_field').hide();

				// If Company is empty, empty Tax number
				$('#billing_tax_number').val('');

				// Set Tax number field to invalid, as it is empty again
				$('#billing_tax_number_field').removeClass('woocommerce-validated');
				$('#billing_tax_number_field').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			<?php } ?>
			<?php if ( 1 == $companytaxnumberpairValue ) { ?>
				$('#billing_tax_number_field').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
				$('#billing_tax_number_field label abbr').hide();
				$('#billing_tax_number_field label span').show();
			<?php } ?>
		} else {
			<?php if ( 0 == $companytaxnumberpairValue ) { ?>
				$('#billing_tax_number_field').show();
			<?php } ?>
				// Add required sign and remove the "not required" text from billing_tax_number_field
				$('#billing_tax_number_field').addClass('validate-required');
				$('#billing_tax_number_field label abbr').show();
				$('#billing_tax_number_field label span').hide();
		}
	}).keyup();
	<?php } ?>
});
</script>
<?php
} );
