<?php

/**
 * Withdrawal confirmation email (plain text).
 *
 * @var WC_Order $order
 * @var string   $email_heading
 * @var string[] $withdrawal_lines
 * @var WC_Email $email
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

echo '= ' . esc_html( wp_strip_all_tags( $email_heading ) ) . " =\n\n";

/* translators: %s: customer first name. */
echo esc_html( sprintf( __( 'Kedves %s!', 'surbma-magyar-woocommerce' ), $order->get_billing_first_name() ) ) . "\n\n";

/* translators: %s: order number. */
echo esc_html( sprintf( __( 'Megkaptuk a #%s rendeléséhez benyújtott elállási kérelmét. Az alábbi tételeket rögzítettük:', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) ) . "\n\n";

foreach ( $withdrawal_lines as $line ) {
	echo '- ' . esc_html( $line ) . "\n";
}

echo "\n" . esc_html__( 'Kérelmét feldolgozzuk, és a visszatérítésről hamarosan tájékoztatjuk.', 'surbma-magyar-woocommerce' ) . "\n\n";

echo esc_html( wp_strip_all_tags( wptexturize( do_shortcode( get_option( 'woocommerce_email_footer_text' ) ) ) ) );
