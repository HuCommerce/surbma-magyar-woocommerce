<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

$license_options = get_option( 'surbma_hc_license', array() );
$home_url = parse_url( get_option( 'home' ) );

// API variables
$api_key = isset( $license_options['licensekey'] ) && $license_options['licensekey'] ? $license_options['licensekey'] : false;
$product_id = isset( $license_options['product_id'] ) && $license_options['product_id'] ? $license_options['product_id'] : false;
$instance = isset( $license_options['instance'] ) && $license_options['instance'] ? $license_options['instance'] : false;
$object = isset( $home_url['host'] ) ? $home_url['host'] : '';

// Create the API request URL
function surbma_hc_create_url( $request_args ) {
	$base_url = 'https://www.hucommerce.hu/';
	$base_url = add_query_arg( 'wc-api', 'wc-am-api', $base_url );
	return $base_url . '&' . http_build_query( $request_args );
}

// Execute manual requests
$manual_request = isset( $_GET['hc-request'] ) ? $_GET['hc-request'] : false;
if ( $api_key && $product_id && $instance && $manual_request ) {
	// Activate request sent from HuCommerce Pro menu with the "Frissítés & Újra aktiválás" button
	if ( 'activate' == $manual_request ) {
		$manual_request_args = array(
			'wc_am_action' => 'activate',
			'api_key'      => $api_key,
			'product_id'   => $product_id,
			'instance' 	   => $instance,
			'object' 	   => $object
		);
	}

	// Dectivate request sent from HuCommerce Pro menu with the "Megtartás & deaktiválás" button
	if ( 'deactivate' == $manual_request ) {
		$manual_request_args = array(
			'wc_am_action' => 'deactivate',
			'api_key'      => $api_key,
			'product_id'   => $product_id,
			'instance' 	   => $instance
		);
	}

	// Status request sent from HuCommerce Pro menu with the "API szinkronizálás" link
	if ( 'status' == $manual_request ) {
		$manual_request_args = array(
			'wc_am_action' => 'status',
			'api_key'      => $api_key,
			'product_id'   => $product_id,
			'instance' 	   => $instance
		);
	}

	$manual_request_url = surbma_hc_create_url( $manual_request_args );
	$manual_request_data = wp_remote_get( $manual_request_url );

	// Delete surbma_hc_license_status option to force this option to save again
	delete_option( 'surbma_hc_license_status' );

	// Remove query parameter from url
	$url = esc_url_raw( remove_query_arg( 'hc-request' ) );
	$url = add_query_arg( 'hc-response', $manual_request, $url );
	wp_redirect( $url );
}

// Check the difference between last check and current time
$license_status = get_option( 'surbma_hc_license_status', array() );
$last_check = isset( $license_status['last_check'] ) && $license_status['last_check'] ? $license_status['last_check'] : false;
$status = isset( $license_status['status'] ) && $license_status['status'] ? $license_status['status'] : false;
$current_time = current_datetime();
$current_time = $current_time->getTimestamp() + $current_time->getOffset();
$last_check_diff = $last_check ? $current_time - $last_check : '86401';

// Status check
if ( ( $api_key && $product_id && $instance && $last_check_diff > ( 24 * 60 * 60 ) ) || ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) ) {
	$request_args = array(
		'wc_am_action' => 'status',
		'api_key'      => $api_key,
		'product_id'   => $product_id,
		'instance' 	   => $instance
	);
	$request_url = surbma_hc_create_url( $request_args );
	$request_data = wp_remote_get( $request_url );

	if ( !is_wp_error( $request_data ) ) {
		$request_data_array = json_decode( $request_data['body'], true );
	} else {
		$request_data_array = array();
	}

	$success = isset( $request_data_array['success'] ) && 1 == $request_data_array['success'] ? true : false;
	$unlimited_activations = isset( $request_data_array['data']['unlimited_activations'] ) && 1 == $request_data_array['data']['unlimited_activations'] ? true : false;
	$total_activations_purchased = isset( $request_data_array['data']['total_activations_purchased'] ) && $request_data_array['data']['total_activations_purchased'] ? $request_data_array['data']['total_activations_purchased'] : false;
	$total_activations = isset( $request_data_array['data']['total_activations'] ) && $request_data_array['data']['total_activations'] ? $request_data_array['data']['total_activations'] : false;
	$activations_remaining = isset( $request_data_array['data']['activations_remaining'] ) && $request_data_array['data']['activations_remaining'] ? $request_data_array['data']['activations_remaining'] : false;
	$activated = isset( $request_data_array['data']['activated'] ) && 1 == $request_data_array['data']['activated'] ? true : false;

	/*
	$api_key = true;
	$success = true;
	$activated = true;
	*/

	// Set the license status
	if ( $api_key ) {
		if ( $success ) {
			if ( $activated ) {
				$status = 'active'; // Set plugin license to active, if license is activated and user has an active subscription.
			} else {
				$status = 'inactive'; // Set plugin license to inactive, if license key is valid, but not activated.
			}
		} else {
			$status = 'invalid'; // Set plugin license to invalid, if user has set a license key, but it is invalid or expired.
		}
	} else {
		$status = 'free'; // Set plugin license to free if no license key given.
	}

	$license_status = array(
		'last_check' => $current_time,
		'status' => $status,
		'success' => $success,
		'unlimited_activations' => $unlimited_activations,
		'total_activations_purchased' => $total_activations_purchased,
		'total_activations' => $total_activations,
		'activations_remaining' => $activations_remaining,
		'activated' => $activated
	);
	update_option( 'surbma_hc_license_status', $license_status );
}

