<?php

/**
 * Withdrawal request: identification screen.
 *
 * @var bool       $logged_in
 * @var WC_Order[] $user_orders
 */

defined( 'ABSPATH' ) || exit;
?>

<?php if ( $logged_in ) : ?>

	<?php if ( ! empty( $user_orders ) ) : ?>
		<p class="cps-hc-withdrawal__intro"><?php esc_html_e( 'Select the order you would like to withdraw from.', 'surbma-magyar-woocommerce' ); ?></p>

		<form class="cps-hc-withdrawal__form cps-hc-withdrawal__form--login" method="post" action="">
			<?php wp_nonce_field( 'cps_hc_gems_withdrawal', 'cps_hc_gems_wd_nonce' ); ?>
			<input type="hidden" name="cps_hc_gems_wd_action" value="identify" />
			<input type="hidden" name="wd_source" value="login" />

			<h2 class="cps-hc-withdrawal__subtitle"><?php esc_html_e( 'Your orders', 'surbma-magyar-woocommerce' ); ?></h2>

			<p class="form-row">
				<label for="cps-hc-wd-order-select"><?php esc_html_e( 'Select an order', 'surbma-magyar-woocommerce' ); ?></label>
				<select id="cps-hc-wd-order-select" name="wd_order" required>
					<?php foreach ( $user_orders as $user_order ) : ?>
						<option value="<?php echo esc_attr( $user_order->get_id() ); ?>">
							<?php
							echo esc_html( sprintf(
								/* translators: 1: order number, 2: order date. */
								__( '#%1$s - %2$s', 'surbma-magyar-woocommerce' ),
								$user_order->get_order_number(),
								wc_format_datetime( $user_order->get_date_created() )
							) );
							?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>

			<p class="form-row">
				<button type="submit" class="button cps-hc-withdrawal__submit"><?php esc_html_e( 'Continue', 'surbma-magyar-woocommerce' ); ?></button>
			</p>
		</form>
	<?php else : ?>
		<div class="cps-hc-withdrawal__notice woocommerce-info">
			<?php esc_html_e( 'You have no orders that are currently eligible for withdrawal (processing or completed, within the 14-day window).', 'surbma-magyar-woocommerce' ); ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<p class="cps-hc-withdrawal__intro"><?php esc_html_e( 'Submit your withdrawal request online without logging in. Please identify your order.', 'surbma-magyar-woocommerce' ); ?></p>

	<form class="cps-hc-withdrawal__form cps-hc-withdrawal__form--guest" method="post" action="">
		<?php wp_nonce_field( 'cps_hc_gems_withdrawal', 'cps_hc_gems_wd_nonce' ); ?>
		<input type="hidden" name="cps_hc_gems_wd_action" value="identify" />
		<input type="hidden" name="wd_source" value="guest" />

		<h2 class="cps-hc-withdrawal__subtitle"><?php esc_html_e( 'Identify your order', 'surbma-magyar-woocommerce' ); ?></h2>

		<p class="form-row">
			<label for="cps-hc-wd-order"><?php esc_html_e( 'Order number', 'surbma-magyar-woocommerce' ); ?></label>
			<input type="text" id="cps-hc-wd-order" name="wd_order" inputmode="numeric" required />
		</p>

		<p class="form-row">
			<label for="cps-hc-wd-email"><?php esc_html_e( 'Billing email address', 'surbma-magyar-woocommerce' ); ?></label>
			<input type="email" id="cps-hc-wd-email" name="wd_email" required />
		</p>

		<p class="form-row">
			<button type="submit" class="button cps-hc-withdrawal__submit"><?php esc_html_e( 'Continue', 'surbma-magyar-woocommerce' ); ?></button>
		</p>
	</form>

<?php endif; ?>
