<?php

/**
 * Module: One product per purchase
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_add_to_cart_validation', function( $passed, $added_product_id ) {
	// Get the settings array
	global $options;

	$module_oneproductincartValue = $options['module-oneproductincart'] ?? false;

	if ( $module_oneproductincartValue ) {
		wc_empty_cart();
	}

	return $passed;
}, 9999, 2 );
