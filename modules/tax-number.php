<?php

/**
 * Module: Tax number field
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Adding Tax number field
add_filter( 'woocommerce_billing_fields', static function( $fields ) {
	// Get the Checkout Company field value
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' );

	// Set the Checkout Company field value to 'optional' if it does not exist
	if ( false == $woocommercecheckoutcompanyfieldValue ) {
		update_option( 'woocommerce_checkout_company_field', 'optional' );
		$woocommercecheckoutcompanyfieldValue = 'optional';
	}

	// Initialize Tax number field
	if ( 'optional' == $woocommercecheckoutcompanyfieldValue || 'required' == $woocommercecheckoutcompanyfieldValue ) {
		// WC Additional Fields API handles this field in My Account and block checkout.
		// Keep the classic field only for the shortcode checkout page.
		if (
			! is_checkout() &&
			function_exists( 'woocommerce_register_additional_checkout_field' ) &&
			version_compare( WC()->version, '8.6.0', '>=' )
		) {
			return $fields;
		}

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
add_filter( 'woocommerce_checkout_fields' , static function( $fields ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$taxnumberplaceholderValue = $cps_hc_gems_options['taxnumberplaceholder'] ?? 0;

	if ( 1 == $taxnumberplaceholderValue ) {
		$fields['billing']['billing_tax_number']['placeholder'] = __( 'Tax number', 'surbma-magyar-woocommerce' );
	}

	return $fields;
}, 20, 1 );

// Adding custom validation message for Tax number field on Checkout page
add_action( 'woocommerce_checkout_process', static function() {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false );

	// Init the Billing Tax number check process
	cps_hc_gems_billing_tax_number_check();
} );

// Adding custom validation message for Tax number field on My Account -> Addresses page
add_action( 'woocommerce_after_save_address_validation', static function( $user_id, $address_type ) {
	// Block My Account saves via REST API — $_POST is empty in that context.
	// woocommerce_blocks_validate_location_address_fields handles that path.
	if (
		function_exists( 'woocommerce_register_additional_checkout_field' ) &&
		version_compare( WC()->version, '8.6.0', '>=' ) &&
		! isset( $_POST['save_address'] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
	) {
		return;
	}

	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce', false );

	// Init the Tax number check process for the current address type
	cps_hc_gems_billing_tax_number_check( $address_type );
}, 10, 2 );

// Tax number check process for billing or shipping address
function cps_hc_gems_billing_tax_number_check( $address_type = 'billing' ) {
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' ) != false ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';
	$company_key   = 'billing' === $address_type ? 'billing_company' : 'shipping_company';
	$company       = ! empty( $_POST[ $company_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $company_key ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$company_check = 'billing' === $address_type && ! empty( $_POST['billing_company_check'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['billing_company_check'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	// WC AF My Account: _wc_{address_type}/{namespace}/{field}. Classic checkout: billing_tax_number.
	$tax_number = '';
	$wc_af_key  = '_wc_' . $address_type . '/cps-hc-gems/billing-tax-number';
	foreach ( array( 'billing_tax_number', $wc_af_key, 'cps-hc-gems/billing-tax-number', 'cps-hc-gems_billing-tax-number' ) as $tax_key ) {
		if ( ! empty( $_POST[ $tax_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$tax_number = sanitize_text_field( wp_unslash( $_POST[ $tax_key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			break;
		}
	}

	if ( 'hidden' !== $woocommercecheckoutcompanyfieldValue && ( ! empty( $company ) || 1 == $company_check || 'required' == $woocommercecheckoutcompanyfieldValue ) && empty( $tax_number ) ) {
		$field_label = __( 'Tax number', 'surbma-magyar-woocommerce' );
		if ( 'billing' === $address_type ) {
			/* translators: %s: Field label */
			$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		} else {
			/* translators: %s: Field label */
			$field_label = sprintf( _x( 'Shipping %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		}
		/* translators: %s: Field label */
		wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), 'error' ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
	}
}

// Saving billing_tax_number field value to user meta on Checkout page for logged in users
add_action( 'woocommerce_checkout_update_user_meta', static function( $customer_id ) {
	// Only proceed if this is a valid customer ID
	if ( empty( $customer_id ) ) {
		return;
	}

	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false );

	$billing_tax_number = !empty( $_POST['billing_tax_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_tax_number'] ) ) : '';
	update_user_meta( $customer_id, 'billing_tax_number', $billing_tax_number );
} );

// Saving billing_tax_number field value to session on Checkout page for not logged in users
add_action( 'woocommerce_checkout_update_order_review', static function ( $posted_data ) {
	// Parse the serialized posted data
	$posted = array();
	parse_str( $posted_data, $posted );

	$billing_tax_number = !empty( $posted['billing_tax_number'] ) ? sanitize_text_field( wp_unslash( $posted['billing_tax_number'] ) ) : '';
	WC()->session->set( 'billing_tax_number', $billing_tax_number );
} );

