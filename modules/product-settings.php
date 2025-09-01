<?php

/**
 * Module: Product customizations
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Get the module's settings - moved to a function for better performance
function surbma_hc_get_product_settings() {
	static $settings = null;
	if ( $settings === null ) {
		global $hc_gems_options;
		$settings = [
			'productsubtitle' => $hc_gems_options['productsubtitle'] ?? 0,
			'removeimagezoom' => $hc_gems_options['productsettings-removeimagezoom'] ?? false,
			'addtocartonarchive' => $hc_gems_options['addtocartonarchive'] ?? false,
			'norelatedproducts' => $hc_gems_options['norelatedproducts'] ?? false,
		];
	}
	return $settings;
}

/*
 ** Metabox for Products - Admin only
 */

// Admin functionality - use admin_init for proper admin context
add_action( 'admin_init', function() {
	$settings = surbma_hc_get_product_settings();
	if ( $settings['productsubtitle'] ) {
		// Registering Metabox for Products
		add_action( 'add_meta_boxes', 'surbma_hc_register_product_metabox' );
		add_action( 'save_post', 'surbma_hc_save_product_metabox', 1, 2 );
	}
} );

function surbma_hc_register_product_metabox() {
	add_meta_box(
		'surbma_hc_product_metabox',
		'HuCommerce ' . __( 'Product Settings', 'surbma-magyar-woocommerce' ),
		'surbma_hc_product_metabox',
		'product',
		'normal',
		'high'
	);
}

// Metabox on the Product edit page
function surbma_hc_product_metabox() {
	global $post;

	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'surbma_hc_product_settings_nonce' );

	// Get the field data if it's already been entered
	$productsubtitle = get_post_meta( $post->ID, 'surbma_hc_product_subtitle', true );

	// Output the fields
	echo '<p>';
	echo '<label for="surbma_hc_product_subtitle">' . esc_html__( 'Product Subtitle', 'surbma-magyar-woocommerce' ) . '</label>';
	echo '<input id="surbma_hc_product_subtitle" name="surbma_hc_product_subtitle" type="text" value="' . esc_textarea( $productsubtitle ) . '" class="widefat">';
	echo '</p>';

	echo '<hr><p style="text-align: center;font-size: smaller;">' . esc_html__( 'These settings are added by the HuCommerce plugin.', 'surbma-magyar-woocommerce' ) . '</p>';
}

// Saving the fields in the Metabox
function surbma_hc_save_product_metabox( $post_id, $post ) {
	// Return if the user doesn't have edit permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Don't store meta data, if this is a revision
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// Verify this came from our screen and with proper authorization
	if ( ! isset( $_POST['surbma_hc_product_subtitle'] ) || ! isset( $_POST['surbma_hc_product_settings_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['surbma_hc_product_settings_nonce'] ), basename(__FILE__) ) ) {
		return $post_id;
	}

	// Sanitize and save the data
	$productsubtitle = sanitize_text_field( wp_unslash( $_POST['surbma_hc_product_subtitle'] ) );
	
	if ( $productsubtitle ) {
		update_post_meta( $post_id, 'surbma_hc_product_subtitle', $productsubtitle );
	} else {
		delete_post_meta( $post_id, 'surbma_hc_product_subtitle' );
	}
}

/*
 ** Product Subtitle - Frontend only
 */

// Frontend functionality - use template_redirect for proper frontend context
add_action( 'template_redirect', function() {
	$settings = surbma_hc_get_product_settings();
	if ( $settings['productsubtitle'] ) {
		// The Title filter
		add_filter( 'the_title', 'surbma_hc_add_product_subtitle', 10, 2 );
		// Custom style for the Subtitle
		add_action( 'wp_head', 'surbma_hc_product_subtitle_styles' );
	}
} );

function surbma_hc_add_product_subtitle( $title, $id ) {
	$productsubtitle = get_post_meta( $id, 'surbma_hc_product_subtitle', true );

	if ( 'product' == get_post_type( $id ) && in_the_loop() && $productsubtitle ) {
		$title = $title . ' <span class="product_subtitle" itemprop="description">' . $productsubtitle . '</span>';
	}

	return $title;
}

function surbma_hc_product_subtitle_styles() {
	echo '<style>.product .product_subtitle {display: block;font-size: smaller;opacity: .75;}</style>';
}

/*
 ** Other Product Settings - Frontend only
 */

// Frontend product settings - use template_redirect for proper frontend context
add_action( 'template_redirect', function() {
	$settings = surbma_hc_get_product_settings();
	
	// Remove Image Zoom
	if ( $settings['removeimagezoom'] ) {
		remove_theme_support( 'wc-product-gallery-zoom' );
	}
	
	// Add to cart button on archive pages
	if ( $settings['addtocartonarchive'] ) {
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );
	}
	
	// Remove related products output
	if ( $settings['norelatedproducts'] ) {
		add_action( 'woocommerce_after_single_product_summary', function() {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}, 0 );
	}
} );

/*
 ** Products per page
 */

add_filter( 'loop_shop_per_page', function( $cols ) {
	// Get the settings array
	global $hc_gems_options;

	$productsettings_productsnumberValue = $hc_gems_options['productsnumber'] ?? false;

	if ( $productsettings_productsnumberValue ) {
		return $productsettings_productsnumberValue;
	}

	return $cols;
}, 20 );

/*
 ** Products per row
 */

add_filter( 'loop_shop_columns', function( $columns ) {
	// Get the settings array
	global $hc_gems_options;

	$productsettings_productsperrowValue = $hc_gems_options['productsperrow'] ?? false;

	if ( $productsettings_productsperrowValue ) {
		return $productsettings_productsperrowValue;
	}

	return $columns;
}, 999 );

/**
 * Change upsell products output
 */

add_filter( 'woocommerce_upsell_display_args', function( $args ) {
	// Get the settings array
	global $hc_gems_options;

	$productsettings_upsellproductsnumberValue = $hc_gems_options['upsellproductsnumber'] ?? false;
	$productsettings_upsellproductsperrowValue = $hc_gems_options['upsellproductsperrow'] ?? false;

	if ( $productsettings_upsellproductsnumberValue ) {
		$args['posts_per_page'] = $productsettings_upsellproductsnumberValue;
	}

	if ( $productsettings_upsellproductsperrowValue ) {
		$args['columns'] = $productsettings_upsellproductsperrowValue;
	}

	return $args;
}, 20 );

/*
 ** Change related products output
 */

add_filter( 'woocommerce_output_related_products_args', function( $args ) {
	// Get the settings array
	global $hc_gems_options;

	$productsettings_relatedproductsnumberValue = $hc_gems_options['relatedproductsnumber'] ?? false;
	$productsettings_relatedproductsperrowValue = $hc_gems_options['relatedproductsperrow'] ?? false;

	if ( $productsettings_relatedproductsnumberValue ) {
		$args['posts_per_page'] = $productsettings_relatedproductsnumberValue;
	}

	if ( $productsettings_relatedproductsperrowValue ) {
		$args['columns'] = $productsettings_relatedproductsperrowValue;
	}

	return $args;
}, 20 );
