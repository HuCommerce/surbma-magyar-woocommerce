<?php

/**
 * Withdrawal request: custom post type, custom statuses and meta.
 *
 * Data model decision DEV-234: a dedicated, non-public CPT
 * `cps_hc_gems_withdrawal` with an auditable lifecycle.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Get the custom withdrawal post statuses with their localized labels.
 *
 * Internal slugs are prefixed to avoid collisions; UI labels are localized.
 *
 * @return array Map of status slug => localized label.
 */
function cps_hc_gems_withdrawal_get_statuses() {
	return array(
		'wd_pending'  => __( 'Beérkezett', 'surbma-magyar-woocommerce' ),
		'wd_accepted' => __( 'Elfogadva', 'surbma-magyar-woocommerce' ),
		'wd_rejected' => __( 'Elutasítva', 'surbma-magyar-woocommerce' ),
		'wd_refunded' => __( 'Visszatérítve', 'surbma-magyar-woocommerce' ),
	);
}

/**
 * Get the localized label for a withdrawal status slug.
 *
 * @param string $status Status slug.
 * @return string Localized label, or the raw slug if unknown.
 */
function cps_hc_gems_withdrawal_get_status_label( $status ) {
	$statuses = cps_hc_gems_withdrawal_get_statuses();

	return isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
}

// Register the custom post statuses.
add_action( 'init', static function () {
	foreach ( cps_hc_gems_withdrawal_get_statuses() as $status => $label ) {
		register_post_status( $status, array(
			'label'                     => $label,
			'public'                    => false,
			'internal'                  => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
		) );
	}
} );

// Register the withdrawal custom post type.
add_action( 'init', static function () {
	$labels = array(
		'name'               => __( 'Elállási kérelmek', 'surbma-magyar-woocommerce' ),
		'singular_name'      => __( 'Elállási kérelem', 'surbma-magyar-woocommerce' ),
		'menu_name'          => __( 'Elállási kérelmek', 'surbma-magyar-woocommerce' ),
		'all_items'          => __( 'Elállási kérelmek', 'surbma-magyar-woocommerce' ),
		'view_item'          => __( 'Elállási kérelem megtekintése', 'surbma-magyar-woocommerce' ),
		'search_items'       => __( 'Elállási kérelmek keresése', 'surbma-magyar-woocommerce' ),
		'not_found'          => __( 'Nincs elállási kérelem.', 'surbma-magyar-woocommerce' ),
		'not_found_in_trash' => __( 'Nincs elállási kérelem a kukában.', 'surbma-magyar-woocommerce' ),
	);

	register_post_type( CPS_HC_GEMS_WITHDRAWAL_CPT, array(
		'labels'              => $labels,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'woocommerce',
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'hierarchical'        => false,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'           => false,
		'map_meta_cap'        => true,
		'capabilities'        => array(
			'edit_post'           => 'edit_shop_orders',
			'read_post'           => 'edit_shop_orders',
			'delete_post'         => 'edit_shop_orders',
			'edit_posts'          => 'edit_shop_orders',
			'edit_others_posts'   => 'edit_shop_orders',
			'delete_posts'        => 'edit_shop_orders',
			'publish_posts'       => 'edit_shop_orders',
			'read_private_posts'  => 'edit_shop_orders',
			'create_posts'        => 'do_not_allow',
		),
	) );
} );

// Register the meta keys (typed, not exposed in REST).
add_action( 'init', static function () {
	$meta_keys = array(
		'_order_id'          => 'integer',
		'_scope'             => 'string',
		'_items'             => 'string',
		'_reason'            => 'string',
		'_requested_at'      => 'string',
		'_processed_at'      => 'string',
		'_refund_status'     => 'string',
		'_consumer_email'    => 'string',
		'_consumer_ip'       => 'string',
		'_identify_method'   => 'string',
		'_window_end'        => 'string',
	);

	foreach ( $meta_keys as $key => $type ) {
		register_post_meta( CPS_HC_GEMS_WITHDRAWAL_CPT, $key, array(
			'type'              => $type,
			'single'            => true,
			'show_in_rest'      => false,
			'auth_callback'     => static function () {
				return current_user_can( 'edit_shop_orders' );
			},
		) );
	}
} );

