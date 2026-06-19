<?php

/**
 * Withdrawal request: login-free front-end endpoint + 2-step flow (DEV-235).
 *
 * Resolves the order via one of three identification paths (tokenized link,
 * logged-in order select, guest order#+email), then walks the consumer through a
 * select-items step and an explicit confirmation step before storing the request.
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Load a withdrawal front-end template with the given args in scope.
 *
 * @param string $name Template filename under templates/withdrawal/.
 * @param array  $args Variables to expose to the template.
 * @return void
 */
function cps_hc_gems_withdrawal_load_template( $name, $args = array() ) {
	$file = CPS_HC_GEMS_DIR . '/templates/withdrawal/' . $name;

	if ( ! file_exists( $file ) ) {
		return;
	}

	extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- controlled, internal args only.
	include $file;
}

/**
 * Compute the items still withdrawable for an order, honoring existing requests.
 *
 * @param WC_Order $order Order object.
 * @return array {
 *     @type bool  $whole_blocked Whether a whole-order request already blocks new ones.
 *     @type array $items         Map of item_id => [name, ordered, available].
 * }
 */
function cps_hc_gems_withdrawal_get_withdrawable_items( $order ) {
	$result = array(
		'whole_blocked' => false,
		'items'         => array(),
	);

	if ( ! $order instanceof WC_Order ) {
		return $result;
	}

	$withdrawn = array();

	foreach ( cps_hc_gems_withdrawal_get_by_order( $order->get_id(), array( 'wd_pending', 'wd_accepted', 'wd_refunded' ) ) as $wd ) {
		if ( 'whole' === get_post_meta( $wd->ID, '_scope', true ) ) {
			$result['whole_blocked'] = true;
			continue;
		}

		foreach ( cps_hc_gems_withdrawal_get_items( $wd->ID ) as $item_id => $qty ) {
			$item_id               = absint( $item_id );
			$withdrawn[ $item_id ] = ( isset( $withdrawn[ $item_id ] ) ? $withdrawn[ $item_id ] : 0 ) + absint( $qty );
		}
	}

	foreach ( $order->get_items() as $item_id => $item ) {
		$ordered   = (int) $item->get_quantity();
		$already   = isset( $withdrawn[ $item_id ] ) ? $withdrawn[ $item_id ] : 0;
		$available = $result['whole_blocked'] ? 0 : max( 0, $ordered - $already );

		$result['items'][ $item_id ] = array(
			'name'      => $item->get_name(),
			'ordered'   => $ordered,
			'available' => $available,
		);
	}

	return $result;
}

/**
 * Whether an order has any items left to withdraw.
 *
 * @param array $withdrawable Result of cps_hc_gems_withdrawal_get_withdrawable_items().
 * @return bool
 */
function cps_hc_gems_withdrawal_has_available_items( $withdrawable ) {
	foreach ( $withdrawable['items'] as $item ) {
		if ( $item['available'] > 0 ) {
			return true;
		}
	}

	return false;
}

// Enqueue the front-end stylesheet on the endpoint.
add_action( 'wp_enqueue_scripts', static function () {
	if ( get_query_var( 'cps_hc_gems_withdrawal' ) ) {
		wp_enqueue_style( 'cps-hc-gems-withdrawal', CPS_HC_GEMS_URL . '/assets/css/withdrawal.css', array(), CPS_HC_GEMS_VERSION );
	}
} );

// Hijack the endpoint and render the withdrawal flow.
add_action( 'template_redirect', static function () {
	if ( ! get_query_var( 'cps_hc_gems_withdrawal' ) ) {
		return;
	}

	cps_hc_gems_withdrawal_render();
} );

/**
 * Render the withdrawal endpoint (full themed page) and exit.
 *
 * @return void
 */
