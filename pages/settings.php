<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function() {
	register_setting(
		'surbma_hc_options',
		'surbma_hc_fields',
		'surbma_hc_fields_validate'
	);
} );

global $returntoshopcartposition_options;
global $returntoshopcheckoutposition_options;
global $legalconfirmationsposition_options;

$returntoshopcartposition_options = array(
	'beforecarttable' => array(
		'value' => 'beforecarttable',
		'label' => __( 'Before Product table (with text)', 'surbma-magyar-woocommerce' )
	),
	'aftercarttable' => array(
		'value' => 'aftercarttable',
		'label' => __( 'After Product table (with text)', 'surbma-magyar-woocommerce' )
	),
	'cartactions' => array(
		'value' => 'cartactions',
		'label' => __( 'Next to Update cart button (without text)', 'surbma-magyar-woocommerce' )
	),
	'proceedtocheckout' => array(
		'value' => 'proceedtocheckout',
		'label' => __( 'Under Proceed to checkout button (without text)', 'surbma-magyar-woocommerce' )
	)
);

$returntoshopcheckoutposition_options = array(
	'nocheckout' => array(
		'value' => 'nocheckout',
		'label' => __( 'Don\'t show on Checkout page', 'surbma-magyar-woocommerce' )
	),
	'beforecheckoutform' => array(
		'value' => 'beforecheckoutform',
		'label' => __( 'Before Checkout form (with text)', 'surbma-magyar-woocommerce' )
	),
	'aftercheckoutform' => array(
		'value' => 'aftercheckoutform',
		'label' => __( 'After Checkout form (with text)', 'surbma-magyar-woocommerce' )
	)
);

$legalconfirmationsposition_options = array(
	'woocommerce_review_order_before_submit' => array(
		'value' => 'woocommerce_review_order_before_submit',
		'label' => __( 'Before Place order button', 'surbma-magyar-woocommerce' )
	),
	'woocommerce_after_order_notes' => array(
		'value' => 'woocommerce_after_order_notes',
		'label' => __( 'After Order notes field', 'surbma-magyar-woocommerce' )
	)
);

function surbma_hc_settings_page() {
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;
	global $legalconfirmationsposition_options;

	$allowed_html = array(
		'option' => array(
			'style'  => array(),
			'selected'  => array(),
			'value'  => array(),
		),
		'div' => array(
			'class'  => array(),
			'uk-alert'  => array(),
		),
	);

	// * HUCOMMERCE START
	$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
	$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );
	// * HUCOMMERCE END

	?>