/**
 * Create a withdrawal request post.
 *
 * @param array $data {
 *     @type int    $order_id        Linked order ID.
 *     @type string $scope           'whole' or 'partial'.
 *     @type array  $items           Map of order_item_id => qty (for partial).
 *     @type string $reason          Optional consumer-stated reason.
 *     @type string $consumer_email  Consumer email from the order.
 *     @type string $consumer_ip     Submission IP.
 *     @type string $identify_method 'link' | 'login' | 'guest'.
 *     @type string $window_end      Window end date (GMT, Y-m-d H:i:s).
 * }
 * @return int|WP_Error The new post ID, or WP_Error on failure.
 */
function cps_hc_gems_withdrawal_create( $data ) {
	$order_id = isset( $data['order_id'] ) ? absint( $data['order_id'] ) : 0;

	if ( ! $order_id ) {
		return new WP_Error( 'cps_hc_gems_withdrawal_no_order', __( 'Hiányzó rendelésazonosító.', 'surbma-magyar-woocommerce' ) );
	}

	$now_gmt = current_time( 'mysql', true );
	$scope   = ( isset( $data['scope'] ) && 'partial' === $data['scope'] ) ? 'partial' : 'whole';

	/* translators: %d: order number. */
	$title = sprintf( __( 'Elállási kérelem - #%d', 'surbma-magyar-woocommerce' ), $order_id );

	$post_id = wp_insert_post( array(
		'post_type'   => CPS_HC_GEMS_WITHDRAWAL_CPT,
		'post_title'  => $title,
		'post_status' => 'wd_pending',
	), true );

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	$items = isset( $data['items'] ) && is_array( $data['items'] ) ? $data['items'] : array();

	update_post_meta( $post_id, '_order_id', $order_id );
	update_post_meta( $post_id, '_scope', $scope );
	update_post_meta( $post_id, '_items', wp_json_encode( $items ) );
	update_post_meta( $post_id, '_reason', isset( $data['reason'] ) ? sanitize_textarea_field( $data['reason'] ) : '' );
	update_post_meta( $post_id, '_requested_at', $now_gmt );
	update_post_meta( $post_id, '_processed_at', '' );
	update_post_meta( $post_id, '_refund_status', '' );
	update_post_meta( $post_id, '_consumer_email', isset( $data['consumer_email'] ) ? sanitize_email( $data['consumer_email'] ) : '' );
	update_post_meta( $post_id, '_consumer_ip', isset( $data['consumer_ip'] ) ? sanitize_text_field( $data['consumer_ip'] ) : '' );
	update_post_meta( $post_id, '_identify_method', isset( $data['identify_method'] ) ? sanitize_key( $data['identify_method'] ) : '' );
	update_post_meta( $post_id, '_window_end', isset( $data['window_end'] ) ? sanitize_text_field( $data['window_end'] ) : '' );

	/**
	 * Fires after a withdrawal request has been stored.
	 *
	 * @since 2026.3.0
	 *
	 * @param int   $post_id  The withdrawal post ID.
	 * @param int   $order_id The linked order ID.
	 * @param array $data     The submitted data.
	 */
	do_action( 'cps_hc_gems_withdrawal_created', $post_id, $order_id, $data );

	return $post_id;
}

/**
 * Get the decoded `_items` map for a withdrawal request.
 *
 * @param int $post_id Withdrawal post ID.
 * @return array Map of order_item_id => qty.
 */
function cps_hc_gems_withdrawal_get_items( $post_id ) {
	$raw = get_post_meta( $post_id, '_items', true );

	if ( empty( $raw ) ) {
		return array();
	}

	$items = json_decode( $raw, true );

	return is_array( $items ) ? $items : array();
}

/**
 * Get withdrawal request posts linked to an order.
 *
 * @param int   $order_id  Order ID.
 * @param array $statuses  Optional list of statuses to filter by.
 * @return WP_Post[] Array of withdrawal posts.
 */
function cps_hc_gems_withdrawal_get_by_order( $order_id, $statuses = array() ) {
	$args = array(
		'post_type'      => CPS_HC_GEMS_WITHDRAWAL_CPT,
		'post_status'    => ! empty( $statuses ) ? $statuses : array_keys( cps_hc_gems_withdrawal_get_statuses() ),
		'posts_per_page' => 50,
		'no_found_rows'  => true,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- low-volume admin/link lookup keyed by order id.
			array(
				'key'   => '_order_id',
				'value' => absint( $order_id ),
			),
		),
	);

	return get_posts( $args );
}
