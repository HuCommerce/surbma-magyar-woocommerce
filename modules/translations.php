<?php

/**
 * Module: Translations for premium plugins & themes
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/*
// Load custom mo translations for plugins and themes
add_filter( 'load_textdomain_mofile', function( $mofile, $domain ) {
	$custom_mofile = SURBMA_HC_PLUGIN_DIR . '/translations/' . $domain . '-' . get_locale() . '.mo';

	// Check if the custom translation file exists
	if ( file_exists( $custom_mofile ) ) {
		return $custom_mofile;
	} else {
		return $mofile;
	}
}, 10, 2 );
*/

/*
// Load custom translations for plugins and themes
add_filter( 'load_translation_file', function( $file, $domain, $locale ) {
	// Get the settings array
	global $hc_gems_options;

	// Get the settings
	$translations_restrictcontentpro_value = $hc_gems_options['translations-restrictcontentpro'] ?? 0;
	$translations_woocommerceapimanager_value = $hc_gems_options['translations-woocommerceapimanager'] ?? 0;
	$translations_woocommercememberships_value = $hc_gems_options['translations-woocommercememberships'] ?? 0;
	$translations_woocommercesubscriptions_value = $hc_gems_options['translations-woocommercesubscriptions'] ?? 0;

	// Return, if no translations are actually activated
	if ( !$translations_woocommerceapimanager_value && !$translations_restrictcontentpro_value && !$translations_woocommercememberships_value && !$translations_woocommercesubscriptions_value ) {
		return $file;
	}

	// Define the custom translation files for each plugin/theme
	$restrictcontentpro_php_file = SURBMA_HC_PLUGIN_DIR . '/translations/restrict-content-pro-' . $locale . '.l10n.php';
	$restrictcontentpro_mo_file  = SURBMA_HC_PLUGIN_DIR . '/translations/restrict-content-pro-' . $locale . '.mo';

	$woocommerceapimanager_php_file = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-api-manager-' . $locale . '.l10n.php';
	$woocommerceapimanager_mo_file  = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-api-manager-' . $locale . '.mo';

	$woocommercememberships_php_file = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-memberships-' . $locale . '.l10n.php';
	$woocommercememberships_mo_file  = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-memberships-' . $locale . '.mo';

	$woocommercesubscriptions_php_file = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-subscriptions-' . $locale . '.l10n.php';
	$woocommercesubscriptions_mo_file  = SURBMA_HC_PLUGIN_DIR . '/translations/woocommerce-subscriptions-' . $locale . '.mo';

	// Check for each domain individually
	if ( $translations_restrictcontentpro_value && 'restrict-content-pro' === $domain ) {
		if ( file_exists( $restrictcontentpro_php_file ) ) {
			return $restrictcontentpro_php_file;
		}
		if ( file_exists( $restrictcontentpro_mo_file ) ) {
			return $restrictcontentpro_mo_file;
		}
	}

	if ( $translations_woocommerceapimanager_value && 'woocommerce-api-manager' === $domain ) {
		if ( file_exists( $woocommerceapimanager_php_file ) ) {
			return $woocommerceapimanager_php_file;
		}
		if ( file_exists( $woocommerceapimanager_mo_file ) ) {
			return $woocommerceapimanager_mo_file;
		}
	}

	if ( $translations_woocommercememberships_value && 'woocommerce-memberships' === $domain ) {
		if ( file_exists( $woocommercememberships_php_file ) ) {
			return $woocommercememberships_php_file;
		}
		if ( file_exists( $woocommercememberships_mo_file ) ) {
			return $woocommercememberships_mo_file;
		}
	}

	if ( $translations_woocommercesubscriptions_value && 'woocommerce-subscriptions' === $domain ) {
		if ( file_exists( $woocommercesubscriptions_php_file ) ) {
			return $woocommercesubscriptions_php_file;
		}
		if ( file_exists( $woocommercesubscriptions_mo_file ) ) {
			return $woocommercesubscriptions_mo_file;
		}
	}

	// If no custom translation exists, return the original translation file
	return $file;
}, 10, 3 );
*/

// Load custom translations for plugins and themes
add_filter( 'load_translation_file', function( $file, $domain, $locale ) {
	// Get the settings array
	global $hc_gems_options;

	// Define translations with their settings key and file paths
	$translations = [
		'restrict-content-pro' => [
			'option_key' => 'translations-restrictcontentpro',
			'php_file'   => SURBMA_HC_PLUGIN_DIR . "/translations/restrict-content-pro-{$locale}.l10n.php",
			'mo_file'    => SURBMA_HC_PLUGIN_DIR . "/translations/restrict-content-pro-{$locale}.mo"
		],
		'woocommerce-api-manager' => [
			'option_key' => 'translations-woocommerceapimanager',
			'php_file'   => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-api-manager-{$locale}.l10n.php",
			'mo_file'    => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-api-manager-{$locale}.mo"
		],
		'woocommerce-memberships' => [
			'option_key' => 'translations-woocommercememberships',
			'php_file'   => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-memberships-{$locale}.l10n.php",
			'mo_file'    => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-memberships-{$locale}.mo"
		],
		'woocommerce-subscriptions' => [
			'option_key' => 'translations-woocommercesubscriptions',
			'php_file'   => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-subscriptions-{$locale}.l10n.php",
			'mo_file'    => SURBMA_HC_PLUGIN_DIR . "/translations/woocommerce-subscriptions-{$locale}.mo"
		]
	];

	// Return early if no translations are activated
	$active_translations = array_filter( $translations, function( $translation ) use ( $hc_gems_options ) {
		return !empty( $hc_gems_options[$translation['option_key']] );
	} );
	
	if ( empty( $active_translations ) ) {
		return $file;
	}

	// Check if the requested domain has an active translation
	if ( isset( $translations[$domain] ) && !empty( $hc_gems_options[$translations[$domain]['option_key']] ) ) {
		if ( file_exists( $translations[$domain]['php_file'] ) ) {
			return $translations[$domain]['php_file'];
		}
		if ( file_exists( $translations[$domain]['mo_file'] ) ) {
			return $translations[$domain]['mo_file'];
		}
	}

	// Return the original translation file if no custom translation exists
	return $file;
}, 10, 3 );