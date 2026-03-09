<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Render the modules menu content.
 *
 * @return void
 */
function cps_hc_gems_render_menu_modules() {
	$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
	$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );
	$pro_notice = '';
	$no_options_notice = '<div class="uk-alert uk-alert-primary cps-alert uk-text-center"><p><strong>' . esc_html__( 'IMPORTANT!', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html__( 'This Module has no options, but it is activated and already working.', 'surbma-magyar-woocommerce' ) . '</p></div>';

	global $couponfieldposition_options;
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;
	global $shippingmethodstohide_options;
	global $legalconfirmationsposition_options;
	global $smtpport_options;
	global $smtpsecure_options;
	global $emptycartbutton_cartpage_options;
	global $emptycartbutton_checkoutpage_options;
	global $productpricehistory_statisticslinkdisplay_options;
	global $catalogmode_productpricedisplay_options;

	// Get the settings array
	global $cps_hc_gems_options;

	?>
	<form class="uk-form-stacked" method="post" action="options.php">
		<?php settings_fields( 'cps_hc_gems_fields_options' ); ?>

		<ul id="cps-hc-gems-modules" class="uk-switcher">
			<li id="hucommerce-modules">
				<div uk-filter="target: .js-filter">
					<div class="uk-grid uk-grid-small uk-grid-divider uk-child-width-auto uk-flex uk-flex-center" uk-grid>
						<div>
							<ul class="uk-subnav uk-subnav-pill" uk-margin>
								<li class="uk-active" uk-filter-control><a href="#"><?php esc_html_e( 'All', 'surbma-magyar-woocommerce' ); ?></a></li>
							</ul>
						</div>
						<div>
							<ul class="uk-subnav uk-subnav-pill" uk-margin>
								<li uk-filter-control="filter: [data-age='new']; group: age"><a href="#"><?php esc_html_e( 'New', 'surbma-magyar-woocommerce' ); ?></a></li>
							</ul>
						</div>
						<div>
							<ul class="uk-subnav uk-subnav-pill" uk-margin>
								<li uk-filter-control="filter: [data-license='free']; group: license"><a href="#"><?php esc_html_e( 'Free', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-license='pro']; group: license"><a href="#">Pro</a></li>
							</ul>
						</div>
						<div>
							<ul class="uk-subnav uk-subnav-pill uk-flex uk-flex-center" uk-margin>
								<li uk-filter-control="filter: [data-tags*='product']; group: tags"><a href="#"><?php esc_html_e( 'Product', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='cart']; group: tags"><a href="#"><?php esc_html_e( 'Cart', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='checkout']; group: tags"><a href="#"><?php esc_html_e( 'Checkout', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='payments']; group: tags"><a href="#"><?php esc_html_e( 'Payments', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='legal']; group: tags"><a href="#"><?php esc_html_e( 'Legal', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='conversion']; group: tags"><a href="#"><?php esc_html_e( 'Conversion', 'surbma-magyar-woocommerce' ); ?></a></li>
								<li uk-filter-control="filter: [data-tags*='other']; group: tags"><a href="#"><?php esc_html_e( 'Other', 'surbma-magyar-woocommerce' ); ?></a></li>
							</ul>
						</div>
					</div>

					<?php
					// Get modules configuration and helper data
					$modules = cps_hc_gems_get_modules_config();
					$sorted_modules = cps_hc_gems_sort_modules_for_display( $modules );
					$new_module_keys = cps_hc_gems_get_new_module_keys( $modules );
					$tag_translations = cps_hc_gems_get_tag_translations();
					?>
					<ul class="js-filter uk-grid uk-margin-large-bottom uk-flex uk-flex-center" uk-grid uk-height-match="target: > li > .uk-card > .uk-card-body">
						<?php foreach ( $sorted_modules as $module_key => $module ) :
							// Skip modules without required UI properties
							if ( ! isset( $module['title'] ) || ! isset( $module['description'] ) || ! isset( $module['tags'] ) ) {
								continue;
							}

							// Determine license type for data attribute
							$data_license = cps_hc_gems_is_pro_module_type( $module['type'] ) ? 'pro' : 'free';

							// Determine if this is a "new" module
							$is_new = in_array( $module_key, $new_module_keys, true );

							// Build data-tags attribute
							$data_tags = implode( ' ', $module['tags'] );

							// Determine if this is a free module (for form field)
							$is_free = cps_hc_gems_is_free_module_type( $module['type'] );
						?>
						<li data-license="<?php echo esc_attr( $data_license ); ?>"<?php echo $is_new ? ' data-age="new"' : ''; ?> data-tags="<?php echo esc_attr( $data_tags ); ?>">
							<div class="cps-card uk-card uk-card-default uk-card-small uk-card-hover">
								<div class="uk-card-body uk-flex uk-flex-column">
									<div class="uk-flex uk-flex-wrap uk-margin-bottom">
										<?php if ( $is_new ) : ?>
											<span class="uk-label uk-label-default uk-margin-xsmall-right"><?php esc_html_e( 'New', 'surbma-magyar-woocommerce' ); ?></span>
										<?php endif; ?>

										<?php if ( $data_license === 'pro' ) : ?>
											<span class="uk-label uk-label-danger uk-margin-xsmall-right">Pro</span>
										<?php else : ?>
											<span class="uk-label uk-label-success uk-margin-xsmall-right"><?php esc_html_e( 'Free', 'surbma-magyar-woocommerce' ); ?></span>
										<?php endif; ?>

										<?php foreach ( $module['tags'] as $tag ) : ?>
											<span class="uk-label uk-label-warning uk-margin-xsmall-right"><?php echo esc_html( $tag_translations[ $tag ] ?? ucfirst( $tag ) ); ?></span>
										<?php endforeach; ?>
									</div>

									<h5 class="uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><?php echo esc_html( $module['title'] ); ?></h5>
									<p class="uk-margin-small-top"><?php echo esc_html( $module['description'] ); ?></p>

									<?php if ( ! empty( $module['doc_slug'] ) ) : ?>
										<p class="uk-margin-auto-top uk-margin-remove-bottom"><a class="cps-more uk-button uk-button-text uk-button-small uk-padding-remove-horizontal uk-animation-toggle" href="https://www.hucommerce.hu/modul/<?php echo esc_attr( $module['doc_slug'] ); ?>/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span class="uk-animation-slide-left-small" uk-icon="icon: arrow-right"></span></a></p>
									<?php endif; ?>
								</div>
								<div class="uk-card-footer uk-background-muted">
									<?php
									$disabled = '';
									$optionValue = isset( $cps_hc_gems_options[ $module['option_key'] ] ) ? $cps_hc_gems_options[ $module['option_key'] ] : 0;
									?>
									<div class="cps-form-module cps-form-horizontal cps-form-checkbox<?php echo esc_html( $disabled ); ?>">
										<div class="uk-form-label uk-text-bold"><span><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?>:</span></div>
										<div class="uk-form-controls">
											<div class="switch-wrap">
												<label class="switch">
													<input id="<?php echo esc_attr( $module['option_key'] ); ?>" name="surbma_hc_fields[<?php echo esc_attr( $module['option_key'] ); ?>]" type="checkbox" value="1" <?php checked( '1', $optionValue ); ?><?php echo esc_html( $disabled ); ?> />
													<span class="slider round"></span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</li>
			<li></li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Masking with placeholder', 'surbma-magyar-woocommerce' ), 'maskcheckoutfieldsplaceholder', __( 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Tax field', 'surbma-magyar-woocommerce' ), 'maskbillingtaxfield', __( 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Postcode field', 'surbma-magyar-woocommerce' ), 'maskbillingpostcodefield', __( 'Allows only 4 numbers.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Phone field', 'surbma-magyar-woocommerce' ), 'maskbillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Shipping Postcode field', 'surbma-magyar-woocommerce' ), 'maskshippingpostcodefield', __( 'Allows only 4 numbers.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Check field values', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Tax field', 'surbma-magyar-woocommerce' ), 'validatebillingtaxfield', __( 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing City field', 'surbma-magyar-woocommerce' ), 'validatebillingcityfield', __( 'Allows only letters and space.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Address field', 'surbma-magyar-woocommerce' ), 'validatebillingaddressfield', __( 'Must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Billing Phone field', 'surbma-magyar-woocommerce' ), 'validatebillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Accept mobile only', 'surbma-magyar-woocommerce' ), 'validatecheckoutfields-mobileonly', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Shipping City field', 'surbma-magyar-woocommerce' ), 'validateshippingcityfield', __( 'Allows only letters and space.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Shipping Address field', 'surbma-magyar-woocommerce' ), 'validateshippingaddressfield', __( 'Must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' ), false, false, 1 ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Free shipping notification', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Show on Product listing pages', 'surbma-magyar-woocommerce' ), 'freeshippingnoticeshoploop' ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Show on Cart page', 'surbma-magyar-woocommerce' ), 'freeshippingnoticecart' ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Show on Checkout page', 'surbma-magyar-woocommerce' ), 'freeshippingnoticecheckout' ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Minimum order amount', 'surbma-magyar-woocommerce' ), 'freeshippingminimumorderamount', '', __( 'Users will need to spend this amount to get free shipping.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Apply minimum order rule before coupon discount', 'surbma-magyar-woocommerce' ), 'freeshippingcouponsdiscounts', __( 'If checked, free shipping would be available based on pre-discount order amount.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Apply minimum order rule without tax', 'surbma-magyar-woocommerce' ), 'freeshippingwithouttax', __( 'If checked, free shipping would be available based on order amount exclusive of tax.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Message before minimum order amount reached', 'surbma-magyar-woocommerce' ), 'freeshippingnoticemessage', __( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Message when minimum order amount reached', 'surbma-magyar-woocommerce' ), 'freeshippingsuccessfulmessage', '', __( 'If you would like to show a message, when minimum order amount reached. Leave empty if you do not want to show this notice to customers.', 'surbma-magyar-woocommerce' ) ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Empty Cart button', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_select( __( 'Button position on Cart page', 'surbma-magyar-woocommerce' ), 'emptycartbutton-cartpage', $emptycartbutton_cartpage_options, 'none' ); ?>
					<?php cps_hc_gems_form_field_select( __( 'Button position on Checkout page', 'surbma-magyar-woocommerce' ), 'emptycartbutton-checkoutpage', $emptycartbutton_checkoutpage_options, 'none' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Button text', 'surbma-magyar-woocommerce' ), 'emptycartbutton-cartpagebuttontext', __( 'Empty cart', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Message text', 'surbma-magyar-woocommerce' ), 'emptycartbutton-checkoutpagemessage', __( 'Changed your mind?', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Link text', 'surbma-magyar-woocommerce' ), 'emptycartbutton-checkoutpagelinktext', __( 'Empty cart & continue shopping', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Confirmation text', 'surbma-magyar-woocommerce' ), 'emptycartbutton-confirmationtext', __( 'Are you sure you want to empty the Cart?', 'surbma-magyar-woocommerce' ) ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Product price history', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<div class="uk-alert-primary cps-alert" uk-alert>
					<p>Ez a modul nincs minden körülmény között tesztelve és nem tudja 100%-ban teljesíteni a funkcionális és/vagy jogi igényeket, feltételeket. Ezért a használata esetén fokozott figyelmet igényel.<br>
					FIGYELEM! A HuCommerce ügyfélszolgálatára beküldött visszajelzések és javaslatok jelentősen gyorsítják a modul fejlesztését, ezért szívesen várjuk az ilyen témájú megkereséseket. Köszönjük!</p>
				</div>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Show lowest price on Product pages', 'surbma-magyar-woocommerce' ), 'productpricehistory-showlowestprice', __( 'It will show the lowest price from the product price history log automatically.', 'surbma-magyar-woocommerce' ), true ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Text before the lowest price', 'surbma-magyar-woocommerce' ), 'productpricehistory-lowestpricetext', __( 'Our lowest price from previous term', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Text when actual sale price is the only sale price recently', 'surbma-magyar-woocommerce' ), 'productpricehistory-nolowestpricetext', __( 'Actual sale price is our lowest price recently', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Show the calculated discount on Product pages', 'surbma-magyar-woocommerce' ), 'productpricehistory-showdiscount', __( 'It will show the discount, that is calculated from the lowest price automatically.', 'surbma-magyar-woocommerce' ), true ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Text before the discount', 'surbma-magyar-woocommerce' ), 'productpricehistory-discounttext', __( 'Current discount based on the lowest price', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Text before the discount, when actual sale price is the only sale price recently', 'surbma-magyar-woocommerce' ), 'productpricehistory-nolowestpricediscounttext', __( 'Actual discount', 'surbma-magyar-woocommerce' ), __( 'Show actual discount based on the regular price', 'surbma-magyar-woocommerce' ) ); ?>

					<li>
						<div class="uk-alert-primary cps-alert" uk-alert>
							<p>FIGYELEM! Minden terméknél beállítható egy egyedi szöveg, ami megjelenik az adott terméknél az ár alatt. Ez felülírja a fenti beállításokat.</p>
						</div>
					</li>

					<?php cps_hc_gems_form_field_select( __( 'Show the link for advanced statistics on Product pages', 'surbma-magyar-woocommerce' ), 'productpricehistory-statisticslinkdisplay', $productpricehistory_statisticslinkdisplay_options, 'show', __( 'It will show a link also on the Product pages, where visitors can see a more detailed Product price history for the actual Product.', 'surbma-magyar-woocommerce' ), true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Text for the advanced statistics link', 'surbma-magyar-woocommerce' ), 'productpricehistory-statisticslinktext', __( 'Advanced statistics', 'surbma-magyar-woocommerce' ), false, true ); ?>

					<li>
						<label class="uk-form-label"><?php esc_html_e( 'Allowed HTML tags', 'surbma-magyar-woocommerce' ); ?></label>
						<div class="uk-form-controls">
							<pre><?php echo esc_html( cps_hc_gems_allowed_post_tags() ); ?></pre>
						</div>
					</li>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Product price additions', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_text( __( 'Price prefix on Product page', 'surbma-magyar-woocommerce' ), 'productpriceadditions-product-prefix' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Price suffix on Product page', 'surbma-magyar-woocommerce' ), 'productpriceadditions-product-suffix' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Price prefix on Archive pages', 'surbma-magyar-woocommerce' ), 'productpriceadditions-archive-prefix' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Price suffix on Archive pages', 'surbma-magyar-woocommerce' ), 'productpriceadditions-archive-suffix' ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php esc_html_e( 'Registration settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_gems_form_field_checkbox( __( 'Save customer IP address on registration', 'surbma-magyar-woocommerce' ), 'regip', __( 'If enabled, the customer\'s IP address will be saved in profile after registration.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Privacy Policy checkbox text on Registration form', 'surbma-magyar-woocommerce' ), 'regacceptpp', __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' ), __( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>

					<li><strong><?php esc_html_e( 'Checkout settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_gems_form_field_select( __( 'Legal confirmation checkboxes position on Checkout page', 'surbma-magyar-woocommerce' ), 'legalconfirmationsposition', $legalconfirmationsposition_options, 'woocommerce_review_order_before_submit' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Section title on Checkout page', 'surbma-magyar-woocommerce' ), 'legalcheckouttitle', __( 'Legal confirmations', 'surbma-magyar-woocommerce' ), __( 'Title above the checkbox. If empty, then no title will be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Section text on Checkout page', 'surbma-magyar-woocommerce' ), 'legalcheckouttext', '', __( 'General description of the legal section. If empty, then this text will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Terms of Service checkbox text on Checkout page', 'surbma-magyar-woocommerce' ), 'accepttos', __( 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'surbma-magyar-woocommerce' ), __( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Privacy Policy checkbox text on Checkout page', 'surbma-magyar-woocommerce' ), 'acceptpp', __( 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'surbma-magyar-woocommerce' ), __( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Custom 1 checkbox label on Checkout page', 'surbma-magyar-woocommerce' ), 'acceptcustom1label', '', __( 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Custom 1 checkbox text on Checkout page', 'surbma-magyar-woocommerce' ), 'acceptcustom1', '', __( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Make Custom 1 checkbox optional', 'surbma-magyar-woocommerce' ), 'legalcheckout-custom1optional', __( 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Custom 2 checkbox label on Checkout page', 'surbma-magyar-woocommerce' ), 'acceptcustom2label', '', __( 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Custom 2 checkbox text on Checkout page', 'surbma-magyar-woocommerce' ), 'acceptcustom2', '', __( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Make Custom 2 checkbox optional', 'surbma-magyar-woocommerce' ), 'legalcheckout-custom2optional', __( 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Custom text before Place order button', 'surbma-magyar-woocommerce' ), 'beforeorderbuttonmessage', '', __( 'This text will be displayed just above the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'Custom text after Place order button', 'surbma-magyar-woocommerce' ), 'afterorderbuttonmessage', '', __( 'This text will be displayed just under the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' ) ); ?>

					<li>
						<label class="uk-form-label"><?php esc_html_e( 'Allowed HTML tags', 'surbma-magyar-woocommerce' ); ?></label>
						<div class="uk-form-controls">
							<pre><?php echo esc_html( cps_hc_gems_allowed_post_tags() ); ?></pre>
						</div>
					</li>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Limit Payment Methods', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Global Information', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><?php esc_html_e( 'Use these fields for your global information and show them with shortcodes. Your email will be safe from bots and your phone number will be active to call you with one tap on mobiles.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_gems_form_field_text( __( 'Name', 'surbma-magyar-woocommerce' ), 'globalinfoname', '', false, false, false, __( 'Shortcode: <code>[hc-nev]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Company', 'surbma-magyar-woocommerce' ), 'globalinfocompany', '', false, false, false, __( 'Shortcode: <code>[hc-ceg]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Headquarters', 'surbma-magyar-woocommerce' ), 'globalinfoheadquarters', '', false, false, false, __( 'Shortcode: <code>[hc-szekhely]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Tax number', 'surbma-magyar-woocommerce' ), 'globalinfotaxnumber', '', false, false, false, __( 'Shortcode: <code>[hc-adoszam]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Company registration number', 'surbma-magyar-woocommerce' ), 'globalinforegnumber', '', false, false, false, __( 'Shortcode: <code>[hc-cegjegyzekszam]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Address of store', 'surbma-magyar-woocommerce' ), 'globalinfoaddress', '', false, false, false, __( 'Shortcode: <code>[hc-cim]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Bank account number', 'surbma-magyar-woocommerce' ), 'globalinfobankaccount', '', false, false, false, __( 'Shortcode: <code>[hc-bankszamlaszam]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Mobile phone number', 'surbma-magyar-woocommerce' ), 'globalinfomobile', '', false, false, false, __( 'Shortcode: <code>[hc-mobiltelefon]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Telephone number', 'surbma-magyar-woocommerce' ), 'globalinfophone', '', false, false, false, __( 'Shortcode: <code>[hc-telefon]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Email', 'surbma-magyar-woocommerce' ), 'globalinfoemail', '', false, false, false, __( 'Shortcode: <code>[hc-email]</code>', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_textarea( __( 'About Us', 'surbma-magyar-woocommerce' ), 'globalinfoaboutus', '', false, false, false, __( ' | Shortcode: <code>[hc-rolunk]</code>', 'surbma-magyar-woocommerce' ) ); ?>

					<li><strong><?php esc_html_e( 'Extra shortcodes', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li class="uk-overflow-auto">
						<table class="uk-table uk-table-divider uk-table-justify uk-table-small">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Shortcode', 'surbma-magyar-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Description', 'surbma-magyar-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Example', 'surbma-magyar-woocommerce' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>[hc-mailto][/hc-mailto]</code></td>
									<td><?php esc_html_e( 'The mailto shortcode can show an email address as a link and encode the characters, so bots can not read it from the source code.', 'surbma-magyar-woocommerce' ); ?></td>
									<td><code>[hc-mailto]email@domain.hu[/hc-mailto]</code></td>
								</tr>
								<tr>
									<td><code>[hc-tel][/hc-tel]</code></td>
									<td><?php esc_html_e( 'The tel shortcode will create a clickable phone number.', 'surbma-magyar-woocommerce' ); ?></td>
									<td><code>[hc-tel]+36 12 345 6789[/hc-tel]</code></td>
								</tr>
							</tbody>
						</table>
					</li>
				</ul>
			</li>
			<li class="cps-hc-gems-translations-section">
				<h3 class="uk-card-title"><?php esc_html_e( 'Translations for premium plugins & themes', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Adds translations for hundreds of the most popular premium plugins & themes. Supported softwares added regularly.', 'surbma-magyar-woocommerce' ); ?></p>
				<div class="uk-alert-primary" uk-alert>
					<p><strong><?php esc_html_e( '🙏 Big thank you for the HelloWP team!', 'surbma-magyar-woocommerce' ); ?></strong></p>
					<p><?php esc_html_e( 'The "WordPress Translations Collections" repository is an open source project started, created and maintained by HelloWP.io that allows you to contribute to the translation of hundreds of Premium WordPress plugins and themes. You can find the repository here: https://github.com/hellowpio/wordpress-translations', 'surbma-magyar-woocommerce' ); ?></p>
				</div>

				<div class="uk-margin">
					<label class="uk-form-label uk-hidden" for="cps-hc-gems-translations-filter"><?php esc_html_e( 'Filter for Plugins & Themes', 'surbma-magyar-woocommerce' ); ?>:</label>
					<div class="uk-inline uk-width-1-1">
					<span class="uk-form-icon" uk-icon="icon: search"></span>
						<input type="text" id="cps-hc-gems-translations-filter" class="uk-input uk-form-large" placeholder="<?php esc_attr_e( 'Start typing to filter the list of plugins and themes...', 'surbma-magyar-woocommerce' ); ?>" aria-label="<?php esc_attr_e( 'Filter translation list by name or domain', 'surbma-magyar-woocommerce' ); ?>">
					</div>
				</div>

				<?php
				$translation_domains = cps_hc_gems_get_translation_domains();
				$translation_names = cps_hc_gems_get_translation_domain_names();
				$plugins_count = count( $translation_domains['plugins'] );
				$themes_count = count( $translation_domains['themes'] );
				?>
				<div class="uk-card uk-card-default uk-card-body uk-margin-small-bottom">
					<h5 class="uk-text-bold"><?php esc_html_e( 'Plugins' ); ?> (<?php echo (int) $plugins_count; ?>)</h5>
					<div class="uk-overflow-auto cps-hc-gems-translations-scroll" style="max-height: 294px;">
						<ul class="cps-form-fields uk-list uk-list-divider uk-margin-right cps-hc-gems-translations-list" data-type="plugins">
							<?php foreach ( $translation_domains['plugins'] as $domain ) : ?>
								<?php
								$name = $translation_names[ $domain ] ?? $domain;
								cps_hc_gems_form_field_checkbox( $name, cps_hc_gems_translation_domain_to_option_key( $domain ), false, false, false, 0, [ 'data-domain' => $domain ] );
								?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="uk-card uk-card-default uk-card-body uk-margin-small-bottom">
					<h5 class="uk-text-bold"><?php esc_html_e( 'Themes' ); ?> (<?php echo (int) $themes_count; ?>)</h5>
					<div class="uk-overflow-auto cps-hc-gems-translations-scroll" style="max-height: 294px;">
						<ul class="cps-form-fields uk-list uk-list-divider uk-margin-right cps-hc-gems-translations-list" data-type="themes">
							<?php foreach ( $translation_domains['themes'] as $domain ) : ?>
								<?php
								$name = $translation_names[ $domain ] ?? $domain;
								cps_hc_gems_form_field_checkbox( $name, cps_hc_gems_translation_domain_to_option_key( $domain ), false, false, false, 0, [ 'data-domain' => $domain ] );
								?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<script>
				(function() {
					var filterEl = document.getElementById( 'cps-hc-gems-translations-filter' );
					var lists = document.querySelectorAll( '.cps-hc-gems-translations-list' );
					if ( ! filterEl || ! lists.length ) { return; }
					function filterTranslations() {
						var q = ( filterEl.value || '' ).trim().toLowerCase();
						lists.forEach( function( ul ) {
							var items = ul.querySelectorAll( 'li.cps-form-checkbox' );
							items.forEach( function( li ) {
								var label = li.querySelector( '.uk-form-label span' );
								var labelText = label ? ( label.textContent || '' ).trim().toLowerCase() : '';
								var domain = ( li.getAttribute && li.getAttribute( 'data-domain' ) ) || '';
								var domainLower = domain.toLowerCase();
								var show = ! q || labelText.indexOf( q ) !== -1 || domainLower.indexOf( q ) !== -1;
								li.classList.toggle( 'uk-hidden', ! show );
							});
						});
					}
					filterEl.addEventListener( 'input', filterTranslations );
					filterEl.addEventListener( 'keyup', filterTranslations );
				})();
				</script>
			</li>
			<?php // * HUCOMMERCE START ?>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Fixes for Hungarian language', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<?php // * HUCOMMERCE END ?>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Tax number field', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php // * HUCOMMERCE START ?>
					<?php $szamlazzhu_vatnumber_Value = isset( $szamlazzhu_options['vat_number_form'] ) ? $szamlazzhu_options['vat_number_form'] : false; ?>
					<?php if ( class_exists( 'WC_Szamlazz' ) && 'yes' == $szamlazzhu_vatnumber_Value ) { ?>
						<div class="uk-alert-danger cps-alert" uk-alert>
							<a class="uk-alert-close" uk-close></a>
							<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Szamlazz.hu & WooCommerce plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\'s settings.', 'surbma-magyar-woocommerce' ); ?></p>
						</div>
					<?php } ?>
					<?php $billingo_vatnumber_Value = isset( $billingo_options['vat_number_form'] ) ? $billingo_options['vat_number_form'] : false; ?>
					<?php if ( class_exists( 'WC_Billingo_Plus' ) && 'yes' == $billingo_vatnumber_Value ) { ?>
						<div class="uk-alert-danger cps-alert" uk-alert>
							<a class="uk-alert-close" uk-close></a>
							<p><?php esc_html_e( 'A Tax number field is already added by the Woo Billingo Plus plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\'s settings.', 'surbma-magyar-woocommerce' ); ?></p>
						</div>
					<?php } ?>
					<?php if ( class_exists( 'WC_Billingo' ) && 'yes' == get_option('wc_billingo_vat_number_form') ) { ?>
						<div class="uk-alert-danger cps-alert" uk-alert>
							<a class="uk-alert-close" uk-close></a>
							<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Billingo & WooCommerce plugin. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the Tax field option at the other plugin\'s settings.', 'surbma-magyar-woocommerce' ); ?></p>
						</div>
					<?php } ?>
					<?php if ( class_exists( 'WC_Billingo' ) && 'yes' == get_option('wc_billingo_vat_number_form_checkbox_custom') ) { ?>
						<div class="uk-alert-danger cps-alert" uk-alert>
							<a class="uk-alert-close" uk-close></a>
							<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Billingo & WooCommerce plugin\'s custom field option. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the "Egyedi meta mezőt használok adószámhoz" option at the other plugin\'s settings.', 'surbma-magyar-woocommerce' ); ?></p>
						</div>
					<?php } ?>
				<?php // * HUCOMMERCE END ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Add placeholder to this field', 'surbma-magyar-woocommerce' ), 'taxnumberplaceholder', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</li>
			<?php // * HUCOMMERCE START ?>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Hungarian translation fixes', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Hide County field if Country is Hungary', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Autofill City after Postcode is given', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<?php // * HUCOMMERCE END ?>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Product customizations', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Product subtitle', 'surbma-magyar-woocommerce' ), 'productsubtitle', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Remove image zoom on single product pages', 'surbma-magyar-woocommerce' ), 'productsettings-removeimagezoom', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Add to cart button on archive pages', 'surbma-magyar-woocommerce' ), 'addtocartonarchive', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Remove related products on single product pages', 'surbma-magyar-woocommerce' ), 'norelatedproducts', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Number of products on archive pages', 'surbma-magyar-woocommerce' ), 'productsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Products per row on archive pages', 'surbma-magyar-woocommerce' ), 'productsperrow', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Number of upsell products on single product pages', 'surbma-magyar-woocommerce' ), 'upsellproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Upsell products per row on single product pages', 'surbma-magyar-woocommerce' ), 'upsellproductsperrow', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Number of related products on single product pages', 'surbma-magyar-woocommerce' ), 'relatedproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( __( 'Related products per row on single product pages', 'surbma-magyar-woocommerce' ), 'relatedproductsperrow', '', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Checkout page customizations', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Conditional display of Company fields', 'surbma-magyar-woocommerce' ), 'billingcompanycheck', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Company and Tax number fields, if billing country is not Hungary', 'surbma-magyar-woocommerce' ), 'checkout-hidecompanytaxfields', false, true, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Country field', 'surbma-magyar-woocommerce' ), 'nocountry', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Order notes field', 'surbma-magyar-woocommerce' ), 'noordercomments', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Additional information section', 'surbma-magyar-woocommerce' ), 'noadditionalinformation', __( 'It will hide Order notes field also.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Inline Company and Tax number fields', 'surbma-magyar-woocommerce' ), 'companytaxnumberpair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Inline Postcode and City fields', 'surbma-magyar-woocommerce' ), 'postcodecitypair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Inline Phone and Email fields', 'surbma-magyar-woocommerce' ), 'phoneemailpair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Make Email field the first field', 'surbma-magyar-woocommerce' ), 'emailtothetop', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Custom submit button text', 'surbma-magyar-woocommerce' ), 'checkout-customsubmitbuttontext', '', false, true, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Plus/minus quantity buttons', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Automatic Cart update', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Continue shopping buttons', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_select( __( 'Button position on Cart page', 'surbma-magyar-woocommerce' ), 'returntoshopcartposition', $returntoshopcartposition_options, 'cartactions', false, false, true ); ?>
					<?php cps_hc_gems_form_field_select( __( 'Button position on Checkout page', 'surbma-magyar-woocommerce' ), 'returntoshopcheckoutposition', $returntoshopcheckoutposition_options, 'nocheckout', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Message text', 'surbma-magyar-woocommerce' ), 'returntoshopmessage', __( 'Would you like to continue shopping?', 'surbma-magyar-woocommerce' ), false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Login and registration redirection', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_text( __( 'Redirection URL after Login', 'surbma-magyar-woocommerce' ), 'loginredirecturl', '', __( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Redirection URL after Registration', 'surbma-magyar-woocommerce' ), 'registrationredirecturl', '', __( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' ), false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Coupon field customizations', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Show Coupons in upper case', 'surbma-magyar-woocommerce' ), 'couponuppercase', __( 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Coupon field on Cart page', 'surbma-magyar-woocommerce' ), 'couponfieldhiddenoncart', __( 'It will hide the Coupon field completely from the Cart page.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Coupon field on Checkout page', 'surbma-magyar-woocommerce' ), 'couponfieldhiddenoncheckout', __( 'It will hide the Coupon field completely from the Checkout page.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( __( 'Coupon field always visible on Checkout page', 'surbma-magyar-woocommerce' ), 'couponfieldalwaysvisible', __( 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', 'surbma-magyar-woocommerce' ), false, true ); ?>
					<?php cps_hc_gems_form_field_select( __( 'Reposition the Coupon field', 'surbma-magyar-woocommerce' ), 'couponfieldposition', $couponfieldposition_options, 'beforecheckoutform', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Redirect Cart page to Checkout page', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'One product per purchase', 'surbma-magyar-woocommerce' ); ?></h3>
				<?php echo wp_kses_post( $no_options_notice ); ?>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Custom Add To Cart Button', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php esc_html_e( 'Single product pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php esc_html_e( 'Give your custom texts to your Add to cart buttons on the product pages. You can set custom texts for different product types. If you leave them empty, the button texts will fall back to default WooCommerce texts.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_gems_form_field_text( __( 'Simple product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-simple', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Grouped product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-grouped', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'External/Affiliate product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-external', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Variable product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-variable', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Variable subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Bookable product (WooCommerce Bookings)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-single-booking', '', false, false, true ); ?>
					<li><strong><?php esc_html_e( 'Product archive pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php esc_html_e( 'Give your custom texts to your Add to cart buttons on the product archive pages. You can set custom texts for different product types. If you leave them empty, the button texts will inherit texts from single product settings or fall back to default WooCommerce texts, if those fields are also empty.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_gems_form_field_text( __( 'Simple product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-simple', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Grouped product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-grouped', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'External/Affiliate product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-external', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Variable product', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-variable', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Variable subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Bookable product (WooCommerce Bookings)', 'surbma-magyar-woocommerce' ), 'custom-addtocart-button-archive-booking', '', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Hide shipping methods', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( __( 'Hide Shipping methods on Cart page', 'surbma-magyar-woocommerce' ), 'hideshippingmethods-cart', __( 'It will hide all Shipping methods on the Cart page.', 'surbma-magyar-woocommerce' ), true, true ); ?>
					<?php cps_hc_gems_form_field_select( __( 'Shipping methods to hide, when free shipping is available', 'surbma-magyar-woocommerce' ), 'shippingmethodstohide', $shippingmethodstohide_options, 'showall', false, false, true ); ?>
					<li>
						<div class="uk-alert-primary cps-alert" uk-alert>
							<p><strong><?php esc_html_e( 'Compatible shipping plugins (Pickup methods)', 'surbma-magyar-woocommerce' ); ?>:</strong> <br>Hungarian Pickup Points for WooCommerce, Pont shipping for Woocommerce (Szathmári), Foxpost, Foxpost Parcel, Postapont</p>
						</div>
					</li>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'SMTP service', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php
					$current_user = wp_get_current_user();
					$current_user_email = urlencode( $current_user->user_email );
				?>

				<a href="<?php echo esc_url( add_query_arg( 'hc-test-email', $current_user_email ) ); ?>" class="uk-button uk-button-primary" uk-tooltip="title: <?php esc_html_e( 'Clicking on this button will send a test email to the actual user\'s email address.', 'surbma-magyar-woocommerce' ); ?>; pos: right"><?php esc_html_e( 'Send test email', 'surbma-magyar-woocommerce' ); ?></a>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><?php esc_html_e( 'SMTP service is a must have for all WooCommerce webshops, as it makes your transactional email delivery more stable and secure. Register a new account at a 3rd party SMTP service and set your credentials here to enable this feature.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_gems_form_field_select( __( 'SMTP port number', 'surbma-magyar-woocommerce' ), 'smtpport', $smtpport_options, '587', false, false, true ); ?>
					<?php cps_hc_gems_form_field_select( __( 'Encryption type', 'surbma-magyar-woocommerce' ), 'smtpsecure', $smtpsecure_options, 'default', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( __( 'SMTP From email address', 'surbma-magyar-woocommerce' ), 'smtpfrom', '', false, false, true, __( 'Optional', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'SMTP From name', 'surbma-magyar-woocommerce' ), 'smtpfromname', '', false, false, true, __( 'Optional', 'surbma-magyar-woocommerce' ) ); ?>
					<?php cps_hc_gems_form_field_text( __( 'The hostname of the mail server', 'surbma-magyar-woocommerce' ), 'smtphost', '', false, false, true, false, 'world' ); ?>
					<?php cps_hc_gems_form_field_text( __( 'Username to use for SMTP authentication', 'surbma-magyar-woocommerce' ), 'smtpuser', '', false, false, true, false, 'user' ); ?>
					<?php cps_hc_gems_form_field_password( __( 'Password to use for SMTP authentication', 'surbma-magyar-woocommerce' ), 'smtppassword', '', false, false, true, false, 'lock' ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Catalog mode', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_select( __( 'Product price display', 'surbma-magyar-woocommerce' ), 'catalogmode-productpricedisplay', $catalogmode_productpricedisplay_options, 'none', false, true, true ); ?>
				</ul>
			</li>
		</ul>
		<div class="uk-text-center uk-margin-top"><input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-large" value="<?php esc_attr_e( 'Save Changes', 'surbma-magyar-woocommerce' ); ?>" /></div>
	</form>
	<script>
	document.addEventListener( 'DOMContentLoaded', function() {
		var contentEl = document.getElementById( 'cps-hc-gems-modules' );
		var navEl = document.getElementById( 'hc-modules-nav' );
		if ( ! contentEl || ! navEl || typeof UIkit === 'undefined' ) { return; }

		// Update URL and _wp_http_referer when tab changes
		navEl.addEventListener( 'click', function( e ) {
			var li = e.target.closest( '#hc-modules-nav > li' );
			if ( ! li || li.classList.contains( 'uk-nav-header' ) ) { return; }
			var items = navEl.querySelectorAll( ':scope > li' );
			var index = Array.prototype.indexOf.call( items, li );
			if ( index < 0 ) { return; }

			var url = new URL( window.location.href );
			if ( index > 0 ) {
				url.searchParams.set( 'tab', index );
			} else {
				url.searchParams.delete( 'tab' );
			}
			history.replaceState( null, '', url.toString() );

			var refererField = document.querySelector( 'input[name="_wp_http_referer"]' );
			if ( refererField ) {
				refererField.value = url.pathname + url.search;
			}
		});

	});
	</script>
	<?php
}


