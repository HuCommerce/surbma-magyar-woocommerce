<?php

/**
 * Module: Product price history - REST endpoint (read only)
 *
 * GET /wp-json/hucommerce/v1/price-history/<id>
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Register the price history route
add_action( 'rest_api_init', static function () {
	register_rest_route( 'hucommerce/v1', '/price-history/(?P<id>\d+)', array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'cps_hc_gems_rest_get_price_history',
		// Public endpoint without nonce, so it works with page cache. Visibility is checked in the callback.
		'permission_callback' => '__return_true',
		'args' => array(
			'id' => array(
				'type' => 'integer',
				'required' => true,
				'sanitize_callback' => 'absint',
			),
		),
	) );
} );

// Check if the product (or variation) price history can be shown to the current user
function cps_hc_gems_price_history_is_visible( $product ) {
	$product_id = $product->get_id();

	// Users, who can edit the product, can see it in any status
	if ( current_user_can( 'edit_post', $product_id ) ) {
		return true;
	}

	// For variations the parent product must be published too
	$visibility_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product_id;

	if ( 'publish' !== get_post_status( $product_id ) || 'publish' !== get_post_status( $visibility_id ) || post_password_required( $visibility_id ) ) {
		return false;
	}

	return true;
}

// REST callback: return the price history of a product or variation
function cps_hc_gems_rest_get_price_history( $request ) {
	$product_id = absint( $request['id'] );
	$product    = $product_id ? wc_get_product( $product_id ) : false;

	// Same error for not existing, not product and not visible IDs, so IDs can't be guessed
	if ( !$product || !cps_hc_gems_price_history_is_visible( $product ) ) {
		return new WP_Error( 'rest_not_found', __( 'Product not found.', 'surbma-magyar-woocommerce' ), array( 'status' => 404 ) );
	}

	$product_price_history = get_post_meta( $product_id, '_hc_product_price_history', true );
	$product_price_history = is_array( $product_price_history ) ? $product_price_history : array();

	// Newest first
	$sorted_history = $product_price_history;
	usort( $sorted_history, static function ( $a, $b ) {
		return strcmp( (string) $b[0], (string) $a[0] );
	} );

	// Saved dates are in the site's timezone
	$timezone     = wp_timezone();
	$recent_limit = current_datetime()->modify( '-30 days' );

	$rows = array();
	foreach ( $sorted_history as $item ) {
		if ( !is_array( $item ) || count( $item ) < 3 ) {
			continue;
		}

		$date    = (string) $item[0];
		$regular = intval( $item[1] );
		$active  = intval( $item[2] );

		if ( 0 === $active || 0 === $regular ) {
			$discount = 0;
		} else {
			$discount = intval( round( ( 1 - ( $active / $regular ) ) * 100 ) );
		}

		$item_date = date_create_immutable( $date, $timezone );

		$rows[] = array(
			'date' => $date,
			'regular' => $regular,
			'active' => $active,
			'discount' => $discount,
			'recent' => $item_date && $item_date >= $recent_limit,
		);
	}

	$data = array(
		'product_id' => $product_id,
		'title' => $product->get_name(),
		'currency_symbol' => html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' ),
		'rows' => $rows,
		'raw' => array_values( $product_price_history ),
	);

	/**
	 * Filters the price history REST response data.
	 *
	 * @since 2026.3.0
	 *
	 * @param array $data       Response data: product_id, title, currency_symbol, rows, raw.
	 * @param int   $product_id Product or variation ID.
	 */
	$data = apply_filters( 'cps_hc_gems_price_history_data', $data, $product_id );

	$response = rest_ensure_response( $data );
	$response->header( 'Cache-Control', is_user_logged_in() ? 'no-store' : 'public, max-age=300' );

	return $response;
}
