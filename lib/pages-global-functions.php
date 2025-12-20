<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Modules page navigation
function cps_hc_gems_page_modules_nav() {
	$screen = get_current_screen();
	$page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
	$modules_hook = $page_hooks['modules'] ?? '';

	$active_modules_menu = $modules_hook == $screen->base ? 'uk-active' : '';

	?>
	<li class="<?php echo esc_attr( $active_modules_menu ); ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=cps_hc_gems_modules' ) ); ?>"><span class="uk-margin-small-right" uk-icon="icon: thumbnails"></span> HuCommerce <?php esc_html_e( 'Modules', 'surbma-magyar-woocommerce' ); ?></a></li>
	<?php if ( $modules_hook == $screen->base ) { ?>
	<li class="cps-settings-subnav">
		<ul class="uk-nav-sub uk-padding-remove-left uk-padding-remove-bottom" uk-switcher="connect: #cps-hc-gems-modules; animation: uk-animation-fade">
			<li><a class="uk-offcanvas-close uk-modal-close-default"><span class="uk-margin-small-right" style="width: 100%;max-width: 20px;" uk-icon="icon: chevron-double-right; ratio: 1"></span> <?php esc_html_e( 'All modules', 'surbma-magyar-woocommerce' ); ?></a></li>
			<li class="uk-nav-header"><span class="uk-margin-small-right" style="width: 100%;max-width: 20px;" uk-icon="icon: settings; ratio: 1"></span> <?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?>:</li>
			<?php cps_hc_gems_module_nav_item( 'Check field formats (Masking)', 'maskcheckoutfields' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Check field values', 'validatecheckoutfields' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Free shipping notification', 'freeshippingnotice' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Empty Cart button', 'module-emptycartbutton' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Product price history', 'module-productpricehistory' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Product price additions', 'module-productpriceadditions' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'legalcheckout' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Limit Payment Methods', 'module-limitpaymentmethods' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Global Information', 'module-globalinfo' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Translations', 'module-translations' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Fixes for Hungarian language', 'huformatfix' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Tax number field', 'taxnumber' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Hungarian translation fixes', 'translations' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Hide County field if Country is Hungary', 'nocounty' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Autofill City after Postcode is given', 'autofillcity' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Product customizations', 'module-productsettings' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Checkout page customizations', 'module-checkout' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Plus/minus quantity buttons', 'plusminus' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Automatic Cart update', 'updatecart' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Continue shopping buttons', 'returntoshop' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Login and registration redirection', 'loginregistrationredirect' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Coupon field customizations', 'module-coupon' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Redirect Cart page to Checkout page', 'module-redirectcart' ); ?>
			<?php cps_hc_gems_module_nav_item( 'One product per purchase', 'module-oneproductincart' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Custom Add To Cart Button', 'module-custom-addtocart-button' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Hide shipping methods', 'module-hideshippingmethods' ); ?>
			<?php cps_hc_gems_module_nav_item( 'SMTP service', 'module-smtp' ); ?>
			<?php cps_hc_gems_module_nav_item( 'Catalog mode', 'module-catalogmode' ); ?>
		</ul>
	</li>
	<?php } ?>
	<?php
}

/**
 * Render the pages navigation items in sidebar
 * Uses pages config for dynamic generation
 */
function cps_hc_gems_pages_nav() {
	$screen = get_current_screen();
	$page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
	$pages = cps_hc_gems_get_visible_pages();

	// Skip first page (modules) as it has its own nav function
	$is_first = true;
	foreach ( $pages as $page_key => $page ) {
		if ( $is_first ) {
			$is_first = false;
			continue; // Skip modules page
		}

		// Skip license and information - they have their own nav section
		if ( in_array( $page_key, ['license', 'information'], true ) ) {
			continue;
		}

		$hook = $page_hooks[ $page_key ] ?? '';
		$active_class = ( $hook === $screen->base ) ? 'uk-active' : '';
		$icon = cps_hc_gems_get_page_icon( $page );

		printf(
			'<li class="%s"><a href="%s"><span class="uk-margin-small-right" uk-icon="icon: %s"></span> %s</a></li>',
			esc_attr( $active_class ),
			esc_url( admin_url( 'admin.php?page=' . $page['menu_slug'] ) ),
			esc_attr( $icon ),
			esc_html( $page['card_title'] )
		);
	}
}

/**
 * Render the license and information navigation items
 */
function cps_hc_gems_page_license_nav() {
	$screen = get_current_screen();
	$page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
	$pages = cps_hc_gems_get_pages_config();

	$nav_pages = ['license', 'information'];

	foreach ( $nav_pages as $page_key ) {
		if ( ! isset( $pages[ $page_key ] ) || $pages[ $page_key ]['status'] !== 'active' ) {
			continue;
		}

		$page = $pages[ $page_key ];
		$hook = $page_hooks[ $page_key ] ?? '';
		$active_class = ( $hook === $screen->base ) ? 'uk-active' : '';
		$icon = cps_hc_gems_get_page_icon( $page );

		printf(
			'<li class="%s"><a href="%s"><span class="uk-margin-small-right" uk-icon="icon: %s"></span> %s</a></li>',
			esc_attr( $active_class ),
			esc_url( admin_url( 'admin.php?page=' . $page['menu_slug'] ) ),
			esc_attr( $icon ),
			esc_html( $page['title'] )
		);
	}
}

// Social page navigation
function cps_hc_gems_page_social_nav() {
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	?>
	<li><a class="uk-inline" href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: mail"></span> <?php esc_html_e( 'Newsletter', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<?php if ( 'free' != HC_LICENSE ) { ?>
	<li><a class="uk-inline" href="#" onclick="Beacon('open'); Beacon('navigate', '/ask/message')"><span class="uk-margin-small-right" uk-icon="icon: lifesaver"></span> <?php esc_html_e( 'Support', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<?php } else { ?>
	<li><a class="uk-inline" href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: lifesaver"></span> <?php esc_html_e( 'Support', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<?php } ?>
	<li><a class="uk-inline" href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: facebook"></span> <?php esc_html_e( 'Facebook group', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<li><a class="uk-inline" href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: wordpress"></span> <?php esc_html_e( 'WordPress.org', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<li><a class="uk-inline" href="https://www.hucommerce.hu" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: world"></span> HuCommerce.hu <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<li><a class="uk-inline" href="https://www.hucommerce.hu/blog/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: rss"></span> HuCommerce Blog <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
	<?php
}

// Header
function cps_hc_gems_page_header() {
	?>
	<div class="cps-admin cps-admin-2">
		<div class="wrap">
	<?php
}

// Notifications
function cps_hc_gems_page_notifications() {
	$screen = get_current_screen();
	$page_hooks = $GLOBALS['cps_hc_gems_page_hooks'] ?? [];
	$license_hook = $page_hooks['license'] ?? '';

	?>
	<?php if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="updated notice is-dismissible">
			<p><strong><?php esc_html_e( 'Settings saved.', 'surbma-magyar-woocommerce' ); ?></strong></p>
		</div>
	<?php } ?>

	<?php if ( isset( $_GET['hc-response'] ) && 'status' == $_GET['hc-response'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="updated notice is-dismissible">
			<p><strong><?php esc_html_e( 'API sync finished.', 'surbma-magyar-woocommerce' ); ?></strong></p>
		</div>
	<?php } ?>

	<?php if ( isset( $_GET['hc-response'] ) && 'email-sent' == $_GET['hc-response'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="updated notice is-dismissible">
			<p><strong><?php esc_html_e( 'Test email sent.', 'surbma-magyar-woocommerce' ); ?></strong></p>
		</div>
	<?php } ?>

	<?php // Free notification ?>
	<?php if ( 'free' == HC_LICENSE && $license_hook != $screen->base ) { ?>
		<div class="notice notice-info is-dismissible">
			<p><strong class="uk-text-uppercase">Figyelem!</strong> Nézd meg, mivel nyújt többet a <a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">HuCommerce Pro</a> verzió!</p>
		</div>
	<?php } ?>

	<?php // Inactive notification ?>
	<?php if ( 'inactive' == HC_LICENSE ) { ?>
		<div class="notice notice-error is-dismissible">
			<p><strong class="uk-text-uppercase">Még nem aktivált HuCommerce Pro licensz kulcs!</strong> <br>A megadott licensz kulcsod nincs aktiválva. A <strong>"HuCommerce → Licensz kezelés"</strong> menüpont alatt tudod a megadott licensz kulcsot frissíteni vagy újra aktiválni.</p>
		</div>
	<?php } ?>

	<?php // Invalid notification ?>
	<?php if ( 'invalid' == HC_LICENSE ) { ?>
		<div class="notice notice-error is-dismissible">
			<p><strong class="uk-text-uppercase">Érvénytelen vagy lejárt HuCommerce Pro licensz kulcs!</strong> <br>Kérlek ellenőrizd az emailben küldött licensz kulcsot és add meg újra vagy frissítsd és aktiváld újra a <strong>"HuCommerce → Licensz kezelés"</strong> menüpont alatt!</p>
		</div>
	<?php } ?>

	<?php // Expired notification ?>
	<?php if ( 'expired' == HC_LICENSE ) { ?>
		<div class="notice notice-error is-dismissible">
			<p><strong class="uk-text-uppercase">Lejárt HuCommerce Pro licensz kulcs!</strong> <br>Amennyiben szeretnéd tovább használni a HuCommerce Pro funkciókat vedd fel az <a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><strong>ügyfélszolgálattal</strong></a> a kapcsolatot.</p>
		</div>
	<?php } ?>

	<h2 class="uk-hidden"></h2>

	<?php
}

// Sidebar
function cps_hc_gems_page_sidebar() {
	?>
	<div class="uk-text-center uk-margin-top uk-margin-medium-bottom"><a href="/wp-admin/admin.php?page=cps_hc_gems_modules"><img src="<?php echo esc_url( CPS_HC_GEMS_URL ); ?>/assets/images/hucommerce-logo-2023-dark.png" alt="HuCommerce" width="150" height="27"></a></div><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
	<ul class="cps-settings-nav uk-nav uk-nav-default">
		<?php cps_hc_gems_page_modules_nav(); ?>
		<li class="uk-nav-divider"><a></a></li>
		<?php cps_hc_gems_pages_nav(); ?>
		<li class="uk-nav-divider"><a></a></li>
		<?php cps_hc_gems_page_license_nav(); ?>
		<li class="uk-nav-divider"><a></a></li>
		<?php cps_hc_gems_page_social_nav(); ?>
		<li class="uk-nav-divider"><a></a></li>
	</ul>
	<?php
}

// Mobile navigation
function cps_hc_gems_page_mobile_nav() {
	?>
	<div class="uk-width-auto uk-hidden@m">
		<a class="uk-text-secondary" href="#cps-settings-mobile-nav" uk-toggle><span uk-navbar-toggle-icon></span></a>
		<div id="cps-settings-mobile-nav" uk-modal="container: .cps-admin">
			<div class="uk-modal-dialog uk-modal-body">
				<button class="uk-modal-close-default" type="button" uk-close></button>
				<?php cps_hc_gems_page_sidebar(); ?>
			</div>
		</div>
	</div>
	<?php
}

// Card footer
function cps_hc_gems_page_card_footer() {
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	?>
	<div class="uk-card-footer">
		<nav class="uk-navbar-container uk-navbar-transparent uk-margin" uk-navbar>
			<div class="uk-navbar-left uk-visible@s">
				<div class="uk-navbar-item">
					<strong>Tetszik a bővítmény? <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Kérlek értékeld 5 csillaggal!</a></strong>
				</div>
			</div>
			<div class="uk-navbar-right">
				<ul class="uk-navbar-nav">
					<li><a href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank"><span uk-icon="icon: mail"></span></a></li>
					<?php if ( 'free' != HC_LICENSE ) { ?>
					<li><a href="#" onclick="Beacon('open'); Beacon('navigate', '/ask/message')"><span uk-icon="icon: lifesaver"></span></a></li>
					<?php } else { ?>
					<li><a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span uk-icon="icon: lifesaver"></span></a></li>
					<?php } ?>
					<li><a href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank"><span uk-icon="icon: facebook"></span></a></li>
					<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/" target="_blank"><span uk-icon="icon: wordpress"></span></a></li>
					<li><a href="https://www.hucommerce.hu" target="_blank"><span uk-icon="icon: world"></span></a></li>
				</ul>
			</div>
		</nav>
	</div>
	<?php
}

// Footer
function cps_hc_gems_page_footer() {
	?>
		</div>
	</div>
	<?php
}
