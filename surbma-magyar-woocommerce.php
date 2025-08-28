<?php

/*
Plugin Name: HuCommerce | Magyar WooCommerce kiegészítések
Requires Plugins: woocommerce
Plugin URI: https://www.hucommerce.hu/
Description: Hasznos javítások és kiegészítések a magyar nyelvű WooCommerce webáruházakhoz.

Version: 2025.1.7

Author: HuCommerce.hu
Author URI: https://www.hucommerce.hu/
Developer: Surbma
Developer URI: https://surbma.com/

Text Domain: surbma-magyar-woocommerce
Domain Path: /languages

WC requires at least: 4.6
WC tested up to: 9.7

License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Prevent direct access
defined( 'ABSPATH' ) || exit;

// Localization
add_action( 'init', function() {
	load_plugin_textdomain( 'surbma-magyar-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
} );

// Retrieve the plugin data to get the Version
$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), 'plugin' );
define( 'SURBMA_HC_PLUGIN_VERSION', isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '1.0' );
define( 'SURBMA_HC_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SURBMA_HC_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'SURBMA_HC_PLUGIN_FILE', __FILE__ );

// Define HuCommerce class for future use
class HuCommerce {}

// Check if WooCommerce is active
add_action( 'plugins_loaded', function() {
	if ( class_exists( 'WooCommerce' ) ) {
		// Start the engines.
		require_once SURBMA_HC_PLUGIN_DIR . '/lib/start.php';
	} else {
		// Notify user, that WooCommerce is not active.
		add_action( 'admin_notices', function() {
			?>
			<div class="notice notice-error">
				<div style="padding: 20px;">
					<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
					<p><strong><?php esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' ); ?></strong></p>
					<p><?php esc_html_e( 'To use HuCommerce plugin, you must activate WooCommerce also.', 'surbma-magyar-woocommerce' ); ?>
					<br><?php esc_html_e( 'If you don\'t want to use WooCommerce, please deactivate HuCommerce plugin!', 'surbma-magyar-woocommerce' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-primary button-large"><span class="dashicons dashicons-admin-plugins" style="position: relative;top: 5px;left: -3px;"></span> <?php esc_html_e( 'Plugins', 'surbma-magyar-woocommerce' ); ?></a></p>
				</div>
			</div>
			<?php
		} );
	}
} );

// Declare compatibility: Custom order tables
// https://developer.woocommerce.com/docs/hpos-extension-recipe-book/
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

// Declare incompatibility: Cart & Checkout blocks
// https://developer.woocommerce.com/2023/11/06/faq-extending-cart-and-checkout-blocks/
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
	}
} );
