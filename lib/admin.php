<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;
include_once SURBMA_HC_PLUGIN_DIR . '/pages/settings.php';
// Admin options menu
add_action( 'admin_menu', function () {
    global  $surbma_hc_settings_page ;
    $surbma_hc_settings_page = add_submenu_page(
        'woocommerce',
        'HuCommerce',
        'HuCommerce',
        'manage_options',
        'surbma-hucommerce-menu',
        'surbma_hc_settings_page'
    );
    if ( function_exists( 'wc_admin_connect_page' ) ) {
        wc_admin_connect_page( array(
            'id'        => 'surbma-hucommerce-menu',
            'screen_id' => 'woocommerce_page_surbma-hucommerce-menu',
            'title'     => 'HuCommerce',
        ) );
    }
}, 999 );
// * HUCOMMERCE START
add_filter( 'plugin_action_links_' . plugin_basename( SURBMA_HC_PLUGIN_FILE ), function ( $actions ) {
    $actions[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=surbma-hucommerce-menu' ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
    $actions[] = '<a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank" style="color: #e22c2f;font-weight: bold;">HuCommerce Pro</a>';
    return $actions;
} );
// * HUCOMMERCE END
// Custom styles and scripts for admin pages
function surbma_hc_init( $hook )
{
    // * HUCOMMERCE START
    ob_start();
    ?>
	var handler = FS.Checkout.configure({
		plugin_id:  '3068',
		plan_id:    '5616',
		public_key: 'pk_50857a3c655c7175f2c985a3fb618',
		image:      'https://ps.w.org/surbma-magyar-woocommerce/assets/icon-256x256.jpg'
	});

	jQuery('.purchase').on('click', function (e) {
		handler.open({
			name     : 'HuCommerce',
			licenses : 1,
		});
		e.preventDefault();
	});
	<?php 
    $freemius_checkout_script = ob_get_contents();
    ob_end_clean();
    // * HUCOMMERCE END
    wp_register_style(
        'surbma-hc-admin',
        SURBMA_HC_PLUGIN_URL . '/assets/css/admin.css',
        false,
        '18.4'
    );
    // * HUCOMMERCE START
    wp_register_script(
        'freemius-checkout',
        'https://checkout.freemius.com/checkout.min.js',
        'jquery',
        SURBMA_HC_PLUGIN_VERSION_NUMBER,
        true
    );
    // * HUCOMMERCE END
    global  $surbma_hc_settings_page ;
    
    if ( $hook == $surbma_hc_settings_page ) {
        add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
        wp_enqueue_style( 'surbma-hc-admin' );
        // * HUCOMMERCE START
        if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
            wp_enqueue_script( 'freemius-checkout' );
        }
        // * HUCOMMERCE END
    }
    
    // * HUCOMMERCE START
    if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
        wp_add_inline_script( 'freemius-checkout', $freemius_checkout_script );
    }
    // * HUCOMMERCE END
}

