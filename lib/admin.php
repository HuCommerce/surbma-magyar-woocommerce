<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'init', static function() {
	$test_email_request = isset( $_GET['hc-test-email'] ) ? sanitize_text_field( wp_unslash( $_GET['hc-test-email'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $test_email_request ) {
		$subject = esc_html__( 'HuCommerce test email', 'surbma-magyar-woocommerce' );
		$body = esc_html__( 'Congratulations! The SMTP settings are correct.', 'surbma-magyar-woocommerce' );
		wp_mail( $test_email_request, $subject, $body );

		// Remove query parameter from url
		$url = esc_url_raw( remove_query_arg( 'hc-test-email' ) );
		$url = add_query_arg( 'hc-response', 'email-sent', $url );
		wp_redirect( $url );
	}
} );

// Admin options menu - auto-generated from pages config
add_action( 'admin_menu', static function() {
	$pages = cps_hc_gems_get_registerable_pages();
	$page_hooks = [];
	$is_first = true;
	$parent_slug = '';

	foreach ( $pages as $page_key => $page ) {
		// Create callback once per page
		$callback = cps_hc_gems_get_page_callback( $page_key );

		if ( $is_first ) {
			// First page becomes the main menu (no callback here - submenu handles it)
			add_menu_page(
				'HuCommerce',
				'HuCommerce',
				'manage_options',
				$page['menu_slug'],
				'', // Empty callback - submenu entry will handle rendering
				'dashicons-welcome-widgets-menus',
				'58'
			);
			$parent_slug = $page['menu_slug'];
			$is_first = false;
		}

		// All pages (including first) get a submenu entry with the callback
		$page_hooks[ $page_key ] = add_submenu_page(
			$parent_slug,
			$page['page_title'],
			$page['title'],
			'manage_options',
			$page['menu_slug'],
			$callback
		);
	}

	// Store page hooks globally for navigation and script loading
	$GLOBALS['cps_hc_gems_page_hooks'] = $page_hooks;

	// WooCommerce admin page connection
	if ( function_exists( 'wc_admin_connect_page' ) ) {
		wc_admin_connect_page(
			array(
				'id'        => 'cps_hc_gems_modules',
				'screen_id' => 'woocommerce_page_cps_hc_gems_modules',
				'title'     => 'HuCommerce'
			)
		);
	}
}, 98 );

add_filter( 'plugin_action_links_' . plugin_basename( CPS_HC_GEMS_FILE ), static function( $actions ) {
	$actions[] = '<a href="'. esc_url( get_admin_url( null, 'admin.php?page=cps_hc_gems_modules') ) .'">' . esc_html__( 'Settings', 'surbma-magyar-woocommerce' ) . '</a>';
	return $actions;
} );

// Custom styles and scripts for admin pages
add_action( 'admin_enqueue_scripts', static function( $hook ) {
	$page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
	$cps_hc_gems_page = in_array( $hook, $page_hooks, true );

	// Load plugin scripts & styles for plugin pages
	if ( $cps_hc_gems_page ) {
		add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
		wp_enqueue_style( 'surbma-hc-admin', CPS_HC_GEMS_URL . '/assets/css/admin.css', array(), CPS_HC_GEMS_VERSION );
	}

	// * HUCOMMERCE START

	// Load page specific Help Scout Beacons
	// HC-ALL-START
	$hs_beacon__ID = 'bdba10a4-0230-4f42-ac98-0af5f013ad4e';

	// HC-HC-START
	if ( $cps_hc_gems_page ) {
		$hs_beacon__ID = 'cc6686f3-4089-42a7-ab45-01a797527267';
	}

	// HC-DB-START
	if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'index.php' ) {
		$hs_beacon__ID = '0703d7a7-98ba-4dce-8071-89996098347f';
	}

	// HC-WC-ORDERS-START
	if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'wc-orders' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$hs_beacon__ID = '56db0132-3d8d-4c1e-a5f6-8462b174de7a';
	}

	// HC-WC-PRODUCTS-START
	if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$hs_beacon__ID = 'a1c2316d-7891-4377-a2af-2569240332bd';
	}

	// HC-WC-SETTINGS-START
	if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$hs_beacon__ID = '654fa50f-d1c3-465e-9be5-93bd6d601ae0';
	}

	ob_start();
		echo '!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});' . PHP_EOL;
		echo "window.Beacon('init', '" . esc_js( $hs_beacon__ID ) . "')" . PHP_EOL;
	$hs_beacon__script = ob_get_contents();
	ob_end_clean();
	wp_add_inline_script( 'jquery', $hs_beacon__script );

	// * HUCOMMERCE END
} );

// Get allowed post tags
function cps_hc_gems_allowed_post_tags() {
	global $allowedposttags;
	$allowed = '';
	foreach ( (array) $allowedposttags as $tag => $attributes ) {
		$allowed .= '<' . $tag . '> ';
	}
	return htmlentities( $allowed );
}

/*
// Admin notice classes:
// notice-success
// notice-success notice-alt
// notice-info
// notice-warning
// notice-error
// Without a class, there is no colored left border.
*/

// PAnD init
add_action( 'admin_init', array( 'PAnD', 'init' ) );

