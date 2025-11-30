<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

function cps_hc_gems_directory_page() {
	include_once( SURBMA_HC_PLUGIN_DIR . '/pages/pages-global-functions.php');
	include_once( SURBMA_HC_PLUGIN_DIR . '/pages/menu-directory.php');

	cps_hc_gems_page_header();
	?>
	<div id="cps-settings">
		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-medium uk-visible@m">
				<?php cps_hc_gems_page_sidebar(); ?>
			</div>
			<div class="uk-width-expand">
				<?php cps_hc_gems_page_notifications(); ?>
				<div class="cps-card uk-card uk-card-default uk-card-hover uk-margin-bottom">
					<div class="uk-card-header">
						<div class="uk-grid-small uk-flex-middle" uk-grid>
							<div class="uk-width-expand">
								<h3 class="uk-card-title uk-margin-remove-bottom">HuCommerce <?php esc_html_e( 'Directory', 'surbma-magyar-woocommerce' ); ?></h3>
								<p class="uk-text-meta uk-margin-remove-top">Hasznos linkek minden WooCommerce webáruház tulajdonosnak. <strong>FIGYELEM!</strong> A linkek partner linkek, amik után jutalékot kaphatunk.</p>
							</div>
							<?php cps_hc_gems_page_mobile_nav(); ?>
						</div>
					</div>
					<div class="uk-card-body uk-background-muted">
						<?php cps_hc_gems_render_menu_directory(); ?>
					</div>
					<?php cps_hc_gems_page_card_footer(); ?>
				</div>
				<?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
			</div>
		</div>
	</div>
	<?php
	cps_hc_gems_page_footer();
}
