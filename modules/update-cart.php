<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// https://gist.github.com/mikaelz/f41e29c6a99a595602e4
add_action( 'wp_enqueue_scripts', function() {
	if ( is_cart() ) {
		ob_start();
		?>
<?php if ( wp_basename( get_bloginfo( 'template_directory' ) ) == 'blocksy' ) { ?>
// Blocksy theme fix
jQuery( function( $ ) {
	$(document.body).on('click', '.ct-increase, .ct-decrease', function() {
		var $qty = $( this ).closest( '.quantity' ).find( '.qty');
		$qty.trigger( 'input' );
	});
} );
<?php } ?>

var timeout;

jQuery( function( $ ) {
	$('div.woocommerce').on('input', '.qty', function() {
		if ( timeout !== undefined ) {
			clearTimeout( timeout );
		}

		if ( $(this).val() == '' ) return;

		timeout = setTimeout(function() {
			$("[name='update_cart']").trigger('click');
		}, '1000' );
	});
} );
<?php
		$script = ob_get_contents();
		ob_end_clean();

		wp_add_inline_script( 'cps-jquery-fix', $script );
	}
} );
