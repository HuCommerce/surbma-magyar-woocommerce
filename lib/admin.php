<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings.php');

// Admin options menu
add_action( 'admin_menu', function() {
	global $surbma_hc_settings_page;
	$surbma_hc_settings_page = add_submenu_page(
		'woocommerce',
		'HuCommerce',
		'HuCommerce',
		'manage_options',
		'surbma-hucommerce-menu',
		'surbma_hc_settings_page'
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
	$actions[] = '<a href="'. esc_url( get_admin_url( null, 'admin.php?page=surbma-hucommerce-menu') ) .'">' . esc_html__( 'Settings' ) . '</a>';
	if ( !SURBMA_HC_PREMIUM ) {
		$actions[] = '<a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank" style="color: #e22c2f;font-weight: bold;">HuCommerce Pro</a>';
	}
	return $actions;
} );
// * HUCOMMERCE END

// Custom styles and scripts for admin pages
add_action( 'admin_enqueue_scripts', function( $hook ) {
	// * HUCOMMERCE START
	if ( SURBMA_HC_PRO_USER ) {
		$current_user = wp_get_current_user();
		$username = $current_user->user_login;
		$email = $current_user->user_email;
		$firstname = $current_user->user_firstname;
		$lastname = $current_user->user_lastname;
		$displayname = $current_user->display_name;
		$userid = $current_user->ID;

		ob_start();
		?>
		!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});

		window.Beacon('init', 'ab57a81e-5722-44ec-9f95-10d6ed71593e')

		window.Beacon('identify', {
			name: '<?php echo esc_js( $displayname ); ?>',
			email: '<?php echo esc_js( $email ); ?>',
			signature: '<?php echo esc_js( hash_hmac( 'sha256', $email, 'Uxg6ogSnpxhCb/0sH/5AIdHpKALTzMYOqYSlsk6xvcU=' ) ); ?>'
		})
		<?php
		$helpscout_beacon_pro_script = ob_get_contents();
		ob_end_clean();
	} else {
		ob_start();
		?>
		!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});

		window.Beacon('init', 'b67c0ee3-b72b-4504-b6c8-a83c5f86cf6e')

		Beacon('show-message', '6f444461-a5d8-4898-b0be-da844efde39d', { force: true })
		<?php
		$helpscout_beacon_free_script = ob_get_contents();
		ob_end_clean();
	}
	// * HUCOMMERCE END

	wp_register_style( 'surbma-hc-admin', SURBMA_HC_PLUGIN_URL . '/assets/css/admin.css' );

	global $surbma_hc_settings_page;

	if ( $hook == $surbma_hc_settings_page ) {
		add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
		wp_enqueue_style( 'surbma-hc-admin' );
		// * HUCOMMERCE START
		if ( SURBMA_HC_PRO_USER ) {
			wp_add_inline_script( 'jquery', $helpscout_beacon_pro_script );
		} else {
			wp_add_inline_script( 'jquery', $helpscout_beacon_free_script );
		}
		// * HUCOMMERCE END
	}
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
	$options = get_option( 'surbma_hc_fields' );

	if ( ! PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
		return;
	}

	if ( $options ) {
		return;
	}

	global $pagenow;
	if ( 'index.php' == $pagenow || 'plugins.php' == $pagenow ) {
		?>
		<div data-dismissible="surbma-hc-notice-welcome-forever" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3><?php esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' ); ?></h3>
			<p><?php esc_html_e( 'First step is to activate the Modules you need and set the individual Module settings.', 'surbma-magyar-woocommerce' ); ?>
			<br><?php esc_html_e( 'To activate Modules and adjust settings, go to this page', 'surbma-magyar-woocommerce' ); ?>: <a href="<?php admin_url(); ?>admin.php?page=surbma-hucommerce-menu">WooCommerce -> HuCommerce</a></p>
			<p style="display: none;"><a class="button button-primary button-large" href="<?php admin_url(); ?>admin.php?page=surbma-hucommerce-menu"><span class="dashicons dashicons-admin-generic" style="position: relative;top: 4px;left: -3px;"></span> <?php esc_html_e( 'HuCommerce Settings', 'surbma-magyar-woocommerce' ); ?></a></p>
			<?php if ( 'free' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
			<h3>HuCommerce Pro</h3>
			<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat, reklámmentes kezelőfelületet és kiemelt ügyfélszolgálati segítséget kapsz.</p>
			<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">HuCommerce Pro megismerése</a></p>
			<p><a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> HuCommerce Pro megvásárlása</a></p>
			<?php } ?>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong><?php esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' ); ?></strong> <?php esc_html_e( 'This notification will never show up again after you close it.', 'surbma-magyar-woocommerce' ); ?></p>
		</div>
		<?php
	}
} );

// * HUCOMMERCE START

