<?php

/**
 * Withdrawal request: order identification helpers (DEV-235).
 *
 * Two non-link identification paths:
 *  - logged-in customer picks from their own in-window orders;
 *  - guest provides order number + billing email (rate-limited, generic errors).
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Order statuses eligible for a withdrawal request.
 *
 * @return string[] WooCommerce status slugs (without the wc- prefix).
 */
function cps_hc_gems_withdrawal_eligible_order_statuses() {
	/**
	 * Filter the order statuses eligible for a withdrawal request.
	 *
	 * @since 2026.3.0
	 *
	 * @param string[] $statuses Status slugs.
	 */
	return apply_filters( 'cps_hc_gems_withdrawal_eligible_order_statuses', array( 'processing', 'completed' ) );
}

/**
 * Get the real client IP, honoring a Cloudflare connecting-IP header.
 *
 * @return string Validated IP, or empty string.
 */
function cps_hc_gems_withdrawal_get_remote_ip() {
	$candidate = '';

	if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
		$candidate = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) );
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$candidate = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}

	$ip = filter_var( $candidate, FILTER_VALIDATE_IP );

	return $ip ? $ip : '';
}

/**
 * Whether an order is eligible for a withdrawal request (status + window).
 *
 * @param WC_Order $order Order object.
 * @return bool
 */
function cps_hc_gems_withdrawal_order_is_eligible( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}

	if ( ! in_array( $order->get_status(), cps_hc_gems_withdrawal_eligible_order_statuses(), true ) ) {
		return false;
	}

	return cps_hc_gems_withdrawal_window_is_open( $order );
}

/**
 * Get the current user's own orders that are still within the withdrawal window.
 *
 * @param int $user_id User ID.
 * @return WC_Order[] Eligible orders, newest first.
 */
function cps_hc_gems_withdrawal_get_user_orders( $user_id ) {
	$user_id = absint( $user_id );

	if ( ! $user_id ) {
		return array();
	}

	$statuses = array_map( static function ( $status ) {
		return 'wc-' . $status;
	}, cps_hc_gems_withdrawal_eligible_order_statuses() );

	$query_args = array(
		'status'  => $statuses,
		'limit'   => 50,
		'orderby' => 'date',
		'order'   => 'DESC',
	);

	$by_id = wc_get_orders( array_merge( $query_args, array(
		'customer_id' => $user_id,
	) ) );

	$orders = array();

	foreach ( $by_id as $order ) {
		$orders[ $order->get_id() ] = $order;
	}

	$user = get_userdata( $user_id );

	if ( $user instanceof WP_User && is_email( $user->user_email ) ) {
		$by_email = wc_get_orders( array_merge( $query_args, array(
			'billing_email' => $user->user_email,
		) ) );

		foreach ( $by_email as $order ) {
			$orders[ $order->get_id() ] = $order;
		}
	}

	$eligible = array();

	foreach ( $orders as $order ) {
		if ( cps_hc_gems_withdrawal_window_is_open( $order ) ) {
			$eligible[] = $order;
		}
	}

	usort(
		$eligible,
		static function ( $a, $b ) {
			$a_date = $a->get_date_created();
			$b_date = $b->get_date_created();
			$a_ts   = $a_date ? $a_date->getTimestamp() : 0;
			$b_ts   = $b_date ? $b_date->getTimestamp() : 0;

			if ( $a_ts === $b_ts ) {
				return 0;
			}

			return ( $b_ts < $a_ts ) ? -1 : 1;
		}
	);

	return $eligible;
}

/**
 * Resolve a logged-in customer's selected order, enforcing ownership + window.
 *
 * @param int $order_id Selected order ID.
 * @param int $user_id  Current user ID.
 * @return WC_Order|null The order if owned and eligible, otherwise null.
 */
function cps_hc_gems_withdrawal_resolve_login_order( $order_id, $user_id ) {
	$order_id = absint( $order_id );
	$user_id  = absint( $user_id );

	if ( ! $order_id || ! $user_id ) {
		return null;
	}

	$order = wc_get_order( $order_id );

	if ( ! $order instanceof WC_Order ) {
		return null;
	}

	if ( (int) $order->get_customer_id() !== $user_id ) {
		$user = get_userdata( $user_id );

		if ( ! $user instanceof WP_User || ! is_email( $user->user_email ) ) {
			return null;
		}

		if ( strtolower( $order->get_billing_email() ) !== strtolower( $user->user_email ) ) {
			return null;
		}
	}

	if ( ! cps_hc_gems_withdrawal_order_is_eligible( $order ) ) {
		return null;
	}

	return $order;
}

/**
 * Look up a guest order by order number + billing email.
 *
 * Rate-limited per IP to resist enumeration; the caller surfaces a single
 * generic error regardless of which part failed.
 *
 * @param string $order_number Submitted order number / ID.
 * @param string $email        Submitted billing email.
 * @return WC_Order|null The order on a verified match, otherwise null.
 */
function cps_hc_gems_withdrawal_guest_lookup( $order_number, $email ) {
	$email = sanitize_email( $email );

	if ( ! is_email( $email ) ) {
		return null;
	}

	$order_id = absint( preg_replace( '/\D/', '', (string) $order_number ) );

	if ( ! $order_id ) {
		return null;
	}

	$order = wc_get_order( $order_id );

	if ( ! $order instanceof WC_Order ) {
		return null;
	}

	if ( strtolower( $order->get_billing_email() ) !== strtolower( $email ) ) {
		return null;
	}

	if ( ! cps_hc_gems_withdrawal_order_is_eligible( $order ) ) {
		return null;
	}

	return $order;
}

/**
 * Whether the current IP has exceeded the guest-lookup rate limit.
 *
 * @return bool True if the request should be blocked.
 */
function cps_hc_gems_withdrawal_guest_lookup_is_rate_limited() {
	$ip = cps_hc_gems_withdrawal_get_remote_ip();

	if ( ! $ip ) {
		return false;
	}

	$key      = 'cps_hc_gems_wd_rl_' . md5( $ip );
	$attempts = (int) get_transient( $key );

	return $attempts >= 10;
}

/**
 * Record a guest-lookup attempt for rate limiting.
 *
 * @return void
 */
function cps_hc_gems_withdrawal_guest_lookup_register_attempt() {
	$ip = cps_hc_gems_withdrawal_get_remote_ip();

	if ( ! $ip ) {
		return;
	}

	$key      = 'cps_hc_gems_wd_rl_' . md5( $ip );
	$attempts = (int) get_transient( $key );

	set_transient( $key, $attempts + 1, 10 * MINUTE_IN_SECONDS );
}
