<?php

/**
 * Withdrawal request: admin list, status transitions, order metabox (DEV-239).
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Custom columns for the withdrawal CPT list.
add_filter( 'manage_' . CPS_HC_GEMS_WITHDRAWAL_CPT . '_posts_columns', static function ( $columns ) {
	$new = array(
		'cb'             => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'title'          => __( 'Kérelem', 'surbma-magyar-woocommerce' ),
		'wd_order'       => __( 'Rendelés', 'surbma-magyar-woocommerce' ),
		'wd_consumer'    => __( 'Vásárló', 'surbma-magyar-woocommerce' ),
		'wd_scope'       => __( 'Hatókör', 'surbma-magyar-woocommerce' ),
		'wd_status'      => __( 'Állapot', 'surbma-magyar-woocommerce' ),
		'wd_requested'   => __( 'Beérkezett', 'surbma-magyar-woocommerce' ),
	);

	return $new;
} );

add_action( 'manage_' . CPS_HC_GEMS_WITHDRAWAL_CPT . '_posts_custom_column', static function ( $column, $post_id ) {
	switch ( $column ) {
		case 'wd_order':
			$order_id = absint( get_post_meta( $post_id, '_order_id', true ) );

			if ( $order_id ) {
				$url = admin_url( 'post.php?post=' . $order_id . '&action=edit' );
				echo '<a href="' . esc_url( $url ) . '">#' . esc_html( $order_id ) . '</a>';
			} else {
				echo '&mdash;';
			}
			break;

		case 'wd_consumer':
			echo esc_html( get_post_meta( $post_id, '_consumer_email', true ) );
			break;

		case 'wd_scope':
			$scope = get_post_meta( $post_id, '_scope', true );
			echo esc_html( 'whole' === $scope ? __( 'Teljes', 'surbma-magyar-woocommerce' ) : __( 'Részleges', 'surbma-magyar-woocommerce' ) );
			break;

		case 'wd_status':
			$status = get_post_status( $post_id );
			echo '<span class="cps-hc-wd-status cps-hc-wd-status--' . esc_attr( $status ) . '">' . esc_html( cps_hc_gems_withdrawal_get_status_label( $status ) ) . '</span>';
			break;

		case 'wd_requested':
			$requested = get_post_meta( $post_id, '_requested_at', true );

			if ( $requested ) {
				$timestamp = strtotime( $requested . ' UTC' );
				echo esc_html( wp_date( 'Y-m-d H:i', $timestamp ) );
			} else {
				echo '&mdash;';
			}
			break;
	}
}, 10, 2 );

// Status transition metabox on the withdrawal edit screen.
add_action( 'add_meta_boxes', static function () {
	add_meta_box(
		'cps_hc_gems_withdrawal_actions',
		__( 'Elállási kérelem kezelése', 'surbma-magyar-woocommerce' ),
		'cps_hc_gems_withdrawal_render_actions_metabox',
		CPS_HC_GEMS_WITHDRAWAL_CPT,
		'side',
		'high'
	);
} );

/**
 * Render the status-transition metabox.
 *
 * @param WP_Post $post Withdrawal post.
 * @return void
 */
