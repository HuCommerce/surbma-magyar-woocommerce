<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Initialize settings
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-select-options.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-functions.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-validate.php');

// Register settings
add_action( 'admin_init', static function() {
	register_setting( 'cps_hc_gems_fields_options', 'surbma_hc_fields', 'cps_hc_gems_fields_validate' );
	register_setting( 'cps_hc_gems_license_options', 'surbma_hc_license', 'cps_hc_gems_license_validate' );
} );
