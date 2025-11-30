<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-select-options.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-functions.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-validate.php');

add_action( 'admin_init', function() {
	register_setting(
		'surbma_hc_options',
		'surbma_hc_fields',
		'cps_hc_gems_fields_validate'
	);
	register_setting(
		'surbma_hc_license_options',
		'surbma_hc_license',
		'cps_hc_gems_license_validate'
	);
} );
