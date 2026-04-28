<?php

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class CPS_HC_Gems_Blocks_Integration implements IntegrationInterface {

	public function get_name(): string {
		return 'hucommerce';
	}

	public function initialize(): void {
		// Script handles are registered via wp_register_script before this runs.
	}

	public function get_script_handles(): array {
		return array();
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
