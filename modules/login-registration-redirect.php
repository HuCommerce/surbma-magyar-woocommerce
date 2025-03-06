<?php

/**
 * Module: Login and registration redirection
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_login_redirect', function( $redirect, $user ) {
	// Get the settings array
	global $hc_gems_options;

	$loginredirecturlValue = $hc_gems_options['loginredirecturl'] ?? wc_get_page_permalink( 'shop' );

	$redirect_page_id = url_to_postid( $redirect );
	$checkout_page_id = wc_get_page_id( 'checkout' );

	if ( $redirect_page_id == $checkout_page_id ) {
		return $redirect;
	}

	if ( '' == $loginredirecturlValue) {
		return $redirect;
	} else {
		return $loginredirecturlValue;
	}
}, 10, 2 );

add_filter( 'woocommerce_registration_redirect', function( $var ) {
	// Get the settings array
	global $hc_gems_options;

	$registrationredirecturlValue = $hc_gems_options['registrationredirecturl'] ?? wc_get_page_permalink( 'shop' );

	if ( '' == $registrationredirecturlValue ) {
		return $var;
	} else {
		return $registrationredirecturlValue;
	}
}, 10, 1 );
