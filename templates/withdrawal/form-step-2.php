<?php

/**
 * Withdrawal request: step 2 - explicit confirmation.
 *
 * @var WC_Order $order
 * @var array    $withdrawable Withdrawable items map.
 * @var string   $identity     Hidden identity fields HTML.
 * @var array    $selection    Parsed selection (scope, items).
 * @var string   $reason       Consumer-stated reason.
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
<form class="cps-hc-withdrawal__form cps-hc-withdrawal__form--confirm" method="post" action="">
	<?php wp_nonce_field( 'cps_hc_gems_withdrawal', 'cps_hc_gems_wd_nonce' ); ?>
	<input type="hidden" name="cps_hc_gems_wd_action" value="confirm" />
	<?php echo wp_kses( $identity, $cps_hc_wd_allowed_hidden ); ?>
	<input type="hidden" name="wd_reason" value="<?php echo esc_attr( $reason ); ?>" />

	<h2 class="cps-hc-withdrawal__subtitle"><?php esc_html_e( 'Kérelem megerősítése', 'surbma-magyar-woocommerce' ); ?></h2>

	<p class="cps-hc-withdrawal__intro">
		<?php
		/* translators: %s: order number. */
		echo esc_html( sprintf( __( 'Az alábbi elállási kérelmet készül benyújtani a #%s rendeléshez:', 'surbma-magyar-woocommerce' ), $order->get_order_number() ) );
		?>
	</p>

	<?php if ( 'whole' === $selection['scope'] ) : ?>
		<input type="hidden" name="wd_whole" value="1" />
		<p class="cps-hc-withdrawal__summary cps-hc-withdrawal__summary--whole">
			<strong><?php esc_html_e( 'Elállás a teljes rendeléstől.', 'surbma-magyar-woocommerce' ); ?></strong>
		</p>
	<?php else : ?>
		<ul class="cps-hc-withdrawal__summary cps-hc-withdrawal__summary--items">
			<?php foreach ( $selection['items'] as $cps_hc_wd_item_id => $cps_hc_wd_qty ) : ?>
				<?php $cps_hc_wd_name = isset( $withdrawable['items'][ $cps_hc_wd_item_id ] ) ? $withdrawable['items'][ $cps_hc_wd_item_id ]['name'] : ''; ?>
				<input type="hidden" name="wd_items[]" value="<?php echo esc_attr( $cps_hc_wd_item_id ); ?>" />
				<input type="hidden" name="wd_qty[<?php echo esc_attr( $cps_hc_wd_item_id ); ?>]" value="<?php echo esc_attr( $cps_hc_wd_qty ); ?>" />
				<li>
					<?php
					/* translators: 1: product name, 2: quantity. */
					echo esc_html( sprintf( __( '%1$s - %2$d db', 'surbma-magyar-woocommerce' ), $cps_hc_wd_name, $cps_hc_wd_qty ) );
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( '' !== $reason ) : ?>
		<p class="cps-hc-withdrawal__summary-reason">
			<strong><?php esc_html_e( 'Indoklás:', 'surbma-magyar-woocommerce' ); ?></strong>
			<?php echo esc_html( $reason ); ?>
		</p>
	<?php endif; ?>

	<p class="cps-hc-withdrawal__legal"><?php esc_html_e( 'A megerősítés gombra kattintva véglegesen benyújtja elállási kérelmét, és azonnal visszaigazoló e-mailt küldünk.', 'surbma-magyar-woocommerce' ); ?></p>

	<p class="form-row">
		<button type="submit" class="button cps-hc-withdrawal__submit cps-hc-withdrawal__submit--confirm"><?php esc_html_e( 'Elállási kérelem megerősítése', 'surbma-magyar-woocommerce' ); ?></button>
	</p>
</form>
