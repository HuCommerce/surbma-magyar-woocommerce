<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Customize the checkout fields if country is Hungary
add_filter( 'woocommerce_get_country_locale', function( $locale ) {
	$locale['HU']['state']['required'] = false;
	return $locale;
} );

// Default billing state reset function
add_filter( 'default_checkout_billing_state', function() {
	return null;
} );

// Default shipping state reset function
add_filter( 'default_checkout_shipping_state', function() {
	return null;
} );

// Hide the state field
add_filter( 'woocommerce_states', function( $states ) {
	$states['HU'] = array();
	return $states;
} );
