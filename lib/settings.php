<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Initialize settings
include_once( CPS_HC_GEMS_DIR . '/settings/settings-select-options.php');
include_once( CPS_HC_GEMS_DIR . '/settings/settings-functions.php');
include_once( CPS_HC_GEMS_DIR . '/settings/settings-defaults.php' );
include_once( CPS_HC_GEMS_DIR . '/settings/settings-validate.php');

// Register settings
add_action( 'admin_init', static function() {
	register_setting( 'cps_hc_gems_fields_options', 'surbma_hc_fields', 'cps_hc_gems_fields_validate' );
} );
