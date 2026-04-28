<?php

defined( 'ABSPATH' ) || exit;

function cps_hc_gems_is_block_checkout(): bool {
	$checkout_page_id = wc_get_page_id( 'checkout' );
	return $checkout_page_id && has_block( 'woocommerce/checkout', $checkout_page_id );
}

function cps_hc_gems_is_block_cart(): bool {
	$cart_page_id = wc_get_page_id( 'cart' );
	return $cart_page_id && has_block( 'woocommerce/cart', $cart_page_id );
}
