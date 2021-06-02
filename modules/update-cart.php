<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// https://gist.github.com/mikaelz/f41e29c6a99a595602e4
add_action( 'wp_enqueue_scripts', function() {
	if ( is_cart() ) {
		ob_start();
		?>
jQuery('div.woocommerce').on('change', '.qty', function() {
	jQuery("[name='update_cart']").removeAttr('disabled');
	jQuery("[name='update_cart']").trigger('click');
});
<?php
		$script = ob_get_contents();
		ob_end_clean();

		wp_add_inline_script( 'cps-jquery-fix', $script );
	}
} );
