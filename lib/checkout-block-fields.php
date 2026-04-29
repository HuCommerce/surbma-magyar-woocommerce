<?php

/**
 * Cart & Checkout block: additional checkout fields API (WooCommerce 8.6+).
 *
 * Bridges classic checkout meta (billing_tax_number, legal checkboxes, etc.)
 * with the block checkout Store API pipeline.
 *
 * @package HuCommerce
 */

defined( 'ABSPATH' ) || exit;

const CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER      = 'hucommerce/tax_number';
const CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_COMPANY_BILLING = 'hucommerce/company_billing';

/**
 * Whether the additional checkout fields API is available.
 */
function cps_hc_gems_checkout_block_fields_api_available(): bool {
	return function_exists( 'woocommerce_register_additional_checkout_field' );
}

/**
 * Read plugin options (same source as modules).
 *
 * @return array<string, mixed>
 */
function cps_hc_gems_checkout_block_get_plugin_options(): array {
	$opts = get_option( 'surbma_hc_fields', array() );
	return is_array( $opts ) ? $opts : array();
}

/**
 * Register block-checkout additional fields when modules and API allow.
 */
function cps_hc_gems_register_checkout_block_additional_fields(): void {
	if ( ! cps_hc_gems_checkout_block_fields_api_available() ) {
		return;
	}

	$opts = cps_hc_gems_checkout_block_get_plugin_options();

	// Tax number module.
	if ( ! empty( $opts['taxnumber'] ) ) {
		cps_hc_gems_register_block_checkout_tax_number_field( $opts );
	}

	// Checkout module: company billing checkbox.
	if ( ! empty( $opts['module-checkout'] ) && ! empty( $opts['billingcompanycheck'] ) ) {
		cps_hc_gems_register_block_checkout_company_billing_field( $opts );
	}

	// Legal module: confirmations as order-scoped additional fields.
	if ( ! empty( $opts['legalcheckout'] ) ) {
		cps_hc_gems_register_block_checkout_legal_fields( $opts );
	}
}

add_action( 'woocommerce_init', 'cps_hc_gems_register_checkout_block_additional_fields', 20 );

/**
 * @param array<string, mixed> $opts Plugin options.
 */
function cps_hc_gems_register_block_checkout_tax_number_field( array $opts ): void {
	$company_mode = get_option( 'woocommerce_checkout_company_field', 'optional' );
	if ( 'hidden' === $company_mode ) {
		return;
	}

	$label = __( 'Tax number', 'surbma-magyar-woocommerce' );
	$args  = array(
		'id'       => CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER,
		'label'    => $label,
		'location' => 'address',
		'type'     => 'text',
		'required' => false,
	);

	if ( ! empty( $opts['taxnumberplaceholder'] ) ) {
		$args['optionalLabel'] = $label;
		$args['attributes']   = array(
			'placeholder' => $label,
		);
	}

	if ( 'required' === $company_mode ) {
		$args['required'] = true;
	} else {
		// Required when billing company name is non-empty or "Company billing" is checked (checkout module).
		$required_schemas = array(
			array(
				'type'       => 'object',
				'properties' => array(
					'customer' => array(
						'type'       => 'object',
						'properties' => array(
							'billing_address' => array(
								'type'       => 'object',
								'properties' => array(
									'company' => array(
										'type'      => 'string',
										'minLength' => 1,
									),
								),
								'required'   => array( 'company' ),
							),
						),
						'required'   => array( 'billing_address' ),
					),
				),
				'required'   => array( 'customer' ),
			),
		);

		if ( ! empty( $opts['module-checkout'] ) && ! empty( $opts['billingcompanycheck'] ) ) {
			$required_schemas[] = array(
				'type'       => 'object',
				'properties' => array(
					'customer' => array(
						'type'       => 'object',
						'properties' => array(
							'billing_address' => array(
								'type'       => 'object',
								'properties' => array(
									'hucommerce~1company_billing' => array(
										'enum' => array( true, '1', 1 ),
									),
								),
								'required'   => array( 'hucommerce~1company_billing' ),
							),
						),
						'required'   => array( 'billing_address' ),
					),
				),
				'required'   => array( 'customer' ),
			);
		}

		$args['required'] = $required_schemas;
	}

	woocommerce_register_additional_checkout_field( $args );
}

/**
 * @param array<string, mixed> $opts Plugin options.
 */
