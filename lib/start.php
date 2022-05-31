<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Localization
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'surbma-magyar-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
} );

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
if ( is_admin() ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/lib/admin.php';
}
// * HUCOMMERCE START
include_once SURBMA_HC_PLUGIN_DIR . '/lib/license.php';
// * HUCOMMERCE END

$options = get_option( 'surbma_hc_fields' );

// * HUCOMMERCE START
// HU Modules

$module_huformatfixValue = isset( $options['huformatfix'] ) ? $options['huformatfix'] : 0;
$module_nocountyValue = isset( $options['nocounty'] ) ? $options['nocounty'] : 0;
$module_autofillcityValue = isset( $options['autofillcity'] ) ? $options['autofillcity'] : 0;
$module_maskcheckoutfieldsValue = isset( $options['maskcheckoutfields'] ) ? $options['maskcheckoutfields'] : 0;
$module_validatecheckoutfieldsValue = isset( $options['validatecheckoutfields'] ) ? $options['validatecheckoutfields'] : 0;
$module_productpricehistoryValue = isset( $options['module-productpricehistory'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-productpricehistory'] : 0;
$module_translationsValue = isset( $options['translations'] ) ? $options['translations'] : 0;

if ( 1 == $module_huformatfixValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/hu-format-fix.php';
}
if ( 1 == $module_nocountyValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/no-county.php';
}
if ( 1 == $module_autofillcityValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/autofill-city.php';
}
if ( 1 == $module_maskcheckoutfieldsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/mask-checkout-fields.php';
}
if ( 1 == $module_validatecheckoutfieldsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/vaildate-checkout-fields.php';
}
if ( 1 == $module_productpricehistoryValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/product-price-history.php';
}
if ( 1 == $module_translationsValue && !is_admin() ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/translations.php';
}
// * HUCOMMERCE END

// Modules

$module_taxnumberValue = isset( $options['taxnumber'] ) ? $options['taxnumber'] : 0;
$module_checkoutValue = isset( $options['module-checkout'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['module-checkout'] : 0;
$module_couponValue = isset( $options['module-coupon'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-coupon'] : 0;
$module_plusminusValue = isset( $options['plusminus'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['plusminus'] : 0;
$module_updatecartValue = isset( $options['updatecart'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['updatecart'] : 0;
$module_redirectcartValue = isset( $options['module-redirectcart'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-redirectcart'] : 0;
$module_oneproductincartValue = isset( $options['module-oneproductincart'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-oneproductincart'] : 0;
$module_custom_addtocart_buttonValue = isset( $options['module-custom-addtocart-button'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-custom-addtocart-button'] : 0;
$module_returntoshopValue = isset( $options['returntoshop'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['returntoshop'] : 0;
$module_loginregistrationredirectValue = isset( $options['loginregistrationredirect'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['loginregistrationredirect'] : 0;
$module_freeshippingnoticeValue = isset( $options['freeshippingnotice'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['freeshippingnotice'] : 0;
$module_hideshippingmethods = isset( $options['module-hideshippingmethods'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-hideshippingmethods'] : 0;
$module_legalcheckoutValue = isset( $options['legalcheckout'] ) && ( 'active' == SURBMA_HC_PLUGIN_LICENSE || !isset( $options['brandnewuser'] ) ) ? $options['legalcheckout'] : 0;
$module_productsettingsValue = isset( $options['module-productsettings'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-productsettings'] : 0;
$module_globalinfoValue = isset( $options['module-globalinfo'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-globalinfo'] : 0;
$module_smtpValue = isset( $options['module-smtp'] ) && 'active' == SURBMA_HC_PLUGIN_LICENSE ? $options['module-smtp'] : 0;

if ( 1 == $module_taxnumberValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/tax-number.php';
}
if ( 1 == $module_checkoutValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/checkout.php';
}
if ( 1 == $module_couponValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/coupon.php';
}
if ( 1 == $module_plusminusValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/plus-minus-buttons.php';
}
if ( 1 == $module_updatecartValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/update-cart.php';
}
if ( 1 == $module_redirectcartValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/redirect-cart.php';
}
if ( 1 == $module_oneproductincartValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/one-product-in-cart.php';
}
if ( 1 == $module_custom_addtocart_buttonValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/custom-addtocart-button.php';
}
if ( 1 == $module_returntoshopValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/return-to-shop.php';
}
if ( 1 == $module_loginregistrationredirectValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/login-registration-redirect.php';
}
if ( 1 == $module_freeshippingnoticeValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/free-shipping-notice.php';
}
if ( 1 == $module_hideshippingmethods ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/hide-shipping-methods.php';
}
if ( 1 == $module_legalcheckoutValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/legal-checkout.php';
}
if ( 1 == $module_productsettingsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/product-settings.php';
}
if ( 1 == $module_globalinfoValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/global-info.php';
}
if ( 1 == $module_smtpValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/smtp.php';
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
