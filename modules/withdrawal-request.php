<?php

/**
 * Module: Withdrawal request (EU 2023/2673)
 *
 * EU Directive 2023/2673 "withdrawal button": lets a consumer exercise their
 * statutory right of withdrawal entirely online, without logging in. Cases are
 * stored in dedicated custom tables (see spec §3.2, built in tasks A2/A3).
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_DEFAULT_SLUG' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_DEFAULT_SLUG', 'cps-hc-gems-withdraw' );
}

if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_WINDOW_DAYS' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_WINDOW_DAYS', 14 );
}

/**
 * Get the configured endpoint slug for the withdrawal page.
 *
 * @return string
 */
function cps_hc_gems_withdrawal_get_slug() {
	global $cps_hc_gems_options;

	$slug = isset( $cps_hc_gems_options['withdrawalrequest-slug'] ) ? $cps_hc_gems_options['withdrawalrequest-slug'] : '';
	$slug = sanitize_title( $slug );

	return $slug ? $slug : CPS_HC_GEMS_WITHDRAWAL_DEFAULT_SLUG;
}

/**
 * Get the configured front-end button label.
 *
 * @return string
 */
function cps_hc_gems_withdrawal_get_button_label() {
	global $cps_hc_gems_options;

	$label = isset( $cps_hc_gems_options['withdrawalrequest-buttonlabel'] ) ? trim( $cps_hc_gems_options['withdrawalrequest-buttonlabel'] ) : '';

	return $label ? $label : __( 'Withdraw from contract', 'surbma-magyar-woocommerce' );
}

require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/token.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/order-lookup.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/email-link.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/class-wc-email-withdrawal.php';

if ( ! is_admin() ) {
	require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/frontend.php';
}
