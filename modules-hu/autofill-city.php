<?php

/**
 * Module: Autofill City after Postcode is given
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function() {
	// Make sure, we are on the right page
	if ( !is_checkout() && !is_wc_endpoint_url( 'edit-address' ) ) {
		return;
	}

	wp_enqueue_script( 'surbma_hc_zipcodes', SURBMA_HC_PLUGIN_URL . '/assets/js/zipcodes.js', array( 'jquery' ), SURBMA_HC_PLUGIN_VERSION, true );
	wp_enqueue_script( 'surbma_hc_autofill', SURBMA_HC_PLUGIN_URL . '/assets/js/autofill.js', array( 'surbma_hc_zipcodes' ), SURBMA_HC_PLUGIN_VERSION, true );
} );
