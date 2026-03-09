<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Get the modules configuration array
 *
 * This is the single source of truth for all module definitions.
 * Contains both loading configuration and UI properties for admin display.
 *
 * @return array The modules configuration array
 */
function cps_hc_gems_get_modules_config() {
	return [
		// Free HU modules
		'hu-format-fix' => [
			'option_key' => 'huformatfix',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
			'title' => __( 'Fixes for Hungarian language', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Fixes the name formats in Hungarian. Changes the order of Last name and First name.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'magyar-formatum-javitasok',
		],
		'no-county' => [
			'option_key' => 'nocounty',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
			'title' => __( 'Hide County field if Country is Hungary', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Using County for Hungarian addresses is very uncommon in Hungary.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion'],
			'doc_slug' => 'megye-mezo-elrejtese-magyar-cim-eseten',
		],
		'autofill-city' => [
			'option_key' => 'autofillcity',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
			'title' => __( 'Autofill City after Postcode is given', 'surbma-magyar-woocommerce' ),
			'description' => __( 'On the Checkout page the City field be automatically filled, when Postcode is entered by the customer.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion'],
			'doc_slug' => 'varos-automatikus-kitoltese-az-iranyitoszam-alapjan',
		],
		'translations-hu' => [
			'option_key' => 'translations',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
			'file' => 'translations.php',
			'frontend_only' => true,
			'title' => __( 'Hungarian translation fixes', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Temporary fixes for Hungarian translations, till the official translation doesn\'t include or missing some strings.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'forditasi-hianyossagok-javitasa',
		],

		// Pro HU modules
		'product-price-history' => [
			'option_key' => 'module-productpricehistory',
			'type' => 'pro_hu',
			'directory' => 'modules-hu',
			'force_enable' => true,
			'title' => __( 'Product price history', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Saves all Product price changes and can display the lowest price from the previous term. This is a Hungarian legal requirement to protect customers rights.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'conversion', 'legal'],
			'doc_slug' => 'termek-ar-tortenet',
		],

		// Legacy HU modules
		'mask-checkout-fields' => [
			'option_key' => 'maskcheckoutfields',
			'type' => 'legacy_hu',
			'directory' => 'modules-hu',
			'title' => __( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Masking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion'],
			'doc_slug' => 'mezok-formatumanak-ellenorzese-maszkolas',
		],
		'validate-checkout-fields' => [
			'option_key' => 'validatecheckoutfields',
			'type' => 'legacy_hu',
			'directory' => 'modules-hu',
			'file' => 'validate-checkout-fields.php',
			'title' => __( 'Check field values', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Checking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion'],
			'doc_slug' => 'mezok-ertekenek-ellenorzese',
		],

		// Free modules
		'translations' => [
			'option_key' => 'module-translations',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Translations for premium plugins & themes', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Adds translations for hundreds of the most popular premium plugins & themes. Supported softwares added regularly.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'forditasok',
		],
		'tax-number' => [
			'option_key' => 'taxnumber',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Tax number field', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Additional Tax field for Company details at Checkout.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'legal'],
			'doc_slug' => 'adoszam-megjelenitese',
		],
		'checkout' => [
			'option_key' => 'module-checkout',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Checkout page customizations', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Extra fields and other customizations on the Checkout page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion'],
			'doc_slug' => 'penztar-oldal-modositasok',
		],
		'coupon' => [
			'option_key' => 'module-coupon',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Coupon field customizations', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Useful settings for the Coupon field on the Checkout page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout'],
			'doc_slug' => 'kupon-mezo-modositasok',
		],
		'plus-minus-buttons' => [
			'option_key' => 'plusminus',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Plus/minus quantity buttons', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Shows plus/minus quantity buttons for products.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'cart'],
			'doc_slug' => 'plusz-minusz-mennyisegi-gombok',
		],
		'update-cart' => [
			'option_key' => 'updatecart',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Automatic Cart update', 'surbma-magyar-woocommerce' ),
			'description' => __( 'It will automatically update the cart, when customer changes the quantity on the Cart page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart'],
			'doc_slug' => 'kosar-automatikus-frissitese-darabszam-modositas-utan',
		],
		'redirect-cart' => [
			'option_key' => 'module-redirectcart',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Redirect Cart page to Checkout page', 'surbma-magyar-woocommerce' ),
			'description' => __( 'It will redirect the Cart page to Checkout page, so visitors can finish the purchase faster.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart', 'checkout', 'conversion'],
			'doc_slug' => 'kosar-atiranyitasa-a-penztar-oldalra',
		],
		'one-product-in-cart' => [
			'option_key' => 'module-oneproductincart',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'One product per purchase', 'surbma-magyar-woocommerce' ),
			'description' => __( 'It will allow only one product in the cart. If cart has a product already, it will be replaced by the new product.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'checkout'],
			'doc_slug' => 'egy-termek-vasarlasonkent',
		],
		'custom-addtocart-button' => [
			'option_key' => 'module-custom-addtocart-button',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Custom Add To Cart Button', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Customize the Add to cart buttons for your webhop.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'conversion'],
			'doc_slug' => 'egyedi-kosarba-teszem-gombok',
		],
		'return-to-shop' => [
			'option_key' => 'returntoshop',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Continue shopping buttons', 'surbma-magyar-woocommerce' ),
			'description' => __( 'A Continue shopping button on Cart and/or Checkout pages, that will bring customer to Shop page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart', 'checkout'],
			'doc_slug' => 'vasarlas-folytatasa-gombok',
		],
		'login-registration-redirect' => [
			'option_key' => 'loginregistrationredirect',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Login and registration redirection', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Set custom landing pages after login and/or registration.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'belepes-es-regisztracio-utani-atiranyitas',
		],
		'hide-shipping-methods' => [
			'option_key' => 'module-hideshippingmethods',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Hide shipping methods', 'surbma-magyar-woocommerce' ),
			'description' => __( 'It will hide all shipping methods, except free shipping, local pickup and other pickup points, when free shipping is available for customers.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart', 'checkout', 'conversion'],
			'doc_slug' => 'szallitasi-modok-elrejtese',
		],
		'product-settings' => [
			'option_key' => 'module-productsettings',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Product customizations', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Extra fields and other customizations for Products.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'conversion'],
			'doc_slug' => 'termek-modositasok',
		],
		'smtp' => [
			'option_key' => 'module-smtp',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'SMTP service', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Send emails from a 3rd party SMTP service, instead of using webserver\'s mail() function.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'smtp-szolgaltatas',
		],
		'catalog-mode' => [
			'option_key' => 'module-catalogmode',
			'type' => 'free',
			'directory' => 'modules',
			'title' => __( 'Catalog mode', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Disables all functions regarding purchasing products. Cart, Checkout and Account pages will be redirected to Shop page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'other'],
			'doc_slug' => 'katalogus-mod',
			'version_added' => '3.5.0',
		],

		// Pro modules
		'empty-cart-button' => [
			'option_key' => 'module-emptycartbutton',
			'type' => 'pro',
			'directory' => 'modules',
			'title' => __( 'Empty Cart button', 'surbma-magyar-woocommerce' ),
			'description' => __( 'It will display buttons, that can empty the entire Cart with one click. You can also add a custom link to your navigation with a special parameter, so it is possible to have an Empty Cart link in your menu. Read more about this option in our Documentation.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart', 'checkout'],
			'doc_slug' => 'kosar-uritese-gomb',
		],
		'product-price-additions' => [
			'option_key' => 'module-productpriceadditions',
			'type' => 'pro',
			'directory' => 'modules',
			'title' => __( 'Product price additions', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Set a default prefix or suffix for your prices. You can use this feature to give a unit of measure for your product prices or give a general information, that is specific for your webshop and your products. With the above settings you can give your default, global prefix and suffix, but you can customize these fields per product also. Even, you can remove it, when you edit your products.', 'surbma-magyar-woocommerce' ),
			'tags' => ['product', 'conversion', 'legal'],
			'doc_slug' => 'termek-ar-kiegeszitesek',
		],
		'limit-payment-methods' => [
			'option_key' => 'module-limitpaymentmethods',
			'type' => 'pro',
			'directory' => 'modules',
			'title' => __( 'Limit Payment Methods', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Disable any Payment Methods for a particular user. The disabled Payment Method will not be shown to the Customer on the Checkout page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'payments'],
			'doc_slug' => 'fizetesi-modok-korlatozasa',
			'version_added' => '3.5.0',
		],

		// Legacy modules
		'free-shipping-notice' => [
			'option_key' => 'freeshippingnotice',
			'type' => 'legacy',
			'directory' => 'modules',
			'title' => __( 'Free shipping notification', 'surbma-magyar-woocommerce' ),
			'description' => __( 'A notification on the Cart page to let customer know, how much total purchase is missing to get free shipping.', 'surbma-magyar-woocommerce' ),
			'tags' => ['cart', 'conversion'],
			'doc_slug' => 'ingyenes-szallitas-ertesites',
		],
		'legal-checkout' => [
			'option_key' => 'legalcheckout',
			'type' => 'legacy',
			'directory' => 'modules',
			'title' => __( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Custom Terms & Conditions and Privacy Policy checkboxes on Checkout page.', 'surbma-magyar-woocommerce' ),
			'tags' => ['checkout', 'conversion', 'legal'],
			'doc_slug' => 'jogi-megfeleles',
		],
		'global-info' => [
			'option_key' => 'module-globalinfo',
			'type' => 'legacy',
			'directory' => 'modules',
			'title' => __( 'Global Information', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Use these fields for your global information and show them with shortcodes. Your email will be safe from bots and your phone number will be active to call you with one tap on mobiles. Local data will be semantic for search engines.', 'surbma-magyar-woocommerce' ),
			'tags' => ['other'],
			'doc_slug' => 'globalis-adatok',
		],
	];
}

/**
 * Determine which modules should show the "New" badge
 *
 * Finds all modules with the highest version_added value and returns their keys.
 * Modules without version_added property never receive the badge.
 *
 * @param array $modules The modules array
 * @return array Array of module keys that should show "New" badge
 */
function cps_hc_gems_get_new_module_keys( $modules ) {
	$versions = [];

	// Collect all version_added values
	foreach ( $modules as $key => $module ) {
		if ( isset( $module['version_added'] ) && ! empty( $module['version_added'] ) ) {
			$versions[ $key ] = $module['version_added'];
		}
	}

	if ( empty( $versions ) ) {
		return [];
	}

	// Find the highest version
	$highest_version = '0.0.0';
	foreach ( $versions as $version ) {
		if ( version_compare( $version, $highest_version, '>' ) ) {
			$highest_version = $version;
		}
	}

	// Return keys of modules with the highest version
	$new_modules = [];
	foreach ( $versions as $key => $version ) {
		if ( version_compare( $version, $highest_version, '==' ) ) {
			$new_modules[] = $key;
		}
	}

	return $new_modules;
}

/**
 * Sort modules for display: PRO first, then Free
 *
 * PRO types include: pro, pro_hu, legacy, legacy_hu
 * Free types include: free, free_hu
 *
 * @param array $modules The modules array
 * @return array Sorted modules array with PRO modules first
 */
function cps_hc_gems_sort_modules_for_display( $modules ) {
	$pro_types = ['pro', 'pro_hu', 'legacy', 'legacy_hu'];

	$pro_modules = [];
	$free_modules = [];

	foreach ( $modules as $key => $module ) {
		if ( in_array( $module['type'], $pro_types, true ) ) {
			$pro_modules[ $key ] = $module;
		} else {
			$free_modules[ $key ] = $module;
		}
	}

	return array_merge( $pro_modules, $free_modules );
}

/**
 * Get tag translations for module card display
 *
 * @return array Associative array of tag => translated label
 */
function cps_hc_gems_get_tag_translations() {
	return [
		'product' => __( 'Product', 'surbma-magyar-woocommerce' ),
		'cart' => __( 'Cart', 'surbma-magyar-woocommerce' ),
		'checkout' => __( 'Checkout', 'surbma-magyar-woocommerce' ),
		'payments' => __( 'Payments', 'surbma-magyar-woocommerce' ),
		'legal' => __( 'Legal', 'surbma-magyar-woocommerce' ),
		'conversion' => __( 'Conversion', 'surbma-magyar-woocommerce' ),
		'other' => __( 'Other', 'surbma-magyar-woocommerce' ),
	];
}

/**
 * Check if a module type is considered "PRO" for licensing purposes
 *
 * @param string $type The module type
 * @return bool True if PRO type, false if Free type
 */
function cps_hc_gems_is_pro_module_type( $type ) {
	return in_array( $type, ['pro', 'pro_hu', 'legacy', 'legacy_hu'], true );
}

/**
 * Check if a module type is considered "Free" for licensing purposes
 *
 * @param string $type The module type
 * @return bool True if Free type, false otherwise
 */
function cps_hc_gems_is_free_module_type( $type ) {
	return in_array( $type, ['free', 'free_hu'], true );
}

// Load modules on init
add_action( 'init', static function() {
	// Get the settings array
	global $cps_hc_gems_options;

	// Get modules configuration
	$modules = cps_hc_gems_get_modules_config();

	// Loop through modules and load them
	foreach ( $modules as $module_key => $module_config ) {
		// Determine the module value based on type and special conditions
		$module_value = 0;

		// Handle force_enable first
		if ( isset( $module_config['force_enable'] ) && $module_config['force_enable'] ) {
			$module_value = 1;
		} else {
			// Get value based on module type
			switch ( $module_config['type'] ) {
				case 'free_hu':
				case 'free':
					// Free modules: get from options directly
					$module_value = $cps_hc_gems_options[ $module_config['option_key'] ] ?? 0;
					break;

				case 'pro_hu':
				case 'pro':
					$module_value = $cps_hc_gems_options[ $module_config['option_key'] ] ?? 0;
					break;

				case 'legacy_hu':
				case 'legacy':
					$module_value = $cps_hc_gems_options[ $module_config['option_key'] ] ?? 0;
					break;
			}
		}

		// Load module if value is 1
		if ( 1 == $module_value ) {
			// Check frontend_only condition
			if ( isset( $module_config['frontend_only'] ) && $module_config['frontend_only'] ) {
				if ( is_admin() ) {
					continue;
				}
			}

			// Determine file path
			$file_name = isset( $module_config['file'] ) ? $module_config['file'] : $module_key . '.php';
			$file_path = CPS_HC_GEMS_DIR . '/' . $module_config['directory'] . '/' . $file_name;

			// Include the module file
			include_once $file_path;
		}
	}
} );
