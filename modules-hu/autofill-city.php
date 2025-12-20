<?php

/**
 * Module: Autofill City after Postcode is given
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', static function() {
	// Make sure, we are on the right page
	if ( !is_checkout() && !is_wc_endpoint_url( 'edit-address' ) ) {
		return;
	}

	wp_enqueue_script( 'surbma_hc_zipcodes', CPS_HC_GEMS_URL . '/assets/js/zipcodes.js', array( 'jquery' ), CPS_HC_GEMS_VERSION, true );
	wp_enqueue_script( 'surbma_hc_autofill', CPS_HC_GEMS_URL . '/assets/js/autofill.js', array( 'surbma_hc_zipcodes' ), CPS_HC_GEMS_VERSION, true );
} );