// Pre-populate billing_tax_number field, if it's empty and session has a value
add_filter( 'default_checkout_billing_tax_number', static function( $value ) {
	// Get the session value
	$session_value = WC()->session->get( 'billing_tax_number' );

	// Pre-populate the field if the session value is not empty and the field value is empty
	if ( !empty( $session_value ) && empty( $value ) ) {
		$value = $session_value;
	}

	return $value;
} );

// Adding editable Tax number field on edit order page
add_filter( 'woocommerce_admin_billing_fields' , static function( $fields ) {
	global $the_order;

	// Block checkout orders: WC Additional Fields API already renders the field in admin.
	// $the_order may be null under HPOS; fall back to URL params.
	$order = $the_order instanceof WC_Order ? $the_order : null;
	if ( ! $order ) {
		$order_id = absint( isset( $_GET['id'] ) ? $_GET['id'] : ( isset( $_GET['post'] ) ? $_GET['post'] : 0 ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $order_id ) {
			$order = wc_get_order( $order_id );
		}
	}
	if ( $order instanceof WC_Order && 'store-api' === $order->get_created_via() ) {
		return $fields;
	}

	$fields['tax_number'] = array(
		'label' => __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => '',
	);

	return $fields;
} );

// Replacement value for Billing & Shipping address on Thank you page.
// add_filter( 'woocommerce_get_order_address', static function( $address, $type, $order ) {
// 	$address['tax_number'] = __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $order->get_meta( '_billing_tax_number' );
// 	return $address;
// }, 10, 3 );

// Adding {tax_number} as a new "replacement" field
add_filter( 'woocommerce_localisation_address_formats', static function( $formats ) {
	foreach ( $formats as $key => &$format ) {
		$format .= "\n{tax_number}";
	}
	return $formats;
} );

// Replacement for the new {tax_number} field
add_filter( 'woocommerce_formatted_address_replacements', static function( $replacements, $args ) {
	$taxnumber = isset( $args['tax_number'] ) ? $args['tax_number'] : '';
	$replacements['{tax_number}'] = $taxnumber;
	return $replacements;
}, 10, 2 );

// Adding Tax number to My Account -> Addresses page
add_filter( 'woocommerce_my_account_my_address_formatted_address', static function( $address, $customer_id, $address_type ) {
	if ( 'billing' !== $address_type ) {
		$address['tax_number'] = '';
		return $address;
	}

	// WC Additional Fields API stores and displays the value itself; avoid duplicate.
	if ( metadata_exists( 'user', $customer_id, '_wc_billing/cps-hc-gems/billing-tax-number' ) ) {
		$address['tax_number'] = '';
		return $address;
	}

	$taxnumber = get_user_meta( $customer_id, 'billing_tax_number', true );
	$address['tax_number'] = '' != $taxnumber ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : '';
	return $address;
}, 10, 3 );

// Adding Tax number to Billing address on Thank you page and admin Preview
add_filter( 'woocommerce_order_formatted_billing_address', static function( $address, $wc_order ) {
	// Block checkout orders: WC Additional Fields API handles display in all contexts.
	if ( $wc_order instanceof WC_Order && 'store-api' === $wc_order->get_created_via() ) {
		$address['tax_number'] = '';
		return $address;
	}

	$taxnumber = $wc_order->get_meta( '_billing_tax_number' );
	$address['tax_number'] = '' != $taxnumber ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : '';
	return $address;
}, 10, 2 );

// Removing Tax number from Shipping address on Thank you page
add_filter( 'woocommerce_order_formatted_shipping_address', static function( $address ) {
	$address['tax_number'] = '';
	return $address;
} );

// Adding Tax number to user profile
add_filter( 'woocommerce_customer_meta_fields', static function( $profileFieldArray ) {
	$fieldData = array(
		'label'			=> __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'description'   => ''
	);
	$profileFieldArray['billing']['fields']['billing_tax_number'] = $fieldData;
	return $profileFieldArray;
} );

// Block checkout: Register Tax number as an additional checkout field (WC 8.6+ Additional Fields API).
add_action( 'woocommerce_init', static function() {
	if ( ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
		return;
	}

	if ( ! function_exists( 'WC' ) || ! WC() || version_compare( WC()->version, '8.6.0', '<' ) ) {
		return;
	}

	$cps_hc_gems_tax_number_company_field = get_option( 'woocommerce_checkout_company_field', 'optional' );

	if ( 'hidden' === $cps_hc_gems_tax_number_company_field ) {
		return;
	}

	$options              = get_option( 'surbma_hc_fields', array() );
	$placeholder_enabled  = is_array( $options ) && 1 === (int) ( $options['taxnumberplaceholder'] ?? 0 );
	$attributes           = array( 'autocomplete' => 'off' );

	if ( $placeholder_enabled ) {
		$attributes['placeholder'] = __( 'Tax number', 'surbma-magyar-woocommerce' );
	}

	woocommerce_register_additional_checkout_field( array(
		'id'         => 'cps-hc-gems/billing-tax-number',
		'label'      => __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'location'   => 'address',
		'required'   => false,
		'attributes' => $attributes,
	) );
}, 20 );

// Block checkout + block My Account: Validate Tax number for billing and shipping address.
// JSON Schema conditional required is not used because location:'address' would apply it incorrectly.
add_action( 'woocommerce_blocks_validate_location_address_fields', static function( $errors, $fields, $group ) {
	$company_field_setting = get_option( 'woocommerce_checkout_company_field', 'optional' );

	if ( 'hidden' === $company_field_setting ) {
		return;
	}

	$company    = $fields['company'] ?? '';
	$tax_number = $fields['cps-hc-gems/billing-tax-number'] ?? '';

	$requires_tax_number = ( 'required' === $company_field_setting ) || ! empty( $company );

	if ( $requires_tax_number && empty( $tax_number ) ) {
		$field_label = __( 'Tax number', 'surbma-magyar-woocommerce' );
		if ( 'billing' === $group ) {
			/* translators: %s: Field label */
			$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		} else {
			/* translators: %s: Field label */
			$field_label = sprintf( _x( 'Shipping %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		}
		/* translators: %s: Field label */
		$errors->add(
			'cps_hc_gems_' . $group . '_tax_number_required',
			sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ) // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		);
	}
}, 10, 3 );

// Block checkout: Save Tax number to order meta and user meta
add_action( 'woocommerce_store_api_checkout_update_order_from_request', static function( WC_Order $order, WP_REST_Request $request ) {
	$billing    = $request->get_param( 'billing_address' ) ?? array();
	$tax_number = sanitize_text_field( $billing['cps-hc-gems/billing-tax-number'] ?? '' );

	$order->update_meta_data( '_billing_tax_number', $tax_number );

	$customer_id = $order->get_customer_id();
	if ( $customer_id ) {
		update_user_meta( $customer_id, 'billing_tax_number', $tax_number );
	}
}, 10, 2 );

// Bidirectional sync between billing_tax_number and WC Additional Fields API meta key.
// Static flag prevents infinite loop between the two directions.
function cps_hc_gems_sync_billing_tax_number( $meta_id, $user_id, $meta_key, $meta_value ) {
	static $syncing = false;
	if ( $syncing ) {
		return;
	}
	$syncing = true;

	if ( '_wc_billing/cps-hc-gems/billing-tax-number' === $meta_key ) {
		update_user_meta( $user_id, 'billing_tax_number', $meta_value );
	} elseif ( 'billing_tax_number' === $meta_key && function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
		update_user_meta( $user_id, '_wc_billing/cps-hc-gems/billing-tax-number', $meta_value );
	}

	$syncing = false;
}
add_action( 'added_user_meta', 'cps_hc_gems_sync_billing_tax_number', 10, 4 );
add_action( 'updated_user_meta', 'cps_hc_gems_sync_billing_tax_number', 10, 4 );

// Block checkout: Move Tax number field after Company field via JS — enqueue via CPS_HC_Gems_Blocks_Integration::get_script_handles().

// Custom JavaScript codes
add_action( 'wp_footer', static function() {
	// Block checkout: blocks-tax-number.js is loaded via WooCommerce Blocks integration (IntegrationInterface).
	if ( cps_hc_gems_is_block_checkout() ) {
		return;
	}

	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' ) != false ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';

	if ( 'hidden' == $woocommercecheckoutcompanyfieldValue || ( ! is_checkout() && ! is_wc_endpoint_url( 'edit-address' ) ) ) {
		return;
	}

	// Get the settings array
	global $cps_hc_gems_options;

	$moduleCheckoutValue = $cps_hc_gems_options['module-checkout'] ?? 0;
	$billingcompanycheckValue = 1 == $moduleCheckoutValue && isset( $cps_hc_gems_options['billingcompanycheck'] ) ? $cps_hc_gems_options['billingcompanycheck'] : 0;
	$companytaxnumberpairValue = 1 == $moduleCheckoutValue && isset( $cps_hc_gems_options['companytaxnumberpair'] ) ? $cps_hc_gems_options['companytaxnumberpair'] : 0;

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
