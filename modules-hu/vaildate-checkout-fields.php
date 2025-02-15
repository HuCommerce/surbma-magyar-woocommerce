<?php

/**
 * Module: Check field values
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'woocommerce_checkout_process', function() {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' );

	// Init the Validating fields function
	cps_wcgems_hc_validate_checkout_fields();
} );

// Adding custom validation message for Billing Company field on My Account -> Addresses page
add_action( 'woocommerce_after_save_address_validation', function( $user_id, $address_type ) {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce', false );

	// Only proceed if this is the billing address form
	if ( 'billing' !== $address_type ) {
		return;
	}

	// Init the Validating fields function
	cps_wcgems_hc_validate_checkout_fields();
}, 10, 2 );

// Validating fields
function cps_wcgems_hc_validate_checkout_fields() {
	// Check if Country is Hungary and stop the process, if not Hungary
	$billing_country = $_POST['billing_country'] ?? '';
	if ( empty( $billing_country ) || 'HU' !== $billing_country ) {
		return;
	}

	// Get the settings array
	global $options;

	// Get the "Address line 2 field" setting
	// $woocommercecheckoutaddress2fieldValue = false !== get_option( 'woocommerce_checkout_address_2_field' ) ? get_option( 'woocommerce_checkout_address_2_field' ) : '';

	// Get the settings
	$validatebillingtaxfieldValue = $options['validatebillingtaxfield'] ?? 0;
	$validatebillingcityfieldValue = $options['validatebillingcityfield'] ?? 0;
	$validatebillingaddressfieldValue = $options['validatebillingaddressfield'] ?? 0;
	$validatebillingphonefieldValue = $options['validatebillingphonefield'] ?? 0;
	$validateshippingcityfieldValue = $options['validateshippingcityfield'] ?? 0;
	$validateshippingaddressfieldValue = $options['validateshippingaddressfield'] ?? 0;

	// Get the submitted fields
	$billing_tax_number = $_POST['billing_tax_number'] ?? '';
	// $billing_postcode = $_POST['billing_postcode'] ?? '';
	$billing_city = $_POST['billing_city'] ?? '';
	$billing_address_1 = $_POST['billing_address_1'] ?? '';
	$billing_phone = $_POST['billing_phone'] ?? '';
	$shipping_city = $_POST['shipping_city'] ?? '';
	$ship_to_different_address = $_POST['ship_to_different_address'] ?? 0;
	$shipping_address_1 = $_POST['shipping_address_1'] ?? '';

	// Set patterns
	$billing_tax_number_pattern_short = '/^\d{11}$/';
	$billing_tax_number_pattern_full = '/^\d{8}-\d{1}-\d{2}$/';
	$billing_tax_number_pattern_eu = '/^HU\d{8}$/';
	// $checkout_postcode_pattern = '/^\d{4}$/';
	$checkout_city_pattern = '/^([\p{L}])+([\p{L} ])*$/iu';
	$checkout_address_1_pattern = '/^(?=.*\p{L})(?=.*\d)(?=.* )/iu';

	if ( 1 == $validatebillingtaxfieldValue && !empty( $billing_tax_number ) && !preg_match( $billing_tax_number_pattern_short, $billing_tax_number ) && !preg_match( $billing_tax_number_pattern_full, $billing_tax_number ) && !preg_match( $billing_tax_number_pattern_eu, $billing_tax_number ) ) {
		$noticeError = __( '<strong>Billing VAT number</strong> field is invalid. Please check again!', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	/*
	if ( !empty( $billing_postcode ) && !preg_match( $checkout_postcode_pattern, $billing_postcode ) ) {
		$noticeError = __( '<strong>Billing Postcode</strong> field is invalid. Please check again!', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}
	if ( !empty( $_POST['billing_postcode'] ) && strlen( sanitize_text_field( $_POST['billing_postcode'] ) ) < 4 ) {
		$noticeError = __( '<strong>Billing Postcode</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}
	if ( !empty( $_POST['billing_postcode'] ) && strlen( sanitize_text_field( $_POST['billing_postcode'] ) ) > 4 ) {
		$noticeError = __( '<strong>Billing Postcode</strong> field is invalid: too much characters.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}
	*/

	if ( 1 == $validatebillingcityfieldValue && $billing_city && !preg_match( $checkout_city_pattern, $billing_city ) ) {
		$noticeError = __( '<strong>Billing City</strong> field is invalid: only letters and space are allowed.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	if ( 1 == $validatebillingaddressfieldValue && $billing_address_1 && empty( $_POST['billing_address_2'] ) && !preg_match( $checkout_address_1_pattern, $billing_address_1 ) ) {
		$noticeError = __( '<strong>Billing Address</strong> field is invalid: must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	if ( 1 == $validatebillingphonefieldValue && $billing_phone && strlen( $billing_phone ) < 11 ) {
		$noticeError = __( '<strong>Billing Phone</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	if ( 1 == $validatebillingphonefieldValue && $billing_phone && substr( $billing_phone, 3, 1 ) == 0 ) {
		$noticeError = __( '<strong>Billing Phone</strong> field is invalid: wrong format.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	if ( 1 == $validateshippingcityfieldValue && 1 == $ship_to_different_address && $shipping_city && !preg_match( $checkout_city_pattern, $shipping_city ) ) {
		$noticeError = __( '<strong>Shipping City</strong> field is invalid: only letters and space are allowed.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	if ( 1 == $validateshippingaddressfieldValue && 1 == $ship_to_different_address && $shipping_address_1 && empty( $_POST['shipping_address_2'] ) && !preg_match( $checkout_address_1_pattern, $shipping_address_1 ) ) {
		$noticeError = __( '<strong>Shipping Address</strong> field is invalid: must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}
}

// Custom JavaScript codes
add_action( 'wp_footer', function() {
	// Make sure, we are on the right page
	if ( !is_checkout() && !is_wc_endpoint_url( 'edit-address' ) ) {
		return;
	}

	// Get the settings array
	global $options;

	// Get the settings
	$validatebillingtaxfieldValue = $options['validatebillingtaxfield'] ?? 0;

	if ( 1 == $validatebillingtaxfieldValue ) {
	?>
<script id="cps-hc-wcgems-validate-checkout-fields">
jQuery(document).ready(function($){
	// Check Billing tax number field value
	$('#billing_tax_number').on('keyup change blur focus', function() {
		const billing_tax_number_field = document.querySelector('#billing_tax_number_field');
		// If field is empty
		if ( $(this).val().length == 0 ) {
			// Only invalid, if field is required
			if ( billing_tax_number_field.classList.contains('validate-required') ) {
				$('#billing_tax_number_field').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			}
		// If field has any value
		} else {
			// Check for Hungarian tax number formats
			if ( /^\d{11}$/.test( $(this).val() ) || /^\d{8}-\d{1}-\d{2}$/.test( $(this).val() ) || /^HU\d{8}$/.test( $(this).val() ) ) {
				$('#billing_tax_number_field').addClass('woocommerce-validated');
			} else {
				if ( billing_tax_number_field.classList.contains('validate-required') ) {
					$('#billing_tax_number_field').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				} else {
					$('#billing_tax_number_field').addClass('woocommerce-invalid');
				}
			}
		}
	});
});
</script>
<?php
	}
} );
