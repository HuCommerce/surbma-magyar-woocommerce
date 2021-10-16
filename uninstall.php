<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// if uninstall.php is not called by WordPress, die.
defined( 'WP_UNINSTALL_PLUGIN' ) || die;

/*
// Check, if another instance of HuCommerce is installed and activated on the current site or network.
if ( function_exists( 'hucommerce_fs' ) ) {
	return;
}
*/

// TEMPORARY HOT FIX
// TODO: Delete options only, if no other instance (free or premium) of HuCommerce is installed.
/*
delete_option( 'surbma_hc_fields' );
delete_option( 'pand-' . md5( 'surbma-hc-notice-welcome' ) );
// * HUCOMMERCE START
delete_option( 'pand-' . md5( 'surbma-hc-notice-v3000' ) );
delete_option( 'pand-' . md5( 'hucommerce-plus-promo' ) );
// * HUCOMMERCE END
*/
