<?php

add_filter( 'woocommerce_package_rates', function( $available_shipping_methods, $package ) {
	$options = get_option( 'surbma_hc_fields' );
	$shippingmethodstohideValue = isset( $options['shippingmethodstohide'] ) ? $options['shippingmethodstohide'] : 'hideall';

	if ( 'hideall' === $shippingmethodstohideValue ) {
		if ( !empty( $available_shipping_methods ) ) {
			$free_rates = array();
			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id ) {
					$free_rates[$methods] = $details;
					break;
				}
			}
			return !empty( $free_rates ) ? $free_rates : $available_shipping_methods;
		}
	}

	if ( 'hideall_except_local' === $shippingmethodstohideValue ) {
		if ( !empty( $available_shipping_methods ) ) {
			$free_local_rates = array();

			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id ) {
					$free_local_rates[$methods] = $details;
					break;
				}
			}

			if ( !empty( $free_local_rates ) ) {
				foreach ( $available_shipping_methods as $methods => $details ) {
					if ( 'local_pickup' === $details->method_id ) {
						$free_local_rates[$methods] = $details;
						break;
					}
				}
				return $free_local_rates;
			}

			return $available_shipping_methods;
		}
	}

	if ( 'hideall_except_pickups' === $shippingmethodstohideValue ) {
		if ( !empty( $available_shipping_methods ) ) {
			$free_local_rates = array();

			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id ) {
					$free_local_rates[$methods] = $details;
					break;
				}
			}

			/*
			 ** Possible shipping methods to add in future:
			 **
			 ** flat_rate
			 ** advanced_flat_rate_shipping
			 ** table_rate
			 ** flexible_shipping_single
			 */
			if ( !empty( $free_local_rates ) ) {
				foreach ( $available_shipping_methods as $methods => $details ) {
					if ( 'local_pickup' === $details->method_id || 'vp_pont' === $details->method_id || 'wc_pont_shipping_method' === $details->method_id || 'foxpost_woo_parcel_apt_shipping' === $details->method_id || 'foxpost_package_point' === $details->method_id || 'wc_postapont' === $details->method_id ) {
						$free_local_rates[$methods] = $details;
						break;
					}
				}
				return $free_local_rates;
			}

			return $available_shipping_methods;
		}
	}

	return $available_shipping_methods;
}, 10, 2 );