function cps_hc_gems_register_block_checkout_company_billing_field( array $opts ): void {
	$company_mode = get_option( 'woocommerce_checkout_company_field', 'optional' );
	if ( 'optional' !== $company_mode ) {
		return;
	}

	woocommerce_register_additional_checkout_field(
		array(
			'id'            => CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_COMPANY_BILLING,
			'label'         => __( 'Company billing', 'surbma-magyar-woocommerce' ),
			'optionalLabel' => __( 'Company billing', 'surbma-magyar-woocommerce' ),
			'location'      => 'address',
			'type'          => 'checkbox',
			'required'      => false,
		)
	);
}

/**
 * @param array<string, mixed> $opts Plugin options.
 */
function cps_hc_gems_register_block_checkout_legal_fields( array $opts ): void {
	$accept_tos = isset( $opts['accepttos'] ) ? wp_unslash( $opts['accepttos'] ) : '';
	$accept_tos = is_string( $accept_tos ) ? trim( $accept_tos ) : '';
	$accept_pp  = isset( $opts['acceptpp'] ) ? wp_unslash( $opts['acceptpp'] ) : '';
	$accept_pp  = is_string( $accept_pp ) ? trim( $accept_pp ) : '';

	$accept_custom1 = isset( $opts['acceptcustom1'] ) ? wp_unslash( $opts['acceptcustom1'] ) : '';
	$accept_custom1 = is_string( $accept_custom1 ) ? trim( $accept_custom1 ) : '';
	$accept_custom2 = isset( $opts['acceptcustom2'] ) ? wp_unslash( $opts['acceptcustom2'] ) : '';
	$accept_custom2 = is_string( $accept_custom2 ) ? trim( $accept_custom2 ) : '';

	$custom1_req = ! ( isset( $opts['legalcheckout-custom1optional'] ) && 1 === (int) $opts['legalcheckout-custom1optional'] );
	$custom2_req = ! ( isset( $opts['legalcheckout-custom2optional'] ) && 1 === (int) $opts['legalcheckout-custom2optional'] );

	if ( $accept_tos ) {
		woocommerce_register_additional_checkout_field(
			array(
				'id'            => 'hucommerce/accept_tos',
				'label'         => wp_kses_post( $accept_tos ),
				'location'      => 'order',
				'type'          => 'checkbox',
				'required'      => true,
				'error_message' => __( 'Please read and accept the terms to proceed.', 'surbma-magyar-woocommerce' ),
			)
		);
	}

	if ( $accept_pp ) {
		woocommerce_register_additional_checkout_field(
			array(
				'id'            => 'hucommerce/accept_pp',
				'label'         => wp_kses_post( $accept_pp ),
				'location'      => 'order',
				'type'          => 'checkbox',
				'required'      => true,
				'error_message' => __( 'Please read and accept the privacy policy to proceed.', 'surbma-magyar-woocommerce' ),
			)
		);
	}

	if ( $accept_custom1 ) {
		woocommerce_register_additional_checkout_field(
			array(
				'id'       => 'hucommerce/accept_custom1',
				'label'    => wp_kses_post( $accept_custom1 ),
				'location' => 'order',
				'type'     => 'checkbox',
				'required' => $custom1_req,
			)
		);
	}

	if ( $accept_custom2 ) {
		woocommerce_register_additional_checkout_field(
			array(
				'id'       => 'hucommerce/accept_custom2',
				'label'    => wp_kses_post( $accept_custom2 ),
				'location' => 'order',
				'type'     => 'checkbox',
				'required' => $custom2_req,
			)
		);
	}
}

/**
 * Map additional field values to legacy billing / order meta used elsewhere in HuCommerce.
 *
 * @param string               $key       Field id.
 * @param mixed                $value     Submitted value.
 * @param string               $group     billing|shipping|other.
 * @param WC_Customer|WC_Order $wc_object Target object.
 */
function cps_hc_gems_sync_additional_checkout_field_to_legacy_meta( string $key, $value, string $group, $wc_object ): void {
	if ( CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER === $key && 'billing' === $group ) {
		$clean = is_string( $value ) ? sanitize_text_field( $value ) : '';
		$wc_object->update_meta_data( '_billing_tax_number', $clean, true );
		if ( $wc_object instanceof WC_Customer && $wc_object->get_id() ) {
			update_user_meta( $wc_object->get_id(), 'billing_tax_number', $clean );
		}
		return;
	}

	if ( CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_COMPANY_BILLING === $key && 'billing' === $group ) {
		$checked = ( true === $value || 1 === $value || '1' === $value || 'true' === $value );
		$wc_object->update_meta_data( 'billing_company_check', $checked ? '1' : '0', true );
		if ( $wc_object instanceof WC_Customer && $wc_object->get_id() ) {
			update_user_meta( $wc_object->get_id(), 'billing_company_check', $checked ? '1' : '0' );
		}
		return;
	}

	if ( ! $wc_object instanceof WC_Order ) {
		return;
	}

	$legal_keys = array(
		'hucommerce/accept_tos'     => 'accept_tos',
		'hucommerce/accept_pp'      => 'accept_pp',
		'hucommerce/accept_custom1' => 'accept_custom1',
		'hucommerce/accept_custom2' => 'accept_custom2',
	);

	if ( ! isset( $legal_keys[ $key ] ) ) {
		return;
	}

	$meta_key = $legal_keys[ $key ];
	$truthy   = ( true === $value || 1 === $value || '1' === $value || 'true' === $value );

	if ( $truthy ) {
		$wc_object->update_meta_data( $meta_key, true, true );
	} else {
		$wc_object->delete_meta_data( $meta_key );
	}
}

