<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// CPS SDK
if ( !function_exists( 'cps' ) ) {
	function cps() {
		// Include CPS SDK.
		require_once CPS_HC_GEMS_DIR . '/cps-sdk/start.php';
	}

	// Init CPS.
	cps();
}

// Set the HuCommerce settings array globally
add_action( 'init', static function() {
	global $cps_hc_gems_options;
	$cps_hc_gems_options = get_option( 'surbma_hc_fields', array() );
	if ( !is_array( $cps_hc_gems_options ) ) {
		$cps_hc_gems_options = array();
	}
}, 0 );

// Include files
include_once CPS_HC_GEMS_DIR . '/lib/modules.php';
include_once CPS_HC_GEMS_DIR . '/lib/helpers.php';
if ( is_admin() ) {
	include_once CPS_HC_GEMS_DIR . '/lib/admin.php';
	include_once CPS_HC_GEMS_DIR . '/lib/settings.php';
	include_once CPS_HC_GEMS_DIR . '/lib/pages.php';
}

// Create a check for WooCommerce version. Used for deprecated functions for older WooCommerce versions
function cps_hc_gems_woocommerce_version_check( $version ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
			return true;
		}
	}
	return false;
}

// Add plugin WooCommerce templates if exist
add_filter( 'woocommerce_locate_template', static function( $template, $template_name, $template_path ) {
	global $woocommerce;
	$_template = $template;

	if ( !$template_path ) {
		$template_path = $woocommerce->template_url;
	}
		$plugin_path = CPS_HC_GEMS_DIR . '/woocommerce/';

	// Look within passed path within the theme – this is priority
	$template = locate_template(
		array( $template_path . $template_name, $template_name )
	);

	// Modification: Get the template from this plugin, if it exists
	if ( !$template && file_exists( $plugin_path . $template_name ) ) {
		$template = $plugin_path . $template_name;
	}

	// Use default template
	if ( !$template ) {
		$template = $_template;
	}

	// Return what we found
	return $template;
}, 10, 3 );
