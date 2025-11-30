<?php

/**
 * Module: Coupon field customizations
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Show Coupons in upper case
add_action( 'init', static function() {
	// Get the settings array
	global $cps_hc_gems_options;

	$couponuppercaseValue = isset( $cps_hc_gems_options['couponuppercase'] ) ? $cps_hc_gems_options['couponuppercase'] : 0;

	if ( 1 == $couponuppercaseValue ) {
		remove_filter( 'woocommerce_coupon_code', 'wc_strtolower' );
		add_filter( 'woocommerce_coupon_code', 'wc_strtoupper' );
	}
} );

// Remove coupon field
add_filter( 'woocommerce_coupons_enabled', static function( $enabled ) {
	// Get the settings array
	global $cps_hc_gems_options;

	$couponfieldhiddenoncartValue = isset( $cps_hc_gems_options['couponfieldhiddenoncart'] ) ? $cps_hc_gems_options['couponfieldhiddenoncart'] : 0;
	$couponfieldhiddenoncheckoutValue = isset( $cps_hc_gems_options['couponfieldhiddenoncheckout'] ) ? $cps_hc_gems_options['couponfieldhiddenoncheckout'] : 0;

	if ( 1 == $couponfieldhiddenoncartValue && is_cart() ) {
		$enabled = false;
	}

	if ( 1 == $couponfieldhiddenoncheckoutValue && is_checkout() ) {
		$enabled = false;
	}

	return $enabled;
} );

// Coupon field always visible
add_action( 'wp_head', static function() {
	if ( is_checkout() ) {
		// Get the settings array
		global $cps_hc_gems_options;

		$couponfieldalwaysvisibleValue = isset( $cps_hc_gems_options['couponfieldalwaysvisible'] ) ? $cps_hc_gems_options['couponfieldalwaysvisible'] : 0;

		if ( 1 == $couponfieldalwaysvisibleValue ) {
			echo '<style id="cps-wcgems-hc-form-coupon-inline-css">.woocommerce-checkout .woocommerce-form-coupon-toggle {display: none;} .woocommerce-checkout .woocommerce-form-coupon {display: block !important;}</style>';
		}
	}
} );

// Coupon field reposition
add_action( 'woocommerce_before_checkout_form', static function() {
	// Get the settings array
	global $cps_hc_gems_options;

	$couponfieldpositionValue = isset( $cps_hc_gems_options['couponfieldposition'] ) ? $cps_hc_gems_options['couponfieldposition'] : 'beforecheckoutform';

	if ( 'aftercheckoutform' == $couponfieldpositionValue ) {
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form');
		add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
	}
}, 0 );