<div class="cps-admin surbma-hc-settings-page">
	<div class="wrap">
		<?php if ( isset( $_GET['settings-updated'] ) && true == $_GET['settings-updated'] ) { ?>
			<div class="updated notice is-dismissible"><p><strong><?php esc_html_e( 'Settings saved.' ); ?></strong></p></div>
		<?php } ?>

		<?php // HuCommerce Pro promo ?>
		<?php if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) { ?>
			<div class="uk-card uk-card-default uk-card-hover uk-card-small uk-grid-collapse uk-margin uk-animation-shake">
				<div class="uk-card-header uk-background-muted">
					<h3 class="uk-card-title uk-float-left uk-margin-remove">HuCommerce Pro</h3>
					<a href="https://www.hucommerce.hu" class="uk-float-right uk-visible@s" target="_blank"><img src="<?php echo esc_url( SURBMA_HC_PLUGIN_URL ); ?>/assets/images/hucommerce-logo.png" alt="HuCommerce" class="alignright"></a>
				</div>
				<div class="uk-card-body">
					<p><strong>FONTOS!</strong> Ez a HuCommerce legutolsó nem fizetős verziója, amiben az összes eddigi modul és funkció elérhető. Amennyiben újabb verzióra frissítesz, bizonyos funkciók nem lesznek elérhetők mentés után. Ez egy stabil és biztonságos verzió, ami további fejlesztéseket nem kap, maximum biztonsági frissítéseket. Minden további fejlesztés a HuCommerce Pro verziójában kap helyet, amire mindenképpen érdemes előfizetni, hiszen nem csak javításokat tartalmaz, hanem rengeteg új és hasznos funkcióval bővül szinte minden hónapban.</p>
					<p><a href="https://www.hucommerce.hu/bovitmenyek/hucommerce/" target="_blank">HuCommerce Pro megismerése</a></p>
				</div>
				<div class="uk-card-footer uk-background-muted">
					<p>
						<a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="uk-button uk-button-default uk-button-primary" target="_blank"><span class="dashicons dashicons-cart" style="position: relative;top: 8px;left: -6px;"></span> HuCommerce Pro megvásárlása</a>
					</p>
				</div>
			</div>
		<?php } ?>

		<?php // HuCommerce partner banner ?>
		<?php
		if ( 'free' == SURBMA_HC_PLUGIN_VERSION ) {
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
					echo '<h3 class="uk-card-title">' . esc_html( $item_ajanlatok->get_title() ) . '</h3>';
					echo wp_kses_post( $item_ajanlatok->get_content() );
					echo '<a href="' . esc_url( $item_ajanlatok->get_permalink() ) . '?utm_source=client-site&utm_medium=hucommerce-banner&utm_campaign=' . urlencode( $item_ajanlatok->get_title() ) . '&utm_content=hucommerce-top" class="uk-button uk-button-default" target="_blank"><span class="dashicons dashicons-external" style="position: relative;top: 8px;left: -6px;"></span> ' . esc_html__( 'View offer', 'surbma-magyar-woocommerce' ) . '</a>';
					echo '<span class="uk-label uk-label-warning uk-position-small uk-position-bottom-right">' . esc_html__( 'Ad', 'surbma-magyar-woocommerce' ) . '</span>';
					echo '<a href="#" class="uk-position-small uk-position-top-right" uk-close uk-toggle="target: #hucommerce-partner-banner-top"></a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</li>';
				endforeach;
			endif;
		}
		?>

		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-3-4@l">
				<form class="uk-form-horizontal" method="post" action="options.php">
					<?php settings_fields( 'surbma_hc_options' ); ?>
					<?php $options = get_option( 'surbma_hc_fields' ); ?>

					<div class="uk-card uk-card-small uk-card-default uk-card-hover uk-margin-bottom">
						<div class="uk-card-header uk-background-muted">
							<h3 class="uk-card-title"><?php esc_html_e( 'HuCommerce modules', 'surbma-magyar-woocommerce' ); ?> <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #modules"></a></h3>
						</div>
						<div id="modules" class="uk-card-body">

							<div class="uk-alert-primary" uk-alert>
								<a class="uk-alert-close" uk-close></a>
								<p><?php esc_html_e( 'Modules are small functions to enhance your WooCommerce website. Activate Modules here and set the options on the next block.', 'surbma-magyar-woocommerce' ); ?></p>
							</div>

							<?php // * HUCOMMERCE START ?>
								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Fixes for Hungarian language', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Fixes the name formats in Hungarian. Changes the order of Last name and First name.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $huformatfixValue = isset( $options['huformatfix'] ) ? $options['huformatfix'] : 0; ?>
												<input id="surbma_hc_fields[huformatfix]" name="surbma_hc_fields[huformatfix]" type="checkbox" value="1" <?php checked( '1', $huformatfixValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Hide County field if Country is Hungary', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Using County for Hungarian addresses is very uncommon in Hungary.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $nocountyValue = isset( $options['nocounty'] ) ? $options['nocounty'] : 0; ?>
												<input id="surbma_hc_fields[nocounty]" name="surbma_hc_fields[nocounty]" type="checkbox" value="1" <?php checked( '1', $nocountyValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Autofill City after Postcode is given', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'On the Checkout page the City field be automatically filled, when Postcode is entered by the customer.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $autofillcityValue = isset( $options['autofillcity'] ) ? $options['autofillcity'] : 0; ?>
												<input id="surbma_hc_fields[autofillcity]" name="surbma_hc_fields[autofillcity]" type="checkbox" value="1" <?php checked( '1', $autofillcityValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( '- Billing VAT number <br>- Billing Postcode <br>- Billing Phone <br>- Shipping Postcode', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $maskcheckoutfieldsValue = isset( $options['maskcheckoutfields'] ) ? $options['maskcheckoutfields'] : 0; ?>
												<input id="maskcheckoutfields" name="surbma_hc_fields[maskcheckoutfields]" type="checkbox" value="1" <?php checked( '1', $maskcheckoutfieldsValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Check field values', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( '- Billing VAT number <br>- Billing Postcode <br>- Billing Phone <br>- Shipping Postcode', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $validatecheckoutfieldsValue = isset( $options['validatecheckoutfields'] ) ? $options['validatecheckoutfields'] : 0; ?>
												<input id="validatecheckoutfields" name="surbma_hc_fields[validatecheckoutfields]" type="checkbox" value="1" <?php checked( '1', $validatecheckoutfieldsValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Hungarian translation fixes', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Temporary fixes for Hungarian translations, till the official translation doesn\’t include or missing some strings.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $translationsValue = isset( $options['translations'] ) ? $options['translations'] : 0; ?>
												<input id="surbma_hc_fields[translations]" name="surbma_hc_fields[translations]" type="checkbox" value="1" <?php checked( '1', $translationsValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>

								<hr>
							<?php // * HUCOMMERCE END ?>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Tax number field', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Additional Tax field for Company details at Checkout.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $taxnumberValue = isset( $options['taxnumber'] ) ? $options['taxnumber'] : 0; ?>
											<input id="surbma_hc_fields[taxnumber]" name="surbma_hc_fields[taxnumber]" type="checkbox" value="1" <?php checked( '1', $taxnumberValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
								<?php // * HUCOMMERCE START ?>
									<?php $szamlazzhu_vatnumber_Value = isset( $szamlazzhu_options['vat_number_form'] ) ? $szamlazzhu_options['vat_number_form'] : false; ?>
									<?php if ( class_exists( 'WC_Szamlazz' ) && 'yes' == $szamlazzhu_vatnumber_Value ) { ?>
										<div class="uk-alert-danger" uk-alert>
											<a class="uk-alert-close" uk-close></a>
											<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Szamlazz.hu & WooCommerce plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\’s settings.', 'surbma-magyar-woocommerce' ); ?></p>
										</div>
									<?php } ?>
									<?php $billingo_vatnumber_Value = isset( $billingo_options['vat_number_form'] ) ? $billingo_options['vat_number_form'] : false; ?>
									<?php if ( class_exists( 'WC_Billingo_Plus' ) && 'yes' == $billingo_vatnumber_Value ) { ?>
										<div class="uk-alert-danger" uk-alert>
											<a class="uk-alert-close" uk-close></a>
											<p><?php esc_html_e( 'A Tax number field is already added by the Woo Billingo Plus plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\’s settings.', 'surbma-magyar-woocommerce' ); ?></p>
										</div>
									<?php } ?>
									<?php if ( class_exists( 'WC_Billingo' ) && 'yes' == get_option('wc_billingo_vat_number_form') ) { ?>
										<div class="uk-alert-danger" uk-alert>
											<a class="uk-alert-close" uk-close></a>
											<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Billingo & WooCommerce plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\’s settings.', 'surbma-magyar-woocommerce' ); ?></p>
										</div>
									<?php } ?>
									<?php if ( class_exists( 'WC_Billingo' ) && 'yes' == get_option('wc_billingo_vat_number_form_checkbox_custom') ) { ?>
										<div class="uk-alert-danger" uk-alert>
											<a class="uk-alert-close" uk-close></a>
											<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Billingo & WooCommerce plugin\’s custom field option. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the "Egyedi meta mezőt használok adószámhoz" option at the other plugin\’s settings.', 'surbma-magyar-woocommerce' ); ?></p>
										</div>
									<?php } ?>
								<?php // * HUCOMMERCE END ?>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Checkout page customizations', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Extra fields and other customizations on the Checkout page.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $module_checkoutValue = isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0; ?>
											<input id="module-checkout" name="surbma_hc_fields[module-checkout]" type="checkbox" value="1" <?php checked( '1', $module_checkoutValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Plus/minus quantity buttons', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Shows plus/minus quantity buttons for products.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $plusminusValue = isset( $options['plusminus'] ) ? $options['plusminus'] : 0; ?>
											<input id="surbma_hc_fields[plusminus]" name="surbma_hc_fields[plusminus]" type="checkbox" value="1" <?php checked( '1', $plusminusValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Automatic Cart update', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'It will automatically update the cart, when customer changes the quantity on the Cart page.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $updatecartValue = isset( $options['updatecart'] ) ? $options['updatecart'] : 0; ?>
											<input id="surbma_hc_fields[updatecart]" name="surbma_hc_fields[updatecart]" type="checkbox" value="1" <?php checked( '1', $updatecartValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Continue shopping buttons', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'A Continue shopping button on Cart and/or Checkout pages, that will bring customer to Shop page.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $returntoshopValue = isset( $options['returntoshop'] ) ? $options['returntoshop'] : 0; ?>
											<input id="surbma_hc_fields[returntoshop]" name="surbma_hc_fields[returntoshop]" type="checkbox" value="1" <?php checked( '1', $returntoshopValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Login and registration redirection', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Set custom landing pages after login and/or registration.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $loginregistrationredirectValue = isset( $options['loginregistrationredirect'] ) ? $options['loginregistrationredirect'] : 0; ?>
											<input id="surbma_hc_fields[loginregistrationredirect]" name="surbma_hc_fields[loginregistrationredirect]" type="checkbox" value="1" <?php checked( '1', $loginregistrationredirectValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Free shipping notification', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'A notification on the Cart page to let customer know, how much total purchase is missing to get free shipping.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $freeshippingnoticeValue = isset( $options['freeshippingnotice'] ) ? $options['freeshippingnotice'] : 0; ?>
											<input id="surbma_hc_fields[freeshippingnotice]" name="surbma_hc_fields[freeshippingnotice]" type="checkbox" value="1" <?php checked( '1', $freeshippingnoticeValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Custom Terms & Conditions and Privacy Policy checkboxes on Checkout page.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $legalcheckoutValue = isset( $options['legalcheckout'] ) ? $options['legalcheckout'] : 0; ?>
											<input id="surbma_hc_fields[legalcheckout]" name="surbma_hc_fields[legalcheckout]" type="checkbox" value="1" <?php checked( '1', $legalcheckoutValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>
						</div>
						<div class="uk-card-footer uk-background-muted">
							<p><input type="submit" class="uk-button uk-button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
						</div>
					</div>

					<div class="uk-card uk-card-small uk-card-default uk-card-hover uk-margin-bottom">
						<div class="uk-card-header uk-background-muted">
							<h3 class="uk-card-title"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?> <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #modulesettings"></a></h3>
						</div>
						<div id="modulesettings" class="uk-card-body">

							<div class="uk-alert-primary" uk-alert>
								<a class="uk-alert-close" uk-close></a>
								<p><?php esc_html_e( 'Here are the settings for all activated modules.', 'surbma-magyar-woocommerce' ); ?></p>
							</div>

							<?php // * HUCOMMERCE START ?>
								<h4 class="uk-heading-divider"><?php esc_html_e( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ); ?></h4>

								<div class="uk-margin">
									<div class="uk-form-label"><?php esc_html_e( 'Masking with placeholder', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
									<div class="uk-form-controls">
										<p class="switch-wrap">
											<label class="switch">
												<?php $maskcheckoutfieldsplaceholderValue = isset( $options['maskcheckoutfieldsplaceholder'] ) ? $options['maskcheckoutfieldsplaceholder'] : 0; ?>
												<input id="maskcheckoutfieldsplaceholder" name="surbma_hc_fields[maskcheckoutfieldsplaceholder]" type="checkbox" value="1" <?php checked( '1', $maskcheckoutfieldsplaceholderValue ); ?> />
												<span class="slider round"></span>
											</label>
										</p>
									</div>
								</div>
							<?php // * HUCOMMERCE END ?>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Tax number field', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Add placeholder to this field', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $taxnumberplaceholderValue = isset( $options['taxnumberplaceholder'] ) ? $options['taxnumberplaceholder'] : 0; ?>
											<input id="taxnumberplaceholder" name="surbma_hc_fields[taxnumberplaceholder]" type="checkbox" value="1" <?php checked( '1', $taxnumberplaceholderValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Checkout page customizations', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Conditional display of Company fields', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $billingcompanycheckValue = isset( $options['billingcompanycheck'] ) ? $options['billingcompanycheck'] : 0; ?>
											<input id="billingcompanycheck" name="surbma_hc_fields[billingcompanycheck]" type="checkbox" value="1" <?php checked( '1', $billingcompanycheckValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Hide Country field', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $nocountryValue = isset( $options['nocountry'] ) ? $options['nocountry'] : 0; ?>
											<input id="nocountry" name="surbma_hc_fields[nocountry]" type="checkbox" value="1" <?php checked( '1', $nocountryValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Hide Order notes field', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $noordercommentsValue = isset( $options['noordercomments'] ) ? $options['noordercomments'] : 0; ?>
											<input id="noordercomments" name="surbma_hc_fields[noordercomments]" type="checkbox" value="1" <?php checked( '1', $noordercommentsValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Inline Company and Tax number fields', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $companytaxnumberpairValue = isset( $options['companytaxnumberpair'] ) ? $options['companytaxnumberpair'] : 0; ?>
											<input id="companytaxnumberpair" name="surbma_hc_fields[companytaxnumberpair]" type="checkbox" value="1" <?php checked( '1', $companytaxnumberpairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Inline Postcode and City fields', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $postcodecitypairValue = isset( $options['postcodecitypair'] ) ? $options['postcodecitypair'] : 0; ?>
											<input id="postcodecitypair" name="surbma_hc_fields[postcodecitypair]" type="checkbox" value="1" <?php checked( '1', $postcodecitypairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Inline Phone and Email fields', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $phoneemailpairValue = isset( $options['phoneemailpair'] ) ? $options['phoneemailpair'] : 0; ?>
											<input id="phoneemailpair" name="surbma_hc_fields[phoneemailpair]" type="checkbox" value="1" <?php checked( '1', $phoneemailpairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Make Email field the first field', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $emailtothetopValue = isset( $options['emailtothetop'] ) ? $options['emailtothetop'] : 0; ?>
											<input id="emailtothetop" name="surbma_hc_fields[emailtothetop]" type="checkbox" value="1" <?php checked( '1', $emailtothetopValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Continue shopping buttons', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Button position on Cart page', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<select class="uk-select" name="surbma_hc_fields[returntoshopcartposition]">
										<?php
										$returntoshopcartpositionValue = isset( $options['returntoshopcartposition'] ) ? $options['returntoshopcartposition'] : 'cartactions';
										$selected = $returntoshopcartpositionValue;
										$p = '';
										$r = '';

										foreach ( $returntoshopcartposition_options as $option ) {
											$label = $option['label'];
											// Make default first in list
											if ( $selected == $option['value'] ) {
												$p = PHP_EOL . '<option style="padding-right: 10px;" selected="selected" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											} else {
												$r .= PHP_EOL . '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											}
										}
										echo wp_kses( $p, $allowed_html ) . wp_kses( $r, $allowed_html );
										?>
									</select>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Button position on Checkout page', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<select class="uk-select" name="surbma_hc_fields[returntoshopcheckoutposition]">
										<?php
										$returntoshopcheckoutpositionValue = isset( $options['returntoshopcheckoutposition'] ) ? $options['returntoshopcheckoutposition'] : 'nocheckout';
										$selected = $returntoshopcheckoutpositionValue;
										$p = '';
										$r = '';

										foreach ( $returntoshopcheckoutposition_options as $option ) {
											$label = $option['label'];
											// Make default first in list
											if ( $selected == $option['value'] ) {
												$p = PHP_EOL . '<option style="padding-right: 10px;" selected="selected" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											} else {
												$r .= PHP_EOL . '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											}
										}
										echo wp_kses( $p, $allowed_html ) . wp_kses( $r, $allowed_html );
										?>
									</select>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[returntoshopmessage]"><?php esc_html_e( 'Message text', 'surbma-magyar-woocommerce' ); ?></label>
								<div class="uk-form-controls">
									<?php $returntoshopmessageValue = isset( $options['returntoshopmessage'] ) ? $options['returntoshopmessage'] : __( 'Would you like to continue shopping?', 'surbma-magyar-woocommerce' ); ?>
									<input id="surbma_hc_fields[returntoshopmessage]" class="uk-input" type="text" name="surbma_hc_fields[returntoshopmessage]" value="<?php echo esc_attr( wp_unslash( $returntoshopmessageValue ) ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Login and registration redirection', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[loginredirecturl]"><?php esc_html_e( 'Redirection URL after Login', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $loginredirecturlValue = isset( $options['loginredirecturl'] ) ? $options['loginredirecturl'] : wc_get_page_permalink( 'shop' ); ?>
									<input id="surbma_hc_fields[loginredirecturl]" class="uk-input" type="text" name="surbma_hc_fields[loginredirecturl]" value="<?php echo esc_attr( wp_unslash( $loginredirecturlValue ) ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[registrationredirecturl]"><?php esc_html_e( 'Redirection URL after Registration', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $registrationredirecturlValue = isset( $options['registrationredirecturl'] ) ? $options['registrationredirecturl'] : wc_get_page_permalink( 'shop' ); ?>
									<input id="surbma_hc_fields[registrationredirecturl]" class="uk-input" type="text" name="surbma_hc_fields[registrationredirecturl]" value="<?php echo esc_attr( wp_unslash( $registrationredirecturlValue ) ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Free shipping notification', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[freeshippingnoticemessage]"><?php esc_html_e( 'Message text', 'surbma-magyar-woocommerce' ); ?></label>
								<div class="uk-form-controls">
									<?php $freeshippingnoticemessageValue = isset( $options['freeshippingnoticemessage'] ) && ( $options['freeshippingnoticemessage'] ) ? $options['freeshippingnoticemessage'] : __( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' ); ?>
									<input id="surbma_hc_fields[freeshippingnoticemessage]" class="uk-input" type="text" name="surbma_hc_fields[freeshippingnoticemessage]" value="<?php echo esc_attr( wp_unslash( $freeshippingnoticemessageValue ) ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider"><?php esc_html_e( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ); ?></h4>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Save customer IP address on registration', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If enabled, the customer\'s IP address will be saved in profile after registration.' , 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $regipValue = isset( $options['regip'] ) ? $options['regip'] : 0; ?>
											<input id="surbma_hc_fields[regip]" name="surbma_hc_fields[regip]" type="checkbox" value="1" <?php checked( '1', $regipValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[regacceptpp]"><?php esc_html_e( 'Privacy Policy checkbox text on Registration form', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $regacceptppValue = isset( $options['regacceptpp'] ) ? $options['regacceptpp'] : esc_attr( __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' ) ); ?>
									<textarea id="surbma_hc_fields[regacceptpp]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[regacceptpp]"><?php echo esc_html( wp_unslash( $regacceptppValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label"><?php esc_html_e( 'Legal confirmation checkboxes position on Checkout page', 'surbma-magyar-woocommerce' ); ?></div>
								<div class="uk-form-controls">
									<select class="uk-select" name="surbma_hc_fields[legalconfirmationsposition]">
										<?php
										$legalconfirmationspositionValue = isset( $options['legalconfirmationsposition'] ) ? $options['legalconfirmationsposition'] : 'revieworderbeforesubmit';
										$selected = $legalconfirmationspositionValue;
										$p = '';
										$r = '';

										foreach ( $legalconfirmationsposition_options as $option ) {
											$label = $option['label'];
											// Make default first in list
											if ( $selected == $option['value'] ) {
												$p = PHP_EOL . '<option style="padding-right: 10px;" selected="selected" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											} else {
												$r .= PHP_EOL . '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $label ) . '</option>';
											}
										}
										echo wp_kses( $p, $allowed_html ) . wp_kses( $r, $allowed_html );
										?>
									</select>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[legalcheckouttitle]"><?php esc_html_e( 'Section title on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'Title above the checkbox. If empty, then no title will be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $legalcheckouttitleValue = isset( $options['legalcheckouttitle'] ) ? $options['legalcheckouttitle'] : __( 'Legal confirmations', 'surbma-magyar-woocommerce' ); ?>
									<input id="surbma_hc_fields[legalcheckouttitle]" class="uk-input" type="text" name="surbma_hc_fields[legalcheckouttitle]" value="<?php echo esc_attr( wp_unslash( $legalcheckouttitleValue ) ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[accepttos]"><?php esc_html_e( 'Terms of Service checkbox text on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $accepttosValue = isset( $options['accepttos'] ) ? $options['accepttos'] : esc_attr( __( 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'surbma-magyar-woocommerce' ) ); ?>
									<textarea id="surbma_hc_fields[accepttos]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[accepttos]"><?php echo esc_html( wp_unslash( $accepttosValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptpp]"><?php esc_html_e( 'Privacy Policy checkbox text on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $acceptppValue = isset( $options['acceptpp'] ) ? $options['acceptpp'] : esc_attr( __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' ) ); ?>
									<textarea id="surbma_hc_fields[acceptpp]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[acceptpp]"><?php echo esc_html( wp_unslash( $acceptppValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptcustom1label]"><?php esc_html_e( 'Custom 1 checkbox label on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $legalcheckouttitleValue = isset( $options['acceptcustom1label'] ) ? $options['acceptcustom1label'] : ''; ?>
									<input id="surbma_hc_fields[acceptcustom1label]" class="uk-input" type="text" name="surbma_hc_fields[acceptcustom1label]" value="<?php echo esc_attr( wp_unslash( $legalcheckouttitleValue ) ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptcustom1]"><?php esc_html_e( 'Custom 1 checkbox text on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $acceptppValue = isset( $options['acceptcustom1'] ) ? $options['acceptcustom1'] : ''; ?>
									<textarea id="surbma_hc_fields[acceptcustom1]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[acceptcustom1]"><?php echo esc_html( wp_unslash( $acceptppValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptcustom2label]"><?php esc_html_e( 'Custom 2 checkbox label on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $legalcheckouttitleValue = isset( $options['acceptcustom2label'] ) ? $options['acceptcustom2label'] : ''; ?>
									<input id="surbma_hc_fields[acceptcustom2label]" class="uk-input" type="text" name="surbma_hc_fields[acceptcustom2label]" value="<?php echo esc_attr( wp_unslash( $legalcheckouttitleValue ) ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptcustom2]"><?php esc_html_e( 'Custom 2 checkbox text on Checkout page', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $acceptppValue = isset( $options['acceptcustom2'] ) ? $options['acceptcustom2'] : ''; ?>
									<textarea id="surbma_hc_fields[acceptcustom2]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[acceptcustom2]"><?php echo esc_html( wp_unslash( $acceptppValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[beforeorderbuttonmessage]"><?php esc_html_e( 'Custom text before Place order button', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'This text will be displayed just above the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $beforeorderbuttonmessageValue = isset( $options['beforeorderbuttonmessage'] ) ? $options['beforeorderbuttonmessage'] : null; ?>
									<textarea id="surbma_hc_fields[beforeorderbuttonmessage]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[beforeorderbuttonmessage]"><?php echo esc_html( wp_unslash( $beforeorderbuttonmessageValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[afterorderbuttonmessage]"><?php esc_html_e( 'Custom text after Place order button', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: <?php esc_attr_e( 'This text will be displayed just under the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' ); ?>; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $afterorderbuttonmessageValue = isset( $options['afterorderbuttonmessage'] ) ? $options['afterorderbuttonmessage'] : null; ?>
									<textarea id="surbma_hc_fields[afterorderbuttonmessage]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[afterorderbuttonmessage]"><?php echo esc_html( wp_unslash( $afterorderbuttonmessageValue ) ); ?></textarea>
									<p class="uk-text-meta"><?php esc_html_e( 'HTML tags are allowed', 'surbma-magyar-woocommerce' ); ?></p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<label class="uk-form-label"><?php esc_html_e( 'Allowed HTML tags', 'surbma-magyar-woocommerce' ); ?></label>
								<div class="uk-form-controls">
									<pre><?php echo allowed_tags(); ?></pre>
								</div>
							</div>
						</div>
						<div class="uk-card-footer uk-background-muted">
							<p><input type="submit" class="uk-button uk-button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
						</div>
					</div>
				</form>
			</div>
			<div class="uk-width-1-4@l">
				<?php cps_hc_gems_admin_sidebar(); ?>
			</div>
		</div>
		<div class="uk-margin-bottom" id="bottom"></div>
	</div>
	<?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
</div>
<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function surbma_hc_fields_validate( $input ) {
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;
	global $legalconfirmationsposition_options;

	$options = get_option( 'surbma_hc_fields' );

	// * HUCOMMERCE START
	$input['huformatfix'] = isset( $input['huformatfix'] ) && 1 == $input['huformatfix'] ? 1 : 0;
	$input['nocounty'] = isset( $input['nocounty'] ) && 1 == $input['nocounty'] ? 1 : 0;
	$input['autofillcity'] = isset( $input['autofillcity'] ) && 1 == $input['autofillcity'] ? 1 : 0;
	$input['translations'] = isset( $input['translations'] ) && 1 == $input['translations'] ? 1 : 0;
	$input['maskcheckoutfields'] = isset( $input['maskcheckoutfields'] ) && 1 == $input['maskcheckoutfields'] ? 1 : 0;
	$input['validatecheckoutfields'] = isset( $input['validatecheckoutfields'] ) && 1 == $input['validatecheckoutfields'] ? 1 : 0;
	$input['maskcheckoutfieldsplaceholder'] = isset( $input['maskcheckoutfieldsplaceholder'] ) && 1 == $input['maskcheckoutfieldsplaceholder'] ? 1 : 0;
	// * HUCOMMERCE END

	// Checkbox validation.
	$input['taxnumber'] = isset( $input['taxnumber'] ) && 1 == $input['taxnumber'] ? 1 : 0;
	$input['module-productsettings'] = isset( $input['module-productsettings'] ) && 1 == $input['module-productsettings'] ? 1 : 0;
	$input['addtocartonarchive'] = isset( $input['addtocartonarchive'] ) && 1 == $input['addtocartonarchive'] ? 1 : 0;
	$input['productsubtitle'] = isset( $input['productsubtitle'] ) && 1 == $input['productsubtitle'] ? 1 : 0;
	$input['module-checkout'] = isset( $input['module-checkout'] ) && 1 == $input['module-checkout'] ? 1 : 0;
	$input['plusminus'] = isset( $input['plusminus'] ) && 1 == $input['plusminus'] ? 1 : 0;
	$input['updatecart'] = isset( $input['updatecart'] ) && 1 == $input['updatecart'] ? 1 : 0;
	$input['returntoshop'] = isset( $input['returntoshop'] ) && 1 == $input['returntoshop'] ? 1 : 0;
	$input['loginregistrationredirect'] = isset( $input['loginregistrationredirect'] ) && 1 == $input['loginregistrationredirect'] ? 1 : 0;
	$input['freeshippingnotice'] = isset( $input['freeshippingnotice'] ) && 1 == $input['freeshippingnotice'] ? 1 : 0;
	$input['legalcheckout'] = isset( $input['legalcheckout'] ) && 1 == $input['legalcheckout'] ? 1 : 0;

	$input['taxnumberplaceholder'] = isset( $input['taxnumberplaceholder'] ) && 1 == $input['taxnumberplaceholder'] ? 1 : 0;
	$input['billingcompanycheck'] = isset( $input['billingcompanycheck'] ) && 1 == $input['billingcompanycheck'] ? 1 : 0;
	$input['nocountry'] = isset( $input['nocountry'] ) && 1 == $input['nocountry'] ? 1 : 0;
	$input['noordercomments'] = isset( $input['noordercomments'] ) && 1 == $input['noordercomments'] ? 1 : 0;
	$input['companytaxnumberpair'] = isset( $input['companytaxnumberpair'] ) && 1 == $input['companytaxnumberpair'] ? 1 : 0;
	$input['postcodecitypair'] = isset( $input['postcodecitypair'] ) && 1 == $input['postcodecitypair'] ? 1 : 0;
	$input['phoneemailpair'] = isset( $input['phoneemailpair'] ) && 1 == $input['phoneemailpair'] ? 1 : 0;
	$input['emailtothetop'] = isset( $input['emailtothetop'] ) && 1 == $input['emailtothetop'] ? 1 : 0;
	$input['regip'] = isset( $input['regip'] ) && 1 == $input['regip'] ? 1 : 0;

	// Our select option must actually be in our array of select options
	if ( !array_key_exists( $input['returntoshopcartposition'], $returntoshopcartposition_options ) ) {
		$input['returntoshopcartposition'] = 'cartactions';
	}
	if ( !array_key_exists( $input['returntoshopcheckoutposition'], $returntoshopcheckoutposition_options ) ) {
		$input['returntoshopcheckoutposition'] = 'nocheckout';
	}
	if ( !array_key_exists( $input['legalconfirmationsposition'], $legalconfirmationsposition_options ) ) {
		$input['legalconfirmationsposition'] = 'revieworderbeforesubmit';
	}

	// Say our text option must be safe text with no HTML tags
	$input['returntoshopmessage'] = wp_filter_nohtml_kses( $input['returntoshopmessage'] );
	$input['loginredirecturl'] = wp_filter_nohtml_kses( $input['loginredirecturl'] );
	$input['registrationredirecturl'] = wp_filter_nohtml_kses( $input['registrationredirecturl'] );
	$input['freeshippingnoticemessage'] = wp_filter_nohtml_kses( $input['freeshippingnoticemessage'] );
	$input['legalcheckouttitle'] = wp_filter_nohtml_kses( $input['legalcheckouttitle'] );

	// Say our text/textarea option must be safe text with the allowed tags for posts
	$input['regacceptpp'] = wp_filter_post_kses( $input['regacceptpp'] );
	$input['accepttos'] = wp_filter_post_kses( $input['accepttos'] );
	$input['acceptpp'] = wp_filter_post_kses( $input['acceptpp'] );
	$input['acceptcustom1label'] = wp_filter_post_kses( $input['acceptcustom1label'] );
	$input['acceptcustom1'] = wp_filter_post_kses( $input['acceptcustom1'] );
	$input['acceptcustom2label'] = wp_filter_post_kses( $input['acceptcustom2label'] );
	$input['acceptcustom2'] = wp_filter_post_kses( $input['acceptcustom2'] );
	$input['beforeorderbuttonmessage'] = wp_filter_post_kses( $input['beforeorderbuttonmessage'] );
	$input['afterorderbuttonmessage'] = wp_filter_post_kses( $input['afterorderbuttonmessage'] );

	return $input;
}
