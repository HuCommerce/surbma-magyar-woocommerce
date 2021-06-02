<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;
// Localization
add_action( 'plugins_loaded', function () {
    load_plugin_textdomain( 'surbma-magyar-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
} );
// * HUCOMMERCE START
// Freemius SDK wrap to prevent conflicts with premium version.
if ( function_exists( 'hucommerce_fs' ) ) {
    // Set license.
    
    if ( hucommerce_fs()->can_use_premium_code() ) {
        define( 'SURBMA_HC_PLUGIN_LICENSE', 'valid' );
    } elseif ( defined( 'SURBMA_HC_PLUGIN_VERSION' ) && SURBMA_HC_PLUGIN_VERSION == 'premium' ) {
        define( 'SURBMA_HC_PLUGIN_LICENSE', 'expired' );
    }

}
// Set plugin version to free if premium version is not active.
if ( !defined( 'SURBMA_HC_PLUGIN_VERSION' ) ) {
    define( 'SURBMA_HC_PLUGIN_VERSION', 'free' );
}
// Set plugin license to free if premium version is not active.
if ( !defined( 'SURBMA_HC_PLUGIN_LICENSE' ) ) {
    define( 'SURBMA_HC_PLUGIN_LICENSE', 'free' );
}
// * HUCOMMERCE END
// CPS SDK

if ( !function_exists( 'cps' ) ) {
    function cps()
    {
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
$options = get_option( 'surbma_hc_fields' );
// * HUCOMMERCE START
// HU Modules
$huformatfixValue = ( isset( $options['huformatfix'] ) ? $options['huformatfix'] : 1 );
$nocountyValue = ( isset( $options['nocounty'] ) ? $options['nocounty'] : 0 );
$autofillcityValue = ( isset( $options['autofillcity'] ) ? $options['autofillcity'] : 0 );
$maskcheckoutfieldsValue = ( isset( $options['maskcheckoutfields'] ) ? $options['maskcheckoutfields'] : 0 );
$validatecheckoutfieldsValue = ( isset( $options['validatecheckoutfields'] ) ? $options['validatecheckoutfields'] : 0 );
$translationsValue = ( isset( $options['translations'] ) ? $options['translations'] : 0 );
if ( 1 == $huformatfixValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/hu-format-fix.php';
}
if ( 1 == $nocountyValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/no-county.php';
}
if ( 1 == $autofillcityValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/autofill-city.php';
}
if ( 1 == $maskcheckoutfieldsValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/mask-checkout-fields.php';
}
if ( 1 == $validatecheckoutfieldsValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/vaildate-checkout-fields.php';
}
if ( 1 == $translationsValue && !is_admin() ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules-hu/translations.php';
}
// * HUCOMMERCE END
// Modules
$taxnumberValue = ( isset( $options['taxnumber'] ) ? $options['taxnumber'] : 0 );
$module_checkoutValue = ( isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0 );
$plusminusValue = ( isset( $options['plusminus'] ) ? $options['plusminus'] : 0 );
$updatecartValue = ( isset( $options['updatecart'] ) ? $options['updatecart'] : 0 );
$returntoshopValue = ( isset( $options['returntoshop'] ) ? $options['returntoshop'] : 0 );
$loginregistrationredirectValue = ( isset( $options['loginregistrationredirect'] ) ? $options['loginregistrationredirect'] : 0 );
$freeshippingnoticeValue = ( isset( $options['freeshippingnotice'] ) ? $options['freeshippingnotice'] : 0 );
$legalcheckoutValue = ( isset( $options['legalcheckout'] ) ? $options['legalcheckout'] : 0 );
$module_productsettingsValue = ( isset( $options['module-productsettings'] ) ? $options['module-productsettings'] : 0 );
if ( 1 == $taxnumberValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/tax-number.php';
}
if ( 1 == $module_checkoutValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/checkout.php';
}
if ( 1 == $plusminusValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/plus-minus-buttons.php';
}
if ( 1 == $updatecartValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/update-cart.php';
}
if ( 1 == $returntoshopValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/return-to-shop.php';
}
if ( 1 == $loginregistrationredirectValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/login-registration-redirect.php';
}
if ( 1 == $freeshippingnoticeValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/free-shipping-notice.php';
}
if ( 1 == $legalcheckoutValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/legal-checkout.php';
}
if ( 1 == $module_productsettingsValue ) {
    include_once SURBMA_HC_PLUGIN_DIR . '/modules/product-settings.php';
}
// Add plugin woocommerce templates if exist
add_filter(
    'woocommerce_locate_template',
    function ( $template, $template_name, $template_path ) {
    global  $woocommerce ;
    $_template = $template;
    if ( !$template_path ) {
        $template_path = $woocommerce->template_url;
    }
    $plugin_path = SURBMA_HC_PLUGIN_DIR . '/woocommerce/';
    // Look within passed path within the theme – this is priority
    $template = locate_template( array( $template_path . $template_name, $template_name ) );
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
},
    10,
    3
);