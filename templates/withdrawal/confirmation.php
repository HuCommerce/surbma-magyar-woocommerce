<?php

/**
 * Withdrawal request: post-submit thank-you.
 *
 * @var WC_Order $order
 * @var int      $post_id   Stored withdrawal post ID.
 * @var array    $selection Parsed selection.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;
?>
<div class="cps-hc-withdrawal__done">
	<div class="cps-hc-withdrawal__notice woocommerce-message">
		<?php esc_html_e( 'Elállási kérelmét rögzítettük. A visszaigazolást e-mailben elküldtük.', 'surbma-magyar-woocommerce' ); ?>
	</div>

	<p>
		<?php
		/* translators: %s: order number. */
		echo esc_html( sprintf( __( 'Rendelés: #%s', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) );
		?>
	</p>

	<?php if ( 'whole' === $selection['scope'] ) : ?>
		<p><?php esc_html_e( 'Elállás a teljes rendeléstől.', 'surbma-magyar-woocommerce' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'Részleges elállás a kiválasztott termékekre.', 'surbma-magyar-woocommerce' ); ?></p>
	<?php endif; ?>
</div>
