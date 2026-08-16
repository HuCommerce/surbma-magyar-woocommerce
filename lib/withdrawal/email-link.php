<?php

/**
 * Withdrawal request: link in the order confirmation email (DEV-238).
 *
 * Injects a clearly-labelled, tokenized withdrawal button into the customer
 * processing/completed order emails, only while the 14-day window is open.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Which customer emails should carry the withdrawal link, per settings.
 *
 * @return string[] WC_Email IDs.
 */
function cps_hc_gems_withdrawal_email_link_targets() {
	global $cps_hc_gems_options;

	$targets = array();

	$processing = isset( $cps_hc_gems_options['withdrawalrequest-emailprocessing'] ) ? $cps_hc_gems_options['withdrawalrequest-emailprocessing'] : 1;
	$completed  = isset( $cps_hc_gems_options['withdrawalrequest-emailcompleted'] ) ? $cps_hc_gems_options['withdrawalrequest-emailcompleted'] : 1;

	if ( 1 === (int) $processing ) {
		$targets[] = 'customer_processing_order';
	}

	if ( 1 === (int) $completed ) {
		$targets[] = 'customer_completed_order';
	}

	return $targets;
}

add_action( 'woocommerce_email_after_order_table', static function ( $order, $sent_to_admin, $plain_text, $email ) {
	if ( $sent_to_admin || ! $order instanceof WC_Order ) {
		return;
	}

	$email_id = $email instanceof WC_Email ? $email->id : '';

	if ( ! in_array( $email_id, cps_hc_gems_withdrawal_email_link_targets(), true ) ) {
		return;
	}

	if ( ! cps_hc_gems_withdrawal_window_is_open( $order ) ) {
		return;
	}

	$url = cps_hc_gems_withdrawal_get_url( $order );

	if ( ! $url ) {
		return;
	}

	$label = cps_hc_gems_withdrawal_get_button_label();

	if ( $plain_text ) {
		echo "\n" . esc_html__( 'Right of withdrawal', 'surbma-magyar-woocommerce' ) . ":\n";
		echo esc_html( $label ) . ': ' . esc_url_raw( $url ) . "\n";
		return;
	}

	echo '<div style="margin: 24px 0;">';
	echo '<p style="margin: 0 0 12px;">' . esc_html__( 'By law, you have the right to withdraw from your purchase. Click the button below to start your withdrawal:', 'surbma-magyar-woocommerce' ) . '</p>';
	echo '<a href="' . esc_url( $url ) . '" style="display: inline-block; padding: 12px 20px; background: #1a1a1a; color: #ffffff; text-decoration: none; border-radius: 4px;">' . esc_html( $label ) . '</a>';
	echo '</div>';
}, 20, 4 );
