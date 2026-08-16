<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || die;

/**
 * Check if the current Checkout page uses the Cart & Checkout Checkout block.
 *
 * @return bool
 */
function cps_hc_gems_is_block_checkout() {
	if ( ! function_exists( 'wc_get_page_id' ) || ! function_exists( 'has_block' ) ) {
		return false;
	}

	$checkout_page_id = wc_get_page_id( 'checkout' );
	if ( empty( $checkout_page_id ) || ! is_numeric( $checkout_page_id ) || (int) $checkout_page_id <= 0 ) {
		return false;
	}

	return has_block( 'woocommerce/checkout', (int) $checkout_page_id );
}

/**
 * Check if the current Cart page uses the Cart & Checkout Cart block.
 *
 * @return bool
 */
function cps_hc_gems_is_block_cart() {
	if ( ! function_exists( 'wc_get_page_id' ) || ! function_exists( 'has_block' ) ) {
		return false;
	}

	$cart_page_id = wc_get_page_id( 'cart' );
	if ( empty( $cart_page_id ) || ! is_numeric( $cart_page_id ) || (int) $cart_page_id <= 0 ) {
		return false;
	}

	return has_block( 'woocommerce/cart', (int) $cart_page_id );
}
