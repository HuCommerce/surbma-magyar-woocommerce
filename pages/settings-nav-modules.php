<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// * HUCOMMERCE START
$options = get_option( 'surbma_hc_fields' );
$freeNotification = SURBMA_HC_PREMIUM ? '' : '<div class="cps-alert uk-alert-danger uk--text-center uk--text-bold uk--padding" uk-alert><p><strong>Ha szeretnéd aktiválni ezt a modult, előbb HuCommerce Pro előfizetést kell vásárolnod!</strong><br>A HuCommerce Pro előfizetés megvásárlásával további fantasztikus funkciókat, reklámmentes kezelőfelületet és kiemelt ügyfélszolgálati segítséget kapsz.</p><a href="https://www.hucommerce.hu/hc/vasarlas/hc-pro/" class="uk-button uk--button-default uk-button-danger uk-button-small" target="_blank">HuCommerce Pro megvásárlása</a></div>';
$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );
$legacyconfirmation = 'free' == SURBMA_HC_PLUGIN_LICENSE && $options && !isset( $options['brandnewuser'] ) ? 'onsubmit="return confirm(\'FIGYELEM! Mivel a HuCommerce ingyenes verzióját használod, ezért az űrlap mentése kikapcsolja az összes HuCommerce Pro modult, tehát esetleg azokat is, amik eddig használatban voltak. Mindenképpen mented az űrlapot?\');"' : '';
// * HUCOMMERCE END

$allowed_html = array(
	'div' => array(
		'class'  => array(),
		'uk-alert'  => array(),
	),
	'p' => array(
		'class'  => array(),
	),
	'strong' => array(),
	'br' => array(),
	'a' => array(
		'href'  => array(),
		'class'  => array(),
		'target'  => array(),
	),
);

global $couponfieldposition_options;
global $returntoshopcartposition_options;
global $returntoshopcheckoutposition_options;
global $shippingmethodstohide_options;
global $legalconfirmationsposition_options;
global $smtpport_options;
global $smtpsecure_options;

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
?>

