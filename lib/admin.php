<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'init', function() {
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

include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-globals.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-modules.php');
// include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-offers.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-directory.php');
// include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-news.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-information.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/page-license.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings.php');

// Admin options menu
add_action( 'admin_menu', function() {
	global $surbma_hc_main_page;
	global $surbma_hc_modules_page;
	global $surbma_hc_offers_page;
	global $surbma_hc_directory_page;
	global $surbma_hc_news_page;
	global $surbma_hc_license_page;
	global $surbma_hc_information_page;

	$surbma_hc_main_page = add_menu_page(
		'HuCommerce',
		'HuCommerce',
		'manage_options',
		'surbma-hucommerce-menu',
		'surbma_hc_modules_page',
		'dashicons-welcome-widgets-menus',
		'58'
	);

	$surbma_hc_modules_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce Modules', 'surbma-magyar-woocommerce' ),
		__( 'Modules', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-menu',
		'surbma_hc_modules_page'
	);

	/*
	$surbma_hc_offers_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce Offers', 'surbma-magyar-woocommerce' ),
		__( 'Offers', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-offers-menu',
		'surbma_hc_offers_page'
	);
	*/

	$surbma_hc_directory_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce Directory', 'surbma-magyar-woocommerce' ),
		__( 'Directory', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-directory-menu',
		'surbma_hc_directory_page'
	);

	/*
	$surbma_hc_news_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce Latest News', 'surbma-magyar-woocommerce' ),
		__( 'Latest News', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-news-menu',
		'surbma_hc_news_page'
	);
	*/

	$surbma_hc_license_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce License Management', 'surbma-magyar-woocommerce' ),
		__( 'License management', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-license-menu',
		'surbma_hc_license_page'
	);

	$surbma_hc_information_page = add_submenu_page(
		'surbma-hucommerce-menu',
		__( 'HuCommerce Information', 'surbma-magyar-woocommerce' ),
		__( 'Information', 'surbma-magyar-woocommerce' ),
		'manage_options',
		'surbma-hucommerce-information-menu',
		'surbma_hc_information_page'
	);

	if ( function_exists( 'wc_admin_connect_page' ) ) {
		wc_admin_connect_page(
			array(
				'id'        => 'surbma-hucommerce-menu',
				'screen_id' => 'woocommerce_page_surbma-hucommerce-menu',
				'title'     => 'HuCommerce'
			)
		);
	}
}, 98 );

// * HUCOMMERCE START
add_filter( 'plugin_action_links_' . plugin_basename( SURBMA_HC_PLUGIN_FILE ), function( $actions ) {
	$actions[] = '<a href="'. esc_url( get_admin_url( null, 'admin.php?page=surbma-hucommerce-menu') ) .'">' . esc_html__( 'Settings', 'surbma-magyar-woocommerce' ) . '</a>';
	if ( !SURBMA_HC_PREMIUM ) {
		$actions[] = '<a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank" style="color: #e22c2f;font-weight: bold;">HuCommerce Pro</a>';
	}
	return $actions;
} );
// * HUCOMMERCE END

// Custom styles and scripts for admin pages
add_action( 'admin_enqueue_scripts', function( $hook ) {
	global $surbma_hc_main_page;
	global $surbma_hc_modules_page;
	global $surbma_hc_offers_page;
	global $surbma_hc_directory_page;
	global $surbma_hc_news_page;
	global $surbma_hc_license_page;
	global $surbma_hc_information_page;

	if ( $hook == $surbma_hc_main_page || $hook == $surbma_hc_modules_page || $hook == $surbma_hc_offers_page || $hook == $surbma_hc_directory_page || $hook == $surbma_hc_news_page || $hook == $surbma_hc_license_page || $hook == $surbma_hc_information_page ) {
		$hc_page = true;
	} else {
		$hc_page = false;
	}

	// Load plugin scripts & styles for plugin pages
	if ( $hc_page ) {
		add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
		wp_enqueue_style( 'surbma-hc-admin', SURBMA_HC_PLUGIN_URL . '/assets/css/admin.css', array(), SURBMA_HC_PLUGIN_VERSION );
	}

	// * HUCOMMERCE START

	// Load page specific Help Scout Beacons
	if ( SURBMA_HC_PRO_USER ) {

		// HC-ALL-PRO
		$hs_beacon__ID = '8343e517-6ce5-408e-b9b7-194ed15224dc';

		// HC-HC-PRO
		if ( $hc_page ) {
			$hs_beacon__ID = 'ab57a81e-5722-44ec-9f95-10d6ed71593e';
		}

		// HC-DB-PRO
		if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'index.php' ) {
			$hs_beacon__ID = '02a19139-0bb6-41f0-b786-bf3dfdf56744';
		}

		// HC-WC-ORDERS-PRO
		if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'wc-orders' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$hs_beacon__ID = '634c7b27-24c2-42ee-822f-ee05c3a1db3e';
		}

		// HC-WC-PRODUCTS-PRO
		if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$hs_beacon__ID = '6eea179a-5225-4c2a-8c8a-b99ca9c6168a';
		}

		// HC-WC-SETTINGS-PRO
		if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$hs_beacon__ID = 'c36756c5-aa68-4a5e-b283-32797b8ca2b3';
		}

	} else {

		// HC-ALL-START
		$hs_beacon__ID = 'bdba10a4-0230-4f42-ac98-0af5f013ad4e';

		// HC-HC-START
		if ( $hc_page ) {
			$hs_beacon__ID = 'cc6686f3-4089-42a7-ab45-01a797527267';
		}

		// HC-DB-START
		if ( $GLOBALS['pagenow'] == 'index.php' ) {
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

	}

	ob_start();
		echo '!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});' . PHP_EOL;
		echo "window.Beacon('init', '" . esc_js( $hs_beacon__ID ) . "')" . PHP_EOL;
		if ( SURBMA_HC_PRO_USER && $hc_page ) {
			$current_user = wp_get_current_user();
			$email = $current_user->user_email;
			if ( $current_user->first_name ) {
				if ( $current_user->last_name ) {
					$name = $current_user->first_name . ' ' . $current_user->last_name;
				} else {
					$name = $current_user->first_name;
				}
			} else {
				$name = $current_user->display_name;
			}
			$website = wp_parse_url( get_site_url(), PHP_URL_HOST );
			echo "window.Beacon('identify', {name: '" . esc_js( $name ) . "',email: '" . esc_js( $email ) . "',signature: '" . esc_js( hash_hmac( 'sha256', $email, 'Uxg6ogSnpxhCb/0sH/5AIdHpKALTzMYOqYSlsk6xvcU=' ) ) . "'})" . PHP_EOL;
			echo "window.Beacon('prefill', {subject: '[" . esc_js( $website ) . "]'})";
		}
	$hs_beacon__script = ob_get_contents();
	ob_end_clean();
	wp_add_inline_script( 'jquery', $hs_beacon__script );

	// * HUCOMMERCE END
} );

