<?php

/**
 * Module: Custom Add To Cart Button
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_product_add_to_cart_text' , 'cps_hc_gems_custom_addtocart_button', 10, 1 );
add_filter( 'woocommerce_product_single_add_to_cart_text' , 'cps_hc_gems_custom_addtocart_button', 10, 1 );
add_filter( 'woocommerce_booking_single_add_to_cart_text' , 'cps_hc_gems_custom_addtocart_button', 10, 1 );
function cps_hc_gems_custom_addtocart_button( $text ) {
	global $product;
	if ( !isset( $product ) || !is_object( $product ) ) {
		return $text;
	}

	$product_type = $product->get_type();

	// Get the settings array
	global $cps_hc_gems_options;

	$custom_addtocart_button_single_simpleValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-simple'] ) && $cps_hc_gems_options['custom-addtocart-button-single-simple'] ? $cps_hc_gems_options['custom-addtocart-button-single-simple'] : $text;
	$custom_addtocart_button_single_groupedValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-grouped'] ) && $cps_hc_gems_options['custom-addtocart-button-single-grouped'] ? $cps_hc_gems_options['custom-addtocart-button-single-grouped'] : $text;
	$custom_addtocart_button_single_externalValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-external'] ) && $cps_hc_gems_options['custom-addtocart-button-single-external'] ? $cps_hc_gems_options['custom-addtocart-button-single-external'] : $text;
	$custom_addtocart_button_single_variableValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-variable'] ) && $cps_hc_gems_options['custom-addtocart-button-single-variable'] ? $cps_hc_gems_options['custom-addtocart-button-single-variable'] : $text;
	$custom_addtocart_button_single_subscriptionValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-subscription'] ) && $cps_hc_gems_options['custom-addtocart-button-single-subscription'] ? $cps_hc_gems_options['custom-addtocart-button-single-subscription'] : $text;
	$custom_addtocart_button_single_variablesubscriptionValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-variable-subscription'] ) && $cps_hc_gems_options['custom-addtocart-button-single-variable-subscription'] ? $cps_hc_gems_options['custom-addtocart-button-single-variable-subscription'] : $text;
	$custom_addtocart_button_single_bookingValue = isset( $cps_hc_gems_options['custom-addtocart-button-single-booking'] ) && $cps_hc_gems_options['custom-addtocart-button-single-booking'] ? $cps_hc_gems_options['custom-addtocart-button-single-booking'] : $text;
	$custom_addtocart_button_archive_simpleValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-simple'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-simple'] ? $cps_hc_gems_options['custom-addtocart-button-archive-simple'] : $custom_addtocart_button_single_simpleValue;
	$custom_addtocart_button_archive_groupedValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-grouped'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-grouped'] ? $cps_hc_gems_options['custom-addtocart-button-archive-grouped'] : $custom_addtocart_button_single_groupedValue;
	$custom_addtocart_button_archive_externalValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-external'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-external'] ? $cps_hc_gems_options['custom-addtocart-button-archive-external'] : $custom_addtocart_button_single_externalValue;
	$custom_addtocart_button_archive_variableValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-variable'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-variable'] ? $cps_hc_gems_options['custom-addtocart-button-archive-variable'] : $custom_addtocart_button_single_variableValue;
	$custom_addtocart_button_archive_subscriptionValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-subscription'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-subscription'] ? $cps_hc_gems_options['custom-addtocart-button-archive-subscription'] : $custom_addtocart_button_single_subscriptionValue;
	$custom_addtocart_button_archive_variablesubscriptionValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-variable-subscription'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-variable-subscription'] ? $cps_hc_gems_options['custom-addtocart-button-archive-variable-subscription'] : $custom_addtocart_button_single_variablesubscriptionValue;
	$custom_addtocart_button_archive_bookingValue = isset( $cps_hc_gems_options['custom-addtocart-button-archive-booking'] ) && $cps_hc_gems_options['custom-addtocart-button-archive-booking'] ? $cps_hc_gems_options['custom-addtocart-button-archive-booking'] : $custom_addtocart_button_single_bookingValue;

	if ( is_product() ) { // Single product pages

		switch ( $product_type ) {
			case 'simple':
				return $custom_addtocart_button_single_simpleValue;
			break;
			case 'grouped':
				return $custom_addtocart_button_single_groupedValue;
			break;
			case 'external':
				return $custom_addtocart_button_single_externalValue;
			break;
			case 'variable':
				return $custom_addtocart_button_single_variableValue;
			break;
			case 'subscription':
				return $custom_addtocart_button_single_subscriptionValue;
			break;
			case 'variable-subscription':
				return $custom_addtocart_button_single_variablesubscriptionValue;
			break;
			case 'booking':
				return $custom_addtocart_button_single_bookingValue;
			break;
			default:
				return $text;
		}

	} else { // Archive pages

		switch ( $product_type ) {
			case 'simple':
				return $custom_addtocart_button_archive_simpleValue;
			break;
			case 'grouped':
				return $custom_addtocart_button_archive_groupedValue;
			break;
			case 'external':
				return $custom_addtocart_button_archive_externalValue;
			break;
			case 'variable':
				return $custom_addtocart_button_archive_variableValue;
			break;
			case 'subscription':
				return $custom_addtocart_button_archive_subscriptionValue;
			break;
			case 'variable-subscription':
				return $custom_addtocart_button_archive_variablesubscriptionValue;
			break;
			case 'booking':
				return $custom_addtocart_button_archive_bookingValue;
			break;
			default:
				return $text;
		}

	}

}
