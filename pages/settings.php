<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-select-options.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-functions.php');
include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-validate.php');

add_action( 'admin_init', function() {
	register_setting(
		'surbma_hc_options',
		'surbma_hc_fields',
		'surbma_hc_fields_validate'
	);
	register_setting(
		'surbma_hc_license_options',
		'surbma_hc_license',
		'surbma_hc_license_validate'
	);
} );

function surbma_hc_settings_page() {
	$options = get_option( 'surbma_hc_fields' );
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();
	$hc_pro_menu_icon = 'active' == SURBMA_HC_PLUGIN_LICENSE ? 'unlock' : 'lock';

	// Nav items
	ob_start();
		?>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: thumbnails"></span> <?php esc_html_e( 'HuCommerce modules', 'surbma-magyar-woocommerce' ); ?></a></li>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: <?php echo $hc_pro_menu_icon; ?>"></span> <?php esc_html_e( 'HuCommerce Pro', 'surbma-magyar-woocommerce' ); ?></a></li>
		<li class="uk-nav-divider"><a></a></li>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: star"></span> <?php esc_html_e( 'Offers', 'surbma-magyar-woocommerce' ); ?></a></li>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: list"></span> <?php esc_html_e( 'Directory', 'surbma-magyar-woocommerce' ); ?></a></li>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: rss"></span> <?php esc_html_e( 'Latest News', 'surbma-magyar-woocommerce' ); ?></a></li>
		<li><a class="uk-offcanvas-close"><span class="uk-margin-small-right" uk-icon="icon: info"></span> <?php esc_html_e( 'Informations', 'surbma-magyar-woocommerce' ); ?></a></li>
		<?php
	$nav_items = ob_get_contents();
	ob_end_clean();

	// Social items
	ob_start();
		?>
		<li class="uk-nav-divider"><a></a></li>
		<li><a class="uk-offcanvas-close uk-inline" href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: mail"></span> <?php esc_html_e( 'Newsletter', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
		<li><a class="uk-offcanvas-close uk-inline" href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: lifesaver"></span> <?php esc_html_e( 'Support', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
		<li><a class="uk-offcanvas-close uk-inline" href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: facebook"></span> <?php esc_html_e( 'Facebook group', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
		<li><a class="uk-offcanvas-close uk-inline" href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: wordpress"></span> <?php esc_html_e( 'WordPress.org', 'surbma-magyar-woocommerce' ); ?> <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
		<li><a class="uk-offcanvas-close uk-inline" href="https://www.hucommerce.hu" target="_blank"><span class="uk-margin-small-right" uk-icon="icon: world"></span> HuCommerce.hu <span class="uk-position-center-right" uk-icon="icon: sign-out; ratio: .8"></span></a></li>
		<?php
	$social_items = ob_get_contents();
	ob_end_clean();

	?>
<div class="cps-admin cps-admin-2">
	<div class="wrap">
		<?php if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) { ?>
			<div class="updated notice is-dismissible"><p><strong><?php esc_html_e( 'Settings saved.' ); ?></strong></p></div>
		<?php } ?>

		<h2 class="uk-hidden"></h2>

		<?php // Inactive notification ?>
		<?php if ( 'inactive' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
			<div class="cps-alert uk-alert-primary uk-animation-slide-top-medium" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<p><strong class="uk-text-uppercase">Még nem aktivált HuCommerce Pro licensz kulcs!</strong> <br>A megadott HuCommerce Pro licensz kulcsod nincs aktiválva. A <strong>"HuCommerce Pro"</strong> almenüpont alatt tudod a megadott licensz kulcsot frissíteni vagy újra aktiválni.</p>
			</div>
		<?php } ?>

		<?php // Invalid notification ?>
		<?php if ( 'invalid' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
			<div class="cps-alert uk-alert-danger uk-animation-slide-top-medium" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<p><strong class="uk-text-uppercase">Érvénytelen vagy lejárt HuCommerce Pro licensz kulcs!</strong> <br>Kérlek ellenőrizd az emailben küldött licensz kulcsot és add meg újra vagy frissítsd és aktiváld újra a <strong>"HuCommerce Pro"</strong> almenüpont alatt!</p>
			</div>
		<?php } ?>

		<?php // Expired notification ?>
		<?php if ( 'expired' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
			<div class="cps-alert uk-alert-danger uk-animation-slide-top-medium" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<p><strong class="uk-text-uppercase">Lejárt HuCommerce Pro licensz kulcs!</strong> <br>Amennyiben szeretnéd tovább használni a HuCommerce Pro funkciókat vedd fel az <a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><strong>ügyfélszolgálattal</strong></a> a kapcsolatot.</p>
			</div>
		<?php } ?>

		<?php // HuCommerce legacy users notice ?>
		<?php if ( 'free' == SURBMA_HC_PLUGIN_LICENSE && $options && !isset( $options['brandnewuser'] ) ) { ?>
			<div class="cps-alert uk-alert-danger uk-animation-slide-top-medium" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<p><strong class="uk-text-uppercase">Figyelem régi HuCommerce felhasználók!</strong></p>
				<p>Az eddig megszokott és beállított HuCommerce modulok közül sok nem használható a HuCommerce 2022.1.0 verziójától. Ezek a modulok átkerültek a HuCommerce fizetős, Pro verziójába. Minden eddig beállított modul továbbra is működik, de módosítani nem lehet a beállításokat, sőt mentés után kikapcsolásra kerülnek ezek a modulok!</p>
				<p>Miért lett fizetős az, ami eddig ingyen volt? Milyen ajánlataink vannak az eddigi felhasználóknak? Kérlek olvasd el a cikkünket erről: <br><a href="https://www.hucommerce.hu/hucommerce-pro-egy-uj-korszak/" target="_blank">HuCommerce Pro, egy új korszak</a></p>
			</div>
		<?php } ?>

		<?php // HuCommerce partner banner ?>
		<?php
		if ( !SURBMA_HC_PREMIUM ) {
			// Partners
			$rss_ajanlatok = fetch_feed( 'https://www.hucommerce.hu/cimke/kiemelt-ajanlat-hucommerce-top/feed/' );
			$maxitems_ajanlatok = false;

			if ( !is_wp_error( $rss_ajanlatok ) ) {
				$maxitems_ajanlatok = $rss_ajanlatok->get_item_quantity( 1 );
				$rss_ajanlatok_items = $rss_ajanlatok->get_items( 0, $maxitems_ajanlatok );
			}

			if ( $maxitems_ajanlatok ) :
				// Loop through each feed item and display each item as a hyperlink.
				foreach ( $rss_ajanlatok_items as $item_ajanlatok ) :
					echo '<div id="hucommerce-partner-banner-top" class="uk-card uk-card-default uk-card-hover uk-card-small uk-grid-collapse uk-margin uk-animation-shake" uk-grid>';
					echo '<div class="uk-card-media-left uk-cover-container uk-width-auto@s">';
					echo '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=hucommerce-top" target="_blank"><img src="' . esc_url( $item_ajanlatok->get_description() ) . '" alt="' . esc_html( $item_ajanlatok->get_title() ) . '" uk-cover></a>';
					echo '<canvas width="300" height="195"></canvas>';
					echo '</div>';
					echo '<div class="uk-width-expand@s">';
					echo '<div class="uk-card-body">';
					echo '<span class="uk-label uk-label-warning uk-position-small uk-position-bottom-right">' . esc_html__( 'Ad', 'surbma-magyar-woocommerce' ) . '</span>';
					echo '<a href="#" class="uk-position-small uk-position-top-right" uk-close uk-toggle="target: #hucommerce-partner-banner-top"></a>';
					echo '<h3 class="uk-card-title uk-margin-remove-top">' . esc_html( $item_ajanlatok->get_title() ) . '</h3>';
					echo wp_kses_post( $item_ajanlatok->get_content() );
					echo '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=hucommerce-top" class="uk-button uk-button-default" target="_blank"><span class="dashicons dashicons-external" style="position: relative;top: 8px;left: -6px;"></span> ' . esc_html__( 'View offer', 'surbma-magyar-woocommerce' ) . '</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</li>';
				endforeach;
			endif;
		}
		?>

		<div id="cps-settings" class="uk-card uk-card-default uk-card-hover uk-margin-bottom">
			<div class="uk-card-header uk-background-muted">
				<nav class="uk-navbar-container uk-margin" uk-navbar>
					<div class="uk-navbar-left">
						<a class="uk-navbar-item uk-logo" href="https://www.hucommerce.hu" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" style="width: auto;margin-right: 0;float: none;"></a>
					</div>

					<div class="uk-navbar-right">
						<ul class="uk-navbar-nav uk-hidden@l">
							<li>
								<a href="#cps-settings-mobile-nav" uk-toggle><span uk-navbar-toggle-icon></span></a>
								<div id="cps-settings-mobile-nav" uk-offcanvas="overlay: true;container: .cps-admin">
									<div class="uk-offcanvas-bar">
										<button class="uk-offcanvas-close" type="button" uk-close></button>

										<ul class="cps-settings-nav uk-nav uk-nav-default uk-margin-medium-top" uk-switcher="connect: #cps-settings-nav-content; animation: uk-animation-fade">
											<?php echo $nav_items; ?>
										</ul>

										<ul class="cps-settings-nav uk-nav uk-nav-default">
											<?php echo $social_items; ?>
										</ul>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</nav>
			</div>

			<div class="uk-card-body uk-padding-remove">
				<div class="uk-grid-collapse uk-grid-match" uk-grid>
					<div class="uk-width-medium uk-visible@l">
						<div class="uk-padding uk-background-muted" style="border-right: 1px solid #e5e5e5;">
							<ul class="cps-settings-nav uk-nav uk-nav-default" uk-switcher="connect: #cps-settings-nav-content; animation: uk-animation-fade">
								<?php echo $nav_items; ?>
							</ul>

							<ul class="cps-settings-nav uk-nav uk-nav-default">
								<?php echo $social_items; ?>
							</ul>
						</div>
					</div>

					<div class="uk-width-expand">
						<div class="uk-padding">
							<ul id="cps-settings-nav-content" class="uk-switcher">
								<li id="hucommerce-modules">
									<?php cps_hc_wcgems_nav_item_header( 'HuCommerce modules' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-modules.php'); ?>
								</li>
								<?php // * HUCOMMERCE START ?>
								<li>
									<?php cps_hc_wcgems_nav_item_header( 'HuCommerce Pro License Management' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-license.php'); ?>
								</li>
								<?php // * HUCOMMERCE END ?>
								<li></li>
								<?php // * HUCOMMERCE START ?>
								<li>
									<?php cps_hc_wcgems_nav_item_header( 'Offers' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-offers.php'); ?>
								</li>
								<?php // * HUCOMMERCE END ?>
								<?php // * HUCOMMERCE START ?>
								<li>
									<?php cps_hc_wcgems_nav_item_header( 'HuCommerce Directory' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-directory.php'); ?>
								</li>
								<?php // * HUCOMMERCE END ?>
								<?php // * HUCOMMERCE START ?>
								<li>
									<?php cps_hc_wcgems_nav_item_header( 'Latest News' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-news.php'); ?>
								</li>
								<?php // * HUCOMMERCE END ?>
								<li>
									<?php cps_hc_wcgems_nav_item_header( 'Informations' ); ?>
									<?php include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings-nav-informations.php'); ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="uk-card-footer uk-background-muted">
				<nav class="uk-navbar-container uk-margin" uk-navbar>
					<div class="uk-navbar-left uk-visible@s">
						<div class="uk-navbar-item">
							<strong>Tetszik a bővítmény? <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Kérlek értékeld 5 csillaggal!</a></strong>
						</div>
					</div>
					<div class="uk-navbar-right">
						<ul class="uk-navbar-nav">
							<li><a href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>" target="_blank"><span uk-icon="icon: mail"></span></a></li>
							<li><a href="https://www.hucommerce.hu/ugyfelszolgalat/" target="_blank"><span uk-icon="icon: lifesaver"></span></a></li>
							<li><a href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank"><span uk-icon="icon: facebook"></span></a></li>
							<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/" target="_blank"><span uk-icon="icon: wordpress"></span></a></li>
							<li><a href="https://www.hucommerce.hu" target="_blank"><span uk-icon="icon: world"></span></a></li>
						</ul>
					</div>
				</nav>
			</div>
		</div>

		<a href="#" class="uk-float-right uk-margin-bottom" uk-totop uk-scroll></a>

		<div class="uk-clearfix" id="bottom"></div>

		<?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
	</div>
</div>
<?php
}