<form class="uk-form-stacked" method="post" action="options.php" <?php echo $legacyconfirmation; ?>>
	<?php settings_fields( 'surbma_hc_options' ); ?>

	<ul class="uk-list uk-list-large" uk-accordion>
		<?php // * HUCOMMERCE START ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Fixes for Hungarian language', 'huformatfix' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Fixes for Hungarian language', 'huformatfix', false, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Fixes the name formats in Hungarian. Changes the order of Last name and First name.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/magyar-formatum-javitasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<?php // * HUCOMMERCE END ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Tax number field', 'taxnumberplaceholder' ); ?>
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
				<?php cps_hc_wcgems_form_field_main( 'Tax number field', 'taxnumber', true, false, true ); ?>

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
				<?php cps_hc_wcgems_form_field_main( 'Hungarian translation fixes', 'translations', false, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Temporary fixes for Hungarian translations, till the official translation doesn\’t include or missing some strings.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/forditasi-hianyossagok-javitasa/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Hide County field if Country is Hungary', 'nocounty' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Hide County field if Country is Hungary', 'nocounty', false, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Using County for Hungarian addresses is very uncommon in Hungary.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/megye-mezo-elrejtese-magyar-cim-eseten/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Autofill City after Postcode is given', 'autofillcity' ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Autofill City after Postcode is given', 'autofillcity', false, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'On the Checkout page the City field be automatically filled, when Postcode is entered by the customer.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/varos-automatikus-kitoltese-az-iranyitoszam-alapjan/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Check field formats (Masking)', 'maskcheckoutfields', true ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Check field formats (Masking)', 'maskcheckoutfields', true, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Masking with placeholder', 'maskcheckoutfieldsplaceholder', 'The masking scheme will be displayed as a placeholder in the field. This will override the default placeholder.', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Tax field', 'maskbillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Postcode field', 'maskbillingpostcodefield', 'Allows only 4 numbers.', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Phone field', 'maskbillingphonefield', false, true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping Postcode field', 'maskshippingpostcodefield', 'Allows only 4 numbers.', true, true, 1 ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Masking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/mezok-formatumanak-ellenorzese-maszkolas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Check field values', 'validatecheckoutfields', true ); ?>
			<div class="uk-accordion-content">
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Check field values', 'validatecheckoutfields', true, false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Tax field', 'validatebillingtaxfield', 'Allowed formats: 00000000-0-00, 00000000000, HU00000000', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing City field', 'validatebillingcityfield', 'Allows only letters and space.', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Address field', 'validatebillingaddressfield', 'Must have at least one letter, one number and one space in the address.', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Billing Phone field', 'validatebillingphonefield', false, true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping City field', 'validateshippingcityfield', 'Allows only letters and space.', true, true, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Shipping Address field', 'validateshippingaddressfield', 'Must have at least one letter, one number and one space in the address.', true, true, 1 ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Checking these fields: Billing VAT number, Billing Postcode, Billing Phone, Shipping Postcode', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/mezok-ertekenek-ellenorzese/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Product price history', 'module-productpricehistory', true, true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<div class="uk-alert-primary cps-alert" uk-alert>
					<p><strong>BETA verzió</strong><br>
					Ez a modul nincs minden körülmény között tesztelve és nem tudja 100%-ban teljesíteni a funkcionális és/vagy jogi igényeket, feltételeket. Ezért a használata esetén fokozott figyelmet igényel.<br>
					FIGYELEM! A HuCommerce ügyfélszolgálatára beküldött visszajelzések és javaslatok jelentősen gyorsítják a modul fejlesztését, ezért szívesen várjuk az ilyen témájú megkereséseket. Köszönjük!</p>
				</div>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Product price history', 'module-productpricehistory', false, true, false ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show lowest price on Product pages', 'productpricehistory-showlowestprice', 'It will show the lowest price from the product price history log automatically.', true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Text before the lowest price', 'productpricehistory-lowestpricetext', 'Our lowest price from previous term', false, true, false, 'HTML tags are allowed' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show the calculated discount on Product pages', 'productpricehistory-showdiscount', 'It will show the discount, that is calculated from the lowest price automatically.', true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Text before the discount', 'productpricehistory-discounttext', 'Current discount based on the lowest price', false, true, false, 'HTML tags are allowed' ); ?>
					<li>
						<div class="uk-alert-danger cps-alert" uk-alert>
							<p>FIGYELEM! Minden terméknél beállítható egy egyedi szöveg, ami megjelenik az adott terméknél az ár alatt. Ez felülírja a fenti beállításokat.</p>
						</div>
					</li>

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
		<?php // * HUCOMMERCE END ?>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Product customizations', 'module-productsettings', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Product customizations', 'module-productsettings' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Product subtitle', 'productsubtitle' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Add to cart button on archive pages', 'addtocartonarchive' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Remove related products on single product pages', 'norelatedproducts', false, true ); ?>
					<li><strong><?php _e( 'Product archive pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<?php cps_hc_wcgems_form_field_text( 'Number of products on archive pages', 'productsnumber', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Products per row on archive pages', 'productsperrow', false, false, true ); ?>
					<li><strong><?php _e( 'Single product pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<?php cps_hc_wcgems_form_field_text( 'Number of upsell products on single product pages', 'upsellproductsnumber', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Upsell products per row on single product pages', 'upsellproductsperrow', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Number of related products on single product pages', 'relatedproductsnumber', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Related products per row on single product pages', 'relatedproductsperrow', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Extra fields and other customizations for Products.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/termek-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Checkout page customizations', 'module-checkout', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Checkout page customizations', 'module-checkout' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Conditional display of Company fields', 'billingcompanycheck' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Country field', 'nocountry' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Order notes field', 'noordercomments' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Additional information section', 'noadditionalinformation', 'It will hide Order notes field also.', true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Company and Tax number fields', 'companytaxnumberpair' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Postcode and City fields', 'postcodecitypair' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Inline Phone and Email fields', 'phoneemailpair' ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Make Email field the first field', 'emailtothetop' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Extra fields and other customizations on the Checkout page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/penztar-oldal-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Plus/minus quantity buttons', 'plusminus' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Plus/minus quantity buttons', 'plusminus' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Shows plus/minus quantity buttons for products.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/plusz-minusz-mennyisegi-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Automatic Cart update', 'updatecart' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Automatic Cart update', 'updatecart' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will automatically update the cart, when customer changes the quantity on the Cart page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kosar-automatikus-frissitese-darabszam-modositas-utan/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Continue shopping buttons', 'returntoshop' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Continue shopping buttons', 'returntoshop', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_select( 'Button position on Cart page', 'returntoshopcartposition', $returntoshopcartposition_options, 'cartactions' ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Button position on Checkout page', 'returntoshopcheckoutposition', $returntoshopcheckoutposition_options, 'nocheckout' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Message text', 'returntoshopmessage', 'Would you like to continue shopping?' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'A Continue shopping button on Cart and/or Checkout pages, that will bring customer to Shop page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/vasarlas-folytatasa-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Login and registration redirection', 'loginregistrationredirect' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Login and registration redirection', 'loginregistrationredirect', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_text( 'Redirection URL after Login', 'loginredirecturl', false, 'Absolute URL path. If empty, then default WooCommerce redirection will be set.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Redirection URL after Registration', 'registrationredirecturl', false, 'Absolute URL path. If empty, then default WooCommerce redirection will be set.' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Set custom landing pages after login and/or registration.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/belepes-es-regisztracio-utani-atiranyitas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Free shipping notification', 'freeshippingnotice', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Free shipping notification', 'freeshippingnotice' ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Product listing pages', 'freeshippingnoticeshoploop', false, true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Cart page', 'freeshippingnoticecart', false, false, false, 1 ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Show on Checkout page', 'freeshippingnoticecheckout', false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Message text', 'freeshippingnoticemessage', 'The remaining amount to get FREE shipping' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'A notification on the Cart page to let customer know, how much total purchase is missing to get free shipping.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/ingyenes-szallitas-ertesites/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'legalcheckout' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Legal compliance (GDPR, CCPA, ePrivacy)', 'legalcheckout', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php esc_html_e( 'Registration settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_wcgems_form_field_checkbox( 'Save customer IP address on registration', 'regip', 'If enabled, the customer\'s IP address will be saved in profile after registration.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Privacy Policy checkbox text on Registration form', 'regacceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.', false, false, 'HTML tags are allowed' ); ?>

					<li><strong><?php esc_html_e( 'Checkout settings', 'surbma-magyar-woocommerce' ); ?></strong></li>

					<?php cps_hc_wcgems_form_field_select( 'Legal confirmation checkboxes position on Checkout page', 'legalconfirmationsposition', $legalconfirmationsposition_options, 'revieworderbeforesubmit' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Section title on Checkout page', 'legalcheckouttitle', 'Legal confirmations', 'Title above the checkbox. If empty, then no title will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Terms of Service checkbox text on Checkout page', 'accepttos', 'I\'ve read and accept the <a href="/tos/" target="_blank">Terms of Service</a>', 'If empty, then this checkbox will not be displayed.', false, false, 'HTML tags are allowed' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Privacy Policy checkbox text on Checkout page', 'acceptpp', 'I\'ve read and accept the <a href="/privacy-policy/" target="_blank">Privacy Policy</a>', 'If empty, then this checkbox will not be displayed.', false, false, 'HTML tags are allowed' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 1 checkbox label on Checkout page', 'acceptcustom1label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 1 checkbox text on Checkout page', 'acceptcustom1', '', 'If empty, then this checkbox will not be displayed.', false, false, 'HTML tags are allowed' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 2 checkbox label on Checkout page', 'acceptcustom2label', '', 'The label of the custom checkbox field. Used by the error message, if checkbox is not accepted. If empty, then no error message will be displayed.' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Custom 2 checkbox text on Checkout page', 'acceptcustom2', '', 'If empty, then this checkbox will not be displayed.', false, false, 'HTML tags are allowed' ); ?>
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
			<?php cps_hc_wcgems_form_accordion_title( 'Coupon field customizations', 'module-coupon', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Coupon field customizations', 'module-coupon', true, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<?php cps_hc_wcgems_form_field_checkbox( 'Show Coupons in upper case', 'couponuppercase', 'Show Coupons in upper case in both admin and front-end, instead of lower case, which is the default setting for WooCommerce.', true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Coupon field on Cart page', 'couponfieldhiddenoncart', 'It will hide the Coupon field completely from the Cart page.', true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Hide Coupon field on Checkout page', 'couponfieldhiddenoncheckout', 'It will hide the Coupon field completely from the Checkout page.', true ); ?>
					<?php cps_hc_wcgems_form_field_checkbox( 'Coupon field always visible on Checkout page', 'couponfieldalwaysvisible', 'It will hide the Coupon field toggle and makes the Coupon field always visible for customers.', true ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Reposition the Coupon field', 'couponfieldposition', $couponfieldposition_options, 'beforecheckoutform', false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Useful settings for the Coupon field on the Checkout page.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kupon-mezo-modositasok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Redirect Cart page to Checkout page', 'module-redirectcart', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Redirect Cart page to Checkout page', 'module-redirectcart', false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will redirect the Cart page to Checkout page, so visitors can finish the purchase faster.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/kosar-atiranyitasa-a-penztar-oldalra/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'One product per purchase', 'module-oneproductincart', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'One product per purchase', 'module-oneproductincart', false, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will allow only one product in the cart. If cart has a product already, it will be replaced by the new product.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/egy-termek-vasarlasonkent/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Custom Add To Cart Button', 'module-custom-addtocart-button', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Custom Add To Cart Button', 'module-custom-addtocart-button', true, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><strong><?php _e( 'Single product pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php _e( 'Give your custom texts to your Add to cart buttons on the product pages. You can set custom texts for different product types. If you leave them empty, the button texts will fall back to default WooCommerce texts.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_text( 'Simple product', 'custom-addtocart-button-single-simple', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Grouped product', 'custom-addtocart-button-single-grouped', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-single-external', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable product', 'custom-addtocart-button-single-variable', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-subscription', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-single-variable-subscription', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-single-booking', false, false, true ); ?>
					<li><strong><?php _e( 'Product archive pages', 'surbma-magyar-woocommerce' ); ?></strong></li>
					<li><p><?php _e( 'Give your custom texts to your Add to cart buttons on the product archive pages. You can set custom texts for different product types. If you leave them empty, the button texts will inherit texts from single product settings or fall back to default WooCommerce texts, if those fields are also empty.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_text( 'Simple product', 'custom-addtocart-button-archive-simple', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Grouped product', 'custom-addtocart-button-archive-grouped', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'External/Affiliate product', 'custom-addtocart-button-archive-external', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable product', 'custom-addtocart-button-archive-variable', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-subscription', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Variable subscription product (WooCommerce Subscriptions)', 'custom-addtocart-button-archive-variable-subscription', false, false, true ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Bookable product (WooCommerce Bookings)', 'custom-addtocart-button-archive-booking', false, false, true ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Customize the Add to cart buttons for your webhop.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/egyedi-kosarba-teszem-gombok/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Hide shipping methods', 'module-hideshippingmethods', true ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Hide shipping methods', 'module-hideshippingmethods', true, true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><strong><?php esc_html_e( 'Compatible shipping plugins (Pickup methods)', 'surbma-magyar-woocommerce' ); ?>:</strong> <br>Hungarian Pickup Points for WooCommerce, Pont WooCommerce (Szathmári), Foxpost, Foxpost Parcel, Postapont</p></li>
					<?php cps_hc_wcgems_form_field_select( 'Shipping methods to hide, when free shipping is available', 'shippingmethodstohide', $shippingmethodstohide_options, 'hideall' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'It will hide all shipping methods, except free shipping, local pickup and other pickup points, when free shipping is available for customers.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/szallitasi-modok-elrejtese/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'Global informations', 'module-globalinfo' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'Global informations', 'module-globalinfo', true ); ?>

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
		<li>
			<?php cps_hc_wcgems_form_accordion_title( 'SMTP service', 'module-smtp' ); ?>
			<div class="uk-accordion-content">
				<?php // * HUCOMMERCE START ?>
				<?php echo wp_kses( $freeNotification, $allowed_html ); ?>
				<?php // * HUCOMMERCE END ?>
				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Activate module', 'surbma-magyar-woocommerce' ); ?></h5>
				<?php cps_hc_wcgems_form_field_main( 'SMTP service', 'module-smtp', true ); ?>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module settings', 'surbma-magyar-woocommerce' ); ?></h5>
				<ul class="cps-form-fields uk-list uk-list-divider">
					<li><p><?php esc_html_e( 'SMTP service is a must have for all WooCommerce webshops, as it makes your transactional email delivery more stable and secure. Register a new account at a 3rd party SMTP service and set your credentials here to enable this feature.', 'surbma-magyar-woocommerce' ); ?></p></li>
					<?php cps_hc_wcgems_form_field_select( 'SMTP port number', 'smtpport', $smtpport_options, '587' ); ?>
					<?php cps_hc_wcgems_form_field_select( 'Encryption type', 'smtpsecure', $smtpsecure_options, 'default' ); ?>

					<?php cps_hc_wcgems_form_field_text( 'SMTP From email address', 'smtpfrom', '', false, false, false, 'Optional' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'SMTP From name', 'smtpfromname', '', false, false, false, 'Optional' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'The hostname of the mail server', 'smtphost', '', false, false, false, false, 'world' ); ?>
					<?php cps_hc_wcgems_form_field_text( 'Username to use for SMTP authentication', 'smtpuser', '', false, false, false, false, 'user' ); ?>

					<?php cps_hc_wcgems_form_field_password( 'Password to use for SMTP authentication', 'smtppassword', '', false, false, false, false, 'lock' ); ?>
				</ul>

				<h5 class="uk-heading-divider uk-text-bold"><?php esc_html_e( 'Module description', 'surbma-magyar-woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Send emails from a 3rd party SMTP service, instead of using webserver\'s mail() function.', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a href="https://www.hucommerce.hu/dokumentum/smtp-szolgaltatas/" target="_blank"><?php esc_html_e( 'Read more', 'surbma-magyar-woocommerce' ); ?> <span uk-icon="icon: sign-out"></span></a></p>
			</div>
		</li>
	</ul>

	<div class="uk-text-center uk-margin-top"><input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-large" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></div>

</form>
