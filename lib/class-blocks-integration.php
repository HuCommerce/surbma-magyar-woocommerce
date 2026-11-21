<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || die;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Cart & Checkout Blocks integration bootstrap.
 */
class CPS_HC_Gems_Blocks_Integration implements IntegrationInterface {

	/**
	 * Initialize integration.
	 *
	 * @return void
	 */
	public function initialize() {
		// Script registration will be added module-by-module in Task 3+.
	}

	/**
	 * Integration slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'cps-hc-gems-blocks-integration';
	}

	/**
	 * Frontend script handles for Cart & Checkout blocks.
	 *
	 * @return array
	 */
	public function get_script_handles() {
		return [];
	}

	/**
	 * Editor script handles for Cart & Checkout blocks.
	 *
	 * @return array
	 */
	public function get_editor_script_handles() {
		return [];
	}

	/**
	 * Script data exposed to frontend scripts.
	 *
	 * @return array
	 */
	public function get_script_data() {
		return [];
	}
}