function cps_hc_gems_withdrawal_render_actions_metabox( $post ) {
	$status        = get_post_status( $post );
	$order_id      = absint( get_post_meta( $post->ID, '_order_id', true ) );
	$scope         = get_post_meta( $post->ID, '_scope', true );
	$reason        = get_post_meta( $post->ID, '_reason', true );
	$refund_status = get_post_meta( $post->ID, '_refund_status', true );
	$processed_at  = get_post_meta( $post->ID, '_processed_at', true );
	$method        = get_post_meta( $post->ID, '_identify_method', true );

	echo '<p><strong>' . esc_html__( 'Állapot:', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html( cps_hc_gems_withdrawal_get_status_label( $status ) ) . '</p>';

	if ( $order_id ) {
		echo '<p><strong>' . esc_html__( 'Rendelés:', 'surbma-magyar-woocommerce' ) . '</strong> <a href="' . esc_url( admin_url( 'post.php?post=' . $order_id . '&action=edit' ) ) . '">#' . esc_html( $order_id ) . '</a></p>';
	}

	echo '<p><strong>' . esc_html__( 'Hatókör:', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html( 'whole' === $scope ? __( 'Teljes rendelés', 'surbma-magyar-woocommerce' ) : __( 'Részleges', 'surbma-magyar-woocommerce' ) ) . '</p>';

	if ( 'partial' === $scope ) {
		$items = cps_hc_gems_withdrawal_get_items( $post->ID );
		$order = $order_id ? wc_get_order( $order_id ) : false;

		if ( ! empty( $items ) && $order instanceof WC_Order ) {
			echo '<ul style="margin-left: 1em; list-style: disc;">';
			foreach ( $items as $item_id => $qty ) {
				$item = $order->get_item( $item_id );
				$name = $item ? $item->get_name() : '';
				echo '<li>' . esc_html( $name ) . ' &times; ' . esc_html( absint( $qty ) ) . '</li>';
			}
			echo '</ul>';
		}
	}

	if ( $method ) {
		echo '<p><strong>' . esc_html__( 'Azonosítás:', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html( $method ) . '</p>';
	}

	if ( $reason ) {
		echo '<p><strong>' . esc_html__( 'Indoklás:', 'surbma-magyar-woocommerce' ) . '</strong><br />' . esc_html( $reason ) . '</p>';
	}

	if ( $processed_at ) {
		echo '<p><strong>' . esc_html__( 'Feldolgozva:', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html( wp_date( 'Y-m-d H:i', strtotime( $processed_at . ' UTC' ) ) ) . '</p>';
	}

	echo '<hr />';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	wp_nonce_field( 'cps_hc_gems_withdrawal_transition', 'cps_hc_gems_wd_transition_nonce' );
	echo '<input type="hidden" name="action" value="cps_hc_gems_withdrawal_transition" />';
	echo '<input type="hidden" name="post_id" value="' . esc_attr( $post->ID ) . '" />';

	echo '<p><label for="cps-hc-wd-refund-status"><strong>' . esc_html__( 'Visszatérítés állapota:', 'surbma-magyar-woocommerce' ) . '</strong></label>';
	echo '<input type="text" id="cps-hc-wd-refund-status" name="refund_status" value="' . esc_attr( $refund_status ) . '" style="width: 100%;" /></p>';

	echo '<p>';
	echo '<button type="submit" name="new_status" value="wd_accepted" class="button">' . esc_html__( 'Elfogadás', 'surbma-magyar-woocommerce' ) . '</button> ';
	echo '<button type="submit" name="new_status" value="wd_rejected" class="button">' . esc_html__( 'Elutasítás', 'surbma-magyar-woocommerce' ) . '</button> ';
	echo '<button type="submit" name="new_status" value="wd_refunded" class="button button-primary">' . esc_html__( 'Visszatérítve', 'surbma-magyar-woocommerce' ) . '</button>';
	echo '</p>';
	echo '</form>';
}

// Handle the status transition POST.
add_action( 'admin_post_cps_hc_gems_withdrawal_transition', static function () {
	$nonce = isset( $_POST['cps_hc_gems_wd_transition_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cps_hc_gems_wd_transition_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'cps_hc_gems_withdrawal_transition' ) ) {
		wp_die( esc_html__( 'Érvénytelen kérés.', 'surbma-magyar-woocommerce' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( ! $post_id || ! current_user_can( 'edit_shop_orders' ) ) {
		wp_die( esc_html__( 'Nincs jogosultsága ehhez a művelethez.', 'surbma-magyar-woocommerce' ) );
	}

	$new_status = isset( $_POST['new_status'] ) ? sanitize_key( wp_unslash( $_POST['new_status'] ) ) : '';
	$statuses   = cps_hc_gems_withdrawal_get_statuses();

	if ( ! isset( $statuses[ $new_status ] ) ) {
		wp_die( esc_html__( 'Ismeretlen állapot.', 'surbma-magyar-woocommerce' ) );
	}

	wp_update_post( array(
		'ID'          => $post_id,
		'post_status' => $new_status,
	) );

	update_post_meta( $post_id, '_processed_at', current_time( 'mysql', true ) );

	if ( isset( $_POST['refund_status'] ) ) {
		update_post_meta( $post_id, '_refund_status', sanitize_text_field( wp_unslash( $_POST['refund_status'] ) ) );
	}

	wp_safe_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit&cps_hc_wd_updated=1' ) );
	exit;
} );

// Metabox on the order edit screen listing linked withdrawals.
add_action( 'woocommerce_admin_order_data_after_order_details', static function ( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return;
	}

	$withdrawals = cps_hc_gems_withdrawal_get_by_order( $order->get_id() );

	if ( empty( $withdrawals ) ) {
		return;
	}

	echo '<div class="cps-hc-wd-order-metabox" style="clear: both; padding-top: 12px;">';
	echo '<h3>' . esc_html__( 'Elállási kérelmek', 'surbma-magyar-woocommerce' ) . '</h3>';
	echo '<ul>';

	foreach ( $withdrawals as $wd ) {
		$status = get_post_status( $wd->ID );
		$scope  = get_post_meta( $wd->ID, '_scope', true );
		$url    = admin_url( 'post.php?post=' . $wd->ID . '&action=edit' );

		echo '<li>';
		echo '<a href="' . esc_url( $url ) . '">' . esc_html( cps_hc_gems_withdrawal_get_status_label( $status ) ) . '</a> &ndash; ';
		echo esc_html( 'whole' === $scope ? __( 'teljes rendelés', 'surbma-magyar-woocommerce' ) : __( 'részleges', 'surbma-magyar-woocommerce' ) );

		if ( 'partial' === $scope ) {
			$items = cps_hc_gems_withdrawal_get_items( $wd->ID );
			$parts = array();

			foreach ( $items as $item_id => $qty ) {
				$item    = $order->get_item( $item_id );
				$name    = $item ? $item->get_name() : '';
				$parts[] = $name . ' (' . absint( $qty ) . ')';
			}

			if ( ! empty( $parts ) ) {
				echo ': ' . esc_html( implode( ', ', $parts ) );
			}
		}

		echo '</li>';
	}

	echo '</ul>';
	echo '</div>';
} );
