<?php

/**
 * Module: Checkout page customizations
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Add new fields
add_filter( 'woocommerce_billing_fields', function( $fields ) {
	// Get the settings array
	global $options;

	// Get the settings
	$billingcompanycheckValue = $options['billingcompanycheck'] ?? 0;
	$woocommercecheckoutcompanyfieldValue = false !== get_option( 'woocommerce_checkout_company_field' ) ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';

	if ( 'optional' == $woocommercecheckoutcompanyfieldValue && 1 == $billingcompanycheckValue ) {
		$fields['billing_company_check'] = array(
			'type' 			=> 'checkbox',
			'class'         => array( 'form-row-wide', 'woocommerce-form-row', 'woocommerce-form-row--wide', 'company' ),
			'label' 		=> '<span>' . __( 'Company billing', 'surbma-magyar-woocommerce' ) . '</span>',
			'label_class'   => array( 'woocommerce-form__label', 'woocommerce-form__label-for-checkbox' ),
			'input_class'   => array( 'woocommerce-form__input', 'woocommerce-form__input-checkbox' ),
			'priority' 		=> 29,
			'clear' 		=> true,
			'required' 		=> false
		);
	}

	return $fields;
} );

// Adding custom validation message for Billing Company field on Checkout page
add_action( 'woocommerce_checkout_process', function() {
	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false );

	// Init the Billing Company check process
	cps_wcgems_hc_billing_company_check();
} );

// Adding custom validation message for Billing Company field on My Account -> Addresses page
add_action( 'woocommerce_after_save_address_validation', function( $user_id, $address_type ) {
	// Only proceed if this is the billing address form
	if ( 'billing' !== $address_type ) {
		return;
	}

	// Nonce verification before doing anything
	check_ajax_referer( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce', false );

	// Init the Billing Company check process
	cps_wcgems_hc_billing_company_check();
}, 10, 2 );

// Billing Company check process
function cps_wcgems_hc_billing_company_check() {
	// Get the settings
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field', 'optional' );

	if ( 'optional' == $woocommercecheckoutcompanyfieldValue && !empty( $_POST['billing_company_check'] ) && 1 == $_POST['billing_company_check'] && empty( $_POST['billing_company'] ) ) {
		$field_label = __( 'Company name', 'woocommerce' ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		/* translators: %s: Field label */
		$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		/* translators: %s: Field label */
		$noticeError = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		wc_add_notice( $noticeError, 'error' );
	}
}

// Pre-populate billing_country field, if it's hidden
add_filter( 'default_checkout_billing_country', function( $value ) {
	// Get the settings array
	global $options;

	// Get the settings
	$nocountryValue = $options['nocountry'] ?? 0;

	if ( 1 == $nocountryValue ) {
		// The country/state
		$store_raw_country = get_option( 'woocommerce_default_country' );
		// Split the country/state
		$split_country = explode( ':', $store_raw_country );
		// Country and state separated:
		$store_country = $split_country[0];

		$value = $store_country;
	}

	return $value;
} );

// Customize the Billing fields on the Checkout and My account -> Addresses pages
add_filter( 'woocommerce_billing_fields', function( $address_fields ) {
	// Get the settings array
	global $options;

	// Get the settings
	$companytaxnumberpairValue = $options['companytaxnumberpair'] ?? 0;
	$phoneemailpairValue = $options['phoneemailpair'] ?? 0;
	$emailtothetopValue = $options['emailtothetop'] ?? 0;

	// Inline Billing Company and Billing Tax number fields
	if ( isset( $address_fields['billing_company'] ) && isset( $address_fields['billing_tax_number'] ) && 1 == $companytaxnumberpairValue ) {
		$address_fields['billing_company']['class'] = array( 'form-row-first' );
		$address_fields['billing_tax_number']['class'] = array( 'form-row-last' );
	}

	// Inline Billing Phone and Billing Email fields
	if ( isset( $address_fields['billing_phone'] ) && isset( $address_fields['billing_email'] ) && 1 == $phoneemailpairValue && 1 !== $emailtothetopValue ) {
		$address_fields['billing_phone']['class'] = array( 'form-row-first' );
		$address_fields['billing_email']['class'] = array( 'form-row-last' );
	}

	// Move Email field to the top of the Checkout form
	if ( 1 == $emailtothetopValue ) {
		$address_fields['billing_email']['priority'] = 5;
	}

	return $address_fields;
}, 10, 1 );

