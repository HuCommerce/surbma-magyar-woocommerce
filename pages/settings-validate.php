<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */

function surbma_hc_fields_validate( $input ) {
	// Get the settings array
	global $hc_gems_options;

	// Get the select options
	global $couponfieldposition_options;
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;
	global $shippingmethodstohide_options;
	global $legalconfirmationsposition_options;
	global $smtpport_options;
	global $smtpsecure_options;
	global $emptycartbutton_cartpage_options;
	global $emptycartbutton_checkoutpage_options;
	global $productpricehistory_statisticslinkdisplay_options;
	global $catalogmode_productpricedisplay_options;

	// * HUCOMMERCE START

	// Checkbox validation.
	$input['huformatfix'] = isset( $input['huformatfix'] ) && 1 == $input['huformatfix'] ? 1 : 0;
	$input['nocounty'] = isset( $input['nocounty'] ) && 1 == $input['nocounty'] ? 1 : 0;
	$input['autofillcity'] = isset( $input['autofillcity'] ) && 1 == $input['autofillcity'] ? 1 : 0;
	$input['translations'] = isset( $input['translations'] ) && 1 == $input['translations'] ? 1 : 0;
	$input['maskcheckoutfields'] = isset( $input['maskcheckoutfields'] ) && 1 == $input['maskcheckoutfields'] ? 1 : 0;
	$input['validatecheckoutfields'] = isset( $input['validatecheckoutfields'] ) && 1 == $input['validatecheckoutfields'] ? 1 : 0;
	$input['maskcheckoutfieldsplaceholder'] = isset( $input['maskcheckoutfieldsplaceholder'] ) && 1 == $input['maskcheckoutfieldsplaceholder'] ? 1 : 0;
	$input['maskbillingtaxfield'] = isset( $input['maskbillingtaxfield'] ) && 1 == $input['maskbillingtaxfield'] ? 1 : 0;
	$input['maskbillingpostcodefield'] = isset( $input['maskbillingpostcodefield'] ) && 1 == $input['maskbillingpostcodefield'] ? 1 : 0;
	$input['maskbillingphonefield'] = isset( $input['maskbillingphonefield'] ) && 1 == $input['maskbillingphonefield'] ? 1 : 0;
	$input['maskshippingpostcodefield'] = isset( $input['maskshippingpostcodefield'] ) && 1 == $input['maskshippingpostcodefield'] ? 1 : 0;
	$input['validatebillingtaxfield'] = isset( $input['validatebillingtaxfield'] ) && 1 == $input['validatebillingtaxfield'] ? 1 : 0;
	$input['validatebillingcityfield'] = isset( $input['validatebillingcityfield'] ) && 1 == $input['validatebillingcityfield'] ? 1 : 0;
	$input['validatebillingaddressfield'] = isset( $input['validatebillingaddressfield'] ) && 1 == $input['validatebillingaddressfield'] ? 1 : 0;
	$input['validatebillingphonefield'] = isset( $input['validatebillingphonefield'] ) && 1 == $input['validatebillingphonefield'] ? 1 : 0;
	$input['validateshippingcityfield'] = isset( $input['validateshippingcityfield'] ) && 1 == $input['validateshippingcityfield'] ? 1 : 0;
	$input['validateshippingaddressfield'] = isset( $input['validateshippingaddressfield'] ) && 1 == $input['validateshippingaddressfield'] ? 1 : 0;
	$input['validatecheckoutfields-mobileonly'] = isset( $input['validatecheckoutfields-mobileonly'] ) && 1 == $input['validatecheckoutfields-mobileonly'] ? 1 : 0;
	$input['productpricehistory-showlowestprice'] = isset( $input['productpricehistory-showlowestprice'] ) && 1 == $input['productpricehistory-showlowestprice'] ? 1 : 0;
	$input['productpricehistory-showdiscount'] = isset( $input['productpricehistory-showdiscount'] ) && 1 == $input['productpricehistory-showdiscount'] ? 1 : 0;

	// Say our text/textarea option must be safe text with the allowed tags for posts
	$input['productpricehistory-lowestpricetext'] = wp_filter_post_kses( $input['productpricehistory-lowestpricetext'] );
	$input['productpricehistory-nolowestpricetext'] = wp_filter_post_kses( $input['productpricehistory-nolowestpricetext'] );
	$input['productpricehistory-discounttext'] = wp_filter_post_kses( $input['productpricehistory-discounttext'] );
	$input['productpricehistory-nolowestpricediscounttext'] = wp_filter_post_kses( $input['productpricehistory-nolowestpricediscounttext'] );

	// Our select option must actually be in our array of select options
	if ( !array_key_exists( $input['productpricehistory-statisticslinkdisplay'], $productpricehistory_statisticslinkdisplay_options ) ) {
		$input['productpricehistory-statisticslinkdisplay'] = 'show';
	}

	// * HUCOMMERCE END

	// Checkbox validation.
	$input['taxnumber'] = isset( $input['taxnumber'] ) && 1 == $input['taxnumber'] ? 1 : 0;
	$input['module-checkout'] = isset( $input['module-checkout'] ) && 1 == $input['module-checkout'] ? 1 : 0;
	$input['plusminus'] = isset( $input['plusminus'] ) && 1 == $input['plusminus'] ? 1 : 0;
	$input['updatecart'] = isset( $input['updatecart'] ) && 1 == $input['updatecart'] ? 1 : 0;
	$input['module-redirectcart'] = isset( $input['module-redirectcart'] ) && 1 == $input['module-redirectcart'] ? 1 : 0;
	$input['module-emptycartbutton'] = isset( $input['module-emptycartbutton'] ) && 1 == $input['module-emptycartbutton'] ? 1 : 0;
	$input['module-oneproductincart'] = isset( $input['module-oneproductincart'] ) && 1 == $input['module-oneproductincart'] ? 1 : 0;
	$input['module-custom-addtocart-button'] = isset( $input['module-custom-addtocart-button'] ) && 1 == $input['module-custom-addtocart-button'] ? 1 : 0;
	$input['returntoshop'] = isset( $input['returntoshop'] ) && 1 == $input['returntoshop'] ? 1 : 0;
	$input['loginregistrationredirect'] = isset( $input['loginregistrationredirect'] ) && 1 == $input['loginregistrationredirect'] ? 1 : 0;
	$input['freeshippingnotice'] = isset( $input['freeshippingnotice'] ) && 1 == $input['freeshippingnotice'] ? 1 : 0;
	$input['module-hideshippingmethods'] = isset( $input['module-hideshippingmethods'] ) && 1 == $input['module-hideshippingmethods'] ? 1 : 0;
	$input['legalcheckout'] = isset( $input['legalcheckout'] ) && 1 == $input['legalcheckout'] ? 1 : 0;
	$input['module-productsettings'] = isset( $input['module-productsettings'] ) && 1 == $input['module-productsettings'] ? 1 : 0;
	$input['module-limitpaymentmethods'] = isset( $input['module-limitpaymentmethods'] ) && 1 == $input['module-limitpaymentmethods'] ? 1 : 0;
	$input['module-globalinfo'] = isset( $input['module-globalinfo'] ) && 1 == $input['module-globalinfo'] ? 1 : 0;
	$input['module-smtp'] = isset( $input['module-smtp'] ) && 1 == $input['module-smtp'] ? 1 : 0;
	$input['module-catalogmode'] = isset( $input['module-catalogmode'] ) && 1 == $input['module-catalogmode'] ? 1 : 0;
	$input['module-translations'] = isset( $input['module-translations'] ) && 1 == $input['module-translations'] ? 1 : 0;

	$input['taxnumberplaceholder'] = isset( $input['taxnumberplaceholder'] ) && 1 == $input['taxnumberplaceholder'] ? 1 : 0;
	$input['billingcompanycheck'] = isset( $input['billingcompanycheck'] ) && 1 == $input['billingcompanycheck'] ? 1 : 0;
	$input['checkout-hidecompanytaxfields'] = isset( $input['checkout-hidecompanytaxfields'] ) && 1 == $input['checkout-hidecompanytaxfields'] ? 1 : 0;
	$input['nocountry'] = isset( $input['nocountry'] ) && 1 == $input['nocountry'] ? 1 : 0;
	$input['noordercomments'] = isset( $input['noordercomments'] ) && 1 == $input['noordercomments'] ? 1 : 0;
	$input['noadditionalinformation'] = isset( $input['noadditionalinformation'] ) && 1 == $input['noadditionalinformation'] ? 1 : 0;
	$input['companytaxnumberpair'] = isset( $input['companytaxnumberpair'] ) && 1 == $input['companytaxnumberpair'] ? 1 : 0;
	$input['postcodecitypair'] = isset( $input['postcodecitypair'] ) && 1 == $input['postcodecitypair'] ? 1 : 0;
	$input['phoneemailpair'] = isset( $input['phoneemailpair'] ) && 1 == $input['phoneemailpair'] ? 1 : 0;
	$input['emailtothetop'] = isset( $input['emailtothetop'] ) && 1 == $input['emailtothetop'] ? 1 : 0;
	$input['couponfieldhiddenoncart'] = isset( $input['couponfieldhiddenoncart'] ) && 1 == $input['couponfieldhiddenoncart'] ? 1 : 0;
	$input['couponfieldhiddenoncheckout'] = isset( $input['couponfieldhiddenoncheckout'] ) && 1 == $input['couponfieldhiddenoncheckout'] ? 1 : 0;
	$input['couponfieldalwaysvisible'] = isset( $input['couponfieldalwaysvisible'] ) && 1 == $input['couponfieldalwaysvisible'] ? 1 : 0;
	$input['freeshippingnoticeshoploop'] = isset( $input['freeshippingnoticeshoploop'] ) && 1 == $input['freeshippingnoticeshoploop'] ? 1 : 0;
	$input['freeshippingnoticecart'] = isset( $input['freeshippingnoticecart'] ) && 1 == $input['freeshippingnoticecart'] ? 1 : 0;
	$input['freeshippingnoticecheckout'] = isset( $input['freeshippingnoticecheckout'] ) && 1 == $input['freeshippingnoticecheckout'] ? 1 : 0;
	$input['freeshippingcouponsdiscounts'] = isset( $input['freeshippingcouponsdiscounts'] ) && 1 == $input['freeshippingcouponsdiscounts'] ? 1 : 0;
	$input['freeshippingwithouttax'] = isset( $input['freeshippingwithouttax'] ) && 1 == $input['freeshippingwithouttax'] ? 1 : 0;
	$input['hideshippingmethods-cart'] = isset( $input['hideshippingmethods-cart'] ) && 1 == $input['hideshippingmethods-cart'] ? 1 : 0;
	$input['regip'] = isset( $input['regip'] ) && 1 == $input['regip'] ? 1 : 0;
	$input['legalcheckout-custom1optional'] = isset( $input['legalcheckout-custom1optional'] ) && 1 == $input['legalcheckout-custom1optional'] ? 1 : 0;
	$input['legalcheckout-custom2optional'] = isset( $input['legalcheckout-custom2optional'] ) && 1 == $input['legalcheckout-custom2optional'] ? 1 : 0;
	$input['addtocartonarchive'] = isset( $input['addtocartonarchive'] ) && 1 == $input['addtocartonarchive'] ? 1 : 0;
	$input['productsubtitle'] = isset( $input['productsubtitle'] ) && 1 == $input['productsubtitle'] ? 1 : 0;
	$input['productsettings-removeimagezoom'] = isset( $input['productsettings-removeimagezoom'] ) && 1 == $input['productsettings-removeimagezoom'] ? 1 : 0;
	$input['norelatedproducts'] = isset( $input['norelatedproducts'] ) && 1 == $input['norelatedproducts'] ? 1 : 0;
	$input['translations-woocommerceapimanager'] = isset( $input['translations-woocommerceapimanager'] ) && 1 == $input['translations-woocommerceapimanager'] ? 1 : 0;
	$input['translations-restrictcontentpro'] = isset( $input['translations-restrictcontentpro'] ) && 1 == $input['translations-restrictcontentpro'] ? 1 : 0;
	$input['translations-woocommercememberships'] = isset( $input['translations-woocommercememberships'] ) && 1 == $input['translations-woocommercememberships'] ? 1 : 0;
	$input['translations-woocommercesubscriptions'] = isset( $input['translations-woocommercesubscriptions'] ) && 1 == $input['translations-woocommercesubscriptions'] ? 1 : 0;

	// Our select option must actually be in our array of select options
	if ( !array_key_exists( $input['couponfieldposition'], $couponfieldposition_options ) ) {
		$input['couponfieldposition'] = 'beforecheckoutform';
	}
	if ( !array_key_exists( $input['returntoshopcartposition'], $returntoshopcartposition_options ) ) {
		$input['returntoshopcartposition'] = 'cartactions';
	}
	if ( !array_key_exists( $input['returntoshopcheckoutposition'], $returntoshopcheckoutposition_options ) ) {
		$input['returntoshopcheckoutposition'] = 'nocheckout';
	}
	if ( !array_key_exists( $input['shippingmethodstohide'], $shippingmethodstohide_options ) ) {
		$input['shippingmethodstohide'] = 'showall';
	}
	if ( !array_key_exists( $input['legalconfirmationsposition'], $legalconfirmationsposition_options ) ) {
		$input['legalconfirmationsposition'] = 'woocommerce_review_order_before_submit';
	}
	if ( !array_key_exists( $input['smtpport'], $smtpport_options ) ) {
		$input['smtpport'] = '587';
	}
	if ( !array_key_exists( $input['smtpsecure'], $smtpsecure_options ) ) {
		$input['smtpsecure'] = 'default';
	}
	if ( !array_key_exists( $input['emptycartbutton-cartpage'], $emptycartbutton_cartpage_options ) ) {
		$input['emptycartbutton-cartpage'] = 'none';
	}
	if ( !array_key_exists( $input['emptycartbutton-checkoutpage'], $emptycartbutton_checkoutpage_options ) ) {
		$input['emptycartbutton-checkoutpage'] = 'none';
	}
	if ( !array_key_exists( $input['catalogmode-productpricedisplay'], $catalogmode_productpricedisplay_options ) ) {
		$input['emptycartbutton-checkoutpage'] = 'none';
	}

	// Say our text option must be safe text with no HTML tags
	$input['checkout-customsubmitbuttontext'] = wp_filter_nohtml_kses( $input['checkout-customsubmitbuttontext'] );
	$input['returntoshopmessage'] = wp_filter_nohtml_kses( $input['returntoshopmessage'] );
	$input['loginredirecturl'] = wp_filter_nohtml_kses( $input['loginredirecturl'] );
	$input['registrationredirecturl'] = wp_filter_nohtml_kses( $input['registrationredirecturl'] );
	$input['custom-addtocart-button-single-simple'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-simple'] );
	$input['custom-addtocart-button-single-grouped'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-grouped'] );
	$input['custom-addtocart-button-single-external'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-external'] );
	$input['custom-addtocart-button-single-variable'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-variable'] );
	$input['custom-addtocart-button-single-subscription'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-subscription'] );
	$input['custom-addtocart-button-single-variable-subscription'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-variable-subscription'] );
	$input['custom-addtocart-button-single-booking'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-single-booking'] );
	$input['custom-addtocart-button-archive-simple'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-simple'] );
	$input['custom-addtocart-button-archive-grouped'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-grouped'] );
	$input['custom-addtocart-button-archive-external'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-external'] );
	$input['custom-addtocart-button-archive-variable'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-variable'] );
	$input['custom-addtocart-button-archive-subscription'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-subscription'] );
	$input['custom-addtocart-button-archive-variable-subscription'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-variable-subscription'] );
	$input['custom-addtocart-button-archive-booking'] = wp_filter_nohtml_kses( $input['custom-addtocart-button-archive-booking'] );
	$input['emptycartbutton-checkoutpagemessage'] = wp_filter_nohtml_kses( $input['emptycartbutton-checkoutpagemessage'] );
	$input['emptycartbutton-checkoutpagelinktext'] = wp_filter_nohtml_kses( $input['emptycartbutton-checkoutpagelinktext'] );
	$input['emptycartbutton-checkoutpageconfirmationtext'] = wp_filter_nohtml_kses( $input['emptycartbutton-checkoutpageconfirmationtext'] );
	$input['legalcheckouttitle'] = wp_filter_nohtml_kses( $input['legalcheckouttitle'] );
	$input['globalinfoname'] = wp_filter_nohtml_kses( $input['globalinfoname'] );
	$input['globalinfocompany'] = wp_filter_nohtml_kses( $input['globalinfocompany'] );
	$input['globalinfoheadquarters'] = wp_filter_nohtml_kses( $input['globalinfoheadquarters'] );
	$input['globalinfotaxnumber'] = wp_filter_nohtml_kses( $input['globalinfotaxnumber'] );
	$input['globalinforegnumber'] = wp_filter_nohtml_kses( $input['globalinforegnumber'] );
	$input['globalinfoaddress'] = wp_filter_nohtml_kses( $input['globalinfoaddress'] );
	$input['globalinfobankaccount'] = wp_filter_nohtml_kses( $input['globalinfobankaccount'] );
	$input['globalinfomobile'] = wp_filter_nohtml_kses( $input['globalinfomobile'] );
	$input['globalinfophone'] = wp_filter_nohtml_kses( $input['globalinfophone'] );
	$input['globalinfoemail'] = wp_filter_nohtml_kses( $input['globalinfoemail'] );
	$input['smtpfrom'] = wp_filter_nohtml_kses( $input['smtpfrom'] );
	$input['smtpfromname'] = wp_filter_nohtml_kses( $input['smtpfromname'] );
	$input['smtphost'] = wp_filter_nohtml_kses( $input['smtphost'] );
	$input['smtpuser'] = wp_filter_nohtml_kses( $input['smtpuser'] );
	$input['smtppassword'] = wp_filter_nohtml_kses( $input['smtppassword'] );
	$input['productpricehistory-statisticslinktext'] = wp_filter_nohtml_kses( $input['productpricehistory-statisticslinktext'] );
	$input['productpriceadditions-product-prefix'] = wp_filter_nohtml_kses( $input['productpriceadditions-product-prefix'] );
	$input['productpriceadditions-product-suffix'] = wp_filter_nohtml_kses( $input['productpriceadditions-product-suffix'] );
	$input['productpriceadditions-archive-prefix'] = wp_filter_nohtml_kses( $input['productpriceadditions-archive-prefix'] );
	$input['productpriceadditions-archive-suffix'] = wp_filter_nohtml_kses( $input['productpriceadditions-archive-suffix'] );

	// Say our text/textarea option must be safe text with the allowed tags for posts
	$input['freeshippingnoticemessage'] = wp_filter_post_kses( $input['freeshippingnoticemessage'] );
	$input['freeshippingsuccessfulmessage'] = wp_filter_post_kses( $input['freeshippingsuccessfulmessage'] );
	$input['regacceptpp'] = wp_filter_post_kses( $input['regacceptpp'] );
	$input['legalcheckouttext'] = wp_filter_post_kses( $input['legalcheckouttext'] );
	$input['accepttos'] = wp_filter_post_kses( $input['accepttos'] );
	$input['acceptpp'] = wp_filter_post_kses( $input['acceptpp'] );
	$input['acceptcustom1label'] = wp_filter_post_kses( $input['acceptcustom1label'] );
	$input['acceptcustom1'] = wp_filter_post_kses( $input['acceptcustom1'] );
	$input['acceptcustom2label'] = wp_filter_post_kses( $input['acceptcustom2label'] );
	$input['acceptcustom2'] = wp_filter_post_kses( $input['acceptcustom2'] );
	$input['beforeorderbuttonmessage'] = wp_filter_post_kses( $input['beforeorderbuttonmessage'] );
	$input['afterorderbuttonmessage'] = wp_filter_post_kses( $input['afterorderbuttonmessage'] );
	$input['globalinfoaboutus'] = wp_filter_post_kses( $input['globalinfoaboutus'] );

	// Say our text option must be numeric only
	$input['productsnumber'] = preg_replace( '/\D/', '', $input['productsnumber'] );
	$input['productsperrow'] = preg_replace( '/\D/', '', $input['productsperrow'] );
	$input['upsellproductsnumber'] = preg_replace( '/\D/', '', $input['upsellproductsnumber'] );
	$input['upsellproductsperrow'] = preg_replace( '/\D/', '', $input['upsellproductsperrow'] );
	$input['relatedproductsnumber'] = preg_replace( '/\D/', '', $input['relatedproductsnumber'] );
	$input['relatedproductsperrow'] = preg_replace( '/\D/', '', $input['relatedproductsperrow'] );
	$input['freeshippingminimumorderamount'] = preg_replace( '/\D/', '', $input['freeshippingminimumorderamount'] );

	// * HUCOMMERCE START
	// If no valid license, check if field has any value. If yes, save it, if no, set to default.
	if ( 'active' != SURBMA_HC_PLUGIN_LICENSE ) {
		// Check field formats (Masking)
		$input['maskcheckoutfieldsplaceholder'] = isset( $hc_gems_options['maskcheckoutfieldsplaceholder'] ) ? $hc_gems_options['maskcheckoutfieldsplaceholder'] : 0;
		$input['maskbillingtaxfield'] = isset( $hc_gems_options['maskbillingtaxfield'] ) ? $hc_gems_options['maskbillingtaxfield'] : 0;
		$input['maskbillingpostcodefield'] = isset( $hc_gems_options['maskbillingpostcodefield'] ) ? $hc_gems_options['maskbillingpostcodefield'] : 0;
		$input['maskbillingphonefield'] = isset( $hc_gems_options['maskbillingphonefield'] ) ? $hc_gems_options['maskbillingphonefield'] : 0;
		$input['maskshippingpostcodefield'] = isset( $hc_gems_options['maskshippingpostcodefield'] ) ? $hc_gems_options['maskshippingpostcodefield'] : 0;

		// Check field values
		$input['validatebillingtaxfield'] = isset( $hc_gems_options['validatebillingtaxfield'] ) ? $hc_gems_options['validatebillingtaxfield'] : 0;
		$input['validatebillingcityfield'] = isset( $hc_gems_options['validatebillingcityfield'] ) ? $hc_gems_options['validatebillingcityfield'] : 0;
		$input['validatebillingaddressfield'] = isset( $hc_gems_options['validatebillingaddressfield'] ) ? $hc_gems_options['validatebillingaddressfield'] : 0;
		$input['validatebillingphonefield'] = isset( $hc_gems_options['validatebillingphonefield'] ) ? $hc_gems_options['validatebillingphonefield'] : 0;
		$input['validateshippingcityfield'] = isset( $hc_gems_options['validateshippingcityfield'] ) ? $hc_gems_options['validateshippingcityfield'] : 0;
		$input['validateshippingaddressfield'] = isset( $hc_gems_options['validateshippingaddressfield'] ) ? $hc_gems_options['validateshippingaddressfield'] : 0;

		// Free shipping notification
		$input['freeshippingnoticeshoploop'] = isset( $hc_gems_options['freeshippingnoticeshoploop'] ) ? $hc_gems_options['freeshippingnoticeshoploop'] : 0;
		$input['freeshippingnoticecart'] = isset( $hc_gems_options['freeshippingnoticecart'] ) ? $hc_gems_options['freeshippingnoticecart'] : 0;
		$input['freeshippingnoticecheckout'] = isset( $hc_gems_options['freeshippingnoticecheckout'] ) ? $hc_gems_options['freeshippingnoticecheckout'] : 0;
		$input['freeshippingminimumorderamount'] = isset( $hc_gems_options['freeshippingminimumorderamount'] ) ? $hc_gems_options['freeshippingminimumorderamount'] : 0;
		$input['freeshippingcouponsdiscounts'] = isset( $hc_gems_options['freeshippingcouponsdiscounts'] ) ? $hc_gems_options['freeshippingcouponsdiscounts'] : 0;
		$input['freeshippingwithouttax'] = isset( $hc_gems_options['freeshippingwithouttax'] ) ? $hc_gems_options['freeshippingwithouttax'] : 0;
		$input['freeshippingnoticemessage'] = isset( $hc_gems_options['freeshippingnoticemessage'] ) ? $hc_gems_options['freeshippingnoticemessage'] : __( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' );
		$input['freeshippingsuccessfulmessage'] = isset( $hc_gems_options['freeshippingsuccessfulmessage'] ) ? $hc_gems_options['freeshippingsuccessfulmessage'] : '';

		// Empty Cart button
		$input['emptycartbutton-cartpage'] = isset( $hc_gems_options['emptycartbutton-cartpage'] ) ? $hc_gems_options['emptycartbutton-cartpage'] : 'none';
		$input['emptycartbutton-checkoutpage'] = isset( $hc_gems_options['emptycartbutton-checkoutpage'] ) ? $hc_gems_options['emptycartbutton-checkoutpage'] : 'none';
		$input['emptycartbutton-checkoutpagemessage'] = isset( $hc_gems_options['emptycartbutton-checkoutpagemessage'] ) ? $hc_gems_options['emptycartbutton-checkoutpagemessage'] : __( 'Changed your mind?', 'surbma-magyar-woocommerce' );
		$input['emptycartbutton-checkoutpagelinktext'] = isset( $hc_gems_options['emptycartbutton-checkoutpagelinktext'] ) ? $hc_gems_options['emptycartbutton-checkoutpagelinktext'] : __( 'Empty cart & continue shopping', 'surbma-magyar-woocommerce' );
		$input['emptycartbutton-checkoutpageconfirmationtext'] = isset( $hc_gems_options['emptycartbutton-checkoutpageconfirmationtext'] ) ? $hc_gems_options['emptycartbutton-checkoutpageconfirmationtext'] : __( 'Are you sure you want to empty the Cart?', 'surbma-magyar-woocommerce' );

		// Product price history
		$input['productpricehistory-showlowestprice'] = isset( $hc_gems_options['productpricehistory-showlowestprice'] ) ? $hc_gems_options['productpricehistory-showlowestprice'] : 0;
		$input['productpricehistory-lowestpricetext'] = isset( $hc_gems_options['productpricehistory-lowestpricetext'] ) ? $hc_gems_options['productpricehistory-lowestpricetext'] : __( 'Our lowest price from previous term', 'surbma-magyar-woocommerce' );
		$input['productpricehistory-nolowestpricetext'] = isset( $hc_gems_options['productpricehistory-nolowestpricetext'] ) ? $hc_gems_options['productpricehistory-nolowestpricetext'] : __( 'Actual sale price is our lowest price recently', 'surbma-magyar-woocommerce' );
		$input['productpricehistory-showdiscount'] = isset( $hc_gems_options['productpricehistory-showdiscount'] ) ? $hc_gems_options['productpricehistory-showdiscount'] : 0;
		$input['productpricehistory-discounttext'] = isset( $hc_gems_options['productpricehistory-discounttext'] ) ? $hc_gems_options['productpricehistory-discounttext'] : __( 'Current discount based on the lowest price', 'surbma-magyar-woocommerce' );
		$input['productpricehistory-nolowestpricediscounttext'] = isset( $hc_gems_options['productpricehistory-nolowestpricediscounttext'] ) ? $hc_gems_options['productpricehistory-nolowestpricediscounttext'] : __( 'Actual discount', 'surbma-magyar-woocommerce' );
		$input['productpricehistory-statisticslinkdisplay'] = isset( $hc_gems_options['productpricehistory-statisticslinkdisplay'] ) ? $hc_gems_options['productpricehistory-statisticslinkdisplay'] : 'show';
		$input['productpricehistory-statisticslinktext'] = isset( $hc_gems_options['productpricehistory-statisticslinktext'] ) ? $hc_gems_options['productpricehistory-statisticslinktext'] : __( 'Advanced statistics', 'surbma-magyar-woocommerce' );

		// Product price additions
		$input['productpriceadditions-product-prefix'] = isset( $hc_gems_options['productpriceadditions-product-prefix'] ) ? $hc_gems_options['productpriceadditions-product-prefix'] : '';
		$input['productpriceadditions-product-suffix'] = isset( $hc_gems_options['productpriceadditions-product-suffix'] ) ? $hc_gems_options['productpriceadditions-product-suffix'] : '';
		$input['productpriceadditions-archive-prefix'] = isset( $hc_gems_options['productpriceadditions-archive-prefix'] ) ? $hc_gems_options['productpriceadditions-archive-prefix'] : '';
		$input['productpriceadditions-archive-suffix'] = isset( $hc_gems_options['productpriceadditions-archive-suffix'] ) ? $hc_gems_options['productpriceadditions-archive-suffix'] : '';

		// Legal compliance
		$input['regip'] = isset( $hc_gems_options['regip'] ) ? $hc_gems_options['regip'] : 0;
		$input['regacceptpp'] = isset( $hc_gems_options['regacceptpp'] ) ? $hc_gems_options['regacceptpp'] : __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' );
		// FIX for deprecated value (revieworderbeforesubmit) if used on old version of the plugin
		$input['legalconfirmationsposition'] = isset( $hc_gems_options['legalconfirmationsposition'] ) && 'revieworderbeforesubmit' != $hc_gems_options['legalconfirmationsposition'] ? $hc_gems_options['legalconfirmationsposition'] : 'woocommerce_review_order_before_submit';
		$input['legalcheckouttitle'] = isset( $hc_gems_options['legalcheckouttitle'] ) ? $hc_gems_options['legalcheckouttitle'] : __( 'Legal confirmations', 'surbma-magyar-woocommerce' );
		$input['accepttos'] = isset( $hc_gems_options['accepttos'] ) ? $hc_gems_options['accepttos'] : __( 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'surbma-magyar-woocommerce' );
		$input['acceptpp'] = isset( $hc_gems_options['acceptpp'] ) ? $hc_gems_options['acceptpp'] : __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' );
		$input['acceptcustom1label'] = isset( $hc_gems_options['acceptcustom1label'] ) ? $hc_gems_options['acceptcustom1label'] : '';
		$input['acceptcustom1'] = isset( $hc_gems_options['acceptcustom1'] ) ? $hc_gems_options['acceptcustom1'] : '';
		$input['legalcheckout-custom1optional'] = isset( $hc_gems_options['legalcheckout-custom1optional'] ) ? $hc_gems_options['legalcheckout-custom1optional'] : 0;
		$input['acceptcustom2label'] = isset( $hc_gems_options['acceptcustom2label'] ) ? $hc_gems_options['acceptcustom2label'] : '';
		$input['acceptcustom2'] = isset( $hc_gems_options['acceptcustom2'] ) ? $hc_gems_options['acceptcustom2'] : '';
		$input['legalcheckout-custom2optional'] = isset( $hc_gems_options['legalcheckout-custom2optional'] ) ? $hc_gems_options['legalcheckout-custom2optional'] : 0;
		$input['beforeorderbuttonmessage'] = isset( $hc_gems_options['beforeorderbuttonmessage'] ) ? $hc_gems_options['beforeorderbuttonmessage'] : '';
		$input['afterorderbuttonmessage'] = isset( $hc_gems_options['afterorderbuttonmessage'] ) ? $hc_gems_options['afterorderbuttonmessage'] : '';

		// Global informations
		$input['globalinfoname'] = isset( $hc_gems_options['globalinfoname'] ) ? $hc_gems_options['globalinfoname'] : '';
		$input['globalinfocompany'] = isset( $hc_gems_options['globalinfocompany'] ) ? $hc_gems_options['globalinfocompany'] : '';
		$input['globalinfoheadquarters'] = isset( $hc_gems_options['globalinfoheadquarters'] ) ? $hc_gems_options['globalinfoheadquarters'] : '';
		$input['globalinfotaxnumber'] = isset( $hc_gems_options['globalinfotaxnumber'] ) ? $hc_gems_options['globalinfotaxnumber'] : '';
		$input['globalinforegnumber'] = isset( $hc_gems_options['globalinforegnumber'] ) ? $hc_gems_options['globalinforegnumber'] : '';
		$input['globalinfoaddress'] = isset( $hc_gems_options['globalinfoaddress'] ) ? $hc_gems_options['globalinfoaddress'] : '';
		$input['globalinfobankaccount'] = isset( $hc_gems_options['globalinfobankaccount'] ) ? $hc_gems_options['globalinfobankaccount'] : '';
		$input['globalinfomobile'] = isset( $hc_gems_options['globalinfomobile'] ) ? $hc_gems_options['globalinfomobile'] : '';
		$input['globalinfophone'] = isset( $hc_gems_options['globalinfophone'] ) ? $hc_gems_options['globalinfophone'] : '';
		$input['globalinfoemail'] = isset( $hc_gems_options['globalinfoemail'] ) ? $hc_gems_options['globalinfoemail'] : '';
		$input['globalinfoaboutus'] = isset( $hc_gems_options['globalinfoaboutus'] ) ? $hc_gems_options['globalinfoaboutus'] : '';
	}

	// Check legacy HuCommerce users
	$input['legacyuser'] = !isset( $hc_gems_options['brandnewuser'] ) || ( isset( $hc_gems_options['legacyuser'] ) && 1 == $hc_gems_options['legacyuser'] ) ? 1 : 0;

	// Check brand new HuCommerce users (from HuCommerce 2022.1.0 version)
	$input['brandnewuser'] = 1;

	// * HUCOMMERCE END

	return $input;
}

function surbma_hc_license_validate( $input ) {
	// Say our text option must be safe text with no HTML tags
	$input['product_id'] = wp_filter_nohtml_kses( $input['product_id'] );
	$input['instance'] = wp_filter_nohtml_kses( $input['instance'] );
	$input['licensekey'] = wp_filter_nohtml_kses( $input['licensekey'] );

	// Save a random string to trigger update_option_surbma_hc_license hook every time
	$input['random'] = wp_generate_password( 10, false );

	return $input;
}
