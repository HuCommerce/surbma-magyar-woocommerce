<?php

/**
 * Module: Withdrawal request (Elállási kérelem)
 *
 * EU Directive 2023/2673 "withdrawal button": lets a consumer exercise their
 * statutory right of withdrawal entirely online, without logging in, recorded as
 * a status-tracked `cps_hc_gems_withdraw` post.
 *
 * This is the only file the module loader includes; it require_once's the parts
 * under lib/withdrawal/.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Default endpoint slug for the public withdrawal page.
 */
if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_DEFAULT_SLUG' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_DEFAULT_SLUG', 'elallas' );
}

/**
 * Custom post type name for stored withdrawal requests.
 *
 * Must stay within WordPress's 20-character post type name limit, so this is the
 * shortened slug (the full "withdrawal" word would be 22 chars and silently fail
 * to register).
 */
if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_CPT' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_CPT', 'cps_hc_gems_withdraw' );
}

/**
 * Withdrawal window length in days (fixed in the MVP, configurable is Pro).
 */
if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_WINDOW_DAYS' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_WINDOW_DAYS', 14 );
}

/**
 * Get the configured endpoint slug for the withdrawal page.
 *
 * @return string Sanitized slug, falling back to the default.
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

	return $label ? $label : __( 'Elállás a szerződéstől', 'surbma-magyar-woocommerce' );
}

// Load the module parts.
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/cpt.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/token.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/order-lookup.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/frontend.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/email-link.php';
require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/class-wc-email-withdrawal.php';

if ( is_admin() ) {
	require_once CPS_HC_GEMS_DIR . '/lib/withdrawal/admin-order.php';
}
