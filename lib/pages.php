<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Get the pages configuration array
 *
 * This is the single source of truth for all admin page definitions.
 * The first item in the array is the main/parent menu.
 * All subsequent items are submenu items.
 * Array order determines sidebar display order.
 *
 * @return array The pages configuration array
 */
function cps_hc_gems_get_pages_config() {
	return [
		// First item = main menu parent
		'modules' => [
			'title' => __( 'Modules', 'surbma-magyar-woocommerce' ),
			'page_title' => __( 'HuCommerce Modules', 'surbma-magyar-woocommerce' ),
			'card_title' => 'HuCommerce ' . __( 'Modules', 'surbma-magyar-woocommerce' ),
			'description' => __( 'Manage and configure HuCommerce modules', 'surbma-magyar-woocommerce' ),
			'icon' => 'thumbnails',
			'menu_slug' => 'cps_hc_gems_modules',
			'menu_file' => 'menu-modules.php',
			'renderer' => 'cps_hc_gems_render_menu_modules',
			'status' => 'active',
		],
		'offers' => [
			'title' => __( 'Offers', 'surbma-magyar-woocommerce' ),
			'page_title' => __( 'HuCommerce Offers', 'surbma-magyar-woocommerce' ),
			'card_title' => __( 'Offers', 'surbma-magyar-woocommerce' ),
			'description' => 'Ajánlatok csak a HuCommerce felhasználóknak válogatott partnerektől.',
			'icon' => 'star',
			'menu_slug' => 'cps_hc_gems_offers',
			'menu_file' => 'menu-offers.php',
			'renderer' => 'cps_hc_gems_render_menu_offers',
			'status' => 'inactive',
		],
		'directory' => [
			'title' => __( 'Directory', 'surbma-magyar-woocommerce' ),
			'page_title' => __( 'HuCommerce Directory', 'surbma-magyar-woocommerce' ),
			'card_title' => 'HuCommerce ' . __( 'Directory', 'surbma-magyar-woocommerce' ),
			'description' => 'Hasznos linkek minden WooCommerce webáruház tulajdonosnak. <strong>FIGYELEM!</strong> A linkek partner linkek, amik után jutalékot kaphatunk.',
			'icon' => 'list',
			'menu_slug' => 'cps_hc_gems_directory',
			'menu_file' => 'menu-directory.php',
			'renderer' => 'cps_hc_gems_render_menu_directory',
			'status' => 'active',
		],
		'news' => [
			'title' => __( 'Latest News', 'surbma-magyar-woocommerce' ),
			'page_title' => __( 'HuCommerce Latest News', 'surbma-magyar-woocommerce' ),
			'card_title' => __( 'Latest News', 'surbma-magyar-woocommerce' ),
			'description' => 'Legújabb híreink a HuCommerce bővítménnyel kapcsolatban.',
			'icon' => 'rss',
			'menu_slug' => 'cps_hc_gems_news',
			'menu_file' => 'menu-news.php',
			'renderer' => 'cps_hc_gems_render_menu_news',
			'status' => 'inactive',
		],
		'information' => [
			'title' => __( 'Information', 'surbma-magyar-woocommerce' ),
			'page_title' => __( 'HuCommerce Information', 'surbma-magyar-woocommerce' ),
			'card_title' => __( 'Information', 'surbma-magyar-woocommerce' ),
			'description' => 'Fontos információk a HuCommerce bővítménnyel és az installációval kapcsolatban.',
			'icon' => 'info',
			'menu_slug' => 'cps_hc_gems_information',
			'menu_file' => 'menu-information.php',
			'renderer' => 'cps_hc_gems_render_menu_information',
			'status' => 'active',
		],
	];
}

/**
 * Get pages filtered by status
 *
 * @param array $statuses Array of status values to include
 * @return array Filtered pages config
 */
function cps_hc_gems_get_pages_by_status( $statuses = ['active'] ) {
	$pages = cps_hc_gems_get_pages_config();
	return array_filter( $pages, function( $page ) use ( $statuses ) {
		return in_array( $page['status'], $statuses, true );
	});
}

/**
 * Get registerable pages (active + hidden)
 *
 * @return array Pages that should be registered in WordPress admin
 */
function cps_hc_gems_get_registerable_pages() {
	return cps_hc_gems_get_pages_by_status( ['active', 'hidden'] );
}

/**
 * Get visible pages for sidebar navigation
 *
 * @return array Pages that should appear in sidebar
 */
function cps_hc_gems_get_visible_pages() {
	return cps_hc_gems_get_pages_by_status( ['active'] );
}

/**
 * Get the icon for a page, handling dynamic icons
 *
 * @param array $page_config The page configuration
 * @return string The icon name
 */
function cps_hc_gems_get_page_icon( $page_config ) {
	return $page_config['icon'];
}

/**
 * Generate a page callback function for WordPress menu registration
 *
 * @param string $page_key The page key from config
 * @return callable The callback function
 */
function cps_hc_gems_get_page_callback( $page_key ) {
	return function() use ( $page_key ) {
		cps_hc_gems_render_page( $page_key );
	};
}

/**
 * Render a page based on its configuration
 *
 * @param string $page_key The page key from the config array
 * @return void
 */
function cps_hc_gems_render_page( $page_key ) {
	$pages = cps_hc_gems_get_pages_config();

	if ( ! isset( $pages[ $page_key ] ) ) {
		return;
	}

	$page = $pages[ $page_key ];

	// Load required files
	include_once CPS_HC_GEMS_DIR . '/lib/pages-global-functions.php';
	include_once CPS_HC_GEMS_DIR . '/pages/' . $page['menu_file'];

	cps_hc_gems_page_header();
	?>
	<div id="cps-settings">
		<div class="uk-grid uk-grid-small" uk-grid>
			<div class="uk-width-medium uk-visible@m">
				<?php cps_hc_gems_page_sidebar(); ?>
			</div>
			<div class="uk-width-expand">
				<?php cps_hc_gems_page_notifications(); ?>
				<div class="cps-card uk-card uk-card-default uk-card-hover uk-margin-bottom">
					<div class="uk-card-header">
						<div class="uk-grid-small uk-flex-middle" uk-grid>
							<div class="uk-width-expand">
								<h3 class="uk-card-title uk-margin-remove-bottom"><?php echo esc_html( $page['card_title'] ); ?></h3>
								<p class="uk-text-meta uk-margin-remove-top"><?php echo wp_kses_post( $page['description'] ); ?></p>
							</div>
							<?php cps_hc_gems_page_mobile_nav(); ?>
						</div>
					</div>
					<div class="uk-card-body uk-background-muted">
						<?php
						if ( function_exists( $page['renderer'] ) ) {
							call_user_func( $page['renderer'] );
						}
						?>
					</div>
					<?php cps_hc_gems_page_card_footer(); ?>
				</div>
				<?php cps_admin_footer( CPS_HC_GEMS_FILE ); ?>
			</div>
		</div>
	</div>
	<?php
	cps_hc_gems_page_footer();
}

