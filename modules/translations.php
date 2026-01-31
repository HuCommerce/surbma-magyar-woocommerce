<?php

/**
 * Module: Translations for premium plugins & themes
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/*
// Load custom mo translations for plugins and themes (each domain in its own subfolder).
add_filter( 'load_textdomain_mofile', static function( $mofile, $domain ) {
	$locale = get_locale();
	$custom_mofile = CPS_HC_GEMS_DIR . '/translations/plugins/' . $domain . '/' . $domain . '-' . $locale . '.mo';

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
add_filter( 'load_translation_file', static function( $file, $domain, $locale ) {
	// Get the settings array
	global $cps_hc_gems_options;

	// Get the settings
	$translations_restrictcontentpro_value = $cps_hc_gems_options['translations-restrictcontentpro'] ?? 0;
	$translations_woocommerceapimanager_value = $cps_hc_gems_options['translations-woocommerceapimanager'] ?? 0;
	$translations_woocommercememberships_value = $cps_hc_gems_options['translations-woocommercememberships'] ?? 0;
	$translations_woocommercesubscriptions_value = $cps_hc_gems_options['translations-woocommercesubscriptions'] ?? 0;

	// Return, if no translations are actually activated
	if ( !$translations_woocommerceapimanager_value && !$translations_restrictcontentpro_value && !$translations_woocommercememberships_value && !$translations_woocommercesubscriptions_value ) {
		return $file;
	}

	// Define the custom translation files for each plugin/theme (each domain in its own subfolder).
	$restrictcontentpro_php_file = CPS_HC_GEMS_DIR . '/translations/plugins/restrict-content-pro/restrict-content-pro-' . $locale . '.l10n.php';
	$restrictcontentpro_mo_file  = CPS_HC_GEMS_DIR . '/translations/plugins/restrict-content-pro/restrict-content-pro-' . $locale . '.mo';

	$woocommerceapimanager_php_file = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-api-manager/woocommerce-api-manager-' . $locale . '.l10n.php';
	$woocommerceapimanager_mo_file  = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-api-manager/woocommerce-api-manager-' . $locale . '.mo';

	$woocommercememberships_php_file = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-memberships/woocommerce-memberships-' . $locale . '.l10n.php';
	$woocommercememberships_mo_file  = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-memberships/woocommerce-memberships-' . $locale . '.mo';

	$woocommercesubscriptions_php_file = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-subscriptions/woocommerce-subscriptions-' . $locale . '.l10n.php';
	$woocommercesubscriptions_mo_file  = CPS_HC_GEMS_DIR . '/translations/plugins/woocommerce-subscriptions/woocommerce-subscriptions-' . $locale . '.mo';

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
add_filter( 'load_translation_file', static function( $file, $domain, $locale ) {
	global $cps_hc_gems_options;

	$domains = cps_hc_gems_get_translation_domains();
	$plugin_translations = $domains['plugins'];
	$theme_translations = $domains['themes'];

	// Return early if no translations are activated.
	$has_active = false;
	foreach ( array_merge( $plugin_translations, $theme_translations ) as $d ) {
		$option_key = cps_hc_gems_translation_domain_to_option_key( $d );
		if ( ! empty( $cps_hc_gems_options[ $option_key ] ) ) {
			$has_active = true;
			break;
		}
	}
	if ( ! $has_active ) {
		return $file;
	}

	// Resolve folder for requested domain.
	if ( in_array( $domain, $plugin_translations, true ) ) {
		$folder = 'plugins';
	} elseif ( in_array( $domain, $theme_translations, true ) ) {
		$folder = 'themes';
	} else {
		return $file;
	}

	if ( empty( $cps_hc_gems_options[ cps_hc_gems_translation_domain_to_option_key( $domain ) ] ) ) {
		return $file;
	}

	$base     = CPS_HC_GEMS_DIR . '/translations/' . $folder . '/' . $domain;
	$php_file = $base . '/' . $domain . '-' . $locale . '.l10n.php';
	$mo_file  = $base . '/' . $domain . '-' . $locale . '.mo';

	if ( file_exists( $php_file ) ) {
		return $php_file;
	}
	if ( file_exists( $mo_file ) ) {
		return $mo_file;
	}

	return $file;
}, 10, 3 );