// Customize the default address fields on the Checkout and My account -> Addresses pages
add_filter( 'woocommerce_default_address_fields' , function( $address_fields ) {
	$woocommercecheckoutaddress2fieldValue = false !== get_option( 'woocommerce_checkout_address_2_field' ) ? get_option( 'woocommerce_checkout_address_2_field' ) : 'optional';

	// Get the settings array
	global $options;

	// Get the settings
	$postcodecitypairValue = $options['postcodecitypair'] ?? 0;

	// Inline Postcode and City fields
	if ( 1 == $postcodecitypairValue ) {
		$address_fields['postcode']['priority'] = 69;
		$address_fields['postcode']['class'] = array( 'form-row-first' );
		$address_fields['city']['class'] = array( 'form-row-last' );
	}

	return $address_fields;
} );

// Customize the Checkout fields
add_filter( 'woocommerce_checkout_fields' , function( $fields ) {
	// Get the settings array
	global $options;

	// Get the settings
	$noordercommentsValue = $options['noordercomments'] ?? 0;

	if ( isset( $fields['order']['order_comments'] ) && 1 == $noordercommentsValue ) {
		unset( $fields['order']['order_comments'] );
	}

	return $fields;
}, 9999 );

// Remove Additional information section
add_action( 'woocommerce_before_checkout_form' , function() {
	// Get the settings array
	global $options;

	$noadditionalinformationValue = $options['noadditionalinformation'] ?? 0;

	if ( 1 == $noadditionalinformationValue ) {
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );
	}
} );

// Custom submit button text
add_filter( 'woocommerce_order_button_text', function( $button_text ) {
	// Get the settings array
	global $options;

	$checkout_customsubmitbuttontextValue = $options['checkout-customsubmitbuttontext'] ?? false;

	if ( !empty( $checkout_customsubmitbuttontextValue ) ) {
		$button_text = $checkout_customsubmitbuttontextValue;
	}

	return $button_text;
} );

