<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Set the HuCommerce settings array globally
global $options;
$options = get_option( 'surbma_hc_fields', array() );
if ( !is_array( $options ) ) {
	$options = array();
}

// CPS SDK
if ( !function_exists( 'cps' ) ) {
	function cps() {
		// Include CPS SDK.
		require_once SURBMA_HC_PLUGIN_DIR . '/cps-sdk/start.php';
	}

	// Init CPS.
	cps();
}

// Include files.
// * HUCOMMERCE START
include_once SURBMA_HC_PLUGIN_DIR . '/lib/license.php';
// * HUCOMMERCE END
include_once SURBMA_HC_PLUGIN_DIR . '/lib/modules.php';
if ( is_admin() ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/lib/admin.php';
}

// Create a check for WooCommerce version. Used for deprecated functions for older WooCommerce versions.
function surbma_hc_woocommerce_version_check( $version ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
			return true;
		}
	}
	return false;
}

// Add plugin WooCommerce templates if exist
add_filter( 'woocommerce_locate_template', function( $template, $template_name, $template_path ) {
	global $woocommerce;
	$_template = $template;

	if ( !$template_path ) {
		$template_path = $woocommerce->template_url;
	}
		$plugin_path = SURBMA_HC_PLUGIN_DIR . '/woocommerce/';

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
