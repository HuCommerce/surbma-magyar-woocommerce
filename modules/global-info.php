<?php

/**
 * Module: Global informations
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_shortcode( 'hc-tel', function( $atts, $content = '' ) {
	return '<a href="tel:+' . preg_replace('/\D/', '', $content) . '">' . $content . '</a>';
} );

add_shortcode( 'hc-mailto', function( $atts, $content = '' ) {
	$encodedemail = '';

	for ( $i = 0; $i <strlen( $content ); $i++ ) {
		$encodedemail .= '&#' . ord( $content[$i] ) . ';';
	}

	return '<a href="mailto:' . $encodedemail . '">' . $encodedemail . '</a>';
} );

add_shortcode( 'hc-nev', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfoname'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfoname'];
} );

add_shortcode( 'hc-ceg', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfocompany'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfocompany'];
} );

add_shortcode( 'hc-szekhely', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfoheadquarters'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfoheadquarters'];
} );

add_shortcode( 'hc-adoszam', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfotaxnumber'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfotaxnumber'];
} );

add_shortcode( 'hc-cegjegyzekszam', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinforegnumber'] ) ) {
		return;
	}

	return $hc_gems_options['globalinforegnumber'];
} );

add_shortcode( 'hc-cim', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfoaddress'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfoaddress'];
} );

add_shortcode( 'hc-bankszamlaszam', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfobankaccount'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfobankaccount'];
} );

add_shortcode( 'hc-mobiltelefon', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfomobile'] ) ) {
		return;
	}

	return '<a href="tel:+' . preg_replace('/\D/', '', $hc_gems_options['globalinfomobile']) . '">' . $hc_gems_options['globalinfomobile'] . '</a>';
} );

add_shortcode( 'hc-telefon', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfophone'] ) ) {
		return;
	}

	return '<a href="tel:+' . preg_replace('/\D/', '', $hc_gems_options['globalinfophone']) . '">' . $hc_gems_options['globalinfophone'] . '</a>';
} );

add_shortcode( 'hc-email', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfoemail'] ) ) {
		return;
	}

	$email = $hc_gems_options['globalinfoemail'];
	$encodedemail = '';

	for ( $i = 0; $i <strlen( $email ); $i++ ) {
		$encodedemail .= '&#' . ord( $email[$i] ) . ';';
	}

	return '<a href="mailto:' . $encodedemail . '">' . $encodedemail . '</a>';
} );

add_shortcode( 'hc-rolunk', function() {
	// Get the settings array
	global $hc_gems_options;

	if ( !isset( $hc_gems_options['globalinfoaboutus'] ) ) {
		return;
	}

	return $hc_gems_options['globalinfoaboutus'];
} );
