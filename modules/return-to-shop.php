<?php

/**
 * Module: Continue shopping buttons
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

$returntoshopcartpositionValue = $options['returntoshopcartposition'] ?? 'cartactions';
$returntoshopcheckoutpositionValue = $options['returntoshopcheckoutposition'] ?? 'nocheckout';

$continueshoppingmessageHook = '';
$continueshoppingmessagePriority = 10;

$continueshoppingbuttonHook = '';
$continueshoppingbuttonPriority = 10;

switch ( $returntoshopcartpositionValue ) {
	case 'beforecarttable':
		$continueshoppingmessageHook = 'woocommerce_before_cart_table';
		break;
		
	case 'aftercarttable':
		$continueshoppingmessageHook = 'woocommerce_after_cart_table';
		break;
		
	case 'cartactions':
		$continueshoppingbuttonHook = 'woocommerce_cart_actions';
		break;
		
	case 'proceedtocheckout':
		$continueshoppingbuttonHook = 'woocommerce_proceed_to_checkout';
		$continueshoppingbuttonPriority = 999;
		break;
}

switch ( $returntoshopcheckoutpositionValue ) {
	case 'beforecheckoutform':
		$continueshoppingmessageHook = 'woocommerce_before_checkout_form';
		$continueshoppingmessagePriority = 0;
		break;
		
	case 'aftercheckoutform':
		$continueshoppingmessageHook = 'woocommerce_after_checkout_form';
		break;
}

add_action( $continueshoppingmessageHook, function() {
	// Get the settings array
	global $options;

	$returntoshopmessageValue = $options['returntoshopmessage'] ?? __( 'Would you like to continue shopping?', 'surbma-magyar-woocommerce' );

	echo '<div class="woocommerce-message returntoshop">';
	echo esc_html( $returntoshopmessageValue ) . ' <a href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '" class="button wc-forward">' . esc_html__( 'Return to shop', 'woocommerce' ) . '</a>'; // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
	echo '</div>';
}, $continueshoppingmessagePriority );

add_action( $continueshoppingbuttonHook, function() {
	echo '<a class="button wc-backward returntoshop" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">';
	echo esc_html__( 'Return to shop', 'woocommerce' ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
	echo '</a>';
}, $continueshoppingbuttonPriority );
