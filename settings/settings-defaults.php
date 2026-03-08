<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Get all default values for plugin options.
 *
 * This function returns the default value for each option key.
 * Translation keys (translations-*) are NOT included here - their default is 0 (disabled).
 *
 * @return array Associative array of option_key => default_value
 */
function cps_hc_gems_get_defaults() {
	return array(
		// =====================================================================
		// CHECKBOX FIELDS (default: 0)
		// =====================================================================

		// HuCommerce specific
		'huformatfix'                        => 0,
		'nocounty'                           => 0,
		'autofillcity'                       => 0,
		'translations'                       => 0,
		'maskcheckoutfields'                 => 0,
		'validatecheckoutfields'             => 0,
		'maskcheckoutfieldsplaceholder'      => 0,
		'maskbillingtaxfield'                => 0,
		'maskbillingpostcodefield'           => 0,
		'maskbillingphonefield'              => 0,
		'maskshippingpostcodefield'          => 0,
		'validatebillingtaxfield'            => 0,
		'validatebillingcityfield'           => 0,
		'validatebillingaddressfield'        => 0,
		'validatebillingphonefield'          => 0,
		'validateshippingcityfield'          => 0,
		'validateshippingaddressfield'       => 0,
		'validatecheckoutfields-mobileonly'  => 0,
		'productpricehistory-showlowestprice' => 0,
		'productpricehistory-showdiscount'   => 0,

		// Module toggles
		'taxnumber'                          => 0,
		'module-checkout'                    => 0,
		'plusminus'                          => 0,
		'updatecart'                         => 0,
		'module-redirectcart'                => 0,
		'module-emptycartbutton'             => 0,
		'module-oneproductincart'            => 0,
		'module-custom-addtocart-button'     => 0,
		'returntoshop'                       => 0,
		'loginregistrationredirect'          => 0,
		'freeshippingnotice'                 => 0,
		'module-hideshippingmethods'         => 0,
		'legalcheckout'                      => 0,
		'module-productsettings'             => 0,
		'module-limitpaymentmethods'         => 0,
		'module-globalinfo'                  => 0,
		'module-smtp'                        => 0,
		'module-catalogmode'                 => 0,
		'module-translations'                => 0,

		// Checkout options
		'taxnumberplaceholder'               => 0,
		'billingcompanycheck'                => 0,
		'checkout-hidecompanytaxfields'      => 0,
		'nocountry'                          => 0,
		'noordercomments'                    => 0,
		'noadditionalinformation'            => 0,
		'companytaxnumberpair'               => 0,
		'postcodecitypair'                   => 0,
		'phoneemailpair'                     => 0,
		'emailtothetop'                      => 0,
		'couponfieldhiddenoncart'            => 0,
		'couponfieldhiddenoncheckout'        => 0,
		'couponfieldalwaysvisible'           => 0,
		'freeshippingnoticeshoploop'         => 0,
		'freeshippingnoticecart'             => 0,
		'freeshippingnoticecheckout'         => 0,
		'freeshippingcouponsdiscounts'       => 0,
		'freeshippingwithouttax'             => 0,
		'hideshippingmethods-cart'           => 0,
		'regip'                              => 0,
		'legalcheckout-custom1optional'      => 0,
		'legalcheckout-custom2optional'      => 0,
		'addtocartonarchive'                 => 0,
		'productsubtitle'                    => 0,
		'productsettings-removeimagezoom'    => 0,
		'norelatedproducts'                  => 0,

		// =====================================================================
		// SELECT FIELDS (default: first/standard option)
		// =====================================================================

		'couponfieldposition'                      => 'beforecheckoutform',
		'returntoshopcartposition'                 => 'cartactions',
		'returntoshopcheckoutposition'             => 'nocheckout',
		'shippingmethodstohide'                    => 'showall',
		'legalconfirmationsposition'               => 'woocommerce_review_order_before_submit',
		'smtpport'                                 => '587',
		'smtpsecure'                               => 'default',
		'emptycartbutton-cartpage'                 => 'none',
		'emptycartbutton-checkoutpage'             => 'none',
		'productpricehistory-statisticslinkdisplay' => 'show',
		'catalogmode-productpricedisplay'          => 'show_prices',

		// =====================================================================
		// TEXT FIELDS (default: empty string)
		// =====================================================================

		'checkout-customsubmitbuttontext'          => '',
		'returntoshopmessage'                      => '',
		'loginredirecturl'                         => '',
		'registrationredirecturl'                  => '',

		// Custom Add to Cart buttons - Single product
		'custom-addtocart-button-single-simple'              => '',
		'custom-addtocart-button-single-grouped'             => '',
		'custom-addtocart-button-single-external'            => '',
		'custom-addtocart-button-single-variable'            => '',
		'custom-addtocart-button-single-subscription'        => '',
		'custom-addtocart-button-single-variable-subscription' => '',
		'custom-addtocart-button-single-booking'             => '',

		// Custom Add to Cart buttons - Archive
		'custom-addtocart-button-archive-simple'             => '',
		'custom-addtocart-button-archive-grouped'            => '',
		'custom-addtocart-button-archive-external'           => '',
		'custom-addtocart-button-archive-variable'           => '',
		'custom-addtocart-button-archive-subscription'       => '',
		'custom-addtocart-button-archive-variable-subscription' => '',
		'custom-addtocart-button-archive-booking'            => '',

		// Empty cart button texts
		'emptycartbutton-checkoutpagemessage'        => '',
		'emptycartbutton-checkoutpagelinktext'       => '',
		'emptycartbutton-checkoutpageconfirmationtext' => '',

		// Legal checkout
		'legalcheckouttitle'                         => '',

		// Global info
		'globalinfoname'                             => '',
		'globalinfocompany'                          => '',
		'globalinfoheadquarters'                     => '',
		'globalinfotaxnumber'                        => '',
		'globalinforegnumber'                        => '',
		'globalinfoaddress'                          => '',
		'globalinfobankaccount'                      => '',
		'globalinfomobile'                           => '',
		'globalinfophone'                            => '',
		'globalinfoemail'                            => '',

		// SMTP settings
		'smtpfrom'                                   => '',
		'smtpfromname'                               => '',
		'smtphost'                                   => '',
		'smtpuser'                                   => '',
		'smtppassword'                               => '',

		// Product price additions
		'productpricehistory-statisticslinktext'     => '',
		'productpriceadditions-product-prefix'       => '',
		'productpriceadditions-product-suffix'       => '',
		'productpriceadditions-archive-prefix'       => '',
		'productpriceadditions-archive-suffix'       => '',

		// =====================================================================
		// TEXTAREA FIELDS (default: empty string)
		// =====================================================================

		'productpricehistory-lowestpricetext'        => '',
		'productpricehistory-nolowestpricetext'      => '',
		'productpricehistory-discounttext'           => '',
		'productpricehistory-nolowestpricediscounttext' => '',
		'freeshippingnoticemessage'                  => '',
		'freeshippingsuccessfulmessage'              => '',
		'regacceptpp'                                => '',
		'legalcheckouttext'                          => '',
		'accepttos'                                  => '',
		'acceptpp'                                   => '',
		'acceptcustom1label'                         => '',
		'acceptcustom1'                              => '',
		'acceptcustom2label'                         => '',
		'acceptcustom2'                              => '',
		'beforeorderbuttonmessage'                   => '',
		'afterorderbuttonmessage'                    => '',
		'globalinfoaboutus'                          => '',

		// =====================================================================
		// NUMERIC FIELDS (default: empty string - displayed as empty in form)
		// =====================================================================

		'productsnumber'                             => '',
		'productsperrow'                             => '',
		'upsellproductsnumber'                       => '',
		'upsellproductsperrow'                       => '',
		'relatedproductsnumber'                      => '',
		'relatedproductsperrow'                      => '',
		'freeshippingminimumorderamount'             => '',
	);
}

/**
 * Get the default value for a specific option key.
 *
 * @param string $key The option key.
 * @return mixed The default value, or 0 for translations-* keys, or null if not found.
 */
function cps_hc_gems_get_default( $key ) {
	// Translation keys default to 0 (disabled)
	if ( strpos( $key, 'translations-' ) === 0 ) {
		return 0;
	}

	$defaults = cps_hc_gems_get_defaults();
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : null;
}
