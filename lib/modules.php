<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'init', function() {
	// Get the settings array
	global $hc_gems_options;

	// Define all modules with their properties
	$modules = [
		// Free HU modules
		'hu-format-fix' => [
			'option_key' => 'huformatfix',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
		],
		'no-county' => [
			'option_key' => 'nocounty',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
		],
		'autofill-city' => [
			'option_key' => 'autofillcity',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
		],
		'translations-hu' => [
			'option_key' => 'translations',
			'type' => 'free_hu',
			'directory' => 'modules-hu',
			'file' => 'translations.php',
			'frontend_only' => true,
		],

		// Pro HU modules
		'product-price-history' => [
			'option_key' => 'module-productpricehistory',
			'type' => 'pro_hu',
			'directory' => 'modules-hu',
			'force_enable' => true, // Force Product Price History module to load to save data for everyone
		],

		// Legacy HU modules
		'mask-checkout-fields' => [
			'option_key' => 'maskcheckoutfields',
			'type' => 'legacy_hu',
			'directory' => 'modules-hu',
		],
		'validate-checkout-fields' => [
			'option_key' => 'validatecheckoutfields',
			'type' => 'legacy_hu',
			'directory' => 'modules-hu',
			'file' => 'validate-checkout-fields.php',
		],

		// Free modules
		'tax-number' => [
			'option_key' => 'taxnumber',
			'type' => 'free',
			'directory' => 'modules',
		],
		'checkout' => [
			'option_key' => 'module-checkout',
			'type' => 'free',
			'directory' => 'modules',
		],
		'coupon' => [
			'option_key' => 'module-coupon',
			'type' => 'free',
			'directory' => 'modules',
		],
		'plus-minus-buttons' => [
			'option_key' => 'plusminus',
			'type' => 'free',
			'directory' => 'modules',
		],
		'update-cart' => [
			'option_key' => 'updatecart',
			'type' => 'free',
			'directory' => 'modules',
		],
		'redirect-cart' => [
			'option_key' => 'module-redirectcart',
			'type' => 'free',
			'directory' => 'modules',
		],
		'one-product-in-cart' => [
			'option_key' => 'module-oneproductincart',
			'type' => 'free',
			'directory' => 'modules',
		],
		'custom-addtocart-button' => [
			'option_key' => 'module-custom-addtocart-button',
			'type' => 'free',
			'directory' => 'modules',
		],
		'return-to-shop' => [
			'option_key' => 'returntoshop',
			'type' => 'free',
			'directory' => 'modules',
		],
		'login-registration-redirect' => [
			'option_key' => 'loginregistrationredirect',
			'type' => 'free',
			'directory' => 'modules',
		],
		'hide-shipping-methods' => [
			'option_key' => 'module-hideshippingmethods',
			'type' => 'free',
			'directory' => 'modules',
		],
		'product-settings' => [
			'option_key' => 'module-productsettings',
			'type' => 'free',
			'directory' => 'modules',
		],
		'smtp' => [
			'option_key' => 'module-smtp',
			'type' => 'free',
			'directory' => 'modules',
		],
		'catalog-mode' => [
			'option_key' => 'module-catalogmode',
			'type' => 'free',
			'directory' => 'modules',
		],

		// Pro modules
		'empty-cart-button' => [
			'option_key' => 'module-emptycartbutton',
			'type' => 'pro',
			'directory' => 'modules',
		],
		'product-price-additions' => [
			'option_key' => 'module-productpriceadditions',
			'type' => 'pro',
			'directory' => 'modules',
		],
		'limit-payment-methods' => [
			'option_key' => 'module-limitpaymentmethods',
			'type' => 'pro',
			'directory' => 'modules',
		],
		'translations' => [
			'option_key' => 'module-translations',
			'type' => 'pro',
			'directory' => 'modules',
		],

		// Legacy modules
		'free-shipping-notice' => [
			'option_key' => 'freeshippingnotice',
			'type' => 'legacy',
			'directory' => 'modules',
		],
		'legal-checkout' => [
			'option_key' => 'legalcheckout',
			'type' => 'legacy',
			'directory' => 'modules',
		],
		'global-info' => [
			'option_key' => 'module-globalinfo',
			'type' => 'legacy',
			'directory' => 'modules',
		],
	];

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
					$module_value = $hc_gems_options[ $module_config['option_key'] ] ?? 0;
					break;

				case 'pro_hu':
				case 'pro':
					// Pro modules: check SURBMA_HC_PREMIUM first
					if ( SURBMA_HC_PREMIUM ) {
						$module_value = $hc_gems_options[ $module_config['option_key'] ] ?? 0;
					} else {
						$module_value = 0;
					}
					break;

				case 'legacy_hu':
				case 'legacy':
					// Legacy modules: check premium OR legacy user condition
					if ( SURBMA_HC_PREMIUM || !isset( $hc_gems_options['brandnewuser'] ) || ( $hc_gems_options['legacyuser'] ?? 0 ) == 1 ) {
						$module_value = $hc_gems_options[ $module_config['option_key'] ] ?? 0;
					} else {
						$module_value = 0;
					}
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
			$file_path = SURBMA_HC_PLUGIN_DIR . '/' . $module_config['directory'] . '/' . $file_name;

			// Include the module file
			include_once $file_path;
		}
	}
} );
