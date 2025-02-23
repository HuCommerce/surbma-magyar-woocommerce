<?php

/**
 * Module: Free shipping notification
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Code reference: https://businessbloomer.com/woocommerce-add-need-spend-x-get-free-shipping-cart-page/
function cps_hc_gems_free_shipping_notice( $returntoshop = true ) {
	// Return false if Cart is empty
	if ( count( WC()->cart->get_cart() ) < 1 ) {
		return;
	}

	// Get the settings array
	global $options;

	$freeshippingminimumorderamountValue = $options['freeshippingminimumorderamount'] ?? 0;
	$freeshippingcouponsdiscountsValue = $options['freeshippingcouponsdiscounts'] ?? 0;
	$freeshippingwithouttaxValue = $options['freeshippingwithouttax'] ?? 0;
	$freeshippingnoticemessageValue = $options['freeshippingnoticemessage'] ?? __( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' );
	$freeshippingsuccessfulmessageValue = $options['freeshippingsuccessfulmessage'] ?? '';

	global $woocommerce;

	// Create minimum order amount array and set it empty by default
	$min_amounts = array();

	// Check if Free Shipping module has its own value for minimum order amount
	if ( $freeshippingminimumorderamountValue ) {
		$min_amounts[] = $freeshippingminimumorderamountValue;
	} else {
		// Get enabled Free Shipping Methods for Rest of the World Zone & populate array $min_amounts
		$default_zone = new WC_Shipping_Zone(0);
		$default_methods = $default_zone->get_shipping_methods();
		foreach ( $default_methods as $key => $value ) {
			if ( 'free_shipping' === $value->id && 'yes' === $value->enabled ) {
				if ( $value->min_amount > 0 ) {
					$min_amounts[] = $value->min_amount;
				}
			}
		}

		// Get enabled Free Shipping Methods for all other Zones & populate array $min_amounts
		$delivery_zones = WC_Shipping_Zones::get_zones();
		foreach ( $delivery_zones as $key => $delivery_zone ) {
			foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
				if ( 'free_shipping' === $value->id && 'yes' === $value->enabled ) {
					if ( $value->min_amount > 0 ) {
						$min_amounts[] = $value->min_amount;
					}
				}
			}
		}
	}

	// Return false if no minimum amount is set
	if ( empty( $min_amounts ) ) {
		return;
	}

	// Find lowest min_amount
	$min_amount = min( $min_amounts );

	// Get "Display prices during cart and checkout" option from WooCommerce -> Settings -> Tax -> Tax options. Values: incl or excl
	$taxdisplaycart = get_option( 'woocommerce_tax_display_cart' );

	// Get Cart Subtotal without Shipping costs
	if ( $freeshippingwithouttaxValue ) { // Tax excluded
		if ( $freeshippingcouponsdiscountsValue ) { // Before Coupon discounts
			$current = WC()->cart->subtotal_ex_tax;
		} else { // After any Coupon discounts
			$current = WC()->cart->subtotal_ex_tax - WC()->cart->get_discount_total();
		}
	} else { // Tax included
		if ( $freeshippingcouponsdiscountsValue ) { // Before Coupon discounts
			$current = WC()->cart->subtotal;
		} else { // After any Coupon discounts
			$current = WC()->cart->subtotal - ( WC()->cart->get_discount_total() + WC()->cart->get_discount_tax() );
		}
	}

	$notice = '';

	// If Subtotal < Min Amount Echo Notice and add "Continue Shopping" button
	if ( $current < $min_amount ) {
		$message = $freeshippingnoticemessageValue . ': ' . wc_price( $min_amount - $current );
		$returnurl = esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) );
		if ( $returntoshop ) {
			$notice = sprintf( '%s <a href="%s" class="button wc-forward">%s</a>', $message, $returnurl, esc_html__( 'Return to shop', 'woocommerce' ) ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		} else {
			$notice = $message;
		}
	} elseif ( $freeshippingsuccessfulmessageValue ) {
		$notice = $freeshippingsuccessfulmessageValue;
	}

	// Debug values
	// $notice = 'Cart total: ' . $current . ' | Min amount: ' . $min_amount . ' | Purchase more: ' . wc_price( $min_amount - $current );

	return $notice;
}

// Get the settings to display the free shipping notice
$freeshippingnoticeshoploopValue = $options['freeshippingnoticeshoploop'] ?? 0;
$freeshippingnoticecartValue = $options['freeshippingnoticecart'] ?? 1;
$freeshippingnoticecheckoutValue = $options['freeshippingnoticecheckout'] ?? 0;

if ( 1 === $freeshippingnoticeshoploopValue ) :

	add_action( 'woocommerce_before_shop_loop', function() {
		$notice = cps_hc_gems_free_shipping_notice( $returntoshop = false );
		if ( $notice ) {
			wc_print_notice( $notice, 'notice' );
		}
	}, 0 );

endif;

if ( 1 === $freeshippingnoticecartValue ) :

	add_action( 'woocommerce_before_cart', function() {
		$notice = cps_hc_gems_free_shipping_notice();
		if ( $notice ) {
			wc_print_notice( $notice, 'notice' );
		}
	} );

endif;

if ( 1 === $freeshippingnoticecheckoutValue ) :

	add_action( 'woocommerce_before_checkout_form', function() {
		$notice = cps_hc_gems_free_shipping_notice();
		if ( $notice ) {
			wc_print_notice( $notice, 'notice' );
		}
	}, 0 );

endif;
