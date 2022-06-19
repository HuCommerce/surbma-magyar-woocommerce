<?php

add_filter( 'woocommerce_package_rates', function( $available_shipping_methods, $package ) {
	$options = get_option( 'surbma_hc_fields' );
	$shippingmethodstohideValue = isset( $options['shippingmethodstohide'] ) ? $options['shippingmethodstohide'] : 'hideall';
	$new_shipping_methods = array();

	if ( !empty( $available_shipping_methods ) ) {

		if ( 'hideall' === $shippingmethodstohideValue ) {
			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id ) {
					$new_shipping_methods[$methods] = $details;
				}
			}
		}

		if ( 'hideall_except_local' === $shippingmethodstohideValue ) {
			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id || 'local_pickup' === $details->method_id ) {
					$new_shipping_methods[$methods] = $details;
				}
			}
		}

		if ( 'hideall_except_pickups' === $shippingmethodstohideValue ) {
			/*
			 ** Possible shipping methods to add in future:
			 **
			 ** flat_rate
			 ** advanced_flat_rate_shipping
			 ** table_rate
			 ** flexible_shipping_single
			 */
			foreach ( $available_shipping_methods as $methods => $details ) {
				if ( 'free_shipping' === $details->method_id || 'local_pickup' === $details->method_id || 'vp_pont' === $details->method_id || 'wc_pont_shipping_method' === $details->method_id || 'foxpost_woo_parcel_apt_shipping' === $details->method_id || 'foxpost_package_point' === $details->method_id || 'wc_postapont' === $details->method_id ) {
					$new_shipping_methods[$methods] = $details;
				}
			}
		}

	}

	return !empty( $new_shipping_methods ) ? $new_shipping_methods : $available_shipping_methods;
}, 10, 2 );
