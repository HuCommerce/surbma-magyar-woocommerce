<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Code reference: https://businessbloomer.com/woocommerce-add-need-spend-x-get-free-shipping-cart-page/
add_action( 'woocommerce_before_cart', function() {
	$options = get_option( 'surbma_hc_fields' );
	$freeshippingnoticemessageValue = isset( $options['freeshippingnoticemessage'] ) && ( $options['freeshippingnoticemessage'] ) ? $options['freeshippingnoticemessage'] : __( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' );
	global $woocommerce;

	// Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts
	$default_zone = new WC_Shipping_Zone(0);
	$default_methods = $default_zone->get_shipping_methods();
	$min_amounts = array();
	foreach ( $default_methods as $key => $value ) {
		if ( 'free_shipping' === $value->id ) {
			if ( $value->min_amount > 0 ) {
				$min_amounts[] = $value->min_amount;
			}
		}
	}

	// Get Free Shipping Methods for all other Zones & populate array $min_amounts
	$delivery_zones = WC_Shipping_Zones::get_zones();
	foreach ( $delivery_zones as $key => $delivery_zone ) {
		foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
			if ( 'free_shipping' === $value->id ) {
				if ( $value->min_amount > 0 ) {
					$min_amounts[] = $value->min_amount;
				}
			}
		}
	}

	if ( empty( $min_amounts ) ) {
		return;
	}

	// Find lowest min_amount
	$min_amount = min( $min_amounts );

	// Get "Display prices during cart and checkout" option: incl or excl
	$taxdisplaycart = get_option( 'woocommerce_tax_display_cart' );

	// Get Cart Subtotal without Shipping costs
	if ( 'incl' == $taxdisplaycart ) {
		// Tax included
		$current = WC()->cart->subtotal;
	} else {
		// Tax excluded
		$current = WC()->cart->subtotal_ex_tax;
	}

	// If Subtotal < Min Amount Echo Notice and add "Continue Shopping" button
	if ( $current < $min_amount ) {
		$message = $freeshippingnoticemessageValue . ': ' . wc_price( $min_amount - $current );
		$returnurl = esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) );
		$notice = sprintf( '%s <a href="%s" class="button wc-forward">%s</a>', $message, $returnurl, esc_html__( 'Return to shop', 'woocommerce' ) );
		wc_print_notice( $notice, 'notice' );
		// TODO: Checking if recommended product is added, it should not duplicate the notice!
		// wc_clear_notices();
		// wc_add_notice( $notice, 'notice' );
		// wc_print_notices();
		// woocommerce_output_all_notices();
	}
} );