add_action( 'admin_enqueue_scripts', 'surbma_hc_init' );
function surbma_hc_admin_sidebar()
{
    $options = get_option( 'surbma_hc_fields' );
    $home_url = get_option( 'home' );
    $current_user = wp_get_current_user();
    ?>
	<div uk-sticky="offset: 108; bottom: #bottom">
		<div class="uk-card uk-card-small uk-card-default uk-card-hover">
			<div class="uk-card-header uk-background-muted">
				<h3 class="uk-card-title"><?php 
    esc_html_e( 'Informations', 'surbma-magyar-woocommerce' );
    ?> <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #informations"></a></h3>
			</div>
			<div id="informations" class="uk-card-body">
				<?php 
    // * HUCOMMERCE START
    ?>
				<?php 
    
    if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
        ?>
				<h4 class="uk-heading-divider"><?php 
        esc_html_e( 'HuCommerce Pro', 'surbma-magyar-woocommerce' );
        ?></h4>
				<p><?php 
        esc_html_e( 'Get access to all features of HuCommerce! The version of HuCommerce Pro will bring you even more awesome features for your webshop. By purchasing the premium version, you can support the development and maintenance of the plugin.', 'surbma-magyar-woocommerce' );
        ?></p>
				<p><a href="https://www.hucommerce.hu/penztar/?add-to-cart=1135" class="uk-button uk-button-default uk-button-danger uk-button-large uk-width-1-1" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 15px;left: -10px;"></span> <?php 
        esc_html_e( 'HuCommerce Pro', 'surbma-magyar-woocommerce' );
        ?></a></p>
				<?php 
    }
    
    ?>
				<?php 
    // * HUCOMMERCE END
    ?>
				<div class="uk-child-width-expand@s uk-text-center uk-grid-small" uk-grid>
					<div>
						<a href="https://www.facebook.com/groups/HuCommerce.hu/" class="facebook-button uk-button uk-button-primary uk-button-large uk-width-1-1" target="_blank"><span uk-icon="facebook"></span></a>
					</div>
					<div>
						<a class="uk-button uk-button-danger uk-button-large uk-width-1-1" href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php 
    echo  urlencode( $current_user->user_email ) ;
    ?>&FNAME=<?php 
    echo  urlencode( $current_user->user_firstname ) ;
    ?>&LNAME=<?php 
    echo  urlencode( $current_user->user_lastname ) ;
    ?>&URL=<?php 
    echo  urlencode( $home_url ) ;
    ?>" target="_blank"><span uk-icon="mail"></span></a>
					</div>
					<div>
						<a class="uk-button uk-button-secondary uk-button-large uk-width-1-1" href="https://www.hucommerce.hu" target="_blank"><span uk-icon="world"></span></a>
					</div>
				</div>

				<?php 
    // * HUCOMMERCE START
    // Partners
    $rss_ajanlatok = fetch_feed( 'https://www.hucommerce.hu/cimke/kiemelt-ajanlat-hucommerce-sidebar/feed/' );
    $maxitems_ajanlatok = false;
    
    if ( !is_wp_error( $rss_ajanlatok ) ) {
        $maxitems_ajanlatok = $rss_ajanlatok->get_item_quantity( 5 );
        $rss_ajanlatok_items = $rss_ajanlatok->get_items( 0, $maxitems_ajanlatok );
    }
    
    
    if ( $maxitems_ajanlatok ) {
        echo  '<h4 class="uk-heading-divider">' . esc_html__( 'Current offer', 'surbma-magyar-woocommerce' ) . '</h4>' ;
        echo  '<div class="rss-widget">' ;
        echo  '<ul>' ;
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_ajanlatok_items as $item_ajanlatok ) {
            echo  '<li>' ;
            echo  '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=hucommerce-sidebar" target="_blank"><img src="' . esc_url( $item_ajanlatok->get_description() ) . '" alt="' . esc_html( $item_ajanlatok->get_title() ) . '" style="display: block;max-width: 100%;height: auto"></a>' ;
            echo  wp_kses_post( $item_ajanlatok->get_content() ) ;
            echo  '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=hucommerce-sidebar" class="uk-button uk-button-primary uk-width-1-1" target="_blank">' . esc_html__( 'View offer', 'surbma-magyar-woocommerce' ) . '</a>' ;
            echo  '</li>' ;
        }
        echo  '</ul>' ;
        echo  '<p class="uk-text-center"><a href="https://www.hucommerce.hu/kategoria/ajanlatok/" target="_blank">' . esc_html__( 'Check all offers', 'surbma-magyar-woocommerce' ) . '</a></p>' ;
        echo  '</div>' ;
    }
    
    // Latest News
    $rss_hirek = fetch_feed( 'https://www.hucommerce.hu/kategoria/hirek/feed/' );
    $maxitems_hirek = false;
    
    if ( !is_wp_error( $rss_hirek ) ) {
        $maxitems_hirek = $rss_hirek->get_item_quantity( 5 );
        $rss_hirek_items = $rss_hirek->get_items( 0, $maxitems_hirek );
    }
    
    
    if ( $maxitems_hirek ) {
        echo  '<h4 class="uk-heading-divider">' . esc_html__( 'Latest News', 'surbma-magyar-woocommerce' ) . '</h4>' ;
        echo  '<div class="rss-widget">' ;
        echo  '<ul>' ;
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_hirek_items as $item_hirek ) {
            echo  '<li>' ;
            echo  '<a href="' . esc_url( $item_hirek->get_permalink() ) . '" target="_blank">' ;
            echo  esc_html( $item_hirek->get_title() ) ;
            echo  '</a>' ;
            echo  '</li>' ;
        }
        echo  '</ul>' ;
        echo  '</div>' ;
    }
    
    // * HUCOMMERCE END
    ?>

				<h4 class="uk-heading-divider"><?php 
    esc_html_e( 'Plugin links', 'surbma-magyar-woocommerce' );
    ?></h4>
				<ul class="uk-list">
					<li><a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce" target="_blank">Hivatalos támogató fórum</a></li>
					<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/#reviews" target="_blank">Olvasd el az értékeléseket (5/5 csillag)</a></li>
					<li><a href="https://www.cherrypickstudios.com" target="_blank">CherryPick Studios</a></li>
				</ul>
				<hr>
				<p>
					<strong>Tetszik a bővítmény? Kérlek értékeld 5 csillaggal:</strong>
					 <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Új értékelés létrehozása</a>
				</p>
				<h4 class="uk-heading-divider"><?php 
    esc_html_e( 'Coming features', 'surbma-magyar-woocommerce' );
    ?></h4>
				<ul class="uk-list">
					<li><span uk-icon="icon: check; ratio: 0.8"></span> Webáruházak kötelező jogi megfelelésének a technikai biztosítása.</li>
					<li><span uk-icon="icon: check; ratio: 0.8"></span> Köszönő oldal egyedi módosítási lehetősége.</li>
				</ul>
			</div>
			<div class="uk-card-footer uk-background-muted">
				<p class="uk-text-center"><?php 
    esc_html_e( 'License: GPLv3 or later License', 'surbma-magyar-woocommerce' );
    ?></p>
			</div>
		</div>
	</div>
	<?php 
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
add_action( 'admin_notices', function () {
    if ( !PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
        return;
    }
    if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
        wp_enqueue_script( 'freemius-checkout' );
    }
    global  $pagenow ;
    $options = get_option( 'surbma_hc_fields' );
    
    if ( ('index.php' == $pagenow || 'plugins.php' == $pagenow) && !$options ) {
        $home_url = get_option( 'home' );
        $current_user = wp_get_current_user();
        ?>
		<div data-dismissible="surbma-hc-notice-welcome-forever" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php 
        echo  esc_url( SURBMA_HC_PLUGIN_URL ) ;
        ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3><?php 
        esc_html_e( 'Thank you for installing HuCommerce plugin!', 'surbma-magyar-woocommerce' );
        ?></h3>
			<p><?php 
        esc_html_e( 'First step is to activate the Modules you need and set the individual Module settings.', 'surbma-magyar-woocommerce' );
        ?>
			<br><?php 
        esc_html_e( 'To activate Modules and adjust settings, go to this page', 'surbma-magyar-woocommerce' );
        ?>: <a href="<?php 
        admin_url();
        ?>admin.php?page=surbma-hucommerce-menu">WooCommerce -> HuCommerce</a></p>
			<p><a class="button button-primary button-large" href="<?php 
        admin_url();
        ?>admin.php?page=surbma-hucommerce-menu"><span class="dashicons dashicons-admin-generic" style="position: relative;top: 4px;left: -3px;"></span> <?php 
        esc_html_e( 'HuCommerce Settings', 'surbma-magyar-woocommerce' );
        ?></a></p>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong><?php 
        esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' );
        ?></strong> <?php 
        esc_html_e( 'This notification will never show up again after you close it.', 'surbma-magyar-woocommerce' );
        ?></p>
		</div>
		<?php 
    }

} );
// * HUCOMMERCE START
// HuCommerce Pro Promo notice
add_action( 'admin_notices', function () {
    if ( PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) {
        return;
    }
    if ( !PAnD::is_admin_notice_active( 'hucommerce-plus-promo-60' ) ) {
        return;
    }
    if ( 'free' != SURBMA_HC_PLUGIN_VERSION ) {
        return;
    }
    global  $pagenow ;
    $options = get_option( 'surbma_hc_fields' );
    
    if ( ('index.php' == $pagenow || 'plugins.php' == $pagenow) && 'free' == SURBMA_HC_PLUGIN_VERSION ) {
        $home_url = get_option( 'home' );
        ?>
		<div data-dismissible="hucommerce-plus-promo-60" class="notice notice-info notice-alt notice-large is-dismissible">
			<a href="https://www.hucommerce.hu" target="_blank"><img src="<?php 
        echo  esc_url( SURBMA_HC_PLUGIN_URL ) ;
        ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright" style="margin: 1em;"></a>
			<h3><?php 
        esc_html_e( 'HuCommerce Pro', 'surbma-magyar-woocommerce' );
        ?></h3>
			<p><?php 
        esc_html_e( 'Get access to all features of HuCommerce! The version of HuCommerce Pro will bring you even more awesome features for your webshop. By purchasing the premium version, you can support the development and maintenance of the plugin.', 'surbma-magyar-woocommerce' );
        ?></p>
			<p>
				<a href="https://www.hucommerce.hu/penztar/?add-to-cart=1135" class="button button-primary button-large" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> <?php 
        esc_html_e( 'Get HuCommerce Pro', 'surbma-magyar-woocommerce' );
        ?></a>
				<a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" class="button button-large" target="_blank"><span class="dashicons dashicons-external" style="position: relative;top: 4px;left: -3px;"></span> <?php 
        esc_html_e( 'More about HuCommerce Pro', 'surbma-magyar-woocommerce' );
        ?></a>
			</p>
			<hr style="margin: 1em 0;">
			<p style="text-align: center;"><strong><?php 
        esc_html_e( 'IMPORTANT!', 'surbma-magyar-woocommerce' );
        ?></strong> <?php 
        esc_html_e( 'This notification will show up after 60 days, when you close it.', 'surbma-magyar-woocommerce' );
        ?></p>
		</div>
		<?php 
    }

} );
add_filter( 'wp_feed_cache_transient_lifetime', function ( $seconds ) {
    return 600;
} );
// Dashboard widget
add_action( 'wp_dashboard_setup', function () {
    global  $wp_meta_boxes ;
    $user_id = get_current_user_id();
    
    if ( !get_user_meta( $user_id, 'surbma_hc_new_dashboard' ) ) {
        delete_user_meta( $user_id, 'meta-box-order_dashboard' );
        update_user_meta( $user_id, 'surbma_hc_new_dashboard', true );
    }
    
    wp_add_dashboard_widget( 'surbma_hc_dashboard_widget', esc_html__( 'HuCommerce', 'surbma-magyar-woocommerce' ), 'surbma_hc_dashboard' );
    $dashboard_widgets = $wp_meta_boxes['dashboard']['normal']['core'];
    $hc_widget = array(
        'surbma_hc_dashboard_widget' => $dashboard_widgets['surbma_hc_dashboard_widget'],
    );
    unset( $wp_meta_boxes['dashboard']['normal']['core']['surbma_hc_dashboard_widget'] );
    $new_dashboard_widgets = array_merge( $hc_widget, $dashboard_widgets );
    // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
    $wp_meta_boxes['dashboard']['normal']['core'] = $new_dashboard_widgets;
}, 0 );
function surbma_hc_dashboard()
{
    $home_url = get_option( 'home' );
    $current_user = wp_get_current_user();
    // Partners
    $rss_ajanlatok = fetch_feed( 'https://www.hucommerce.hu/cimke/kiemelt-ajanlat-dashboard/feed/' );
    $maxitems_ajanlatok = false;
    
    if ( !is_wp_error( $rss_ajanlatok ) ) {
        $maxitems_ajanlatok = $rss_ajanlatok->get_item_quantity( 1 );
        $rss_ajanlatok_items = $rss_ajanlatok->get_items( 0, $maxitems_ajanlatok );
    }
    
    
    if ( $maxitems_ajanlatok ) {
        echo  '<div class="rss-widget" style="background: #f0f6fc;border: 1px solid #c3c4c7;border-left: 4px solid #72aee6;margin-bottom: 2em;padding: 1em;overflow: hidden;">' ;
        echo  '<ul>' ;
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_ajanlatok_items as $item_ajanlatok ) {
            echo  '<li>' ;
            echo  '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=dashboard" target="_blank"><img src="' . esc_url( $item_ajanlatok->get_description() ) . '" alt="' . esc_html( $item_ajanlatok->get_title() ) . '" style="display: block;max-width: 33%;height: auto;float: left;margin: 0 1em 0 0;"></a>' ;
            echo  '<strong>' . esc_html( $item_ajanlatok->get_title() ) . '</strong>' ;
            echo  wp_kses_post( $item_ajanlatok->get_content() ) ;
            echo  '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=dashboard" class="button button-primary button-large" target="_blank" style="display: inline-block;float: left;">' . esc_html__( 'View offer', 'surbma-magyar-woocommerce' ) . '</a>' ;
            echo  '</li>' ;
        }
        echo  '</ul>' ;
        echo  '<p><a href="https://www.hucommerce.hu/kategoria/ajanlatok/" target="_blank" style="display: inline-block;line-height: 32px;float: right;">' . esc_html__( 'Check all offers', 'surbma-magyar-woocommerce' ) . '</a></p>' ;
        echo  '</div>' ;
    }
    
    echo  '<a href="https://www.hucommerce.hu" target="_blank"><img src="' . esc_url( SURBMA_HC_PLUGIN_URL ) . '/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>' ;
    // HuCommerce Pro
    
    if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
        echo  '<h3><strong>' . esc_html__( 'HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</strong></h3>' ;
        echo  '<p>' . esc_html__( 'Get access to all features of HuCommerce! The version of HuCommerce Pro will bring you even more awesome features for your webshop. By purchasing the premium version, you can support the development and maintenance of the plugin.', 'surbma-magyar-woocommerce' ) . '</p>' ;
        echo  '<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">' . esc_html__( 'More about HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</a></p>' ;
        echo  '<p>' ;
        echo  '<a href="https://www.hucommerce.hu/penztar/?add-to-cart=1135" class="button button-primary" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 4px;left: -3px;"></span> ' . esc_html__( 'Get HuCommerce Pro', 'surbma-magyar-woocommerce' ) . '</a>' ;
        echo  '</p>' ;
        echo  '<hr style="margin: 2em 0 1em;clear: both;">' ;
    }
    
    // Community
    echo  '<h3><strong>' . esc_html__( 'HuCommerce Community', 'surbma-magyar-woocommerce' ) . '</strong></h3>' ;
    echo  '<p>' . esc_html__( 'Please join our Facebook Group and subscribe to our HuCommerce newsletter!', 'surbma-magyar-woocommerce' ) . '</p>' ;
    echo  '<p>' ;
    echo  '<a href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank" class="button button-primary"><span class="dashicons dashicons-facebook-alt" style="position: relative;top: 3px;left: -3px;"></span>' . esc_html__( 'Facebook Group', 'surbma-magyar-woocommerce' ) . '</a>' ;
    echo  ' ' ;
    echo  '<a href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=' . urlencode( $current_user->user_email ) . '&FNAME=' . urlencode( $current_user->user_firstname ) . '&LNAME=' . urlencode( $current_user->user_lastname ) . '&URL=' . urlencode( $home_url ) . '" target="_blank" class="button button-secondary"><span class="dashicons dashicons-email" style="position: relative;top: 3px;left: -3px;"></span> ' . esc_html__( 'Subscribe', 'surbma-magyar-woocommerce' ) . '</a>' ;
    echo  '</p>' ;
    // Latest News
    $rss_hirek = fetch_feed( 'https://www.hucommerce.hu/kategoria/hirek/feed/' );
    $maxitems_hirek = false;
    
    if ( !is_wp_error( $rss_hirek ) ) {
        $maxitems_hirek = $rss_hirek->get_item_quantity( 5 );
        $rss_hirek_items = $rss_hirek->get_items( 0, $maxitems_hirek );
    }
    
    
    if ( $maxitems_hirek ) {
        echo  '<hr style="margin: 2em 0 1em;clear: both;">' ;
        echo  '<h3><strong>' . esc_html__( 'Latest News from HuCommerce', 'surbma-magyar-woocommerce' ) . '</strong></h3>' ;
        echo  '<div class="rss-widget">' ;
        echo  '<ul>' ;
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_hirek_items as $item_hirek ) {
            $itemdate = $item_hirek->get_date( 'Y-m-d' );
            echo  '<li>' ;
            echo  '<a href="' . esc_url( $item_hirek->get_permalink() ) . '" target="_blank">' ;
            echo  '<span class="rss-date">' . esc_html( $itemdate ) . '</span> - ' . esc_html( $item_hirek->get_title() ) ;
            echo  '</a>' ;
            echo  '</li>' ;
        }
        echo  '</ul>' ;
        echo  '</div>' ;
    }

}

// * HUCOMMERCE END