<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'woocommerce_checkout_process', function() {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' );

	if ( !empty( $_POST['billing_country'] ) && 'HU' == $_POST['billing_country'] ) {
		$billing_city_pattern = '/^([\p{L}])+([\p{L} ])*$/iu';
		$billing_address_1_pattern = '/^(?=.*\p{L})(?=.*\d)(?=.* )/iu';

		if ( !empty( $_POST['billing_tax_number'] ) && strlen( sanitize_text_field( $_POST['billing_tax_number'] ) ) < 13 ) {
			$noticeError = __( '<strong>Billing VAT number</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['billing_postcode'] ) && strlen( sanitize_text_field( $_POST['billing_postcode'] ) ) < 4 ) {
			$noticeError = __( '<strong>Billing Postcode</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['billing_city'] ) && !preg_match( $billing_city_pattern, sanitize_text_field( $_POST['billing_city'] ) ) ) {
			$noticeError = __( '<strong>Billing City</strong> field is invalid: only letters and space are allowed.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['billing_address_1'] ) && !preg_match( $billing_address_1_pattern, sanitize_text_field( $_POST['billing_address_1'] ) ) ) {
			$noticeError = __( '<strong>Billing Address</strong> field is invalid: missing letter and/or number characters.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['billing_phone'] ) && strlen( sanitize_text_field( $_POST['billing_phone'] ) ) < 11 ) {
			$noticeError = __( '<strong>Billing Phone</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['billing_phone'] ) && substr( sanitize_text_field( $_POST['billing_phone'] ), 3, 1 ) == 0 ) {
			$noticeError = __( '<strong>Billing Phone</strong> field is invalid: wrong format.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
		if ( !empty( $_POST['shipping_postcode'] ) && strlen( sanitize_text_field( $_POST['shipping_postcode'] ) ) < 4 ) {
			$noticeError = __( '<strong>Shipping Postcode</strong> field is invalid: too few characters.', 'surbma-magyar-woocommerce' );
			wc_add_notice( $noticeError, 'error' );
		}
	}
} );