// Custom JavaScript codes
add_action( 'wp_footer', function() {
	// Make sure, we are on the right page
	if ( !is_checkout() && !is_wc_endpoint_url( 'edit-address' ) ) {
		return;
	}

	// Get the settings array
	global $options;

	$billingcompanycheckValue = $options['billingcompanycheck'] ?? 0;
	$checkout_hidecompanytaxfields_value = $options['checkout-hidecompanytaxfields'] ?? 0;
	$checkout_hidecompanyfield_value = $options['checkout-hide_company_field_if_not_hungary'] ?? 0;
	$checkout_hidetaxfield_value = $options['checkout-hide_tax_field_if_not_hungary'] ?? 0;
	$nocountryValue = $options['nocountry'] ?? 0;
	$companytaxnumberpairValue = $options['companytaxnumberpair'] ?? 0;

	$woocommercecheckoutcompanyfieldValue = false !== get_option( 'woocommerce_checkout_company_field' ) ? get_option( 'woocommerce_checkout_company_field' ) : 'optional';

	?>
<script id="cps-hc-wcgems-checkout">
jQuery(document).ready(function($){
	// Fix for previous version, that saved '- N/A -'' value if billing_company was empty
	if ( $('#billing_company').val() == '- N/A -' ){
		$('#billing_company').val('');
	}

	<?php if ( 1 == $billingcompanycheckValue ) { ?>
		$("#billing_company_field label span").remove();
		$("#billing_tax_number_field label span").remove();
	<?php } ?>

	// All the actions, when the billing_company_check field is unchecked
	function showCompanyFields() {
		$('#billing_company_field').show();

		// Add saved values back
		if(localStorage.getItem('billing_company')){
			$('#billing_company').val(localStorage.getItem('billing_company'));
		}
		if(localStorage.getItem('billing_tax_number')){
			$('#billing_tax_number').val(localStorage.getItem('billing_tax_number'));
		}
		localStorage.removeItem('billing_company');
		localStorage.removeItem('billing_tax_number');
	}

	// All the actions, when the billing_company_check field is unchecked
	function hideCompanyFields() {
		// Save already entered value, if customer wants to enable company fields again
		localStorage.setItem('billing_company', $('#billing_company').val());
		localStorage.setItem('billing_tax_number', $('#billing_tax_number').val());

		// Hiding the company related fields
		$('#billing_company_field').hide();
		$('#billing_tax_number_field').hide();

		// Empty the company related field values, because we don't want to save company details
		$('#billing_company').val('');
		$('#billing_tax_number').val('');

		// Reset classes, as they are empty again
		$("#billing_company_field").removeClass('validate-required');
		$("#billing_company_field").removeClass('woocommerce-validated');
		$("#billing_company_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
		$("#billing_tax_number_field").removeClass('validate-required');
		$("#billing_tax_number_field").removeClass('woocommerce-validated');
		$("#billing_tax_number_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
	}

	<?php if ( 'optional' == $woocommercecheckoutcompanyfieldValue && 1 == $billingcompanycheckValue ) { ?>
		$('#billing_company_check_field label span.optional').hide();

		// Add required sign and remove the "not required" text from billing_company_field
		$('#billing_company_field label').append( ' <abbr class="required" title="required">*</abbr>' );
		$('#billing_company_field label span').hide();

		if($('#billing_company_check').prop('checked') == true){
			$('#billing_company_field').addClass('validate-required');
			$('#billing_tax_number_field').addClass('validate-required');
		}

		if($('#billing_company_check').prop('checked') == false){
			$('#billing_company_field').hide();
			$('#billing_tax_number_field').hide();
		}

		$('#billing_company_check').click(function(){
			if($(this).prop('checked') == true){
				$('#billing_company_field').addClass('validate-required');
				$('#billing_tax_number_field').addClass('validate-required');
				$('#billing_tax_number_field').show();
				showCompanyFields();
			}
			else if($(this).prop('checked') == false){
				hideCompanyFields();
			}
		});
	<?php } ?>

	<?php if ( 1 == $checkout_hidecompanytaxfields_value ) { ?>
		// Function to hide/show company fields based on selected country
		function hideShowCompanyFields() {
			const selectedCountry = $('#billing_country').val();

			<?php if ( 1 == $billingcompanycheckValue ) { ?>
				if ( selectedCountry !== 'HU' ) {
					// Hiding the Company checkbox
					$('#billing_company_check_field').hide();

					// Uncheck the Company checkbox
					$('#billing_company_check').prop('checked', false);

					hideCompanyFields();
				} else {
					$('#billing_company_check_field').show();
				}
			<?php } else { ?>
				if ( selectedCountry !== 'HU' ) {
					hideCompanyFields();
				} else {
					showCompanyFields();
					if ( $('#billing_company').val().trim() !== '' ) {
						$('#billing_tax_number_field').show();
					}
				}
			<?php } ?>
		}

		hideShowCompanyFields();

		// Call the function when the Country dropdown changes
		$('#billing_country').on('change', function() {
			hideShowCompanyFields();
		});
	<?php } ?>

	<?php if ( 1 == $nocountryValue ) { ?>
		$("#billing_country_field").hide();
	<?php } ?>
});
</script>
<?php
}, 99 );