// Welcome notice
add_action( 'admin_notices', static function() {
	if ( ! PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
		return;
	}

	// Get the settings array
	global $cps_hc_gems_options;
	if ( !empty( $cps_hc_gems_options ) ) {
		return;
	}

	global $pagenow;
	if ( 'index.php' == $pagenow || 'plugins.php' == $pagenow ) {
		?>
		<div data-dismissible="surbma-hc-notice-welcome-forever" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( CPS_HC_GEMS_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
			<h3><?php esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' ); ?></h3>
			<p><?php esc_html_e( 'First step is to activate the Modules you need and set the individual Module settings.', 'surbma-magyar-woocommerce' ); ?>
			<br><?php esc_html_e( 'To activate Modules and adjust settings, go to this page', 'surbma-magyar-woocommerce' ); ?>: <a href="<?php echo esc_url( admin_url( 'admin.php?page=cps_hc_gems_modules' ) ); ?>">WooCommerce -> HuCommerce</a></p>
			<p style="display: none;"><a class="button button-primary button-large" href="<?php echo esc_url( admin_url( 'admin.php?page=cps_hc_gems_modules' ) ); ?>"><span class="dashicons dashicons-admin-generic" style="position: relative;top: 4px;left: -3px;"></span> <?php esc_html_e( 'HuCommerce Settings', 'surbma-magyar-woocommerce' ); ?></a></p>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong><?php esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' ); ?></strong> <?php esc_html_e( 'This notification will never show up again after you close it.', 'surbma-magyar-woocommerce' ); ?></p>
		</div>
		<?php
	}
} );

// Missing Company name setting notification
add_action( 'admin_notices', static function() {
	// Get the settings array
	global $cps_hc_gems_options;
	$module_taxnumberValue = $cps_hc_gems_options['taxnumber'] ?? 0;
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' );

	if ( 1 == $module_taxnumberValue && false == $woocommercecheckoutcompanyfieldValue ) {
		?>
		<div class="notice notice-warning notice-alt notice-large is--dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( CPS_HC_GEMS_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
			<h3 class="uk-margin-remove-top">HuCommerce értesítés</h3>
			<p>⚠️ <strong>FONTOS!</strong> Ezt az értesítést azért látod, mert a HuCommerce Adószám megjelenítését használod és a WooCommerce Cégnév megjelenítésének a beállítása hiányzik. Ezért az Adószám mező nem jelenik meg a Pénztár oldalon.</p>
			<p>✅ <strong>MEGOLDÁS:</strong> Az alábbi gombra kattintva nyisd meg a Testreszabást! Ez egy új fülön fog megnyílni. Ott a WooCommerce → Péntrár fülön találod a "Cégnév mező" opciót. Bármin van éppen, azt változtasd meg, kattints a "Közzététel" gombra, majd állítsd be arra, amire szeretnéd és kattints megint a "Közzététel" gombra!</p>
			<p>👍 Ezután az Adószám már meg fog újra jelenni és ez az értesítés eltűnik.</p>
			<p><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-admin-customizer" style="position: relative;top: 4px;left: -3px;"></span> Testreszabás megnyitása</a></p>
		</div>
		<?php
	}
} );

// Purge feed cache after 24 hours
add_filter( 'wp_feed_cache_transient_lifetime', static function( $seconds ) {
	return 86400;
} );

// Dashboard widget
add_action( 'wp_dashboard_setup', static function() {
	global $wp_meta_boxes;
	$user_id = get_current_user_id();

	// Migration: Transfer old meta key to new one
	if ( get_user_meta( $user_id, 'surbma_hc_new_dashboard', true ) ) {
		update_user_meta( $user_id, 'cps_hc_gems_new_dashboard', true );
		delete_user_meta( $user_id, 'surbma_hc_new_dashboard' );
	}

	if ( !get_user_meta( $user_id, 'cps_hc_gems_new_dashboard' ) ) {
		delete_user_meta( $user_id, 'meta-box-order_dashboard' );
		update_user_meta( $user_id, 'cps_hc_gems_new_dashboard', true );
	}

	wp_add_dashboard_widget( 'cps_hc_gems_dashboard_widget', esc_html__( 'HuCommerce', 'surbma-magyar-woocommerce' ), 'cps_hc_gems_dashboard' );

	$dashboard_widgets = $wp_meta_boxes['dashboard']['normal']['core'];
	$cps_hc_gems_widget = array( 'cps_hc_gems_dashboard_widget' => $dashboard_widgets['cps_hc_gems_dashboard_widget'] );

	unset( $wp_meta_boxes['dashboard']['normal']['core']['cps_hc_gems_dashboard_widget'] );

	$new_dashboard_widgets = array_merge( $cps_hc_gems_widget, $dashboard_widgets );
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$wp_meta_boxes['dashboard']['normal']['core'] = $new_dashboard_widgets;
}, 0 );

function cps_hc_gems_dashboard() {
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	echo '<a href="https://www.hucommerce.hu" target="_blank"><img src="' . esc_url( CPS_HC_GEMS_URL ) . '/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage

	// Community
	echo '<h3><strong>' . esc_html__( 'HuCommerce Community', 'surbma-magyar-woocommerce' ) . '</strong></h3>';
	echo '<p>' . esc_html__( 'Please join our Facebook Group and subscribe to our HuCommerce newsletter!', 'surbma-magyar-woocommerce' ) . '</p>';
	echo '<p>';
	echo '<a href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank" class="button button-primary"><span class="dashicons dashicons-facebook-alt" style="position: relative;top: 3px;left: -3px;"></span>' . esc_html__( 'Facebook Group', 'surbma-magyar-woocommerce' ) . '</a>';
	echo ' ';
	echo '<a href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=' . urlencode( $current_user->user_email ) . '&FNAME=' . urlencode( $current_user->user_firstname ) . '&LNAME=' . urlencode( $current_user->user_lastname ) . '&URL=' . urlencode( $home_url ) . '" target="_blank" class="button button-secondary"><span class="dashicons dashicons-email" style="position: relative;top: 3px;left: -3px;"></span> ' . esc_html__( 'Subscribe', 'surbma-magyar-woocommerce' ) . '</a>';
	echo '</p>';
}

// * HUCOMMERCE END
