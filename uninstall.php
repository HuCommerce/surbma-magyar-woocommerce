<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// if uninstall.php is not called by WordPress, die.
defined( 'WP_UNINSTALL_PLUGIN' ) || die;

delete_option( 'surbma_hc_fields' );
delete_option( 'surbma_hc_license' );
delete_option( 'surbma_hc_license_status' );
delete_option( 'pand-' . md5( 'surbma-hc-notice-welcome' ) );
// * HUCOMMERCE START
delete_option( 'pand-' . md5( 'surbma-hc-notice-v3000' ) );
delete_option( 'pand-' . md5( 'hucommerce-plus-promo' ) );
delete_option( 'pand-' . md5( 'hucommerce-plus-promo-60' ) );
delete_option( 'pand-' . md5( 'hucommerce-pro-promo-60' ) );
// * HUCOMMERCE END

// Clean up user meta for all users
delete_metadata( 'user', 0, 'cps_hc_gems_new_dashboard', '', true );
delete_metadata( 'user', 0, 'surbma_hc_new_dashboard', '', true );
