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
	$billing_country = !empty( $_POST['billing_country'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
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
	$validatecheckoutfields_mobileonly_value = $options['validatecheckoutfields-mobileonly'] ?? 0;

	// Get the submitted fields
	$billing_tax_number = !empty( $_POST['billing_tax_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_tax_number'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	// $billing_postcode = !empty( $_POST['billing_postcode'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_postcode'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$billing_city = !empty( $_POST['billing_city'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$billing_address_1 = !empty( $_POST['billing_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$billing_phone = !empty( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$shipping_city = !empty( $_POST['shipping_city'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_city'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$ship_to_different_address = !empty( $_POST['ship_to_different_address'] ) ? sanitize_text_field( wp_unslash( $_POST['ship_to_different_address'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$shipping_address_1 = !empty( $_POST['shipping_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_1'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	// Define area code arrays
	$budapest_area_code = array( 1 );
	$mobile_area_codes = array( 20, 30, 31, 70 );
	$six_digit_area_codes = array( 22, 23, 24, 25, 26, 27, 28, 29, 32, 33, 34, 35, 36, 37, 42, 44, 45, 46, 47, 48, 49, 52, 53, 54, 56, 57, 59, 62, 63, 66, 68, 69, 72, 73, 74, 75, 76, 77, 78, 79, 82, 83, 84, 85, 87, 88, 89, 92, 93, 94, 95, 96, 99 );

	// Set patterns
	$billing_tax_number_pattern_short = '/^\d{11}$/';
	$billing_tax_number_pattern_full = '/^\d{8}-\d{1}-\d{2}$/';
	$billing_tax_number_pattern_eu = '/^HU\d{8}$/';
	// $checkout_postcode_pattern = '/^\d{4}$/';
	$checkout_city_pattern = '/^([\p{L}])+([\p{L} ])*$/iu';
	$checkout_address_1_pattern = '/^(?=.*\p{L})(?=.*\d)(?=.* )/iu';

	// TAX number validation
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

	// City validation
	if ( 1 == $validatebillingcityfieldValue && $billing_city && !preg_match( $checkout_city_pattern, $billing_city ) ) {
		$noticeError = __( '<strong>Billing City</strong> field is invalid: only letters and space are allowed.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	// Address validation
	if ( 1 == $validatebillingaddressfieldValue && $billing_address_1 && empty( $_POST['billing_address_2'] ) && !preg_match( $checkout_address_1_pattern, $billing_address_1 ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$noticeError = __( '<strong>Billing Address</strong> field is invalid: must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	// Phone number validation
	if ( 1 == $validatebillingphonefieldValue && $billing_phone ) {
		// Check if phone number starts with +36
		if ( substr( $billing_phone, 0, 3 ) !== '+36' ) {
			$noticeError = __( '<strong>Billing Phone</strong> field is invalid: must start with "+36".', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		// Check if characters are valid
		elseif ( $billing_phone[0] !== '+' || !ctype_digit( substr( $billing_phone, 1 ) ) ) {
			$noticeError = __( '<strong>Billing Phone</strong> field is invalid: can only contain "+" as first character and digits.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		} else {
			// Remove +36 prefix for further validation
			$phoneNumber = substr( $billing_phone, 3 );

			// Extract area code
			$areaCode = '';
			for ( $i = 0; $i <= 2; $i++ ) {
				$possibleAreaCode = substr( $phoneNumber, 0, $i + 1 );

				// If mobile-only validation is enabled
				if ( $validatecheckoutfields_mobileonly_value == 1 ) {
					if ( in_array( (int)$possibleAreaCode, $mobile_area_codes ) ) {
						$areaCode = $possibleAreaCode;
						break;
					}
				} else {
					// Normal validation with all area codes
					if ( in_array( (int)$possibleAreaCode, $budapest_area_code ) || in_array( (int)$possibleAreaCode, $mobile_area_codes ) || in_array( (int)$possibleAreaCode, $six_digit_area_codes ) ) {
						$areaCode = $possibleAreaCode;
						break;
					}
				}
			}

			// Check if area code is valid
			if ( !$areaCode ) {
				if ( $validatecheckoutfields_mobileonly_value == 1 ) {
					$noticeError = __( '<strong>Billing Phone</strong> field is invalid: only mobile numbers are accepted.', 'surbma-magyar-woocommerce' );
				} else {
					$noticeError = __( '<strong>Billing Phone</strong> field is invalid: wrong area code.', 'surbma-magyar-woocommerce' );
				}
				wc_add_notice( $noticeError, 'error' );
			} else {
				// Remove area code from phone number to get subscriber number
				$subscriber = substr( $phoneNumber, strlen( $areaCode ) );

				if ( $validatecheckoutfields_mobileonly_value == 1 ) {
					// For mobile-only validation, check only mobile number length
					if ( strlen( $subscriber ) !== 7 ) {
						$noticeError = __( '<strong>Billing Phone</strong> field is invalid: mobile numbers must have 7 digits after area code.', 'surbma-magyar-woocommerce' );
						wc_add_notice( $noticeError, 'error' );
					}
				} else {
					// Normal validation with all number types
					if ( in_array( (int)$areaCode, $budapest_area_code ) && strlen( $subscriber ) !== 7 ) {
						$noticeError = __( '<strong>Billing Phone</strong> field is invalid: Budapest numbers must have 7 digits after area code.', 'surbma-magyar-woocommerce' );
						wc_add_notice( $noticeError, 'error' );
					} elseif ( in_array( (int)$areaCode, $mobile_area_codes ) && strlen( $subscriber ) !== 7 ) {
						$noticeError = __( '<strong>Billing Phone</strong> field is invalid: mobile numbers must have 7 digits after area code.', 'surbma-magyar-woocommerce' );
						wc_add_notice( $noticeError, 'error' );
					} elseif ( in_array( (int)$areaCode, $six_digit_area_codes ) && strlen( $subscriber ) !== 6 ) {
						$noticeError = __( '<strong>Billing Phone</strong> field is invalid: this area code must have 6 digits after it.', 'surbma-magyar-woocommerce' );
						wc_add_notice( $noticeError, 'error' );
					}
				}
			}
		}
	}

	// Shipping city validation
	if ( 1 == $validateshippingcityfieldValue && 1 == $ship_to_different_address && $shipping_city && !preg_match( $checkout_city_pattern, $shipping_city ) ) {
		$noticeError = __( '<strong>Shipping City</strong> field is invalid: only letters and space are allowed.', 'surbma-magyar-woocommerce' );
		wc_add_notice( $noticeError, 'error' );
	}

	// Shipping address validation
	if ( 1 == $validateshippingaddressfieldValue && 1 == $ship_to_different_address && $shipping_address_1 && empty( $_POST['shipping_address_2'] ) && !preg_match( $checkout_address_1_pattern, $shipping_address_1 ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
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