/*
 *
 * HuCommerce Whitelist
 *
 * We have a whitelist for domains, that don't need API key to use PRO version
 *
*/

// Get the current website's domain
$current_domain = $_SERVER['HTTP_HOST'];
$current_domain = preg_replace('/^(www\.)/i', '', $current_domain); // remove www prefix if it exists
$current_domain = preg_replace('/\/.*$/i', '', $current_domain); // remove everything after the first slash

// Enable PRO for hucommerce.hu domain
if ( 'hucommerce.hu' == $current_domain ) {
	define( 'SURBMA_HC_PLUGIN_LICENSE', 'active' );
	define( 'SURBMA_HC_PREMIUM', true );
	define( 'SURBMA_HC_PRO_USER', true );
} else {
	/*
	 * Temporary disabled feature, will be enabled in the future
	 *
	$response = wp_remote_get( 'https://www.hucommerce.hu/hucommerce-whitelist.json' );

	if ( !is_wp_error( $response ) ) {
		$json = wp_remote_retrieve_body( $response );
		$domains = json_decode( $json, true );

		// Check if current domain is in the whitelist. If it is, enable PRO version.
		if ( in_array( array( 'domain' => $current_domain ), $domains ) ) {
			define( 'SURBMA_HC_PLUGIN_LICENSE', 'active' );
			define( 'SURBMA_HC_PREMIUM', true );
			define( 'SURBMA_HC_PRO_USER', true );
		}
	}
	*/
}

/*
 *
 * SURBMA_HC_PLUGIN_LICENSE
 *
 * This global is to check license status, if user has rights to use premium features.
 * Values can be: active, inactive, invalid, free
 *
*/
if ( !defined( 'SURBMA_HC_PLUGIN_LICENSE' ) ) {
	if ( $status ) {
		define( 'SURBMA_HC_PLUGIN_LICENSE', $status );
	} else {
		define( 'SURBMA_HC_PLUGIN_LICENSE', 'free' );
	}
}

/*
 *
 * SURBMA_HC_PREMIUM
 *
 * This global is for plugin functions to easily set conditions for free and premium features.
 * Values can be: true, false (BUT php uses it to be 1 or none)
 *
*/
if ( !defined( 'SURBMA_HC_PREMIUM' ) ) {
	if ( 'active' == $status ) {
		define( 'SURBMA_HC_PREMIUM', true );
	} else {
		define( 'SURBMA_HC_PREMIUM', false );
	}
}

/*
 *
 * SURBMA_HC_PRO_USER
 *
 * This global is to set conditions for users, who have given a license key, even if it is expired or invalid.
 * Values can be: true, false (BUT php uses it to be 1 or none)
 *
*/
if ( !defined( 'SURBMA_HC_PRO_USER' ) ) {
	if ( isset( $license_options['licensekey'] ) && $license_options['licensekey'] ) {
		define( 'SURBMA_HC_PRO_USER', true );
	} else {
		define( 'SURBMA_HC_PRO_USER', false );
	}
}

