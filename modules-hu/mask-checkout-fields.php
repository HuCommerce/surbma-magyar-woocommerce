<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// jQuery Mask Plugin: https://igorescobar.github.io/jQuery-Mask-Plugin/
add_action( 'wp_enqueue_scripts', function() {
	if ( is_checkout() ) {
		wp_enqueue_script( 'surbma_hc_jquery_mask', SURBMA_HC_PLUGIN_URL . '/assets/js/jquery.mask.js', array( 'jquery' ), SURBMA_HC_PLUGIN_VERSION_NUMBER, true );
	}
} );

add_action( 'wp_enqueue_scripts', function() {
	$options = get_option( 'surbma_hc_fields' );
	$maskcheckoutfieldsplaceholderValue = isset( $options['maskcheckoutfieldsplaceholder'] ) ? $options['maskcheckoutfieldsplaceholder'] : 0;
	if ( is_checkout() ) {
		ob_start();
		?>
jQuery(document).ready(function($){
	// Mask the Billing fields
	function HCmaskcheckoutbillingfields(){
<?php if ( 1 == $maskcheckoutfieldsplaceholderValue ) { ?>
		$('#billing_tax_number').mask('00000000-0-00', {placeholder: '________-_-__'});
		$('#billing_postcode').mask('0000', {placeholder: '____'});
		$('#billing_phone').mask('+36000000009', {placeholder: '+36_________'});
<?php } else { ?>
		$('#billing_tax_number').mask('00000000-0-00');
		$('#billing_postcode').mask('0000');
		$('#billing_phone').mask('+36000000009');
<?php } ?>
		$('#billing_phone').focus(function() {
			if( $('#billing_phone').val() == '' || $('#billing_phone').val() == '+' || $('#billing_phone').val() == '+3' ){
				$('#billing_phone').val('+36');
			}
		});
	}

	// Unmask the Billing fields
	function HCunmaskcheckoutbillingfields(){
		$('#billing_tax_number').unmask();
		$('#billing_postcode').unmask();
		$('#billing_phone').unmask();
	}

	// Mask the Billing fields if Country is HU
	if( $('#billing_country').val() == 'HU' ){
		HCmaskcheckoutbillingfields();
	}
	// Check if Billing Country has changed
	$('#billing_country').change(function() {
		if( $('#billing_country').val() == 'HU' ){
			HCmaskcheckoutbillingfields();
		} else {
			HCunmaskcheckoutbillingfields();
		}
	});

	// Mask the Shipping fields
	function HCmaskcheckoutshippingfields(){
		<?php if ( 1 == $maskcheckoutfieldsplaceholderValue ) { ?>
			$('#shipping_postcode').mask('0000', {placeholder: '____'});
		<?php } else { ?>
			$('#shipping_postcode').mask('0000');
		<?php } ?>
	}

	// Unmask the Shipping fields
	function HCunmaskcheckoutshippingfields(){
		$('#shipping_postcode').unmask();
	}

	// Mask the Shipping fields if Country is HU
	if( $('#shipping_country').val() == 'HU' ){
		HCmaskcheckoutshippingfields();
	}
	// Check if Shipping Country has changed
	$('#shipping_country').change(function() {
		if( $('#shipping_country').val() == 'HU' ){
			HCmaskcheckoutshippingfields();
		} else {
			HCunmaskcheckoutshippingfields();
		}
	});

	// DEBUG
	/*
	$('#billing_tax_number').keyup(function() {
		console.log($('#billing_tax_number').val());
		console.log($('#billing_tax_number').cleanVal());
	}).keyup();
	*/
});
<?php
		$script = ob_get_contents();
		ob_end_clean();

		wp_add_inline_script( 'cps-jquery-fix', $script );
	}
} );
