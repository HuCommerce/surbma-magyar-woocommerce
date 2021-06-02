<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/* TODO
/*
/* Ezt még tesztelnem kell, hogy egyáltalán szükség van-e erre a javításra. Most úgy tűnik, hogy nem kell.
/*
// Fix locale if WPML is active.
add_filter( 'locale', function( $locale ) {
	if( !is_admin() && defined( 'ICL_LANGUAGE_CODE' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		$locale = $languages[ICL_LANGUAGE_CODE]['default_locale'];
	}
	return $locale;
} );
*/

/*
// Activated only for debug! Displays WPML variables and values.
add_action( 'wp_footer', function() {
	echo 'ICL_LANGUAGE_CODE: ' . ICL_LANGUAGE_CODE;
	echo '<br>';
	echo 'get_locale: ' . get_locale();
	echo '<br>';
	echo 'icl_get_languages array:';
	echo '<pre>';
	print_r( icl_get_languages() );
	echo '</pre>';
	$languages = icl_get_languages( 'skip_missing=0' );
	echo 'Default locale language code: ' . $languages[ICL_LANGUAGE_CODE]['default_locale'];
	echo '<br>';
	echo 'Default locale language codes:';
	echo '<br>';
	foreach( $languages as $l ) {
		echo $l['default_locale'];
		echo '<br>';
	}
} );
*/

// CSS fixes for themes
add_action( 'wp_head', function() {
	?>
<style id="hucommerce-theme-fix">
<?php if ( is_checkout() && wp_basename( get_bloginfo( 'template_directory' ) ) == 'Avada' ) { ?>
	/* Avada CSS FIX */
	form.checkout .form-row-first {float: left !important;width: 48% !important;}
	form.checkout .form-row-last {float: right !important;width: 48% !important;}
<?php } ?>
</style>
<?php
}, 999 );

// Customize the checkout default address fields
add_filter( 'woocommerce_default_address_fields' , function( $address_fields ) {
	// Deprecated function since WooCommerce 4.4
	// Modifications only if language is Hungarian
	if ( !surbma_hc_woocommerce_version_check( '4.4' ) && ( get_locale() == 'hu_HU' || get_locale() == 'hu' ) ) {
		$address_fields['last_name']['priority'] = 10;
		$address_fields['last_name']['class'] = array( 'form-row-first' );

		$address_fields['first_name']['priority'] = 20;
		$address_fields['first_name']['class'] = array( 'form-row-last' );
	}

	return $address_fields;
} );

// Customize the checkout default address fields
add_filter( 'woocommerce_default_address_fields' , function( $address_fields ) {
	// Deprecated function since WooCommerce 4.4
	if ( !surbma_hc_woocommerce_version_check( '4.4' ) ) {
		$woocommercecheckoutaddress2fieldValue = get_option( 'woocommerce_checkout_address_2_field' ) != false ? get_option( 'woocommerce_checkout_address_2_field' ) : 'optional';
		$options = get_option( 'surbma_hc_fields' );

		// Put Postcode and City fields before Address fields
		$address_fields['postcode']['priority'] = 69;
		// $address_fields['city']['priority'] = 60;
		$address_fields['address_1']['priority'] = 95;
		if ( 'hidden' != $woocommercecheckoutaddress2fieldValue ) {
			$address_fields['address_2']['priority'] = 96;
		}
	}

	return $address_fields;
} );

// Fixed Hungarian address format
add_filter( 'woocommerce_localisation_address_formats', function( $format ) {
	$format['HU']="{name}\n{company}\n{postcode} {city}\n{address_1}\n{address_2}\n{country}";
	return $format;
} );

// Change the name order if language is Hungarian.
add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ) {
	if ( get_locale() == 'hu_HU' || get_locale() == 'hu' ) {
		$replacements['{name}'] = $args['last_name'] . ' ' . $args['first_name'];
	}
	return $replacements;
}, 10, 2 );
