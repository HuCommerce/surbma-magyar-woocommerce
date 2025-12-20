<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

/**
 * Render the modules menu content.
 *
 * @return void
 */
function cps_hc_gems_render_menu_modules() {
	// * HUCOMMERCE START
	$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
	$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );
	$pro_notice = 'active' == HC_LICENSE ? '' : '<div class="cps-alert uk-alert uk-alert-danger"><p><strong>Ha szeretnéd használni ezt a modult, előbb HuCommerce Pro előfizetést kell vásárolnod!</strong><br>A HuCommerce Pro előfizetés megvásárlásával további fantasztikus funkciókat és kiemelt ügyfélszolgálati segítséget kapsz.</p><a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="uk-button uk-button-danger uk-button-small" target="_blank">HuCommerce Pro megvásárlása</a></div>';
	$no_options_notice = '<div class="uk-alert uk-alert-primary cps-alert uk-text-center"><p><strong>' . esc_html__( 'IMPORTANT!', 'surbma-magyar-woocommerce' ) . '</strong> ' . esc_html__( 'This Module has no options, but it is activated and already working.', 'surbma-magyar-woocommerce' ) . '</p></div>';
	// * HUCOMMERCE END

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

	// Translation fixes
	__( 'Add placeholder to this field', 'surbma-magyar-woocommerce' );
	__( 'Masking with placeholder', 'surbma-magyar-woocommerce' );
	__( 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.', 'surbma-magyar-woocommerce' );
	__( 'Billing Tax field', 'surbma-magyar-woocommerce' );
	__( 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', 'surbma-magyar-woocommerce' );
	__( 'Billing Postcode field', 'surbma-magyar-woocommerce' );
	__( 'Allows only 4 numbers.', 'surbma-magyar-woocommerce' );
	__( 'Billing Phone field', 'surbma-magyar-woocommerce' );
	__( 'Shipping Postcode field', 'surbma-magyar-woocommerce' );
	__( 'Billing City field', 'surbma-magyar-woocommerce' );
	__( 'Allows only letters and space.', 'surbma-magyar-woocommerce' );
	__( 'Billing Address field', 'surbma-magyar-woocommerce' );
	__( 'Must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' );
	__( 'Shipping City field', 'surbma-magyar-woocommerce' );
	__( 'Shipping Address field', 'surbma-magyar-woocommerce' );
	__( 'Show lowest price on Product pages', 'surbma-magyar-woocommerce' );
	__( 'It will show the lowest price from the product price history log automatically.', 'surbma-magyar-woocommerce' );
	__( 'Text before the lowest price', 'surbma-magyar-woocommerce' );
	__( 'Text when actual sale price is the only sale price recently', 'surbma-magyar-woocommerce' );
	__( 'Actual sale price is our lowest price recently', 'surbma-magyar-woocommerce' );
	__( 'Our lowest price from previous term', 'surbma-magyar-woocommerce' );
	__( 'Show the calculated discount on Product pages', 'surbma-magyar-woocommerce' );
	__( 'It will show the discount, that is calculated from the lowest price automatically.', 'surbma-magyar-woocommerce' );
	__( 'Text before the discount', 'surbma-magyar-woocommerce' );
	__( 'Text before the discount, when actual sale price is the only sale price recently', 'surbma-magyar-woocommerce' );
	__( 'Actual discount', 'surbma-magyar-woocommerce' );
	__( 'Current discount based on the lowest price', 'surbma-magyar-woocommerce' );
	__( 'Product subtitle', 'surbma-magyar-woocommerce' );
	__( 'Remove image zoom on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Add to cart button on archive pages', 'surbma-magyar-woocommerce' );
	__( 'Remove related products on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Number of products on archive pages', 'surbma-magyar-woocommerce' );
	__( 'Products per row on archive pages', 'surbma-magyar-woocommerce' );
	__( 'Number of upsell products on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Upsell products per row on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Number of related products on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Related products per row on single product pages', 'surbma-magyar-woocommerce' );
	__( 'Conditional display of Company fields', 'surbma-magyar-woocommerce' );
	__( 'Hide Company and Tax number fields, if billing country is not Hungary', 'surbma-magyar-woocommerce' );
	__( 'Hide Country field', 'surbma-magyar-woocommerce' );
	__( 'Hide Order notes field', 'surbma-magyar-woocommerce' );
	__( 'Hide Additional information section', 'surbma-magyar-woocommerce' );
	__( 'It will hide Order notes field also.', 'surbma-magyar-woocommerce' );
	__( 'Inline Company and Tax number fields', 'surbma-magyar-woocommerce' );
	__( 'Inline Postcode and City fields', 'surbma-magyar-woocommerce' );
	__( 'Inline Phone and Email fields', 'surbma-magyar-woocommerce' );
	__( 'Make Email field the first field', 'surbma-magyar-woocommerce' );
	__( 'Custom submit button text', 'surbma-magyar-woocommerce' );
	__( 'Button position on Cart page', 'surbma-magyar-woocommerce' );
	__( 'Button position on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Message text', 'surbma-magyar-woocommerce' );
	__( 'Redirection URL after Login', 'surbma-magyar-woocommerce' );
	__( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' );
	__( 'Redirection URL after Registration', 'surbma-magyar-woocommerce' );
	__( 'Show on Product listing pages', 'surbma-magyar-woocommerce' );
	__( 'Show on Cart page', 'surbma-magyar-woocommerce' );
	__( 'Show on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Save customer IP address on registration', 'surbma-magyar-woocommerce' );
	__( 'If enabled, the customer\'s IP address will be saved in profile after registration.', 'surbma-magyar-woocommerce' );
	__( 'Privacy Policy checkbox text on Registration form', 'surbma-magyar-woocommerce' );
	__( 'If empty, then this checkbox will not be displayed.', 'surbma-magyar-woocommerce' );
	__( 'Legal confirmation checkboxes position on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Section title on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Title above the checkbox. If empty, then no title will be displayed.', 'surbma-magyar-woocommerce' );
	__( 'Terms of Service checkbox text on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Privacy Policy checkbox text on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Custom 1 checkbox label on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.', 'surbma-magyar-woocommerce' );
	__( 'Custom 1 checkbox text on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Custom 2 checkbox label on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Custom 2 checkbox text on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Custom text before Place order button', 'surbma-magyar-woocommerce' );
	__( 'This text will be displayed just above the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' );
	__( 'Custom text after Place order button', 'surbma-magyar-woocommerce' );
	__( 'This text will be displayed just under the Place order button on Checkout page. If empty, then no text will be displayed.', 'surbma-magyar-woocommerce' );
	__( 'Show Coupons in upper case', 'surbma-magyar-woocommerce' );
	__( 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', 'surbma-magyar-woocommerce' );
	__( 'Hide Coupon field on Cart page', 'surbma-magyar-woocommerce' );
	__( 'It will hide the Coupon field completely from the Cart page.', 'surbma-magyar-woocommerce' );
	__( 'Hide Coupon field on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'It will hide the Coupon field completely from the Checkout page.', 'surbma-magyar-woocommerce' );
	__( 'Coupon field always visible on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', 'surbma-magyar-woocommerce' );
	__( 'Reposition the Coupon field', 'surbma-magyar-woocommerce' );
	__( 'Simple product', 'surbma-magyar-woocommerce' );
	__( 'Grouped product', 'surbma-magyar-woocommerce' );
	__( 'External/Affiliate product', 'surbma-magyar-woocommerce' );
	__( 'Variable product', 'surbma-magyar-woocommerce' );
	__( 'Subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' );
	__( 'Variable subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' );
	__( 'Bookable product (WooCommerce Bookings)', 'surbma-magyar-woocommerce' );
	__( 'Shipping methods to hide, when free shipping is available', 'surbma-magyar-woocommerce' );
	__( 'Name', 'surbma-magyar-woocommerce' );
	__( 'Company', 'surbma-magyar-woocommerce' );
	__( 'Headquarters', 'surbma-magyar-woocommerce' );
	__( 'Company registration number', 'surbma-magyar-woocommerce' );
	__( 'Address of store', 'surbma-magyar-woocommerce' );
	__( 'Bank account number', 'surbma-magyar-woocommerce' );
	__( 'Mobile phone number', 'surbma-magyar-woocommerce' );
	__( 'Telephone number', 'surbma-magyar-woocommerce' );
	__( 'About Us', 'surbma-magyar-woocommerce' );
	__( 'SMTP port number', 'surbma-magyar-woocommerce' );
	__( 'Encryption type', 'surbma-magyar-woocommerce' );
	__( 'SMTP From email address', 'surbma-magyar-woocommerce' );
	__( 'Optional', 'surbma-magyar-woocommerce' );
	__( 'SMTP From name', 'surbma-magyar-woocommerce' );
	__( 'The hostname of the mail server', 'surbma-magyar-woocommerce' );
	__( 'Username to use for SMTP authentication', 'surbma-magyar-woocommerce' );
	__( 'Password to use for SMTP authentication', 'surbma-magyar-woocommerce' );
	__( 'Show the link for advanced statistics on Product pages', 'surbma-magyar-woocommerce' );
	__( 'It will show a link also on the Product pages, where visitors can see a more detailed Product price history for the actual Product.', 'surbma-magyar-woocommerce' );
	__( 'Text for the advanced statistics link', 'surbma-magyar-woocommerce' );
	__( 'Show Empty Cart button on Cart page', 'surbma-magyar-woocommerce' );
	__( 'Show Empty Cart button on Checkout page', 'surbma-magyar-woocommerce' );
	__( 'Button text', 'surbma-magyar-woocommerce' );
	__( 'Link text', 'surbma-magyar-woocommerce' );
	__( 'Confirmation text', 'surbma-magyar-woocommerce' );
	__( 'Empty cart', 'surbma-magyar-woocommerce' );
	__( 'Changed your mind?', 'surbma-magyar-woocommerce' );
	__( 'Empty cart & continue shopping', 'surbma-magyar-woocommerce' );
	__( 'Are you sure you want to empty the Cart?', 'surbma-magyar-woocommerce' );
	__( 'Minimum order amount', 'surbma-magyar-woocommerce' );
	__( 'Users will need to spend this amount to get free shipping.', 'surbma-magyar-woocommerce' );
	__( 'Apply minimum order rule before coupon discount', 'surbma-magyar-woocommerce' );
	__( 'If checked, free shipping would be available based on pre-discount order amount.', 'surbma-magyar-woocommerce' );
	__( 'Apply minimum order rule without tax', 'surbma-magyar-woocommerce' );
	__( 'If checked, free shipping would be available based on order amount exclusive of tax.', 'surbma-magyar-woocommerce' );
	__( 'Message before minimum order amount reached', 'surbma-magyar-woocommerce' );
	__( 'The remaining amount to get FREE shipping', 'surbma-magyar-woocommerce' );
	__( 'Message when minimum order amount reached', 'surbma-magyar-woocommerce' );
	__( 'If you would like to show a message, when minimum order amount reached. Leave empty if you do not want to show this notice to customers.', 'surbma-magyar-woocommerce' );
	__( 'Make Custom 1 checkbox optional', 'surbma-magyar-woocommerce' );
	__( 'Make Custom 2 checkbox optional', 'surbma-magyar-woocommerce' );
	__( 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.', 'surbma-magyar-woocommerce' );
	__( 'Activate module', 'surbma-magyar-woocommerce' );
	__( 'Price prefix on Product page', 'surbma-magyar-woocommerce' );
	__( 'Price suffix on Product page', 'surbma-magyar-woocommerce' );
	__( 'Price prefix on Archive pages', 'surbma-magyar-woocommerce' );
	__( 'Price suffix on Archive pages', 'surbma-magyar-woocommerce' );
	__( 'Translations', 'surbma-magyar-woocommerce' );
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
									$disabled = $is_free || 'active' == HC_LICENSE || ( isset( $cps_hc_gems_options[ $module['option_key'] ] ) && 1 == $cps_hc_gems_options[ $module['option_key'] ] ) ? '' : ' disabled';
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
					<?php cps_hc_gems_form_field_checkbox( 'Masking with placeholder', 'maskcheckoutfieldsplaceholder', 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing Tax field', 'maskbillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing Postcode field', 'maskbillingpostcodefield', 'Allows only 4 numbers.', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing Phone field', 'maskbillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Shipping Postcode field', 'maskshippingpostcodefield', 'Allows only 4 numbers.', false, false, 1 ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Check field values', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Billing Tax field', 'validatebillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing City field', 'validatebillingcityfield', 'Allows only letters and space.', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing Address field', 'validatebillingaddressfield', 'Must have at least one letter, one number and one space in the address.', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Billing Phone field', 'validatebillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Accept mobile only', 'validatecheckoutfields-mobileonly', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Shipping City field', 'validateshippingcityfield', 'Allows only letters and space.', false, false, 1 ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Shipping Address field', 'validateshippingaddressfield', 'Must have at least one letter, one number and one space in the address.', false, false, 1 ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Free shipping notification', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Show on Product listing pages', 'freeshippingnoticeshoploop' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Show on Cart page', 'freeshippingnoticecart' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Show on Checkout page', 'freeshippingnoticecheckout' ); ?>
					<?php cps_hc_gems_form_field_number( 'Minimum order amount', 'freeshippingminimumorderamount', '', 'Users will need to spend this amount to get free shipping.' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Apply minimum order rule before coupon discount', 'freeshippingcouponsdiscounts', 'If checked, free shipping would be available based on pre-discount order amount.' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Apply minimum order rule without tax', 'freeshippingwithouttax', 'If checked, free shipping would be available based on order amount exclusive of tax.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Message before minimum order amount reached', 'freeshippingnoticemessage', 'The remaining amount to get FREE shipping' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Message when minimum order amount reached', 'freeshippingsuccessfulmessage', '', 'If you would like to show a message, when minimum order amount reached. Leave empty if you do not want to show this notice to customers.' ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Empty Cart button', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_select( 'Button position on Cart page', 'emptycartbutton-cartpage', $emptycartbutton_cartpage_options, 'none' ); ?>
					<?php cps_hc_gems_form_field_select( 'Button position on Checkout page', 'emptycartbutton-checkoutpage', $emptycartbutton_checkoutpage_options, 'none' ); ?>
					<?php cps_hc_gems_form_field_text( 'Button text', 'emptycartbutton-cartpagebuttontext', 'Empty cart' ); ?>
					<?php cps_hc_gems_form_field_text( 'Message text', 'emptycartbutton-checkoutpagemessage', 'Changed your mind?' ); ?>
					<?php cps_hc_gems_form_field_text( 'Link text', 'emptycartbutton-checkoutpagelinktext', 'Empty cart & continue shopping' ); ?>
					<?php cps_hc_gems_form_field_text( 'Confirmation text', 'emptycartbutton-confirmationtext', 'Are you sure you want to empty the Cart?' ); ?>
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
					<?php cps_hc_gems_form_field_checkbox( 'Show lowest price on Product pages', 'productpricehistory-showlowestprice', 'It will show the lowest price from the product price history log automatically.', true ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Text before the lowest price', 'productpricehistory-lowestpricetext', 'Our lowest price from previous term' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Text when actual sale price is the only sale price recently', 'productpricehistory-nolowestpricetext', 'Actual sale price is our lowest price recently' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Show the calculated discount on Product pages', 'productpricehistory-showdiscount', 'It will show the discount, that is calculated from the lowest price automatically.', true ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Text before the discount', 'productpricehistory-discounttext', 'Current discount based on the lowest price' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Text before the discount, when actual sale price is the only sale price recently', 'productpricehistory-nolowestpricediscounttext', 'Actual discount', 'Show actual discount based on the regular price' ); ?>

					<li>
						<div class="uk-alert-primary cps-alert" uk-alert>
							<p>FIGYELEM! Minden terméknél beállítható egy egyedi szöveg, ami megjelenik az adott terméknél az ár alatt. Ez felülírja a fenti beállításokat.</p>
						</div>
					</li>

					<?php cps_hc_gems_form_field_select( 'Show the link for advanced statistics on Product pages', 'productpricehistory-statisticslinkdisplay', $productpricehistory_statisticslinkdisplay_options, 'show', 'It will show a link also on the Product pages, where visitors can see a more detailed Product price history for the actual Product.', true ); ?>
					<?php cps_hc_gems_form_field_text( 'Text for the advanced statistics link', 'productpricehistory-statisticslinktext', 'Advanced statistics', false, true ); ?>

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
					<?php cps_hc_gems_form_field_text( 'Price prefix on Product page', 'productpriceadditions-product-prefix' ); ?>
					<?php cps_hc_gems_form_field_text( 'Price suffix on Product page', 'productpriceadditions-product-suffix' ); ?>
					<?php cps_hc_gems_form_field_text( 'Price prefix on Archive pages', 'productpriceadditions-archive-prefix' ); ?>
					<?php cps_hc_gems_form_field_text( 'Price suffix on Archive pages', 'productpriceadditions-archive-suffix' ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<?php // HuCommerce legacy users notice ?>
				<?php if ( 'free' == HC_LICENSE && $cps_hc_gems_options && !isset( $cps_hc_gems_options['brandnewuser'] ) ) { ?>
					<div class="cps-alert uk-alert-danger" uk-alert>
						<p><strong class="uk-text-uppercase">Figyelem!</strong> A "Jogi megfelelés" modul átkerült a HuCommerce fizetős, Pro verziójába. Minden eddigi beállítás továbbra is működik, de módosítani nem lehet a beállításokat. Mentés után is használhatod a modult korlátlan ideig, ha már egyszer beállítottad.</p>
					</div>
				<?php } ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php esc_html_e( 'Registration settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_gems_form_field_checkbox( 'Save customer IP address on registration', 'regip', 'If enabled, the customer\'s IP address will be saved in profile after registration.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Privacy Policy checkbox text on Registration form', 'regacceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.' ); ?>

					<li><strong><?php esc_html_e( 'Checkout settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_gems_form_field_select( 'Legal confirmation checkboxes position on Checkout page', 'legalconfirmationsposition', $legalconfirmationsposition_options, 'woocommerce_review_order_before_submit' ); ?>
					<?php cps_hc_gems_form_field_text( 'Section title on Checkout page', 'legalcheckouttitle', 'Legal confirmations', 'Title above the checkbox. If empty, then no title will be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Section text on Checkout page', 'legalcheckouttext', '', 'General description of the legal section. If empty, then this text will not be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Terms of Service checkbox text on Checkout page', 'accepttos', 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Privacy Policy checkbox text on Checkout page', 'acceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_gems_form_field_text( 'Custom 1 checkbox label on Checkout page', 'acceptcustom1label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Custom 1 checkbox text on Checkout page', 'acceptcustom1', '', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Make Custom 1 checkbox optional', 'legalcheckout-custom1optional', 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.' ); ?>
					<?php cps_hc_gems_form_field_text( 'Custom 2 checkbox label on Checkout page', 'acceptcustom2label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Custom 2 checkbox text on Checkout page', 'acceptcustom2', '', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Make Custom 2 checkbox optional', 'legalcheckout-custom2optional', 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Custom text before Place order button', 'beforeorderbuttonmessage', '', 'This text will be displayed just above the Place order button on Checkout page. If empty, then no text will be displayed.' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'Custom text after Place order button', 'afterorderbuttonmessage', '', 'This text will be displayed just under the Place order button on Checkout page. If empty, then no text will be displayed.' ); ?>

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
					<?php cps_hc_gems_form_field_text( 'Name', 'globalinfoname', '', false, false, false, 'Shortcode: <code>[hc-nev]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Company', 'globalinfocompany', '', false, false, false, 'Shortcode: <code>[hc-ceg]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Headquarters', 'globalinfoheadquarters', '', false, false, false, 'Shortcode: <code>[hc-szekhely]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Tax number', 'globalinfotaxnumber', '', false, false, false, 'Shortcode: <code>[hc-adoszam]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Company registration number', 'globalinforegnumber', '', false, false, false, 'Shortcode: <code>[hc-cegjegyzekszam]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Address of store', 'globalinfoaddress', '', false, false, false, 'Shortcode: <code>[hc-cim]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Bank account number', 'globalinfobankaccount', '', false, false, false, 'Shortcode: <code>[hc-bankszamlaszam]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Mobile phone number', 'globalinfomobile', '', false, false, false, 'Shortcode: <code>[hc-mobiltelefon]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Telephone number', 'globalinfophone', '', false, false, false, 'Shortcode: <code>[hc-telefon]</code>' ); ?>
					<?php cps_hc_gems_form_field_text( 'Email', 'globalinfoemail', '', false, false, false, 'Shortcode: <code>[hc-email]</code>' ); ?>
					<?php cps_hc_gems_form_field_textarea( 'About Us', 'globalinfoaboutus', '', false, false, false, ' | Shortcode: <code>[hc-rolunk]</code>' ); ?>

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
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Translations for premium plugins & themes', 'surbma-magyar-woocommerce' ); ?></h3>

				<?php echo wp_kses_post( $pro_notice ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Adds translations for most popular premium plugins & themes. Supported softwares added regularly. Please let us know, what plugin or theme do you need to be translated next time!', 'surbma-magyar-woocommerce' ); ?></p>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Kestrel API Manager for WooCommerce', 'translations-woocommerceapimanager' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Restrict Content Pro', 'translations-restrictcontentpro' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'WooCommerce Memberships', 'translations-woocommercememberships' ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'WooCommerce Subscriptions', 'translations-woocommercesubscriptions' ); ?>
				</ul>

				<div class="uk-overflow-auto uk-margin-large-top">
					<table class="uk-table uk-table-divider uk-table-justify uk-table-small">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Supported softwares', 'surbma-magyar-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Type', 'surbma-magyar-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Link', 'surbma-magyar-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Languages', 'surbma-magyar-woocommerce' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Kestrel API Manager for WooCommerce</td>
								<td><span class="uk-label"><?php esc_html_e( 'Plugin', 'surbma-magyar-woocommerce' ); ?></span></td>
								<td><a href="https://automattic.pxf.io/PO6mB6" target="_blank">https://woocommerce.com/products/woocommerce-api-manager/</a></td>
								<td><code>HU</code></td>
							</tr>
							<tr>
								<td>Restrict Content Pro</td>
								<td><span class="uk-label"><?php esc_html_e( 'Plugin', 'surbma-magyar-woocommerce' ); ?></span></td>
								<td><a href="https://restrictcontentpro.com/" target="_blank">https://restrictcontentpro.com/</a></td>
								<td><code>HU</code></td>
							</tr>
							<tr>
								<td>WooCommerce Memberships</td>
								<td><span class="uk-label"><?php esc_html_e( 'Plugin', 'surbma-magyar-woocommerce' ); ?></span></td>
								<td><a href="https://automattic.pxf.io/JKmRBQ" target="_blank">https://woocommerce.com/products/woocommerce-memberships/</a></td>
								<td><code>HU</code></td>
							</tr>
							<tr>
								<td>WooCommerce Subscriptions</td>
								<td><span class="uk-label"><?php esc_html_e( 'Plugin', 'surbma-magyar-woocommerce' ); ?></span></td>
								<td><a href="https://automattic.pxf.io/OemyzA" target="_blank">https://woocommerce.com/products/woocommerce-subscriptions/</a></td>
								<td><code>HU</code></td>
							</tr>
						</tbody>
					</table>
				</div>
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
					<?php cps_hc_gems_form_field_checkbox( 'Add placeholder to this field', 'taxnumberplaceholder', false, false, true ); ?>
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
					<?php cps_hc_gems_form_field_checkbox( 'Product subtitle', 'productsubtitle', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Remove image zoom on single product pages', 'productsettings-removeimagezoom', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Add to cart button on archive pages', 'addtocartonarchive', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Remove related products on single product pages', 'norelatedproducts', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Number of products on archive pages', 'productsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Products per row on archive pages', 'productsperrow', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Number of upsell products on single product pages', 'upsellproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Upsell products per row on single product pages', 'upsellproductsperrow', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Number of related products on single product pages', 'relatedproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_number( 'Related products per row on single product pages', 'relatedproductsperrow', '', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Checkout page customizations', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Conditional display of Company fields', 'billingcompanycheck', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Company and Tax number fields, if billing country is not Hungary', 'checkout-hidecompanytaxfields', false, true, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Country field', 'nocountry', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Order notes field', 'noordercomments', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Additional information section', 'noadditionalinformation', 'It will hide Order notes field also.', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Inline Company and Tax number fields', 'companytaxnumberpair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Inline Postcode and City fields', 'postcodecitypair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Inline Phone and Email fields', 'phoneemailpair', false, false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Make Email field the first field', 'emailtothetop', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Custom submit button text', 'checkout-customsubmitbuttontext', '', false, true, true ); ?>
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
					<?php cps_hc_gems_form_field_select( 'Button position on Cart page', 'returntoshopcartposition', $returntoshopcartposition_options, 'cartactions', false, false, true ); ?>
					<?php cps_hc_gems_form_field_select( 'Button position on Checkout page', 'returntoshopcheckoutposition', $returntoshopcheckoutposition_options, 'nocheckout', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Message text', 'returntoshopmessage', 'Would you like to continue shopping?', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Login and registration redirection', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_text( 'Redirection URL after Login', 'loginredirecturl', '', 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Redirection URL after Registration', 'registrationredirecturl', '', 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Coupon field customizations', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Show Coupons in upper case', 'couponuppercase', 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Coupon field on Cart page', 'couponfieldhiddenoncart', 'It will hide the Coupon field completely from the Cart page.', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Hide Coupon field on Checkout page', 'couponfieldhiddenoncheckout', 'It will hide the Coupon field completely from the Checkout page.', false, true ); ?>
					<?php cps_hc_gems_form_field_checkbox( 'Coupon field always visible on Checkout page', 'couponfieldalwaysvisible', 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', false, true ); ?>
					<?php cps_hc_gems_form_field_select( 'Reposition the Coupon field', 'couponfieldposition', $couponfieldposition_options, 'beforecheckoutform', false, false, true ); ?>
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
					<?php cps_hc_gems_form_field_text( 'Simple product', 'custom-addtocart-button-single-simple', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Grouped product', 'custom-addtocart-button-single-grouped', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-single-external', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Variable product', 'custom-addtocart-button-single-variable', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-single-booking', '', false, false, true ); ?>
					<li><strong><?php esc_html_e( 'Product archive pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php esc_html_e( 'Give your custom texts to your Add to cart buttons on the product archive pages. You can set custom texts for different product types. If you leave them empty, the button texts will inherit texts from single product settings or fall back to default WooCommerce texts, if those fields are also empty.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_gems_form_field_text( 'Simple product', 'custom-addtocart-button-archive-simple', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Grouped product', 'custom-addtocart-button-archive-grouped', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-archive-external', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Variable product', 'custom-addtocart-button-archive-variable', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-archive-booking', '', false, false, true ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Hide shipping methods', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_checkbox( 'Hide Shipping methods on Cart page', 'hideshippingmethods-cart', 'It will hide all Shipping methods on the Cart page.', true, true ); ?>
					<?php cps_hc_gems_form_field_select( 'Shipping methods to hide, when free shipping is available', 'shippingmethodstohide', $shippingmethodstohide_options, 'showall', false, false, true ); ?>
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
					<?php cps_hc_gems_form_field_select( 'SMTP port number', 'smtpport', $smtpport_options, '587', false, false, true ); ?>
					<?php cps_hc_gems_form_field_select( 'Encryption type', 'smtpsecure', $smtpsecure_options, 'default', false, false, true ); ?>
					<?php cps_hc_gems_form_field_text( 'SMTP From email address', 'smtpfrom', '', false, false, true, 'Optional' ); ?>
					<?php cps_hc_gems_form_field_text( 'SMTP From name', 'smtpfromname', '', false, false, true, 'Optional' ); ?>
					<?php cps_hc_gems_form_field_text( 'The hostname of the mail server', 'smtphost', '', false, false, true, false, 'world' ); ?>
					<?php cps_hc_gems_form_field_text( 'Username to use for SMTP authentication', 'smtpuser', '', false, false, true, false, 'user' ); ?>
					<?php cps_hc_gems_form_field_password( 'Password to use for SMTP authentication', 'smtppassword', '', false, false, true, false, 'lock' ); ?>
				</ul>
			</li>
			<li>
				<h3 class="uk-card-title"><?php esc_html_e( 'Catalog mode', 'surbma-magyar-woocommerce' ); ?></h3>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_gems_form_field_select( 'Product price display', 'catalogmode-productpricedisplay', $catalogmode_productpricedisplay_options, 'none', false, true, true ); ?>
				</ul>
			</li>
		</ul>
		<div class="uk-text-center uk-margin-top"><input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-large" value="<?php esc_attr_e( 'Save Changes', 'surbma-magyar-woocommerce' ); ?>" /></div>
	</form>
	<?php
}


