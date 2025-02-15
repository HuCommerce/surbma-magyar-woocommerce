<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// * HUCOMMERCE START

// Free HU modules
$module_huformatfixValue = $options['huformatfix'] ?? 0;
$module_nocountyValue = $options['nocounty'] ?? 0;
$module_autofillcityValue = $options['autofillcity'] ?? 0;
$module_translationsValue = $options['translations'] ?? 0;

// New Pro HU modules
$module_productpricehistoryValue = $options['module-productpricehistory'] ?? 0;
// Force Product Price History module to load to save data for everyone
$module_productpricehistoryValue = 1;

// Legacy Pro HU modules
$module_maskcheckoutfieldsValue = SURBMA_HC_PREMIUM || !isset( $options['brandnewuser'] ) || ( $options['legacyuser'] ?? 0 ) == 1 ? ( $options['maskcheckoutfields'] ?? 0 ) : 0;
$module_validatecheckoutfieldsValue = SURBMA_HC_PREMIUM || !isset( $options['brandnewuser'] ) || ( $options['legacyuser'] ?? 0 ) == 1 ? ( $options['validatecheckoutfields'] ?? 0 ) : 0;

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

// Free modules
$module_taxnumberValue = $options['taxnumber'] ?? 0;
$module_checkoutValue = $options['module-checkout'] ?? 0;
$module_couponValue = $options['module-coupon'] ?? 0;
$module_plusminusValue = $options['plusminus'] ?? 0;
$module_updatecartValue = $options['updatecart'] ?? 0;
$module_redirectcartValue = $options['module-redirectcart'] ?? 0;
$module_oneproductincartValue = $options['module-oneproductincart'] ?? 0;
$module_custom_addtocart_buttonValue = $options['module-custom-addtocart-button'] ?? 0;
$module_returntoshopValue = $options['returntoshop'] ?? 0;
$module_loginregistrationredirectValue = $options['loginregistrationredirect'] ?? 0;
$module_hideshippingmethods = $options['module-hideshippingmethods'] ?? 0;
$module_productsettingsValue = $options['module-productsettings'] ?? 0;
$module_smtpValue = $options['module-smtp'] ?? 0;
$module_catalogmodeValue = $options['module-catalogmode'] ?? 0;

// New Pro modules
$module_emptycartbuttonValue = SURBMA_HC_PREMIUM ? ( $options['module-emptycartbutton'] ?? 0 ) : 0;
$module_productpriceadditionsValue = SURBMA_HC_PREMIUM ? ( $options['module-productpriceadditions'] ?? 0 ) : 0;
$module_limitpaymentmethodsValue = SURBMA_HC_PREMIUM ? ( $options['module-limitpaymentmethods'] ?? 0 ) : 0;
$module_translationsValue = SURBMA_HC_PREMIUM ? ( $options['module-translations'] ?? 0 ) : 0;

// Legacy Pro modules
$module_freeshippingnoticeValue = SURBMA_HC_PREMIUM || !isset( $options['brandnewuser'] ) || ( $options['legacyuser'] ?? 0 ) == 1 ? ( $options['freeshippingnotice'] ?? 0 ) : 0;
$module_legalcheckoutValue = SURBMA_HC_PREMIUM || !isset( $options['brandnewuser'] ) || ( $options['legacyuser'] ?? 0 ) == 1 ? ( $options['legalcheckout'] ?? 0 ) : 0;
$module_globalinfoValue = SURBMA_HC_PREMIUM || !isset( $options['brandnewuser'] ) || ( $options['legacyuser'] ?? 0 ) == 1 ? ( $options['module-globalinfo'] ?? 0 ) : 0;

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
if ( 1 == $module_emptycartbuttonValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/empty-cart-button.php';
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
if ( 1 == $module_limitpaymentmethodsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/limit-payment-methods.php';
}
if ( 1 == $module_globalinfoValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/global-info.php';
}
if ( 1 == $module_smtpValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/smtp.php';
}
if ( 1 == $module_productpriceadditionsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/product-price-additions.php';
}
if ( 1 == $module_catalogmodeValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/catalog-mode.php';
}
if ( 1 == $module_translationsValue ) {
	include_once SURBMA_HC_PLUGIN_DIR . '/modules/translations.php';
}
