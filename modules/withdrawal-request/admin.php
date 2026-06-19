<?php

/**
 * Withdrawal request admin list and detail screens.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the admin list screen slug.
 *
 * @return string
 */
function cps_hc_gems_withdraw_get_menu_slug() {
	return 'edit.php?post_type=' . cps_hc_gems_withdraw_get_post_type();
}

add_action(
	'admin_menu',
	static function () {
		add_submenu_page(
			'woocommerce',
			__( 'Withdrawal requests', 'surbma-magyar-woocommerce' ),
			__( 'Withdrawal requests', 'surbma-magyar-woocommerce' ),
			'manage_woocommerce',
			cps_hc_gems_withdraw_get_menu_slug()
		);
	},
	99
);

add_action(
	'admin_menu',
	static function () {
		global $submenu;

		if ( ! isset( $submenu['woocommerce'] ) || ! is_array( $submenu['woocommerce'] ) ) {
			return;
		}

		$menu_slug = cps_hc_gems_withdraw_get_menu_slug();
		$withdraw_item = null;
		$withdraw_index = null;

		foreach ( $submenu['woocommerce'] as $index => $item ) {
			if ( isset( $item[2] ) && $item[2] === $menu_slug ) {
				$withdraw_item = $item;
				$withdraw_index = $index;
				break;
			}
		}

		if ( null === $withdraw_item || null === $withdraw_index ) {
			return;
		}

		unset( $submenu['woocommerce'][ $withdraw_index ] );

		$order_slugs = array( 'wc-orders', 'edit.php?post_type=shop_order' );
		$inserted = false;
		$new_submenu = array();

		foreach ( $submenu['woocommerce'] as $item ) {
			$new_submenu[] = $item;

			if ( ! $inserted && isset( $item[2] ) && in_array( $item[2], $order_slugs, true ) ) {
				$new_submenu[] = $withdraw_item;
				$inserted = true;
			}
		}

		if ( ! $inserted ) {
			$new_submenu[] = $withdraw_item;
		}

		$submenu['woocommerce'] = $new_submenu;
	},
	100
);

add_filter(
	'parent_file',
	static function ( $parent_file ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && cps_hc_gems_withdraw_get_post_type() === $screen->post_type ) {
			return 'woocommerce';
		}

		return $parent_file;
	}
);

add_filter(
	'submenu_file',
	static function ( $submenu_file ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && cps_hc_gems_withdraw_get_post_type() === $screen->post_type ) {
			return cps_hc_gems_withdraw_get_menu_slug();
		}

		return $submenu_file;
	}
);

add_filter(
	'manage_' . cps_hc_gems_withdraw_get_post_type() . '_posts_columns',
	static function ( $columns ) {
		return array(
			'cb'       => $columns['cb'] ?? '<input type="checkbox" />',
			'title'    => __( 'Request', 'surbma-magyar-woocommerce' ),
			'order'    => __( 'Order', 'surbma-magyar-woocommerce' ),
			'customer' => __( 'Customer', 'surbma-magyar-woocommerce' ),
			'date'     => __( 'Submitted', 'surbma-magyar-woocommerce' ),
			'status'   => __( 'Status', 'surbma-magyar-woocommerce' ),
		);
	}
);

add_action(
	'manage_' . cps_hc_gems_withdraw_get_post_type() . '_posts_custom_column',
	static function ( $column, $post_id ) {
		$request = cps_hc_gems_withdraw_get( $post_id );

		if ( ! $request ) {
			return;
		}

		switch ( $column ) {
			case 'order':
				$order = wc_get_order( $request['order_id'] );
				if ( $order ) {
					$order_url = $order->get_edit_order_url();
					printf(
						'<a href="%1$s">#%2$s</a>',
						esc_url( $order_url ),
						esc_html( $order->get_order_number() )
					);
				} else {
					echo esc_html( '#' . $request['order_id'] );
				}
				break;

			case 'customer':
				$name = $request['customer_name'];
				$email = $request['customer_email'];

				if ( $name ) {
					echo esc_html( $name );
				}

				if ( $email ) {
					if ( $name ) {
						echo '<br />';
					}
					printf(
						'<a href="mailto:%1$s">%2$s</a>',
						esc_attr( $email ),
						esc_html( $email )
					);
				}
				break;

			case 'status':
				$statuses = cps_hc_gems_withdraw_get_statuses();
				$label = $statuses[ $request['status'] ] ?? $request['status'];
				printf(
					'<span class="hc-withdraw-status hc-withdraw-status-%1$s">%2$s</span>',
					esc_attr( $request['status'] ),
					esc_html( $label )
				);
				break;
		}
	},
	10,
	2
);