add_action( 'woocommerce_set_additional_field_value', 'cps_hc_gems_sync_additional_checkout_field_to_legacy_meta', 10, 4 );

/**
 * Default tax number from legacy customer / order meta for block checkout.
 *
 * @param null|string|bool     $value     Filter default.
 * @param string               $group     billing|shipping|other.
 * @param WC_Customer|WC_Data $wc_object Customer or order.
 * @return mixed
 */
function cps_hc_gems_default_additional_field_tax_number( $value, string $group, $wc_object ) {
	if ( 'billing' !== $group ) {
		return $value;
	}

	if ( $wc_object instanceof WC_Order ) {
		$existing = $wc_object->get_meta( '_billing_tax_number', true );
		return is_string( $existing ) ? $existing : '';
	}

	if ( $wc_object instanceof WC_Customer ) {
		$uid = $wc_object->get_id();
		if ( $uid ) {
			$from_user = get_user_meta( $uid, 'billing_tax_number', true );
			return is_string( $from_user ) ? $from_user : '';
		}
	}

	return $value;
}

add_filter( 'woocommerce_get_default_value_for_' . CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER, 'cps_hc_gems_default_additional_field_tax_number', 10, 3 );

/**
 * Default company billing checkbox from user meta.
 *
 * @param null|string|bool     $value     Filter default.
 * @param string               $group     billing|shipping|other.
 * @param WC_Customer|WC_Data $wc_object Customer or order.
 * @return mixed
 */
function cps_hc_gems_default_additional_field_company_billing( $value, string $group, $wc_object ) {
	if ( 'billing' !== $group ) {
		return $value;
	}

	if ( $wc_object instanceof WC_Customer ) {
		$uid = $wc_object->get_id();
		if ( $uid ) {
			$raw = get_user_meta( $uid, 'billing_company_check', true );
			return ( '1' === $raw || 1 === $raw || true === $raw );
		}
	}

	return $value;
}

add_filter( 'woocommerce_get_default_value_for_' . CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_COMPANY_BILLING, 'cps_hc_gems_default_additional_field_company_billing', 10, 3 );

/**
 * Hungarian tax number format validation for block checkout (Store API).
 *
 * @param WP_Error $errors      Error object.
 * @param array    $fields      Additional field values for this location.
 * @param string   $group       billing|shipping|other.
 */
function cps_hc_gems_validate_block_checkout_tax_number_for_billing( WP_Error $errors, array $fields, string $group ): void {
	if ( 'billing' !== $group ) {
		return;
	}

	$opts = cps_hc_gems_checkout_block_get_plugin_options();
	if ( empty( $opts['validatecheckoutfields'] ) || empty( $opts['validatebillingtaxfield'] ) ) {
		return;
	}

	$billing_country = isset( $fields['country'] ) ? sanitize_text_field( (string) $fields['country'] ) : '';
	if ( '' === $billing_country || 'HU' !== $billing_country ) {
		return;
	}

	$raw = isset( $fields[ CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER ] ) ? (string) $fields[ CPS_HC_GEMS_CHECKOUT_BLOCK_FIELD_TAX_NUMBER ] : '';
	$raw = trim( $raw );
	if ( '' === $raw ) {
		return;
	}

	$pattern_short = '/^\d{11}$/';
	$pattern_full  = '/^\d{8}-\d{1}-\d{2}$/';
	$pattern_eu    = '/^HU\d{8}$/';

	if ( preg_match( $pattern_short, $raw ) || preg_match( $pattern_full, $raw ) || preg_match( $pattern_eu, $raw ) ) {
		return;
	}

	$errors->add(
		'hc_invalid_billing_tax_number',
		__( '<strong>Billing VAT number</strong> field is invalid. Please check again!', 'surbma-magyar-woocommerce' )
	);
}

add_action( 'woocommerce_blocks_validate_location_address_fields', 'cps_hc_gems_validate_block_checkout_tax_number_for_billing', 10, 3 );