function cps_hc_gems_withdrawal_render() {
	$action   = isset( $_POST['cps_hc_gems_wd_action'] ) ? sanitize_key( wp_unslash( $_POST['cps_hc_gems_wd_action'] ) ) : '';
	$notice   = '';
	$nonce_ok = true;

	if ( $action ) {
		$nonce    = isset( $_POST['cps_hc_gems_wd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cps_hc_gems_wd_nonce'] ) ) : '';
		$nonce_ok = (bool) wp_verify_nonce( $nonce, 'cps_hc_gems_withdrawal' );

		if ( ! $nonce_ok ) {
			$action = '';
			$notice = __( 'A munkamenet lejárt. Kérjük, próbálja újra.', 'surbma-magyar-woocommerce' );
		}
	}

	// Resolve order context. The tokenized link takes priority over everything.
	$order  = null;
	$source = '';

	$link_order = isset( $_REQUEST['order'] ) ? absint( $_REQUEST['order'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$link_key   = isset( $_REQUEST['key'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( $link_order && $link_key ) {
		$candidate = wc_get_order( $link_order );

		if ( $candidate instanceof WC_Order && cps_hc_gems_withdrawal_verify_token( $candidate, $link_key ) ) {
			if ( ! cps_hc_gems_withdrawal_window_is_open( $candidate ) ) {
				cps_hc_gems_withdrawal_render_message(
					__( 'Az elállási időszak lejárt', 'surbma-magyar-woocommerce' ),
					__( 'Erre a rendelésre már lejárt a 14 napos elállási határidő, ezért elállási kérelem nem rögzíthető.', 'surbma-magyar-woocommerce' )
				);
				return;
			}

			$order  = $candidate;
			$source = 'link';
		} else {
			cps_hc_gems_withdrawal_render_message(
				__( 'Érvénytelen hivatkozás', 'surbma-magyar-woocommerce' ),
				__( 'Ez az elállási hivatkozás érvénytelen vagy lejárt.', 'surbma-magyar-woocommerce' )
			);
			return;
		}
	}

	// No link: resolve via the login or guest identification path.
	if ( ! $order ) {
		$src = isset( $_POST['wd_source'] ) ? sanitize_key( wp_unslash( $_POST['wd_source'] ) ) : '';

		if ( 'identify' === $action ) {
			if ( 'login' === $src && is_user_logged_in() ) {
				$order = cps_hc_gems_withdrawal_resolve_login_order( isset( $_POST['wd_order'] ) ? absint( $_POST['wd_order'] ) : 0, get_current_user_id() );

				if ( $order ) {
					$source = 'login';
				} else {
					$notice = __( 'A kiválasztott rendelés nem érhető el elálláshoz.', 'surbma-magyar-woocommerce' );
				}
			} elseif ( 'guest' === $src ) {
				if ( cps_hc_gems_withdrawal_guest_lookup_is_rate_limited() ) {
					$notice = __( 'Túl sok próbálkozás. Kérjük, próbálja meg később.', 'surbma-magyar-woocommerce' );
				} else {
					cps_hc_gems_withdrawal_guest_lookup_register_attempt();

					$order = cps_hc_gems_withdrawal_guest_lookup(
						isset( $_POST['wd_order'] ) ? sanitize_text_field( wp_unslash( $_POST['wd_order'] ) ) : '',
						isset( $_POST['wd_email'] ) ? sanitize_text_field( wp_unslash( $_POST['wd_email'] ) ) : ''
					);

					if ( $order ) {
						$source = 'guest';
					} else {
						$notice = __( 'Nem találtunk a megadott adatokkal elállásra jogosult rendelést.', 'surbma-magyar-woocommerce' );
					}
				}
			}
		} elseif ( 'select' === $action || 'confirm' === $action ) {
			// Re-resolve the already-identified order from the carried hidden fields.
			if ( 'login' === $src && is_user_logged_in() ) {
				$order  = cps_hc_gems_withdrawal_resolve_login_order( isset( $_POST['wd_order'] ) ? absint( $_POST['wd_order'] ) : 0, get_current_user_id() );
				$source = 'login';
			} elseif ( 'guest' === $src ) {
				$order  = cps_hc_gems_withdrawal_guest_lookup(
					isset( $_POST['wd_order'] ) ? sanitize_text_field( wp_unslash( $_POST['wd_order'] ) ) : '',
					isset( $_POST['wd_email'] ) ? sanitize_text_field( wp_unslash( $_POST['wd_email'] ) ) : ''
				);
				$source = 'guest';
			}

			if ( ! $order ) {
				$action = '';
				$notice = __( 'A munkamenet lejárt. Kérjük, kezdje újra.', 'surbma-magyar-woocommerce' );
			}
		}
	}

	// Open the themed page.
	get_header();
	echo '<div class="cps-hc-withdrawal woocommerce">';
	echo '<h1 class="cps-hc-withdrawal__title">' . esc_html__( 'Elállás a szerződéstől', 'surbma-magyar-woocommerce' ) . '</h1>';

	if ( $notice ) {
		echo '<div class="cps-hc-withdrawal__notice woocommerce-error">' . esc_html( $notice ) . '</div>';
	}

	if ( ! $order ) {
		cps_hc_gems_withdrawal_load_template( 'identify.php', array(
			'logged_in'   => is_user_logged_in(),
			'user_orders' => is_user_logged_in() ? cps_hc_gems_withdrawal_get_user_orders( get_current_user_id() ) : array(),
		) );
	} else {
		$withdrawable = cps_hc_gems_withdrawal_get_withdrawable_items( $order );
		$identity     = cps_hc_gems_withdrawal_identity_fields( $order, $source );

		if ( ! cps_hc_gems_withdrawal_has_available_items( $withdrawable ) ) {
			echo '<div class="cps-hc-withdrawal__notice woocommerce-info">' . esc_html__( 'Erre a rendelésre már nincs elállásra jelölhető tétel.', 'surbma-magyar-woocommerce' ) . '</div>';
		} elseif ( 'confirm' === $action ) {
			cps_hc_gems_withdrawal_process_confirm( $order, $source, $withdrawable, $identity );
		} elseif ( 'select' === $action ) {
			cps_hc_gems_withdrawal_process_select( $order, $withdrawable, $identity );
		} else {
			cps_hc_gems_withdrawal_load_template( 'form-step-1.php', array(
				'order'        => $order,
				'withdrawable' => $withdrawable,
				'identity'     => $identity,
				'selection'    => null,
			) );
		}
	}

	echo '</div>';
	get_footer();
	exit;
}

/**
 * Build the hidden identity fields that carry identification across POST steps.
 *
 * @param WC_Order $order  Resolved order.
 * @param string   $source Identification source (link|login|guest).
 * @return string HTML of hidden inputs.
 */
function cps_hc_gems_withdrawal_identity_fields( $order, $source ) {
	$html = '<input type="hidden" name="wd_source" value="' . esc_attr( $source ) . '" />';

	if ( 'link' === $source ) {
		$html .= '<input type="hidden" name="order" value="' . esc_attr( $order->get_id() ) . '" />';
		$html .= '<input type="hidden" name="key" value="' . esc_attr( cps_hc_gems_withdrawal_get_token( $order ) ) . '" />';
	} elseif ( 'login' === $source ) {
		$html .= '<input type="hidden" name="wd_order" value="' . esc_attr( $order->get_id() ) . '" />';
	} elseif ( 'guest' === $source ) {
		$html .= '<input type="hidden" name="wd_order" value="' . esc_attr( $order->get_id() ) . '" />';
		$html .= '<input type="hidden" name="wd_email" value="' . esc_attr( $order->get_billing_email() ) . '" />';
	}

	return $html;
}

/**
 * Parse a posted item selection into a normalized scope/items structure.
 *
 * @param array $withdrawable Withdrawable items map.
 * @return array {
 *     @type string $scope 'whole'|'partial'.
 *     @type array  $items Map of item_id => qty (partial only).
 *     @type string $error Validation error message, empty when valid.
 * }
 */
function cps_hc_gems_withdrawal_parse_selection( $withdrawable ) {
	$result = array(
		'scope' => 'partial',
		'items' => array(),
		'error' => '',
	);

	$whole = ! empty( $_POST['wd_whole'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked by caller.

	if ( $whole ) {
		// A whole-order request is only valid when nothing has been withdrawn yet.
		foreach ( $withdrawable['items'] as $item_id => $item ) {
			if ( $item['available'] !== $item['ordered'] ) {
				$result['error'] = __( 'Erre a rendelésre már létezik részleges elállási kérelem, ezért a teljes rendelésre nem nyújtható be.', 'surbma-magyar-woocommerce' );
				return $result;
			}
		}

		$result['scope'] = 'whole';
		$result['items'] = array();

		return $result;
	}

	$posted_items = isset( $_POST['wd_items'] ) && is_array( $_POST['wd_items'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wd_items'] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked by caller.
	$posted_qty   = isset( $_POST['wd_qty'] ) && is_array( $_POST['wd_qty'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wd_qty'] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked by caller.

	foreach ( $posted_items as $item_id ) {
		$item_id = absint( $item_id );

		if ( ! isset( $withdrawable['items'][ $item_id ] ) ) {
			continue;
		}

		$available = $withdrawable['items'][ $item_id ]['available'];

		if ( $available < 1 ) {
			continue;
		}

		$qty = isset( $posted_qty[ $item_id ] ) ? absint( $posted_qty[ $item_id ] ) : 1;
		$qty = max( 1, min( $qty, $available ) );

		$result['items'][ $item_id ] = $qty;
	}

	if ( empty( $result['items'] ) ) {
		$result['error'] = __( 'Kérjük, jelöljön ki legalább egy terméket, vagy válassza a teljes rendelést.', 'surbma-magyar-woocommerce' );
	}

	return $result;
}

/**
 * Handle the step-1 submission: validate the selection, render the step-2 confirmation.
 *
 * @param WC_Order $order        Resolved order.
 * @param array    $withdrawable Withdrawable items map.
 * @param string   $identity     Hidden identity fields HTML.
 * @return void
 */
function cps_hc_gems_withdrawal_process_select( $order, $withdrawable, $identity ) {
	$selection = cps_hc_gems_withdrawal_parse_selection( $withdrawable );
	$reason    = isset( $_POST['wd_reason'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wd_reason'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked in cps_hc_gems_withdrawal_render().

	if ( $selection['error'] ) {
		echo '<div class="cps-hc-withdrawal__notice woocommerce-error">' . esc_html( $selection['error'] ) . '</div>';

		cps_hc_gems_withdrawal_load_template( 'form-step-1.php', array(
			'order'        => $order,
			'withdrawable' => $withdrawable,
			'identity'     => $identity,
			'selection'    => null,
		) );

		return;
	}

	cps_hc_gems_withdrawal_load_template( 'form-step-2.php', array(
		'order'        => $order,
		'withdrawable' => $withdrawable,
		'identity'     => $identity,
		'selection'    => $selection,
		'reason'       => $reason,
	) );
}

/**
 * Handle the step-2 submission: re-validate and store the withdrawal request.
 *
 * @param WC_Order $order        Resolved order.
 * @param string   $source       Identification source.
 * @param array    $withdrawable Withdrawable items map.
 * @param string   $identity     Hidden identity fields HTML.
 * @return void
 */
function cps_hc_gems_withdrawal_process_confirm( $order, $source, $withdrawable, $identity ) {
	$selection = cps_hc_gems_withdrawal_parse_selection( $withdrawable );
	$reason    = isset( $_POST['wd_reason'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wd_reason'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked in cps_hc_gems_withdrawal_render().

	if ( $selection['error'] ) {
		echo '<div class="cps-hc-withdrawal__notice woocommerce-error">' . esc_html( $selection['error'] ) . '</div>';

		cps_hc_gems_withdrawal_load_template( 'form-step-1.php', array(
			'order'        => $order,
			'withdrawable' => $withdrawable,
			'identity'     => $identity,
			'selection'    => null,
		) );

		return;
	}

	$post_id = cps_hc_gems_withdrawal_create( array(
		'order_id'        => $order->get_id(),
		'scope'           => $selection['scope'],
		'items'           => $selection['items'],
		'reason'          => $reason,
		'consumer_email'  => $order->get_billing_email(),
		'consumer_ip'     => cps_hc_gems_withdrawal_get_remote_ip(),
		'identify_method' => $source,
		'window_end'      => gmdate( 'Y-m-d H:i:s', cps_hc_gems_withdrawal_get_window_end( $order ) ),
	) );

	if ( is_wp_error( $post_id ) ) {
		echo '<div class="cps-hc-withdrawal__notice woocommerce-error">' . esc_html__( 'A kérelem rögzítése nem sikerült. Kérjük, próbálja újra.', 'surbma-magyar-woocommerce' ) . '</div>';
		return;
	}

	/**
	 * Fires to send the immediate, synchronous withdrawal confirmation email.
	 *
	 * @since 2026.3.0
	 *
	 * @param int $post_id The stored withdrawal post ID.
	 */
	do_action( 'cps_hc_gems_withdrawal_send_confirmation', $post_id );

	cps_hc_gems_withdrawal_load_template( 'confirmation.php', array(
		'order'     => $order,
		'post_id'   => $post_id,
		'selection' => $selection,
	) );
}

/**
 * Render a standalone themed message page (expired / invalid link) and exit.
 *
 * @param string $title   Heading.
 * @param string $message Body text.
 * @return void
 */
function cps_hc_gems_withdrawal_render_message( $title, $message ) {
	get_header();
	echo '<div class="cps-hc-withdrawal woocommerce">';
	echo '<h1 class="cps-hc-withdrawal__title">' . esc_html( $title ) . '</h1>';
	echo '<div class="cps-hc-withdrawal__notice woocommerce-info">' . esc_html( $message ) . '</div>';
	echo '</div>';
	get_footer();
	exit;
}
