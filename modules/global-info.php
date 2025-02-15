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
	global $options;

	if ( !isset( $options['globalinfoname'] ) ) {
		return;
	}

	return $options['globalinfoname'];
} );

add_shortcode( 'hc-ceg', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfocompany'] ) ) {
		return;
	}

	return $options['globalinfocompany'];
} );

add_shortcode( 'hc-szekhely', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfoheadquarters'] ) ) {
		return;
	}

	return $options['globalinfoheadquarters'];
} );

add_shortcode( 'hc-adoszam', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfotaxnumber'] ) ) {
		return;
	}

	return $options['globalinfotaxnumber'];
} );

add_shortcode( 'hc-cegjegyzekszam', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinforegnumber'] ) ) {
		return;
	}

	return $options['globalinforegnumber'];
} );

add_shortcode( 'hc-cim', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfoaddress'] ) ) {
		return;
	}

	return $options['globalinfoaddress'];
} );

add_shortcode( 'hc-bankszamlaszam', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfobankaccount'] ) ) {
		return;
	}

	return $options['globalinfobankaccount'];
} );

add_shortcode( 'hc-mobiltelefon', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfomobile'] ) ) {
		return;
	}

	return '<a href="tel:+' . preg_replace('/\D/', '', $options['globalinfomobile']) . '">' . $options['globalinfomobile'] . '</a>';
} );

add_shortcode( 'hc-telefon', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfophone'] ) ) {
		return;
	}

	return '<a href="tel:+' . preg_replace('/\D/', '', $options['globalinfophone']) . '">' . $options['globalinfophone'] . '</a>';
} );

add_shortcode( 'hc-email', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfoemail'] ) ) {
		return;
	}

	$email = $options['globalinfoemail'];
	$encodedemail = '';

	for ( $i = 0; $i <strlen( $email ); $i++ ) {
		$encodedemail .= '&#' . ord( $email[$i] ) . ';';
	}

	return '<a href="mailto:' . $encodedemail . '">' . $encodedemail . '</a>';
} );

add_shortcode( 'hc-rolunk', function() {
	// Get the settings array
	global $options;

	if ( !isset( $options['globalinfoaboutus'] ) ) {
		return;
	}

	return $options['globalinfoaboutus'];
} );