// Fires when the surbma_hc_license option is added
add_action( 'add_option_surbma_hc_license', function( $name, $value ) {
	// update_option( 'surbma_hc_license_test', $value['licensekey'] );
	$home_url = parse_url( get_option( 'home' ) );

	// API variables
	$api_key = isset( $value['licensekey'] ) && $value['licensekey'] ? $value['licensekey'] : false;
	$product_id = isset( $value['product_id'] ) && $value['product_id'] ? $value['product_id'] : '1135';
	$instance = isset( $value['instance'] ) && $value['instance'] ? $value['instance'] : false;
	$object = isset( $home_url['host'] ) ? $home_url['host'] : '';

	$request_args = array(
		'wc_am_action'	=> 'activate',
		'api_key'		=> $api_key,
		'product_id'	=> $product_id,
		'instance'		=> $instance,
		'object'		=> $object
	);

	$request_url = surbma_hc_create_url( $request_args );
	$request_data = wp_remote_get( $request_url );

	// Delete surbma_hc_license_status option to force this option to save again
	delete_option( 'surbma_hc_license_status' );
}, 10, 2 );

// Fires when the surbma_hc_license option is updated with new values
add_action( 'update_option_surbma_hc_license', function( $old_value, $value ) {
	$home_url = parse_url( get_option( 'home' ) );

	// API variables
	$api_key = isset( $value['licensekey'] ) && $value['licensekey'] ? $value['licensekey'] : false;
	$product_id = isset( $value['product_id'] ) && $value['product_id'] ? $value['product_id'] : false;
	$instance = isset( $value['instance'] ) && $value['instance'] ? $value['instance'] : false;
	$object = isset( $home_url['host'] ) ? $home_url['host'] : '';

	$old_api_key = isset( $old_value['licensekey'] ) && $old_value['licensekey'] ? $old_value['licensekey'] : false;
	$old_product_id = isset( $old_value['product_id'] ) && $old_value['product_id'] ? $old_value['product_id'] : false;
	$old_instance = isset( $old_value['instance'] ) && $old_value['instance'] ? $old_value['instance'] : false;

	// Deactivate previous API key
	if ( $old_api_key && $old_product_id && $old_instance ) {
		$deactivate_request_args = array(
			'wc_am_action'	=> 'deactivate',
			'api_key'		=> $old_api_key,
			'product_id'	=> $old_product_id,
			'instance'		=> $old_instance
		);
		$deactivate_request_url = surbma_hc_create_url( $deactivate_request_args );
		$deactivate_request_data = wp_remote_get( $deactivate_request_url );
	}

	// Activate new API key
	if ( $api_key && $product_id && $instance ) {
		$request_args = array(
			'wc_am_action'	=> 'activate',
			'api_key'		=> $api_key,
			'product_id'	=> $product_id,
			'instance'		=> $instance,
			'object'		=> $object
		);
		$request_url = surbma_hc_create_url( $request_args );
		$request_data = wp_remote_get( $request_url );
	}

	// Delete surbma_hc_license_status option to force this option to save again
	delete_option( 'surbma_hc_license_status' );
}, 10, 2 );

// License notices
add_action( 'admin_notices', function() {
	// Invalid notice
	if ( 'invalid' == SURBMA_HC_PLUGIN_LICENSE && ( !isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && 'surbma-hucommerce-menu' != $_GET['page'] ) ) ) {
		?>
		<div class="notice notice-error notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3>Érvénytelen vagy lejárt licensz kulcs a HuCommerce Pro beállításánál!</h3>
			<p>Kérlek ellenőrizd az emailben küldött licensz kulcsot és add meg újra vagy frissítsd és aktiváld újra a HuCommerce beállításánál!
			<br>A licensz kulcsot a <strong>"HuCommerce Pro"</strong> almenüpontban tudod megadni a következő oldalon: <a href="<?php admin_url(); ?>admin.php?page=surbma-hucommerce-menu">WooCommerce -> HuCommerce</a></p>
		</div>
		<?php
	}

	// Inactive notice
	if ( 'inactive' == SURBMA_HC_PLUGIN_LICENSE && ( !isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && 'surbma-hucommerce-menu' != $_GET['page'] ) ) ) {
		?>
		<div class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3>Még nem aktivált HuCommerce Pro licensz kulcs!</h3>
			<p>A megadott HuCommerce Pro licensz kulcsod nincs aktiválva. A HuCommerce Pro almenüpont alatt tudod a megadott licensz kulcsot frissíteni vagy újra aktiválni.
			<br>Amennyiben bármi probléma merül fel az újra aktiválás során vedd fel az ügyfélszolgálattal a kapcsolatot: <a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank">HuCommerce Ügyfélszolgálat</a></p>
		</div>
		<?php
	}
} );
