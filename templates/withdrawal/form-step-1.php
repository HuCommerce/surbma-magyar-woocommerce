<?php

/**
 * Withdrawal request: step 1 - select what to withdraw.
 *
 * @var WC_Order $order
 * @var array    $withdrawable Withdrawable items map.
 * @var string   $identity     Hidden identity fields HTML.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

$cps_hc_wd_allowed_hidden = array(
	'input' => array(
		'type'  => true,
		'name'  => true,
		'value' => true,
	),
);
?>
<form class="cps-hc-withdrawal__form cps-hc-withdrawal__form--select" method="post" action="">
	<?php wp_nonce_field( 'cps_hc_gems_withdrawal', 'cps_hc_gems_wd_nonce' ); ?>
	<input type="hidden" name="cps_hc_gems_wd_action" value="select" />
	<?php echo wp_kses( $identity, $cps_hc_wd_allowed_hidden ); ?>

	<p class="cps-hc-withdrawal__intro">
		<?php
		/* translators: %s: order number. */
		echo esc_html( sprintf( __( 'Order: #%s', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) );
		?>
	</p>

	<h2 class="cps-hc-withdrawal__subtitle"><?php esc_html_e( 'Which products would you like to withdraw?', 'surbma-magyar-woocommerce' ); ?></h2>

	<table class="cps-hc-withdrawal__items shop_table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Withdraw', 'surbma-magyar-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Product', 'surbma-magyar-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'surbma-magyar-woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $withdrawable['items'] as $cps_hc_wd_item_id => $cps_hc_wd_item ) : ?>
				<?php
				if ( $cps_hc_wd_item['available'] < 1 ) {
					continue;
				}
				?>
				<tr>
					<td>
						<input type="checkbox" name="wd_items[]" value="<?php echo esc_attr( $cps_hc_wd_item_id ); ?>" id="cps-hc-wd-item-<?php echo esc_attr( $cps_hc_wd_item_id ); ?>" />
					</td>
					<td>
						<label for="cps-hc-wd-item-<?php echo esc_attr( $cps_hc_wd_item_id ); ?>"><?php echo esc_html( $cps_hc_wd_item['name'] ); ?></label>
					</td>
					<td>
						<?php if ( $cps_hc_wd_item['available'] > 1 ) : ?>
							<input type="number" name="wd_qty[<?php echo esc_attr( $cps_hc_wd_item_id ); ?>]" value="<?php echo esc_attr( $cps_hc_wd_item['available'] ); ?>" min="1" max="<?php echo esc_attr( $cps_hc_wd_item['available'] ); ?>" class="cps-hc-withdrawal__qty" />
							<span class="cps-hc-withdrawal__qty-max">
								<?php
								/* translators: %d: maximum withdrawable quantity. */
								echo esc_html( sprintf( __( '/ max %d', 'surbma-magyar-woocommerce' ), $cps_hc_wd_item['available'] ) );
								?>
							</span>
						<?php else : ?>
							<?php echo esc_html( $cps_hc_wd_item['available'] ); ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ( ! $withdrawable['whole_blocked'] ) : ?>
		<p class="cps-hc-withdrawal__whole">
			<label>
				<input type="checkbox" name="wd_whole" value="1" />
				<?php esc_html_e( 'Withdraw from the entire order', 'surbma-magyar-woocommerce' ); ?>
			</label>
		</p>
	<?php endif; ?>

	<p class="form-row">
		<label for="cps-hc-wd-reason"><?php esc_html_e( 'Reason (optional)', 'surbma-magyar-woocommerce' ); ?></label>
		<textarea id="cps-hc-wd-reason" name="wd_reason" rows="3"></textarea>
	</p>

	<p class="form-row">
		<button type="submit" class="button cps-hc-withdrawal__submit"><?php esc_html_e( 'Continue to confirmation', 'surbma-magyar-woocommerce' ); ?></button>
	</p>
</form>