// Get allowed post tags
function cps_wcgems_hc_allowed_post_tags() {
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
add_action( 'admin_notices', function() {
	if ( ! PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
		return;
	}

	// Get the settings array
	global $hc_gems_options;
	if ( !empty( $hc_gems_options ) ) {
		return;
	}

	global $pagenow;
	if ( 'index.php' == $pagenow || 'plugins.php' == $pagenow ) {
		?>
		<div data-dismissible="surbma-hc-notice-welcome-forever" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
			<h3><?php esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' ); ?></h3>
			<p><?php esc_html_e( 'First step is to activate the Modules you need and set the individual Module settings.', 'surbma-magyar-woocommerce' ); ?>
			<br><?php esc_html_e( 'To activate Modules and adjust settings, go to this page', 'surbma-magyar-woocommerce' ); ?>: <a href="<?php echo esc_url( admin_url( 'admin.php?page=surbma-hucommerce-menu' ) ); ?>">WooCommerce -> HuCommerce</a></p>
			<p style="display: none;"><a class="button button-primary button-large" href="<?php echo esc_url( admin_url( 'admin.php?page=surbma-hucommerce-menu' ) ); ?>"><span class="dashicons dashicons-admin-generic" style="position: relative;top: 4px;left: -3px;"></span> <?php esc_html_e( 'HuCommerce Settings', 'surbma-magyar-woocommerce' ); ?></a></p>
			<?php if ( 'free' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
			<h3>HuCommerce Pro</h3>
			<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat és kiemelt ügyfélszolgálati segítséget kapsz.</p>
			<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">HuCommerce Pro megismerése</a></p>
			<p><a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> HuCommerce Pro megvásárlása</a></p>
			<?php } ?>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong><?php esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' ); ?></strong> <?php esc_html_e( 'This notification will never show up again after you close it.', 'surbma-magyar-woocommerce' ); ?></p>
		</div>
		<?php
	}
} );

// Missing Company name setting notification
add_action( 'admin_notices', function() {
	// Get the settings array
	global $hc_gems_options;
	$module_taxnumberValue = $hc_gems_options['taxnumber'] ?? 0;
	$woocommercecheckoutcompanyfieldValue = get_option( 'woocommerce_checkout_company_field' );

	if ( 1 == $module_taxnumberValue && false == $woocommercecheckoutcompanyfieldValue ) {
		?>
		<div class="notice notice-warning notice-alt notice-large is--dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
			<h3 class="uk-margin-remove-top">HuCommerce értesítés</h3>
			<p>⚠️ <strong>FONTOS!</strong> Ezt az értesítést azért látod, mert a HuCommerce Adószám megjelenítését használod és a WooCommerce Cégnév megjelenítésének a beállítása hiányzik. Ezért az Adószám mező nem jelenik meg a Pénztár oldalon.</p>
			<p>✅ <strong>MEGOLDÁS:</strong> Az alábbi gombra kattintva nyisd meg a Testreszabást! Ez egy új fülön fog megnyílni. Ott a WooCommerce → Péntrár fülön találod a "Cégnév mező" opciót. Bármin van éppen, azt változtasd meg, kattints a "Közzététel" gombra, majd állítsd be arra, amire szeretnéd és kattints megint a "Közzététel" gombra!</p>
			<p>👍 Ezután az Adószám már meg fog újra jelenni és ez az értesítés eltűnik.</p>
			<p><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-admin-customizer" style="position: relative;top: 4px;left: -3px;"></span> Testreszabás megnyitása</a></p>
		</div>
		<?php
	}
} );

// * HUCOMMERCE START

// HuCommerce Pro Promo notice
add_action( 'admin_notices', function() {
	if ( PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
		return;
	}

	/*
	if ( PAnD::is_admin_notice_active( 'hucommerce-legacy-users-forever' ) ) {
		return;
	}
	*/

	if ( ! PAnD::is_admin_notice_active( 'hucommerce-pro-promo-60' ) ) {
		return;
	}

	if ( 'free' != SURBMA_HC_PLUGIN_LICENSE ) {
		return;
	}

	// Get the settings array
	global $hc_gems_options;
	if ( empty( $hc_gems_options ) ) {
		return;
	}

	global $pagenow;
	if ( 'index.php' == $pagenow || 'plugins.php' == $pagenow ) {
		?>
		<div data-dismissible="hucommerce-pro-promo-60" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
			<h3>HuCommerce Pro</h3>
			<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat és kiemelt ügyfélszolgálati segítséget kapsz.</p>
			<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">HuCommerce Pro megismerése</a></p>
			<p><a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> HuCommerce Pro megvásárlása</a></p>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong>FIGYELEM!</strong> Ez az értesítés 60 nap múlva újra megjelenik a lezárás után.</p>
		</div>
		<?php
	}
} );

// Purge feed cache after 24 hours
add_filter( 'wp_feed_cache_transient_lifetime', function( $seconds ) {
	return 86400;
} );

// Dashboard widget
add_action( 'wp_dashboard_setup', function() {
	global $wp_meta_boxes;
	$user_id = get_current_user_id();

	if ( !get_user_meta( $user_id, 'surbma_hc_new_dashboard' ) ) {
		delete_user_meta( $user_id, 'meta-box-order_dashboard' );
		update_user_meta( $user_id, 'surbma_hc_new_dashboard', true );
	}

	wp_add_dashboard_widget( 'surbma_hc_dashboard_widget', esc_html__( 'HuCommerce', 'surbma-magyar-woocommerce' ), 'surbma_hc_dashboard' );

	$dashboard_widgets = $wp_meta_boxes['dashboard']['normal']['core'];
	$hc_widget = array( 'surbma_hc_dashboard_widget' => $dashboard_widgets['surbma_hc_dashboard_widget'] );

	unset( $wp_meta_boxes['dashboard']['normal']['core']['surbma_hc_dashboard_widget'] );

	$new_dashboard_widgets = array_merge( $hc_widget, $dashboard_widgets );
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$wp_meta_boxes['dashboard']['normal']['core'] = $new_dashboard_widgets;
}, 0 );

function surbma_hc_dashboard() {
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	echo '<a href="https://www.hucommerce.hu" target="_blank"><img src="' . esc_url( SURBMA_HC_PLUGIN_URL ) . '/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage

	// HuCommerce Pro
	if ( !SURBMA_HC_PREMIUM ) {
		echo '<h3><strong>' . esc_html__( 'HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</strong></h3>';
		echo '<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat és kiemelt ügyfélszolgálati segítséget kapsz.</p>';
		echo '<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">' . esc_html__( 'More about HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</a></p>';
		echo '<p>';
		echo '<a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="button button-primary" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> ' . esc_html__( 'Get HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</a>';
		echo '</p>';
		echo '<hr style="margin: 2em 0 1em;clear: both;">';
	}

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
