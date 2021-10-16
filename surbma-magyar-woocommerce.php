<?php

/*
Plugin Name: HuCommerce | Magyar WooCommerce kiegészítések
Plugin URI: https://www.hucommerce.hu/
Description: Hasznos javítások a magyar nyelvű WooCommerce webáruházakhoz.
Version: 30.1.0
Author: HuCommerce.hu
Author URI: https://www.hucommerce.hu/
Developer: Surbma
Developer URI: https://surbma.com/
Text Domain: surbma-magyar-woocommerce
Domain Path: /languages
WC requires at least: 4.6
WC tested up to: 5.8
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
// Prevent direct access
defined( 'ABSPATH' ) || exit;
// Avoid conflicts if Free and Premium versions are activated at the same time

if ( function_exists( 'hucommerce_fs' ) ) {
    hucommerce_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'hucommerce_fs' ) ) {
        // Create a helper function for easy SDK access.
        function hucommerce_fs()
        {
            global  $hucommerce_fs ;
            
            if ( !isset( $hucommerce_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $hucommerce_fs = fs_dynamic_init( array(
                    'id'              => '3068',
                    'slug'            => 'surbma-magyar-woocommerce',
                    'premium_slug'    => 'surbma-magyar-woocommerce-premium',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_50857a3c655c7175f2c985a3fb618',
                    'is_premium'      => false,
                    'premium_suffix'  => '(Premium)',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'        => 'surbma-hucommerce-menu',
                    'pricing'     => false,
                    'contact'     => false,
                    'support'     => false,
                    'affiliation' => false,
                    'parent'      => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $hucommerce_fs;
        }
        
        // Init Freemius.
        hucommerce_fs();
        // Signal that SDK was initiated.
        do_action( 'hucommerce_fs_loaded' );
        hucommerce_fs()->add_filter( 'plugin_icon', function () {
            return dirname( __FILE__ ) . '/assets/images/icon-256x256.jpg';
        } );
    }
    
    define( 'SURBMA_HC_PLUGIN_VERSION_NUMBER', '30.1.0' );
    define( 'SURBMA_HC_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'SURBMA_HC_PLUGIN_URL', plugins_url( '', __FILE__ ) );
    define( 'SURBMA_HC_PLUGIN_FILE', __FILE__ );
    // Check if WooCommerce is active
    add_action( 'plugins_loaded', function () {
        
        if ( class_exists( 'WooCommerce' ) ) {
            // Start the engines.
            require_once SURBMA_HC_PLUGIN_DIR . '/lib/start.php';
        } else {
            // Notify user, that WooCommerce is not active.
            add_action( 'admin_notices', function () {
                ?>
				<div class="notice notice-error">
					<div style="padding: 20px;">
						<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php 
                echo  esc_url( SURBMA_HC_PLUGIN_URL ) ;
                ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>
						<p><strong><?php 
                esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' );
                ?></strong></p>
						<p><?php 
                esc_html_e( 'To use HuCommerce plugin, you must activate WooCommerce also.', 'surbma-magyar-woocommerce' );
                ?>
						<br><?php 
                esc_html_e( 'If you don\'t want to use WooCommerce, please deactivate HuCommerce plugin!', 'surbma-magyar-woocommerce' );
                ?></p>
						<p><a href="<?php 
                admin_url();
                ?>plugins.php" class="button button-primary button-large"><span class="dashicons dashicons-admin-plugins" style="position: relative;top: 5px;left: -3px;"></span> <?php 
                esc_html_e( 'Plugins' );
                ?></a></p>
					</div>
				</div>
				<?php 
            } );
        }
    
    } );
    // Create a check for WooCommerce version. Used for deprecated functions for older WooCommerce versions.
    function surbma_hc_woocommerce_version_check( $version )
    {
        
        if ( class_exists( 'WooCommerce' ) ) {
            global  $woocommerce ;
            if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
                return true;
            }
        }
        
        return false;
    }

}
