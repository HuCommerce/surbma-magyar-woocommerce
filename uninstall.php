<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// if uninstall.php is not called by WordPress, die.
defined( 'WP_UNINSTALL_PLUGIN' ) || die;

delete_option( 'surbma_hc_fields' );
delete_option( 'pand-' . md5( 'surbma-hc-notice-welcome' ) );
// * HUCOMMERCE START
delete_option( 'pand-' . md5( 'surbma-hc-notice-v3000' ) );
delete_option( 'pand-' . md5( 'hucommerce-plus-promo' ) );
// * HUCOMMERCE END
