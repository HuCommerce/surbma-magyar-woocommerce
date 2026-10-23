<?php

/**
 * Withdrawal request: tokenized, login-free access (DEV-236).
 *
 * Per-order HMAC token + a public rewrite endpoint, valid for a fixed 14-day
 * window measured from the delivery (completion) date.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Order meta key holding the stable withdrawal token.
 */
if ( ! defined( 'CPS_HC_GEMS_WITHDRAWAL_TOKEN_META' ) ) {
	define( 'CPS_HC_GEMS_WITHDRAWAL_TOKEN_META', '_cps_hc_gems_withdrawal_token' );
}

/**
 * Compute the HMAC token for an order.
 *
 * The token is derived from the order ID and its creation date, signed with the
 * site auth salt. It is stable (regenerable) and not guessable.
 *
 * @param WC_Order $order Order object.
 * @return string Hex token, or empty string on failure.
 */
function cps_hc_gems_withdrawal_compute_token( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return '';
	}

	$created = $order->get_date_created();
	$created = $created ? $created->getTimestamp() : 0;

	$data = $order->get_id() . '|' . $created;

	return hash_hmac( 'sha256', $data, wp_salt( 'auth' ) );
}

/**
 * Get (and lazily store) the withdrawal token for an order.
 *
 * @param WC_Order $order Order object.
 * @return string Token, or empty string on failure.
 */
function cps_hc_gems_withdrawal_get_token( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return '';
	}

	$token = $order->get_meta( CPS_HC_GEMS_WITHDRAWAL_TOKEN_META, true );

	if ( ! $token ) {
		$token = cps_hc_gems_withdrawal_compute_token( $order );

		if ( $token ) {
			$order->update_meta_data( CPS_HC_GEMS_WITHDRAWAL_TOKEN_META, $token );
			$order->save();
		}
	}

	return $token;
}

/**
 * Verify a token against an order (timing-safe).
 *
 * @param WC_Order $order Order object.
 * @param string   $key   Token from the request.
 * @return bool True if valid.
 */
function cps_hc_gems_withdrawal_verify_token( $order, $key ) {
	if ( ! $order instanceof WC_Order || ! is_string( $key ) || '' === $key ) {
		return false;
	}

	$expected = cps_hc_gems_withdrawal_compute_token( $order );

	if ( '' === $expected ) {
		return false;
	}

	return hash_equals( $expected, $key );
}

/**
 * Get the timestamp at which the withdrawal window starts for an order.
 *
 * Best available proxy for "delivery date": completion date, falling back to the
 * paid date, then the creation date.
 *
 * @param WC_Order $order Order object.
 * @return int Unix timestamp (GMT), or 0 if undeterminable.
 */
function cps_hc_gems_withdrawal_get_window_start( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return 0;
	}

	$date = $order->get_date_completed();

	if ( ! $date ) {
		$date = $order->get_date_paid();
	}

	if ( ! $date ) {
		$date = $order->get_date_created();
	}

	return $date ? $date->getTimestamp() : 0;
}

/**
 * Get the timestamp at which the withdrawal window ends for an order.
 *
 * @param WC_Order $order Order object.
 * @return int Unix timestamp (GMT), or 0 if undeterminable.
 */
function cps_hc_gems_withdrawal_get_window_end( $order ) {
	$start = cps_hc_gems_withdrawal_get_window_start( $order );

	if ( ! $start ) {
		return 0;
	}

	return $start + ( CPS_HC_GEMS_WITHDRAWAL_WINDOW_DAYS * DAY_IN_SECONDS );
}

/**
 * Whether the withdrawal window is currently open for an order.
 *
 * @param WC_Order $order Order object.
 * @return bool
 */
function cps_hc_gems_withdrawal_window_is_open( $order ) {
	$end = cps_hc_gems_withdrawal_get_window_end( $order );

	if ( ! $end ) {
		return false;
	}

	return time() <= $end;
}

/**
 * Build the public, tokenized withdrawal URL for an order.
 *
 * @param WC_Order $order Order object.
 * @return string Absolute URL, or empty string on failure.
 */
function cps_hc_gems_withdrawal_get_url( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return '';
	}

	$token = cps_hc_gems_withdrawal_get_token( $order );

	if ( ! $token ) {
		return '';
	}

	$base = home_url( '/' . cps_hc_gems_withdrawal_get_slug() . '/' );

	return add_query_arg( array(
		'order' => $order->get_id(),
		'key'   => $token,
	), $base );
}

// Register the rewrite endpoint and query vars.
add_action( 'init', static function () {
	$slug = cps_hc_gems_withdrawal_get_slug();

	add_rewrite_rule( '^' . preg_quote( $slug, '/' ) . '/?$', 'index.php?cps_hc_gems_withdrawal=1', 'top' );
} );

add_filter( 'query_vars', static function ( $vars ) {
	$vars[] = 'cps_hc_gems_withdrawal';

	return $vars;
} );

/**
 * Flush rewrite rules once after the settings are saved, so a changed slug or a
 * freshly enabled module gets a working endpoint without a manual permalink save.
 */
add_action( 'update_option_surbma_hc_fields', static function () {
	update_option( 'cps_hc_gems_withdrawal_flush', 1, false );
} );

add_action( 'init', static function () {
	if ( get_option( 'cps_hc_gems_withdrawal_flush' ) ) {
		flush_rewrite_rules( false );
		delete_option( 'cps_hc_gems_withdrawal_flush' );
	}
}, 99 );
