<?php

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class CPS_HC_Gems_Blocks_Integration implements IntegrationInterface {

	public function get_name(): string {
		return 'hucommerce';
	}

	public function initialize(): void {
		wp_register_script(
			'cps-hc-gems-blocks-tax-number',
			CPS_HC_GEMS_URL . '/assets/js/blocks-tax-number.js',
			array(),
			CPS_HC_GEMS_VERSION,
			true
		);
	}

	public function get_script_handles(): array {
		$handles = array();

		$cps_hc_gems_options = get_option( 'surbma_hc_fields', array() );
		if ( is_array( $cps_hc_gems_options ) && 1 === (int) ( $cps_hc_gems_options['taxnumber'] ?? 0 ) ) {
			$handles[] = 'cps-hc-gems-blocks-tax-number';
		}

		return $handles;
	}

	public function get_editor_script_handles(): array {
		return array();
	}

	public function get_script_data(): array {
		return array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);
	}

}