add_filter(
	'manage_edit-' . cps_hc_gems_withdraw_get_post_type() . '_sortable_columns',
	static function ( $columns ) {
		$columns['date'] = 'date';
		$columns['status'] = 'status';

		return $columns;
	}
);

add_action(
	'add_meta_boxes',
	static function () {
		add_meta_box(
			'cps_hc_gems_withdraw_details',
			__( 'Withdrawal request details', 'surbma-magyar-woocommerce' ),
			'cps_hc_gems_withdraw_render_details_meta_box',
			cps_hc_gems_withdraw_get_post_type(),
			'normal',
			'high'
		);
	}
);

/**
 * Render the withdrawal request detail meta box.
 *
 * @param WP_Post $post Current post object.
 */
function cps_hc_gems_withdraw_render_details_meta_box( $post ) {
	$request = cps_hc_gems_withdraw_get( $post->ID );

	if ( ! $request ) {
		echo '<p>' . esc_html__( 'Withdrawal request data is unavailable.', 'surbma-magyar-woocommerce' ) . '</p>';
		return;
	}

	wp_nonce_field( 'cps_hc_gems_withdraw_save_status', 'cps_hc_gems_withdraw_status_nonce' );

	$order = wc_get_order( $request['order_id'] );
	$statuses = cps_hc_gems_withdraw_get_statuses();
	$submitted = get_date_from_gmt( $request['created_at'], get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
	?>
	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Order', 'surbma-magyar-woocommerce' ); ?></th>
				<td>
					<?php if ( $order ) : ?>
						<a href="<?php echo esc_url( $order->get_edit_order_url() ); ?>">
							#<?php echo esc_html( $order->get_order_number() ); ?>
						</a>
					<?php else : ?>
						#<?php echo esc_html( $request['order_id'] ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Customer', 'surbma-magyar-woocommerce' ); ?></th>
				<td>
					<?php if ( $request['customer_name'] ) : ?>
						<?php echo esc_html( $request['customer_name'] ); ?><br />
					<?php endif; ?>
					<a href="mailto:<?php echo esc_attr( $request['customer_email'] ); ?>">
						<?php echo esc_html( $request['customer_email'] ); ?>
					</a>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Submitted', 'surbma-magyar-woocommerce' ); ?></th>
				<td><?php echo esc_html( $submitted ); ?></td>
			</tr>
			<tr>
				<th scope="row"><label for="cps_hc_gems_withdraw_status"><?php esc_html_e( 'Status', 'surbma-magyar-woocommerce' ); ?></label></th>
				<td>
					<select name="cps_hc_gems_withdraw_status" id="cps_hc_gems_withdraw_status">
						<?php foreach ( $statuses as $status_key => $status_label ) : ?>
							<option value="<?php echo esc_attr( $status_key ); ?>" <?php selected( $request['status'], $status_key ); ?>>
								<?php echo esc_html( $status_label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

add_action(
	'save_post_' . cps_hc_gems_withdraw_get_post_type(),
	static function ( $post_id ) {
		if ( ! isset( $_POST['cps_hc_gems_withdraw_status_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cps_hc_gems_withdraw_status_nonce'] ) ), 'cps_hc_gems_withdraw_save_status' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! isset( $_POST['cps_hc_gems_withdraw_status'] ) ) {
			return;
		}

		$status = sanitize_key( wp_unslash( $_POST['cps_hc_gems_withdraw_status'] ) );
		cps_hc_gems_withdraw_update_status( $post_id, $status );
	}
);

add_action(
	'admin_head',
	static function () {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || cps_hc_gems_withdraw_get_post_type() !== $screen->post_type ) {
			return;
		}
		?>
		<style>
			.hc-withdraw-status {
				display: inline-block;
				padding: 2px 8px;
				border-radius: 3px;
				background: #f0f0f1;
				color: #1d2327;
				font-size: 12px;
				line-height: 1.6;
			}

			.hc-withdraw-status-confirmed {
				background: #e5f5fa;
				color: #007cba;
			}

			.hc-withdraw-status-processed {
				background: #edfaef;
				color: #008a20;
			}

			.hc-withdraw-status-rejected {
				background: #fcf0f1;
				color: #d63638;
			}
		</style>
		<?php
	}
);
