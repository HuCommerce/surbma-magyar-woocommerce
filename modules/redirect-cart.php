<?php

add_action( 'template_redirect', function() {
	if ( is_cart() ) {
		wp_safe_redirect( wc_get_checkout_url() );
		exit;
	}
} );
