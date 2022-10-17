<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// * HUCOMMERCE START
$options = get_option( 'surbma_hc_fields' );
$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );
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

// Translation fixes
__( 'Fixes for Hungarian language', 'surbma-magyar-woocommerce' );
__( 'Tax number field', 'surbma-magyar-woocommerce' );
__( 'Add placeholder to this field', 'surbma-magyar-woocommerce' );
__( 'Hungarian translation fixes', 'surbma-magyar-woocommerce' );
__( 'Hide County field if Country is Hungary', 'surbma-magyar-woocommerce' );
__( 'Autofill City after Postcode is given', 'surbma-magyar-woocommerce' );
__( 'Check field formats (Masking)', 'surbma-magyar-woocommerce' );
__( 'Masking with placeholder', 'surbma-magyar-woocommerce' );
__( 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.', 'surbma-magyar-woocommerce' );
__( 'Billing Tax field', 'surbma-magyar-woocommerce' );
__( 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', 'surbma-magyar-woocommerce' );
__( 'Billing Postcode field', 'surbma-magyar-woocommerce' );
__( 'Allows only 4 numbers.', 'surbma-magyar-woocommerce' );
__( 'Billing Phone field', 'surbma-magyar-woocommerce' );
__( 'Shipping Postcode field', 'surbma-magyar-woocommerce' );
__( 'Check field values', 'surbma-magyar-woocommerce' );
__( 'Billing City field', 'surbma-magyar-woocommerce' );
__( 'Allows only letters and space.', 'surbma-magyar-woocommerce' );
__( 'Billing Address field', 'surbma-magyar-woocommerce' );
__( 'Must have at least one letter, one number and one space in the address.', 'surbma-magyar-woocommerce' );
__( 'Shipping City field', 'surbma-magyar-woocommerce' );
__( 'Shipping Address field', 'surbma-magyar-woocommerce' );
__( 'Product price history', 'surbma-magyar-woocommerce' );
__( 'Show lowest price on Product pages', 'surbma-magyar-woocommerce' );
__( 'It will show the lowest price from the product price history log automatically.', 'surbma-magyar-woocommerce' );
__( 'Text before the lowest price', 'surbma-magyar-woocommerce' );
__( 'Our lowest price from previous term', 'surbma-magyar-woocommerce' );
__( 'Show the calculated discount on Product pages', 'surbma-magyar-woocommerce' );
__( 'It will show the discount, that is calculated from the lowest price automatically.', 'surbma-magyar-woocommerce' );
__( 'Text before the discount', 'surbma-magyar-woocommerce' );
__( 'Current discount based on the lowest price', 'surbma-magyar-woocommerce' );
__( 'Product customizations', 'surbma-magyar-woocommerce' );
__( 'Product subtitle', 'surbma-magyar-woocommerce' );
__( 'Add to cart button on archive pages', 'surbma-magyar-woocommerce' );
__( 'Remove related products on single product pages', 'surbma-magyar-woocommerce' );
__( 'Number of products on archive pages', 'surbma-magyar-woocommerce' );
__( 'Products per row on archive pages', 'surbma-magyar-woocommerce' );
__( 'Number of upsell products on single product pages', 'surbma-magyar-woocommerce' );
__( 'Upsell products per row on single product pages', 'surbma-magyar-woocommerce' );
__( 'Number of related products on single product pages', 'surbma-magyar-woocommerce' );
__( 'Related products per row on single product pages', 'surbma-magyar-woocommerce' );
__( 'Checkout page customizations', 'surbma-magyar-woocommerce' );
__( 'Conditional display of Company fields', 'surbma-magyar-woocommerce' );
__( 'Hide Country field', 'surbma-magyar-woocommerce' );
__( 'Hide Order notes field', 'surbma-magyar-woocommerce' );
__( 'Hide Additional information section', 'surbma-magyar-woocommerce' );
__( 'It will hide Order notes field also.', 'surbma-magyar-woocommerce' );
__( 'Inline Company and Tax number fields', 'surbma-magyar-woocommerce' );
__( 'Inline Postcode and City fields', 'surbma-magyar-woocommerce' );
__( 'Inline Phone and Email fields', 'surbma-magyar-woocommerce' );
__( 'Make Email field the first field', 'surbma-magyar-woocommerce' );
__( 'Plus/minus quantity buttons', 'surbma-magyar-woocommerce' );
__( 'Automatic Cart update', 'surbma-magyar-woocommerce' );
__( 'Continue shopping buttons', 'surbma-magyar-woocommerce' );
__( 'Button position on Cart page', 'surbma-magyar-woocommerce' );
__( 'Button position on Checkout page', 'surbma-magyar-woocommerce' );
__( 'Message text', 'surbma-magyar-woocommerce' );
__( 'Login and registration redirection', 'surbma-magyar-woocommerce' );
__( 'Redirection URL after Login', 'surbma-magyar-woocommerce' );
__( 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', 'surbma-magyar-woocommerce' );
__( 'Redirection URL after Registration', 'surbma-magyar-woocommerce' );
__( 'Free shipping notification', 'surbma-magyar-woocommerce' );
__( 'Show on Product listing pages', 'surbma-magyar-woocommerce' );
__( 'Show on Cart page', 'surbma-magyar-woocommerce' );
__( 'Show on Checkout page', 'surbma-magyar-woocommerce' );
__( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'surbma-magyar-woocommerce' );
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
__( 'Coupon field customizations', 'surbma-magyar-woocommerce' );
__( 'Show Coupons in upper case', 'surbma-magyar-woocommerce' );
__( 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', 'surbma-magyar-woocommerce' );
__( 'Hide Coupon field on Cart page', 'surbma-magyar-woocommerce' );
__( 'It will hide the Coupon field completely from the Cart page.', 'surbma-magyar-woocommerce' );
__( 'Hide Coupon field on Checkout page', 'surbma-magyar-woocommerce' );
__( 'It will hide the Coupon field completely from the Checkout page.', 'surbma-magyar-woocommerce' );
__( 'Coupon field always visible on Checkout page', 'surbma-magyar-woocommerce' );
__( 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', 'surbma-magyar-woocommerce' );
__( 'Reposition the Coupon field', 'surbma-magyar-woocommerce' );
__( 'Redirect Cart page to Checkout page', 'surbma-magyar-woocommerce' );
__( 'One product per purchase', 'surbma-magyar-woocommerce' );
__( 'Custom Add To Cart Button', 'surbma-magyar-woocommerce' );
__( 'Simple product', 'surbma-magyar-woocommerce' );
__( 'Grouped product', 'surbma-magyar-woocommerce' );
__( 'External/Affiliate product', 'surbma-magyar-woocommerce' );
__( 'Variable product', 'surbma-magyar-woocommerce' );
__( 'Subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' );
__( 'Variable subscription product (WooCommerce Subscriptions)', 'surbma-magyar-woocommerce' );
__( 'Bookable product (WooCommerce Bookings)', 'surbma-magyar-woocommerce' );
__( 'Hide shipping methods', 'surbma-magyar-woocommerce' );
__( 'Shipping methods to hide, when free shipping is available', 'surbma-magyar-woocommerce' );
__( 'Global informations', 'surbma-magyar-woocommerce' );
__( 'Name', 'surbma-magyar-woocommerce' );
__( 'Company', 'surbma-magyar-woocommerce' );
__( 'Headquarters', 'surbma-magyar-woocommerce' );
__( 'Company registration number', 'surbma-magyar-woocommerce' );
__( 'Address of store', 'surbma-magyar-woocommerce' );
__( 'Bank account number', 'surbma-magyar-woocommerce' );
__( 'Mobile phone number', 'surbma-magyar-woocommerce' );
__( 'Telephone number', 'surbma-magyar-woocommerce' );
__( 'About Us', 'surbma-magyar-woocommerce' );
__( 'SMTP service', 'surbma-magyar-woocommerce' );
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
__( 'Empty Cart button', 'surbma-magyar-woocommerce' );
__( 'Show Empty Cart button on Cart page', 'surbma-magyar-woocommerce' );
__( 'Show Empty Cart button on Checkout page', 'surbma-magyar-woocommerce' );
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
__( 'Message after minimum order amount reached', 'surbma-magyar-woocommerce' );
__( 'Make Custom 1 checkbox optional', 'surbma-magyar-woocommerce' );
__( 'Make Custom 2 checkbox optional', 'surbma-magyar-woocommerce' );
__( 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.', 'surbma-magyar-woocommerce' );
?>

<form class="uk-form-stacked" method="post" action="options.php">
	<?php settings_fields( 'surbma_hc_options' ); ?>

	<ul class="uk-list uk-list-large" uk-accordion>
		<?php // * HUCOMMERCE START ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Fixes for Hungarian language', 'huformatfix' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Fixes for Hungarian language', 'huformatfix', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Fixes the name formats in Hungarian. Changes the order of Last name and First name.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/magyar-formatum-javitasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<?php // * HUCOMMERCE END ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Tax number field', 'taxnumber' ); ?>
			<div class="uk-accordion-content">
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
							<p><?php esc_html_e( 'A Tax number field is already added by the Integration for Billingo & WooCommerce plugin\’s custom field option. If you want to use the Tax field added by the HuCommerce plugin, you need to disable the "Egyedi meta mezőt használok adószámhoz" option at the other plugin\'s settings.', 'surbma-magyar-woocommerce' ); ?></p>
						</div>
					<?php } ?>
				<?php // * HUCOMMERCE END ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Tax number field', 'taxnumber', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Add placeholder to this field', 'taxnumberplaceholder', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Additional Tax field for Company details at Checkout.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/adoszam-megjelenitese/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</div>
		</li>
		<?php // * HUCOMMERCE START ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Hungarian translation fixes', 'translations' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Hungarian translation fixes', 'translations', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Temporary fixes for Hungarian translations, till the official translation doesn\’t include or missing some strings.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/forditasi-hianyossagok-javitasa/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Hide County field if Country is Hungary', 'nocounty' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Hide County field if Country is Hungary', 'nocounty', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Using County for Hungarian addresses is very uncommon in Hungary.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/megye-mezo-elrejtese-magyar-cim-eseten/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Autofill City after Postcode is given', 'autofillcity' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Autofill City after Postcode is given', 'autofillcity', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'On the Checkout page the City field be automatically filled, when Postcode is entered by the customer.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/varos-automatikus-kitoltese-az-iranyitoszam-alapjan/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<?php // * HUCOMMERCE END ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Product customizations', 'module-productsettings' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Product customizations', 'module-productsettings', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Product subtitle', 'productsubtitle', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Add to cart button on archive pages', 'addtocartonarchive', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Remove related products on single product pages', 'norelatedproducts', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Number of products on archive pages', 'productsnumber', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Products per row on archive pages', 'productsperrow', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Number of upsell products on single product pages', 'upsellproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Upsell products per row on single product pages', 'upsellproductsperrow', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Number of related products on single product pages', 'relatedproductsnumber', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Related products per row on single product pages', 'relatedproductsperrow', '', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Extra fields and other customizations for Products.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/termek-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Checkout page customizations', 'module-checkout' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Checkout page customizations', 'module-checkout', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Conditional display of Company fields', 'billingcompanycheck', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Country field', 'nocountry', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Order notes field', 'noordercomments', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Additional information section', 'noadditionalinformation', 'It will hide Order notes field also.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Company and Tax number fields', 'companytaxnumberpair', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Postcode and City fields', 'postcodecitypair', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Phone and Email fields', 'phoneemailpair', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Make Email field the first field', 'emailtothetop', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Extra fields and other customizations on the Checkout page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/penztar-oldal-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Plus/minus quantity buttons', 'plusminus' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Plus/minus quantity buttons', 'plusminus', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Shows plus/minus quantity buttons for products.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/plusz-minusz-mennyisegi-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Automatic Cart update', 'updatecart' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Automatic Cart update', 'updatecart', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will automatically update the cart, when customer changes the quantity on the Cart page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kosar-automatikus-frissitese-darabszam-modositas-utan/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Continue shopping buttons', 'returntoshop' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Continue shopping buttons', 'returntoshop', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_select( 'Button position on Cart page', 'returntoshopcartposition', $returntoshopcartposition_options, 'cartactions', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Button position on Checkout page', 'returntoshopcheckoutposition', $returntoshopcheckoutposition_options, 'nocheckout', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Message text', 'returntoshopmessage', 'Would you like to continue shopping?', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'A Continue shopping button on Cart and/or Checkout pages, that will bring customer to Shop page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/vasarlas-folytatasa-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Login and registration redirection', 'loginregistrationredirect' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Login and registration redirection', 'loginregistrationredirect', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_text( 'Redirection URL after Login', 'loginredirecturl', '', 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Redirection URL after Registration', 'registrationredirecturl', '', 'Absolute URL path. If empty, then default WooCommerce redirection will be set.', false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Set custom landing pages after login and/or registration.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/belepes-es-regisztracio-utani-atiranyitas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Coupon field customizations', 'module-coupon' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Coupon field customizations', 'module-coupon', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show Coupons in upper case', 'couponuppercase', 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Coupon field on Cart page', 'couponfieldhiddenoncart', 'It will hide the Coupon field completely from the Cart page.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Coupon field on Checkout page', 'couponfieldhiddenoncheckout', 'It will hide the Coupon field completely from the Checkout page.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Coupon field always visible on Checkout page', 'couponfieldalwaysvisible', 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Reposition the Coupon field', 'couponfieldposition', $couponfieldposition_options, 'beforecheckoutform', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Useful settings for the Coupon field on the Checkout page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kupon-mezo-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Redirect Cart page to Checkout page', 'module-redirectcart' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Redirect Cart page to Checkout page', 'module-redirectcart', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will redirect the Cart page to Checkout page, so visitors can finish the purchase faster.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kosar-atiranyitasa-a-penztar-oldalra/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'One product per purchase', 'module-oneproductincart' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'One product per purchase', 'module-oneproductincart', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will allow only one product in the cart. If cart has a product already, it will be replaced by the new product.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/egy-termek-vasarlasonkent/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Custom Add To Cart Button', 'module-custom-addtocart-button' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Custom Add To Cart Button', 'module-custom-addtocart-button', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php _e( 'Single product pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php _e( 'Give your custom texts to your Add to cart buttons on the product pages. You can set custom texts for different product types. If you leave them empty, the button texts will fall back to default WooCommerce texts.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_text( 'Simple product', 'custom-addtocart-button-single-simple', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Grouped product', 'custom-addtocart-button-single-grouped', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-single-external', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable product', 'custom-addtocart-button-single-variable', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-subscription', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-single-booking', '', false, false, true ); ?>
					<li><strong><?php _e( 'Product archive pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php _e( 'Give your custom texts to your Add to cart buttons on the product archive pages. You can set custom texts for different product types. If you leave them empty, the button texts will inherit texts from single product settings or fall back to default WooCommerce texts, if those fields are also empty.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_text( 'Simple product', 'custom-addtocart-button-archive-simple', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Grouped product', 'custom-addtocart-button-archive-grouped', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-archive-external', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable product', 'custom-addtocart-button-archive-variable', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-subscription', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-variable-subscription', '', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-archive-booking', '', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Customize the Add to cart buttons for your webhop.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/egyedi-kosarba-teszem-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Hide shipping methods', 'module-hideshippingmethods' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Hide shipping methods', 'module-hideshippingmethods', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><strong><?php esc_html_e( 'Compatible shipping plugins (Pickup methods)', 'surbma-magyar-woocommerce' ); ?>:</strong> <br>Hungarian Pickup Points for WooCommerce, Pont shipping for Woocommerce (Szathmári), Foxpost, Foxpost Parcel, Postapont</p></li>
					<?php cps_hc_wcgems_form_field_select( 'Shipping methods to hide, when free shipping is available', 'shippingmethodstohide', $shippingmethodstohide_options, 'hideall', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will hide all shipping methods, except free shipping, local pickup and other pickup points, when free shipping is available for customers.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/szallitasi-modok-elrejtese/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'SMTP service', 'module-smtp' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'SMTP service', 'module-smtp', true ); ?>
				<br>
				<?php
					$current_user = wp_get_current_user();
					$current_user_email = urlencode( $current_user->user_email );
				?>
				<a href="<?php echo esc_url( add_query_arg( 'hc-test-email', $current_user_email ) ); ?>" class="uk-button uk-button-primary" uk-tooltip="title: <?php esc_html_e( 'Clicking on this button will send a test email to the actual user\'s email address.', 'surbma-magyar-woocommerce' ); ?>; pos: right"><?php esc_html_e( 'Send test email', 'surbma-magyar-woocommerce' ); ?></a>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><?php esc_html_e( 'SMTP service is a must have for all WooCommerce webshops, as it makes your transactional email delivery more stable and secure. Register a new account at a 3rd party SMTP service and set your credentials here to enable this feature.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_select( 'SMTP port number', 'smtpport', $smtpport_options, '587', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Encryption type', 'smtpsecure', $smtpsecure_options, 'default', false, false, true ); ?>

					<?php cps_hc_wcgems_form_field_text( 'SMTP From email address', 'smtpfrom', '', false, false, true, 'Optional' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'SMTP From name', 'smtpfromname', '', false, false, true, 'Optional' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'The hostname of the mail server', 'smtphost', '', false, false, true, false, 'world' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Username to use for SMTP authentication', 'smtpuser', '', false, false, true, false, 'user' ); ?>

					<?php cps_hc_wcgems_form_field_password( 'Password to use for SMTP authentication', 'smtppassword', '', false, false, true, false, 'lock' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Send emails from a 3rd party SMTP service, instead of using webserver\'s mail() function.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/smtp-szolgaltatas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
	</ul>

	<h4>HuCommerce Pro modulok</h4>
	<hr>

	<?php if ( !SURBMA_HC_PREMIUM ) { ?>
		<div class="cps-alert uk-alert-danger" uk-alert>
			<p><strong>Ha szeretnéd aktiválni ezeket a modulokat, előbb HuCommerce Pro előfizetést kell vásárolnod!</strong><br>A HuCommerce Pro előfizetés megvásárlásával további fantasztikus funkciókat és kiemelt ügyfélszolgálati segítséget kapsz.</p>
			<a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="uk-button uk-button-danger uk-button-small" target="_blank">HuCommerce Pro megvásárlása</a>
		</div>
	<?php } ?>

	<ul class="uk-list uk-list-large" uk-accordion>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Check field formats (Masking)', 'maskcheckoutfields' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Check field formats (Masking)', 'maskcheckoutfields' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Masking with placeholder', 'maskcheckoutfieldsplaceholder', 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Tax field', 'maskbillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Postcode field', 'maskbillingpostcodefield', 'Allows only 4 numbers.', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Phone field', 'maskbillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping Postcode field', 'maskshippingpostcodefield', 'Allows only 4 numbers.', false, false, 1 ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Masking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/mezok-formatumanak-ellenorzese-maszkolas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Check field values', 'validatecheckoutfields' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Check field values', 'validatecheckoutfields' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Tax field', 'validatebillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing City field', 'validatebillingcityfield', 'Allows only letters and space.', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Address field', 'validatebillingaddressfield', 'Must have at least one letter, one number and one space in the address.', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Phone field', 'validatebillingphonefield', false, false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping City field', 'validateshippingcityfield', 'Allows only letters and space.', false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping Address field', 'validateshippingaddressfield', 'Must have at least one letter, one number and one space in the address.', false, false, 1 ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Checking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/mezok-ertekenek-ellenorzese/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Free shipping notification', 'freeshippingnotice' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Free shipping notification', 'freeshippingnotice' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Product listing pages', 'freeshippingnoticeshoploop' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Cart page', 'freeshippingnoticecart' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Checkout page', 'freeshippingnoticecheckout' ); ?>
					<?php cps_hc_wcgems_form_field_number( 'Minimum order amount', 'freeshippingminimumorderamount', '', 'Users will need to spend this amount to get free shipping.' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Apply minimum order rule before coupon discount', 'freeshippingcouponsdiscounts', 'If checked, free shipping would be available based on pre-discount order amount.' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Apply minimum order rule without tax', 'freeshippingwithouttax', 'If checked, free shipping would be available based on order amount exclusive of tax.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Message before minimum order amount reached', 'freeshippingnoticemessage', 'The remaining amount to get FREE shipping' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Message when minimum order amount reached', 'freeshippingsuccessfulmessage', '', 'If you would like to show a message, when minimum order amount reached. Leave empty if you do not want to show this notice to customers.' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'A notification on the Cart page to let customer know, how much total purchase is missing to get free shipping.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/ingyenes-szallitas-ertesites/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Empty Cart button', 'module-emptycartbutton', true ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Empty Cart button', 'module-emptycartbutton' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_select( 'Button position on Cart page', 'emptycartbutton-cartpage', $emptycartbutton_cartpage_options, 'none' ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Button position on Checkout page', 'emptycartbutton-checkoutpage', $emptycartbutton_checkoutpage_options, 'none' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Message text', 'emptycartbutton-checkoutpagemessage', 'Changed your mind?' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Link text', 'emptycartbutton-checkoutpagelinktext', 'Empty cart & continue shopping' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Confirmation text', 'emptycartbutton-checkoutpageconfirmationtext', 'Are you sure you want to empty the Cart?' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will display buttons, that can empty the entire Cart with one click. You can also add a custom link to your navigation with a special parameter, so it is possible to have an Empty Cart link in your menu. Read more about this option in our Documentation.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kosar-uritese-gomb/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Product price history', 'module-productpricehistory' ); ?>
			<div class="uk-accordion-content">
				<div class="uk-alert-primary cps-alert" uk-alert>
					<p>Ez a modul nincs minden körülmény között tesztelve és nem tudja 100%-ban teljesíteni a funkcionális és/vagy jogi igényeket, feltételeket. Ezért a használata esetén fokozott figyelmet igényel.<br>
					FIGYELEM! A HuCommerce ügyfélszolgálatára beküldött visszajelzések és javaslatok jelentősen gyorsítják a modul fejlesztését, ezért szívesen várjuk az ilyen témájú megkereséseket. Köszönjük!</p>
				</div>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Product price history', 'module-productpricehistory' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show lowest price on Product pages', 'productpricehistory-showlowestprice', 'It will show the lowest price from the product price history log automatically.', true ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Text before the lowest price', 'productpricehistory-lowestpricetext', 'Our lowest price from previous term' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show the calculated discount on Product pages', 'productpricehistory-showdiscount', 'It will show the discount, that is calculated from the lowest price automatically.', true ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Text before the discount', 'productpricehistory-discounttext', 'Current discount based on the lowest price' ); ?>

					<li>
						<div class="uk-alert-primary cps-alert" uk-alert>
							<p>FIGYELEM! Minden terméknél beállítható egy egyedi szöveg, ami megjelenik az adott terméknél az ár alatt. Ez felülírja a fenti beállításokat.</p>
						</div>
					</li>

					<?php cps_hc_wcgems_form_field_checkbox( 'Show the link for advanced statistics on Product pages', 'productpricehistory-showstatisticslink', 'It will show a link also on the Product pages, where visitors can see a more detailed Product price history for the actual Product.', true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Text for the advanced statistics link', 'productpricehistory-statisticslinktext', 'Advanced statistics', false, true ); ?>

					<li>
						<label class="uk-form-label"><?php esc_html_e( 'Allowed HTML tags', 'surbma-magyar-woocommerce' ); ?></label>
						<div class="uk-form-controls">
							<pre><?php echo cps_wcgems_hc_allowed_post_tags(); ?></pre>
						</div>
					</li>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Saves all Product price changes and can display the lowest price from the previous term. This is a Hungarian legal requirement to protect customers rights.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/termek-ar-tortenet/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Product price additions', 'module-productpriceadditions', true ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Product price additions', 'module-productpriceadditions' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_text( 'Price prefix on Product page', 'productpriceadditions-product-prefix' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Price suffix on Product page', 'productpriceadditions-product-suffix' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Price prefix on Archive pages', 'productpriceadditions-archive-prefix' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Price suffix on Archive pages', 'productpriceadditions-archive-suffix' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Set a default prefix or suffix for your prices. You can use this feature to give a unit of measure for your product prices or give a general information, that is specific for your webshop and your products. With the above settings you can give your default, global prefix and suffix, but you can customize these fields per product also. Even, you can remove it, when you edit your products.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/termek-ar-kiegeszitesek/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'legalcheckout' ); ?>
			<div class="uk-accordion-content">
		<?php // HuCommerce legacy users notice ?>
				<?php if ( 'free' == SURBMA_HC_PLUGIN_LICENSE && $options && !isset( $options['brandnewuser'] ) ) { ?>
					<div class="cps-alert uk-alert-danger" uk-alert>
						<p><strong class="uk-text-uppercase">Figyelem!</strong> A "Jogi megfelelés" modul átkerült a HuCommerce fizetős, Pro verziójába. Minden eddigi beállítás továbbra is működik, de módosítani nem lehet a beállításokat. Mentés után is használhatod a modult korlátlan ideig, ha már egyszer beállítottad.</p>
					</div>
				<?php } ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'legalcheckout' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php esc_html_e( 'Registration settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_wcgems_form_field_checkbox( 'Save customer IP address on registration', 'regip', 'If enabled, the customer\'s IP address will be saved in profile after registration.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Privacy Policy checkbox text on Registration form', 'regacceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.' ); ?>

					<li><strong><?php esc_html_e( 'Checkout settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_wcgems_form_field_select( 'Legal confirmation checkboxes position on Checkout page', 'legalconfirmationsposition', $legalconfirmationsposition_options, 'revieworderbeforesubmit' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Section title on Checkout page', 'legalcheckouttitle', 'Legal confirmations', 'Title above the checkbox. If empty, then no title will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Terms of Service checkbox text on Checkout page', 'accepttos', 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Privacy Policy checkbox text on Checkout page', 'acceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 1 checkbox label on Checkout page', 'acceptcustom1label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Custom 1 checkbox text on Checkout page', 'acceptcustom1', '', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Make Custom 1 checkbox optional', 'legalcheckout-custom1optional', 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 2 checkbox label on Checkout page', 'acceptcustom2label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Custom 2 checkbox text on Checkout page', 'acceptcustom2', '', 'If empty, then this checkbox will not be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Make Custom 2 checkbox optional', 'legalcheckout-custom2optional', 'If this option is enabled, the checkbox on the Checkout page won\'t be required anymore.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Custom text before Place order button', 'beforeorderbuttonmessage', '', 'This text will be displayed just above the Place order button on Checkout page. If empty, then no text will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'Custom text after Place order button', 'afterorderbuttonmessage', '', 'This text will be displayed just under the Place order button on Checkout page. If empty, then no text will be displayed.' ); ?>

					<li>
						<label class="uk-form-label"><?php esc_html_e( 'Allowed HTML tags', 'surbma-magyar-woocommerce' ); ?></label>
						<div class="uk-form-controls">
							<pre><?php echo cps_wcgems_hc_allowed_post_tags(); ?></pre>
						</div>
					</li>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Custom Terms & Conditions and Privacy Policy checkboxes on Checkout page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/jogi-megfeleles/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Disclaimer', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'HuCommerce modules are tools to comply with local and/or international rules and laws, but it is the webshop owner\'s duty to make sure to comply with all rules and laws! Developers and the owners of HuCommerce take no responsibility for any legal compliance. However our mission is to provide all necessary tools for these challenges.', 'surbma-magyar-woocommerce' ); ?></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Global informations', 'module-globalinfo' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Global informations', 'module-globalinfo' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><?php esc_html_e( 'Use these fields for your global informations and show them with shortcodes. Your email will be safe from bots and your phone number will be active to call you with one tap on mobiles.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_text( 'Name', 'globalinfoname', '', false, false, false, 'Shortcode: <code>[hc-nev]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Company', 'globalinfocompany', '', false, false, false, 'Shortcode: <code>[hc-ceg]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Headquarters', 'globalinfoheadquarters', '', false, false, false, 'Shortcode: <code>[hc-szekhely]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Tax number', 'globalinfotaxnumber', '', false, false, false, 'Shortcode: <code>[hc-adoszam]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Company registration number', 'globalinforegnumber', '', false, false, false, 'Shortcode: <code>[hc-cegjegyzekszam]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Address of store', 'globalinfoaddress', '', false, false, false, 'Shortcode: <code>[hc-cim]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Bank account number', 'globalinfobankaccount', '', false, false, false, 'Shortcode: <code>[hc-bankszamlaszam]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Mobile phone number', 'globalinfomobile', '', false, false, false, 'Shortcode: <code>[hc-mobiltelefon]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Telephone number', 'globalinfophone', '', false, false, false, 'Shortcode: <code>[hc-telefon]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Email', 'globalinfoemail', '', false, false, false, 'Shortcode: <code>[hc-email]</code>' ); ?>
					<?php cps_hc_wcgems_form_field_textarea( 'About Us', 'globalinfoaboutus', '', false, false, false, ' | Shortcode: <code>[hc-rolunk]</code>' ); ?>

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

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Use these fields for your global informations and show them with shortcodes. Your email will be safe from bots and your phone number will be active to call you with one tap on mobiles. Local data will be semantic for search engines.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/globalis-adatok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
	</ul>

	<div class="uk-text-center uk-margin-top"><input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-large" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></div>

</form>
