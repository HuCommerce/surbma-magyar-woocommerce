<?php

/**
 * Withdrawal request storage helpers.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the withdrawal request post type slug.
 *
 * WordPress allows post type names up to 20 characters.
 *
 * @return string
 */
function cps_hc_gems_withdraw_get_post_type() {
	return 'cps_hc_gems_withdraw';
}

/**
 * Get supported withdrawal request statuses.
 *
 * @return array<string, string> Status slug => translated label.
 */
function cps_hc_gems_withdraw_get_statuses() {
	return array(
		'pending'   => __( 'Pending', 'surbma-magyar-woocommerce' ),
		'confirmed' => __( 'Confirmed', 'surbma-magyar-woocommerce' ),
		'processed' => __( 'Processed', 'surbma-magyar-woocommerce' ),
		'rejected'  => __( 'Rejected', 'surbma-magyar-woocommerce' ),
	);
}

/**
 * Check whether a status slug is valid.
 *
 * @param string $status Status slug.
 * @return bool
 */
function cps_hc_gems_withdraw_is_valid_status( $status ) {
	$statuses = cps_hc_gems_withdraw_get_statuses();

	return is_string( $status ) && array_key_exists( $status, $statuses );
}

/**
 * Create a withdrawal request entry.
 *
 * @param array $args {
 *     Request data.
 *
 *     @type int    $order_id       WooCommerce order ID.
 *     @type string $customer_email Consumer email address.
 *     @type string $customer_name  Consumer display name.
 *     @type string $status         Optional. Defaults to pending.
 * }
 * @return int|false Post ID on success, false on failure.
 */
function cps_hc_gems_withdraw_create( $args ) {
	$order_id = isset( $args['order_id'] ) ? absint( $args['order_id'] ) : 0;
	$customer_email = isset( $args['customer_email'] ) ? sanitize_email( $args['customer_email'] ) : '';
	$customer_name = isset( $args['customer_name'] ) ? sanitize_text_field( $args['customer_name'] ) : '';
	$status = isset( $args['status'] ) ? sanitize_key( $args['status'] ) : 'pending';

	if ( ! $order_id || ! is_email( $customer_email ) ) {
		return false;
	}

	if ( ! cps_hc_gems_withdraw_is_valid_status( $status ) ) {
		$status = 'pending';
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return false;
	}

	if ( '' === $customer_name ) {
		$customer_name = trim( $order->get_formatted_billing_full_name() );
	}

	/* translators: %s: WooCommerce order number */
	$post_title = sprintf(
		__( 'Withdrawal request for order #%s', 'surbma-magyar-woocommerce' ),
		$order->get_order_number()
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => cps_hc_gems_withdraw_get_post_type(),
			'post_status' => 'publish',
			'post_title'  => $post_title,
		),
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, '_hc_withdraw_order_id', $order_id );
	update_post_meta( $post_id, '_hc_withdraw_customer_email', $customer_email );
	update_post_meta( $post_id, '_hc_withdraw_customer_name', $customer_name );
	update_post_meta( $post_id, '_hc_withdraw_status', $status );

	return (int) $post_id;
}

/**
 * Get a withdrawal request as an associative array.
 *
 * @param int $post_id Withdrawal request post ID.
 * @return array|null
 */
function cps_hc_gems_withdraw_get( $post_id ) {
	$post_id = absint( $post_id );
	$post = get_post( $post_id );

	if ( ! $post || cps_hc_gems_withdraw_get_post_type() !== $post->post_type ) {
		return null;
	}

	$status = get_post_meta( $post_id, '_hc_withdraw_status', true );
	if ( ! cps_hc_gems_withdraw_is_valid_status( $status ) ) {
		$status = 'pending';
	}

	return array(
		'id'             => $post_id,
		'order_id'       => absint( get_post_meta( $post_id, '_hc_withdraw_order_id', true ) ),
		'customer_email' => (string) get_post_meta( $post_id, '_hc_withdraw_customer_email', true ),
		'customer_name'  => (string) get_post_meta( $post_id, '_hc_withdraw_customer_name', true ),
		'status'         => $status,
		'created_at'     => $post->post_date_gmt,
	);
}

/**
 * Update the status of a withdrawal request.
 *
 * @param int    $post_id Withdrawal request post ID.
 * @param string $status  Status slug.
 * @return bool
 */
function cps_hc_gems_withdraw_update_status( $post_id, $status ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || ! cps_hc_gems_withdraw_is_valid_status( $status ) ) {
		return false;
	}

	$request = cps_hc_gems_withdraw_get( $post_id );
	if ( ! $request ) {
		return false;
	}

	update_post_meta( $post_id, '_hc_withdraw_status', $status );

	return true;
}

/**
 * Get withdrawal requests linked to an order.
 *
 * @param int $order_id WooCommerce order ID.
 * @return array<int, array>
 */
function cps_hc_gems_withdraw_get_by_order_id( $order_id ) {
	$order_id = absint( $order_id );

	if ( ! $order_id ) {
		return array();
	}

	$query = new WP_Query(
		array(
			'post_type'      => cps_hc_gems_withdraw_get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_hc_withdraw_order_id',
					'value' => $order_id,
				),
			),
		)
	);

	$requests = array();

	foreach ( $query->posts as $post ) {
		$request = cps_hc_gems_withdraw_get( $post->ID );
		if ( $request ) {
			$requests[] = $request;
		}
	}

	return $requests;
}