// HuCommerce legacy users notice
add_action( 'admin_notices', function() {
	$options = get_option( 'surbma_hc_fields' );
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	if ( ! PAnD::is_admin_notice_active( 'hucommerce-legacy-users-forever' ) ) {
		return;
	}

	if ( SURBMA_HC_PREMIUM ) {
		return;
	}

	if ( !$options ) {
		return;
	}

	if ( isset( $options['brandnewuser'] ) ) {
		return;
	}

	if ( isset( $_GET['page'] ) && 'surbma-hucommerce-menu' == $_GET['page'] ) {
		return;
	}

	?>
	<div data-dismissible="hucommerce-legacy-users-forever" class="notice notice-warning notice-alt notice-large is-dismissible">
		<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
		<h3>Figyelem régi HuCommerce felhasználók!</h3>
		<p>Az eddig megszokott és beállított HuCommerce modulok közül sok nem használható a HuCommerce 2022.1.0 verziójától. Ezek a modulok átkerültek a HuCommerce fizetős, Pro verziójába. Minden eddig beállított modul továbbra is működik, de módosítani nem lehet a beállításokat, sőt mentés után kikapcsolásra kerülnek ezek a modulok!</p>
		<p><strong>Milyen ajánlataink vannak az eddigi felhasználóknak?</strong></p>
		<p><a href="https://www.hucommerce.hu/hucommerce-pro-egy-uj-korszak/" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> Kedvezmények mutatása és a HuCommerce Pro megvásárlása</a></p>
		<p>Miért lett fizetős az, ami eddig ingyen volt? Kérlek olvasd el a cikkünket erről: <a href="https://www.hucommerce.hu/hucommerce-pro-egy-uj-korszak/" target="_blank">HuCommerce Pro, egy új korszak</a></p>
		<hr style="margin: 1em 0;">
		<p style="text-align: center;"><strong><?php esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' ); ?></strong> <?php esc_html_e( 'This notification will never show up again after you close it.', 'surbma-magyar-woocommerce' ); ?></p>
	</div>
	<?php
} );

// HuCommerce Pro Promo notice
add_action( 'admin_notices', function() {
	$options = get_option( 'surbma_hc_fields' );

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

	if ( !$options ) {
		return;
	}

	global $pagenow;
	if ( 'index.php' == $pagenow || 'plugins.php' == $pagenow ) {
		?>
		<div data-dismissible="hucommerce-pro-promo-60" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3>HuCommerce Pro</h3>
			<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat, reklámmentes kezelőfelületet és kiemelt ügyfélszolgálati segítséget kapsz.</p>
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

	// Offers
	if ( !SURBMA_HC_PREMIUM ) {
		$rss_ajanlatok = fetch_feed( 'https://www.hucommerce.hu/cimke/kiemelt-ajanlat-dashboard/feed/' );
		$maxitems_ajanlatok = false;

		if ( !is_wp_error( $rss_ajanlatok ) ) {
			$maxitems_ajanlatok = $rss_ajanlatok->get_item_quantity( 1 );
			$rss_ajanlatok_items = $rss_ajanlatok->get_items( 0, $maxitems_ajanlatok );
		}

		if ( $maxitems_ajanlatok ) {
			echo '<div class="rss-widget" style="background: #f0f6fc;border: 1px solid #c3c4c7;border-left: 4px solid #72aee6;margin-bottom: 2em;padding: 1em;overflow: hidden;">';
			echo '<ul>';
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_ajanlatok_items as $item_ajanlatok ) :
				echo '<li>';
				echo '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=dashboard" target="_blank"><img src="' . esc_url( $item_ajanlatok->get_description() ) . '" alt="' . esc_html( $item_ajanlatok->get_title() ) . '" style="display: block;max-width: 33%;height: auto;float: left;margin: 0 1em 0 0;"></a>';
				echo '<strong>' . esc_html( $item_ajanlatok->get_title() ) . '</strong>';
				echo wp_kses_post( $item_ajanlatok->get_content() );
				echo '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=dashboard" class="button button-primary button-large" target="_blank" style="display: inline-block;float: left;">' . esc_html__( 'View offer', 'surbma-magyar-woocommerce' ) . '</a>';
				echo '</li>';
			endforeach;
			echo '</ul>';
			echo '<p><a href="https://www.hucommerce.hu/kategoria/ajanlatok/" target="_blank" style="display: inline-block;line-height: 32px;float: right;">' . esc_html__( 'Check all offers', 'surbma-magyar-woocommerce' ) . '</a></p>';
			echo '</div>';
		}
	}

	echo '<a href="https://www.hucommerce.hu" target="_blank"><img src="' . esc_url( SURBMA_HC_PLUGIN_URL ) . '/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>';

	// HuCommerce Pro
	if ( !SURBMA_HC_PREMIUM ) {
		echo '<h3><strong>' . esc_html__( 'HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</strong></h3>';
		echo '<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A HuCommerce Pro verzió megvásárlásával további fantasztikus funkciókat, reklámmentes kezelőfelületet és kiemelt ügyfélszolgálati segítséget kapsz.</p>';
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

	// Latest News
	$rss_hirek = fetch_feed( 'https://www.hucommerce.hu/kategoria/hirek/feed/' );
	$maxitems_hirek = false;

	if ( !is_wp_error( $rss_hirek ) ) {
		$maxitems_hirek = $rss_hirek->get_item_quantity( 5 );
		$rss_hirek_items = $rss_hirek->get_items( 0, $maxitems_hirek );
	}

	if ( $maxitems_hirek ) :
		echo '<hr style="margin: 2em 0 1em;clear: both;">';
		echo '<h3><strong>' . esc_html__( 'Latest News from HuCommerce', 'surbma-magyar-woocommerce' ) . '</strong></h3>';
		echo '<div class="rss-widget">';
		echo '<ul>';
		// Loop through each feed item and display each item as a hyperlink.
		foreach ( $rss_hirek_items as $item_hirek ) :
			$itemdate = $item_hirek->get_date( 'Y-m-d' );
			echo '<li>';
			echo '<a href="' . esc_url( $item_hirek->get_permalink() ) . '" target="_blank">';
			echo '<span class="rss-date">' . esc_html( $itemdate ) . '</span> - ' . esc_html( $item_hirek->get_title() );
			echo '</a>';
			echo '</li>';
		endforeach;
		echo '</ul>';
		echo '</div>';
	endif;
}

// * HUCOMMERCE END
