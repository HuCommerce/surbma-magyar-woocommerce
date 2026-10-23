<?php

/**
 * Withdrawal request: post-submit thank-you.
 *
 * @var WC_Order $order
 * @var int      $case_id   Stored withdrawal case ID.
 * @var array    $selection Parsed selection.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;
?>
<div class="cps-hc-withdrawal__done">
	<div class="cps-hc-withdrawal__notice woocommerce-message">
		<?php esc_html_e( 'Your withdrawal request has been recorded. We sent a confirmation email.', 'surbma-magyar-woocommerce' ); ?>
	</div>

	<p>
		<?php
		/* translators: %s: order number. */
		echo esc_html( sprintf( __( 'Order: #%s', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) );
		?>
	</p>

	<?php if ( 'whole' === $selection['scope'] ) : ?>
		<p><?php esc_html_e( 'Withdrawal from the entire order.', 'surbma-magyar-woocommerce' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'Partial withdrawal for the selected products.', 'surbma-magyar-woocommerce' ); ?></p>
	<?php endif; ?>
</div>
