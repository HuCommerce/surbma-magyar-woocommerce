<?php

/**
 * Module: Withdrawal request (EU right of withdrawal)
 */

defined( 'ABSPATH' ) || exit;

require_once CPS_HC_GEMS_DIR . '/modules/withdrawal-request/storage.php';
require_once CPS_HC_GEMS_DIR . '/modules/withdrawal-request/post-type.php';

if ( is_admin() ) {
	require_once CPS_HC_GEMS_DIR . '/modules/withdrawal-request/admin.php';
}
