<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

function surbma_hc_mask( $str ) {
	if ( !$str || 'active' != SURBMA_HC_PLUGIN_LICENSE ) {
		return $str;
	}
	$str_length = strlen( $str );
	$visibleLength = 4;
	$maskedLength = $str_length - ( $visibleLength * 2 );
	return substr( $str, 0, $visibleLength ) . str_repeat( '&bull;', $maskedLength ) . substr( $str, $str_length - $visibleLength );
}

$license_status = get_option( 'surbma_hc_license_status', array() );

?>
<p><?php esc_html_e( 'Here you need to enter and activate your HuCommerce Pro API key. After successful activation, all HuCommerce Pro features will be available to you during your active subscription. In case of an expired subscription, the Pro features will be disabled!', 'surbma-magyar-woocommerce' ); ?></p>

<form class="uk-form-stacked" method="post" action="options.php">
	<?php settings_fields( 'surbma_hc_license_options' ); ?>
	<?php $license_options = get_option( 'surbma_hc_license', array() ); ?>
	<?php $home_url = wp_parse_url( get_option( 'home' ) ); ?>
	<?php $instance = $home_url['host'] ?? ''; ?>
	<?php $licensekeyValue = $license_options['licensekey'] ?? ''; ?>
	<?php $disabled = 'active' == SURBMA_HC_PLUGIN_LICENSE ? ' disabled' : ''; ?>
	<?php $inputType = defined( 'WP_DEBUG' ) && 1 == WP_DEBUG ? 'text' : 'hidden'; ?>

	<?php
		$products = array(
			'1135' => esc_attr__( 'HuCommerce Pro (annual subscription)', 'surbma-magyar-woocommerce' ),
			'4436' => esc_attr__( 'HuCommerce Pro – Multi 2 (annual subscription)', 'surbma-magyar-woocommerce' ),
			'1568' => esc_attr__( 'HuCommerce Pro – Multi 5 (annual subscription)', 'surbma-magyar-woocommerce' ),
			'1567' => esc_attr__( 'HuCommerce Pro – Multi 10 (annual subscription)', 'surbma-magyar-woocommerce' ),
			'2856' => esc_attr__( 'HuCommerce Pro (monthly subscription)', 'surbma-magyar-woocommerce' ),
			'4437' => esc_attr__( 'HuCommerce Pro (lifetime)', 'surbma-magyar-woocommerce' ),
			'1324' => esc_attr__( 'HuCommerce Pro - FREE TRIAL', 'surbma-magyar-woocommerce' )
		);

		if ( isset( $license_options['product_id'] ) && $license_options['product_id'] ) {
			$product_idValue = $license_options['product_id'];
		} else {
			$product_idValue = '1135';
		}
	?>
	<label class="uk-form-label" for="surbma_hc_license[product_id]"><?php esc_html_e( 'HuCommerce plan', 'surbma-magyar-woocommerce' ); ?>:</label>
	<select id="surbma_hc_license[product_id]" class="uk-select uk-form-large uk-margin-bottom" name="surbma_hc_license[product_id]"<?php echo esc_html( $disabled ); ?>>
		<?php foreach ( $products as $id => $name ) { ?>
			<option value="<?php echo esc_attr( $id ); ?>"<?php selected( $product_idValue, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php } ?>
	</select>

	<?php $instanceValue = isset( $license_options['instance'] ) && $license_options['instance'] ? $license_options['instance'] : $instance; ?>
	<input id="surbma_hc_license[instance]" class="uk-input uk-form-large uk-margin-bottom" type="<?php echo esc_attr( $inputType ); ?>" name="surbma_hc_license[instance]" value="<?php echo esc_attr( $instanceValue ); ?>" placeholder="Instance" style="font-family: monospace;" readonly />

	<div class="uk-grid-small" uk-grid>

		<label class="uk-form-label uk-width-1-1" for="surbma_hc_license[licensekey]" style="line-height: 1;"><?php esc_html_e( 'API key', 'surbma-magyar-woocommerce' ); ?>:</label>
		<div class="uk-form-controls uk-width-expand@xl">
			<div class="uk-inline uk-width-expand">
				<span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
				<input id="surbma_hc_license[licensekey]" class="uk-input uk-form-large" type="text" name="surbma_hc_license[licensekey]" value="<?php echo esc_attr( wp_unslash( surbma_hc_mask( $licensekeyValue ) ) ); ?>" placeholder="<?php esc_attr_e( 'API key', 'surbma-magyar-woocommerce' ); ?>" style="font-family: monospace;"<?php echo esc_html( $disabled ); ?> />
			</div>
		</div>

		<div class="uk-width-auto@xl">
			<?php if ( 'active' == SURBMA_HC_PLUGIN_LICENSE ) { ?>
				<input type="submit" class="uk-button uk-button-danger uk-button-large uk-width-auto@l" value="<?php esc_attr_e( 'Deactivating', 'surbma-magyar-woocommerce' ); ?>" />
			<?php } else { ?>
				<input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-auto@l" value="<?php esc_attr_e( 'Activating', 'surbma-magyar-woocommerce' ); ?>" />
			<?php } ?>
		</div>

	</div>
</form>

<?php
	if ( 'active' == SURBMA_HC_PLUGIN_LICENSE ) {
		$licensestatus = '<span class="uk-label uk-label-success">' . esc_html__( 'Active', 'surbma-magyar-woocommerce' ) . '</span>';
	} elseif ( 'invalid' == SURBMA_HC_PLUGIN_LICENSE || ( $licensekeyValue ) ) {
		$licensestatus = '<span class="uk-label uk-label-danger">' . esc_html__( 'Invalid API key', 'surbma-magyar-woocommerce' ) . '</span>';
	} else {
		$licensestatus = '<span class="uk-label">' . esc_html__( 'Not activated', 'surbma-magyar-woocommerce' ) . '</span>';
	}
?>
<p><strong><?php esc_html_e( 'API status', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php echo wp_kses_post( $licensestatus ); ?></p>
<hr>
<p><a class="uk-button uk-button-primary" href="<?php echo esc_url( add_query_arg( 'hc-request', 'status' ) ); ?>" uk-tooltip="title: <?php esc_attr_e( 'Sync your API key manually, if API status is not updated automatically.', 'surbma-magyar-woocommerce' ); ?>; pos: top"><span style="position: relative;top: -2px;" uk-icon="icon: refresh; ratio: .8"></span> <?php esc_html_e( 'Sync API', 'surbma-magyar-woocommerce' ); ?></a> <a class="uk-button uk-button-primary" href="https://www.hucommerce.hu/fiokom/api-keys/" target="_blank" uk-tooltip="title: <?php esc_attr_e( 'Manage your API key in your Account on the HuCommerce.hu website.', 'surbma-magyar-woocommerce' ); ?>; pos: top"><span style="position: relative;top: -2px;" uk-icon="icon: cog; ratio: .8"></span> <?php esc_html_e( 'Manage API', 'surbma-magyar-woocommerce' ); ?></a> <a class="uk-button uk-button-primary" href="https://www.hucommerce.hu/fiokom/elofizetesek/" target="_blank" uk-tooltip="title: <?php esc_attr_e( 'Manage your subscriptions in your Account on the HuCommerce.hu website.', 'surbma-magyar-woocommerce' ); ?>; pos: top"><span style="position: relative;top: -2px;" uk-icon="icon: credit-card; ratio: .8"></span> <?php esc_html_e( 'Manage subscription', 'surbma-magyar-woocommerce' ); ?></a></p>

<div class="uk-card uk-card-default uk-card-small uk-card-body">
<h4>HuCommerce DEV mód</h4>
<p>A DEV mód, azaz fejlesztői mód lehetőséget arra, hogy egy nem élesített weboldalon az API kulcs aktiválása nélkül használhasd a HuCommerce Pro összes modulját és funkcióját.</p>
	<p><strong>A következő módokon lehet aktiválni a HuCommerce DEV módot:</strong></p>
	<ul class="uk-list uk-list-disc">
		<li><strong>HA</strong> a webáruház domain végződése (TLD) <code>.local</code> vagy <code>.dev</code>, akkor automatikusan aktiválódik a DEV mód.</li>
		<li><strong>HA</strong> aktiválod a <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=site-visibility' ) ); ?>">WooCommerce "Coming Soon"</a> módját.</li>
		<li><strong>VAGY</strong> ha a <code>WP_ENVIRONMENT_TYPE</code> globális változóval lett meghatározva az installáció típusa (ami nem 'production') a <code>wp-config.php</code> fájlban.</li>
	</ul>
</div>

<?php if ( $licensekeyValue ) { ?>
<h4><?php esc_html_e( 'API key related data', 'surbma-magyar-woocommerce' ); ?></h4>
<ul class="uk-list">
	<li><strong><?php esc_html_e( 'Total activations purchased', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php echo wp_kses_post( intval( $license_status['total_activations_purchased'] ) ); ?></li>
	<li><strong><?php esc_html_e( 'Total activations', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php echo wp_kses_post( intval( $license_status['total_activations'] ) ); ?></li>
	<li><strong><?php esc_html_e( 'Activations remaining', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php echo wp_kses_post( intval( $license_status['activations_remaining'] ) ); ?></li>
</ul>
<?php }

if ( defined( 'WP_DEBUG' ) && 1 == WP_DEBUG ) {
	echo '<h4>' . esc_html__( 'License options', 'surbma-magyar-woocommerce' ) . '</h4>';
	echo '<textarea id="hc-gems-license-options" class="uk-textarea" cols="50" rows="8" style="background: #000;" readonly>';
	print_r( $license_options ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
	echo '</textarea>';
	echo '<h4>' . esc_html__( 'License status', 'surbma-magyar-woocommerce' ) . '</h4>';
	echo '<textarea id="hc-gems-license-status" class="uk-textarea" cols="50" rows="12" style="background: #000;" readonly>';
	print_r( $license_status ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
	echo '</textarea>';
}
