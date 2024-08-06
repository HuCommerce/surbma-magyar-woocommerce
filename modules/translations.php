<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Load custom translations for plugins and themes
add_filter( 'load_textdomain_mofile', function( $mofile, $domain ) {
	$custom_mofile = SURBMA_HC_PLUGIN_DIR . '/languages/' . $domain . '-' . get_locale() . '.mo';

	// Check if the custom translation file exists
	if ( file_exists( $custom_mofile ) ) {
		return $custom_mofile;
	} else {
		return $mofile;
	}
}, 10, 2 );
