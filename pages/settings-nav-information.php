<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

global $hc_gems_options;

$plugin_file = SURBMA_HC_PLUGIN_FILE;
$plugin_data = get_plugin_data( $plugin_file );
$plugin_version = $plugin_data['Version'];
$plugin_name = $plugin_data['Name'];
$plugin_pluginURI = $plugin_data['PluginURI'];

?>
<ul class="uk-list">
	<li><strong><?php esc_html_e( 'Plugin', 'surbma-magyar-woocommerce' ); ?>:</strong> <a class="uk-link-reset" href="<?php echo esc_url( $plugin_pluginURI ); ?>" target="_blank"><?php echo esc_html( $plugin_name ); ?></a></li>
	<li><strong><?php esc_html_e( 'Version', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php echo esc_html( $plugin_version ); ?></li>
	<li><strong><?php esc_html_e( 'License', 'surbma-magyar-woocommerce' ); ?>:</strong> <?php esc_html_e( 'GPLv3 or later License', 'surbma-magyar-woocommerce' ); ?></li>
</ul>

<h4 class="uk-heading-divider"><?php esc_html_e( 'Plugin links', 'surbma-magyar-woocommerce' ); ?></h4>
<ul class="uk-list">
	<li><a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce" target="_blank">Hivatalos támogató fórum a WordPress.org weboldalon</a></li>
	<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/#reviews" target="_blank">Olvasd el az értékeléseket (5/5 csillag)</a></li>
</ul>
<hr>
<p>
	<strong>Tetszik a bővítmény? Kérlek értékeld 5 csillaggal:</strong>
	 <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Új értékelés létrehozása</a>
</p>

<h4 class="uk-heading-divider">Tervezett funkciók</h4>
<ul class="uk-list">
	<li><span uk-icon="icon: check; ratio: 0.8"></span> Webáruházak kötelező jogi megfelelésének a technikai biztosítása.</li>
	<li><span uk-icon="icon: check; ratio: 0.8"></span> Köszönő oldal egyedi módosítási lehetősége.</li>
</ul>

<h4 class="uk-heading-divider"><?php esc_html_e( 'HuCommerce options', 'surbma-magyar-woocommerce' ); ?></h4>
<textarea id="hc-gems-options" class="uk-textarea" cols="50" rows="10" style="background: #000;" readonly><?php print_r( $hc_gems_options ); ?></textarea>
<button class="uk-button uk-button-secondary uk-margin-top" onclick="copyHuCommerceOptions()"><?php esc_html_e( 'Copy HuCommerce options', 'surbma-magyar-woocommerce' ); ?></button>

<h4 class="uk-heading-divider"><?php esc_html_e( 'Website information', 'surbma-magyar-woocommerce' ); ?></h4>
<?php
// Global WP_Debug_Data
if ( ! class_exists( 'WP_Debug_Data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
}
if ( ! class_exists( 'WP_Site_Health' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
}
WP_Debug_Data::check_for_updates();
$info = WP_Debug_Data::debug_data();
echo '<textarea id="website-information" class="uk-textarea" cols="50" rows="10" style="background: #000;" readonly>' . esc_attr( WP_Debug_Data::format( $info, 'info' ) ) . '</textarea>';
?>
<button class="uk-button uk-button-secondary uk-margin-top" onclick="copyWebsiteInformation()"><?php esc_html_e( 'Copy website information', 'surbma-magyar-woocommerce' ); ?></button>

<script>
	function copyHuCommerceOptions() {
		/* Get the text field */
		var copyText = document.getElementById("hc-gems-options");

		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /* For mobile devices */

		/* Copy the text inside the text field */
		navigator.clipboard.writeText(copyText.value);

		/* Alert the copied text */
		setTimeout(function() {
			alert("<?php esc_html_e( 'HuCommerce options are copied', 'surbma-magyar-woocommerce' ); ?>");
		}, 500);
	}

	function copyWebsiteInformation() {
		/* Get the text field */
		var copyText = document.getElementById("website-information");

		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /* For mobile devices */

		/* Copy the text inside the text field */
		navigator.clipboard.writeText(copyText.value);

		/* Alert the copied text */
		setTimeout(function() {
			alert("<?php esc_html_e( 'Website information is copied', 'surbma-magyar-woocommerce' ); ?>");
		}, 500);
	}
</script>
