<?php

/**
 * Withdrawal request: confirmation email (DEV-237).
 *
 * A WC_Email subclass sent immediately and synchronously to the consumer when a
 * withdrawal request is confirmed. The class is defined lazily inside the
 * registration filter so WC_Email is guaranteed to be loaded.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Register the withdrawal confirmation email with WooCommerce.
 *
 * @param array $emails Registered email classes.
 * @return array
 */
function cps_hc_gems_withdrawal_register_email( $emails ) {
	if ( ! class_exists( 'WC_Email' ) ) {
		return $emails;
	}

	if ( ! class_exists( 'CPS_HC_Gems_Withdrawal_Email' ) ) {

		/**
		 * Withdrawal confirmation email.
		 */
		class CPS_HC_Gems_Withdrawal_Email extends WC_Email {

			/**
			 * Linked withdrawal post ID.
			 *
			 * @var int
			 */
			public $withdrawal_id = 0;

			/**
			 * Withdrawal scope (whole|partial).
			 *
			 * @var string
			 */
			public $withdrawal_scope = '';

			/**
			 * Human-readable list of withdrawn items.
			 *
			 * @var string[]
			 */
			public $withdrawal_lines = array();

			/**
			 * Constructor.
			 */
			public function __construct() {
				$this->id             = 'cps_hc_gems_withdrawal';
				$this->customer_email = true;
				$this->title          = __( 'Elállási kérelem visszaigazolása', 'surbma-magyar-woocommerce' );
				$this->description    = __( 'Visszaigazoló e-mail a vásárlónak, amint elállási kérelmet nyújt be.', 'surbma-magyar-woocommerce' );

				$this->template_html  = 'email-confirmation-html.php';
				$this->template_plain = 'email-confirmation-plain.php';
				$this->template_base  = CPS_HC_GEMS_DIR . '/templates/withdrawal/';

				$this->placeholders = array(
					'{order_number}' => '',
					'{site_title}'   => $this->get_blogname(),
				);

				parent::__construct();
			}

			/**
			 * Default email subject.
			 *
			 * @return string
			 */
			public function get_default_subject() {
				global $cps_hc_gems_options;

				$subject = isset( $cps_hc_gems_options['withdrawalrequest-emailsubject'] ) ? trim( $cps_hc_gems_options['withdrawalrequest-emailsubject'] ) : '';

				return $subject ? $subject : __( 'Elállási kérelmét megkaptuk - #{order_number}', 'surbma-magyar-woocommerce' );
			}

			/**
			 * Default email heading.
			 *
			 * @return string
			 */
			public function get_default_heading() {
				global $cps_hc_gems_options;

				$heading = isset( $cps_hc_gems_options['withdrawalrequest-emailheading'] ) ? trim( $cps_hc_gems_options['withdrawalrequest-emailheading'] ) : '';

				return $heading ? $heading : __( 'Elállási kérelmét megkaptuk', 'surbma-magyar-woocommerce' );
			}

			/**
			 * Trigger the email for a stored withdrawal request.
			 *
			 * @param int $withdrawal_id Withdrawal post ID.
			 * @return void
			 */
			public function trigger( $withdrawal_id ) {
				$this->setup_locale();

				$withdrawal_id = absint( $withdrawal_id );
				$order_id      = $withdrawal_id ? absint( get_post_meta( $withdrawal_id, '_order_id', true ) ) : 0;
				$order         = $order_id ? wc_get_order( $order_id ) : false;

				if ( $order instanceof WC_Order ) {
					$this->object                         = $order;
					$this->withdrawal_id                  = $withdrawal_id;
					$this->withdrawal_scope               = (string) get_post_meta( $withdrawal_id, '_scope', true );
					$this->withdrawal_lines               = $this->build_lines( $order, $withdrawal_id );
					$this->recipient                      = $order->get_billing_email();
					$this->placeholders['{order_number}'] = $order->get_order_number();
				}

				if ( $this->is_enabled() && $this->get_recipient() ) {
					$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
				}

				$this->restore_locale();
			}

			/**
			 * Build a human-readable list of withdrawn items.
			 *
			 * @param WC_Order $order         Order object.
			 * @param int      $withdrawal_id Withdrawal post ID.
			 * @return string[]
			 */
			protected function build_lines( $order, $withdrawal_id ) {
				if ( 'whole' === get_post_meta( $withdrawal_id, '_scope', true ) ) {
					return array( __( 'Elállás a teljes rendeléstől.', 'surbma-magyar-woocommerce' ) );
				}

				$lines = array();
				$items = cps_hc_gems_withdrawal_get_items( $withdrawal_id );

				foreach ( $items as $item_id => $qty ) {
					$item = $order->get_item( $item_id );
					$name = $item ? $item->get_name() : '';

					/* translators: 1: product name, 2: quantity. */
					$lines[] = sprintf( __( '%1$s - %2$d db', 'surbma-magyar-woocommerce' ), $name, absint( $qty ) );
				}

				return $lines;
			}

			/**
			 * Get the HTML content.
			 *
			 * @return string
			 */
			public function get_content_html() {
				return wc_get_template_html( $this->template_html, array(
					'order'            => $this->object,
					'email_heading'    => $this->get_heading(),
					'withdrawal_lines' => $this->withdrawal_lines,
					'sent_to_admin'    => false,
					'plain_text'       => false,
					'email'            => $this,
				), '', $this->template_base );
			}

			/**
			 * Get the plain-text content.
			 *
			 * @return string
			 */
			public function get_content_plain() {
				return wc_get_template_html( $this->template_plain, array(
					'order'            => $this->object,
					'email_heading'    => $this->get_heading(),
					'withdrawal_lines' => $this->withdrawal_lines,
					'sent_to_admin'    => false,
					'plain_text'       => true,
					'email'            => $this,
				), '', $this->template_base );
			}
		}
	}

	$emails['CPS_HC_Gems_Withdrawal_Email'] = new CPS_HC_Gems_Withdrawal_Email();

	return $emails;
}
add_filter( 'woocommerce_email_classes', 'cps_hc_gems_withdrawal_register_email' );

// Fire the confirmation email immediately and synchronously on confirm.
add_action( 'cps_hc_gems_withdrawal_send_confirmation', static function ( $withdrawal_id ) {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$mailer = WC()->mailer();
	$emails = $mailer->get_emails();

	if ( isset( $emails['CPS_HC_Gems_Withdrawal_Email'] ) ) {
		$emails['CPS_HC_Gems_Withdrawal_Email']->trigger( $withdrawal_id );
	}
} );
