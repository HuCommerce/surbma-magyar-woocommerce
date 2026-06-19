<?php

/**
 * Withdrawal confirmation email (HTML).
 *
 * @var WC_Order $order
 * @var string   $email_heading
 * @var string[] $withdrawal_lines
 * @var bool     $sent_to_admin
 * @var bool     $plain_text
 * @var WC_Email $email
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Output the email header.
 *
 * @since 2026.3.0
 *
 * @hooked WC_Emails::email_header()
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
	<?php
	/* translators: %s: customer first name. */
	echo esc_html( sprintf( __( 'Kedves %s!', 'surbma-magyar-woocommerce' ), $order->get_billing_first_name() ) );
	?>
</p>

<p>
	<?php
	/* translators: %s: order number. */
	echo esc_html( sprintf( __( 'Megkaptuk a #%s rendeléséhez benyújtott elállási kérelmét. Az alábbi tételeket rögzítettük:', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) );
	?>
</p>

<ul>
	<?php foreach ( $withdrawal_lines as $line ) : ?>
		<li><?php echo esc_html( $line ); ?></li>
	<?php endforeach; ?>
</ul>

<p><?php esc_html_e( 'Kérelmét feldolgozzuk, és a visszatérítésről hamarosan tájékoztatjuk.', 'surbma-magyar-woocommerce' ); ?></p>

<?php
/**
 * Output the email footer.
 *
 * @since 2026.3.0
 *
 * @hooked WC_Emails::email_footer()
 */
do_action( 'woocommerce_email_footer', $email );
