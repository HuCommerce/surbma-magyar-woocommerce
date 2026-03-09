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
		<?php $hc_active_tab = isset( $_GET['tab'] ) ? absint( $_GET['tab'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<ul id="hc-modules-nav" class="uk-nav-sub uk-padding-remove-left uk-padding-remove-bottom" uk-switcher="connect: #cps-hc-gems-modules; animation: uk-animation-fade; active: <?php echo esc_attr( $hc_active_tab ); ?>">
			<li><a class="uk-offcanvas-close uk-modal-close-default"><span class="uk-margin-small-right" style="width: 100%;max-width: 20px;" uk-icon="icon: chevron-double-right; ratio: 1"></span> <?php esc_html_e( 'All modules', 'surbma-magyar-woocommerce' ); ?></a></li>
			<li class="uk-nav-header"><span class="uk-margin-small-right" style="width: 100%;max-width: 20px;" uk-icon="icon: settings; ratio: 1"></span> <?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?>:</li>
			<?php cps_hc_gems_module_nav_item( __( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ), 'maskcheckoutfields' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Check field values', 'surbma-magyar-woocommerce' ), 'validatecheckoutfields' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Free shipping notification', 'surbma-magyar-woocommerce' ), 'freeshippingnotice' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Empty Cart button', 'surbma-magyar-woocommerce' ), 'module-emptycartbutton' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Product price history', 'surbma-magyar-woocommerce' ), 'module-productpricehistory' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Product price additions', 'surbma-magyar-woocommerce' ), 'module-productpriceadditions' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ), 'legalcheckout' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Limit Payment Methods', 'surbma-magyar-woocommerce' ), 'module-limitpaymentmethods' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Global Information', 'surbma-magyar-woocommerce' ), 'module-globalinfo' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Translations', 'surbma-magyar-woocommerce' ), 'module-translations' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Fixes for Hungarian language', 'surbma-magyar-woocommerce' ), 'huformatfix' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Tax number field', 'surbma-magyar-woocommerce' ), 'taxnumber' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Hungarian translation fixes', 'surbma-magyar-woocommerce' ), 'translations' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Hide County field if Country is Hungary', 'surbma-magyar-woocommerce' ), 'nocounty' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Autofill City after Postcode is given', 'surbma-magyar-woocommerce' ), 'autofillcity' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Product customizations', 'surbma-magyar-woocommerce' ), 'module-productsettings' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Checkout page customizations', 'surbma-magyar-woocommerce' ), 'module-checkout' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Plus/minus quantity buttons', 'surbma-magyar-woocommerce' ), 'plusminus' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Automatic Cart update', 'surbma-magyar-woocommerce' ), 'updatecart' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Continue shopping buttons', 'surbma-magyar-woocommerce' ), 'returntoshop' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Login and registration redirection', 'surbma-magyar-woocommerce' ), 'loginregistrationredirect' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Coupon field customizations', 'surbma-magyar-woocommerce' ), 'module-coupon' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Redirect Cart page to Checkout page', 'surbma-magyar-woocommerce' ), 'module-redirectcart' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'One product per purchase', 'surbma-magyar-woocommerce' ), 'module-oneproductincart' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Custom Add To Cart Button', 'surbma-magyar-woocommerce' ), 'module-custom-addtocart-button' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Hide shipping methods', 'surbma-magyar-woocommerce' ), 'module-hideshippingmethods' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'SMTP service', 'surbma-magyar-woocommerce' ), 'module-smtp' ); ?>
			<?php cps_hc_gems_module_nav_item( __( 'Catalog mode', 'surbma-magyar-woocommerce' ), 'module-catalogmode' ); ?>
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

	$nav_pages = ['information'];

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
	<li><a class="uk-inline" href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: lifesaver"></span> <?php esc_html_e( 'Support', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
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

	<?php // Permanent pro features notice (non-dismissible) ?>
	<div class="notice notice-warning">
		<p><strong>FIGYELEM!</strong> A HuCommerce Pro funkciók ideiglenesen ingyenesen elérhetőek ebben a bővítményben. Egy hamarosan érkező újabb verzióban ezek a funkciók átkerülnek egy különálló Pro bővítménybe. Így a Pro verzió megvásárlása nélkül a következő verziótól már nem tudod használni ezeket a funkciókat.</p>
	</div>

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
					<li><a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span uk-icon="icon: lifesaver"></span></a></li>
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
