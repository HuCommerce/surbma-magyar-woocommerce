<?php

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Update _hc_product_price_history post meta
function surbma_hc_update_product_price_history( $product_id ) {
	$product = wc_get_product( $product_id );

	// Stop if we don't process a product
	if ( ! $product ) {
		return;
	}

	$product_regular_price = intval( $product->get_regular_price() );
	$product_sale_price = intval( $product->get_sale_price() );

	// Always get the actual/active price
	$product_price = intval( $product->get_price() );

	//
	if ( 0 === $product_price || 0 === $product_regular_price ) {
		$product_price_discount = 0;
	} else {
		$product_price_discount = intval( number_format( round( ( ( 1 - ( $product_price / $product_regular_price ) ) * 100 ), 2 ), 2 ) );
	}

	$current_time = current_datetime();
	$current_time = strval( date( 'Y-m-d H:i:s', $current_time->getTimestamp() + $current_time->getOffset() ) );

	/* I had to remove this condition to save price changes even, when the active and regular prices are equal
	// Stop if there is no active sale
	if ( $product_regular_price == $product_price ) {
		return;
	}
	*/

	if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) {
		$product_price_history = get_post_meta( $product_id, '_hc_product_price_history', true );
		$product_regular_price_old = $product_price_history[0][1];
		$product_price_old = $product_price_history[0][2];

		// Save only when any price changes
		if ( $product_regular_price != $product_regular_price_old || $product_price != $product_price_old ) {
			$product_price_new = array( $current_time, $product_regular_price, $product_price );
			array_unshift( $product_price_history, $product_price_new );
			update_post_meta( $product_id, '_hc_product_price_history', $product_price_history );
		}
	} else {
		$product_price_history = array(
			array( $current_time, $product_regular_price, $product_price )
		);
		add_post_meta( $product_id, '_hc_product_price_history', $product_price_history );
	}
}

/* The problem with these methods is, that they run multiple times
// Trigger surbma_hc_update_product_price_history when a product price changed
add_action( 'updated_post_meta', function( $meta_id, $product_id, $meta_key, $meta_value ) {
	if ( '_price' == $meta_key || '_regular_price' == $meta_key ) {
		surbma_hc_update_product_price_history( $product_id );
	}
}, 10, 4 );

// Trigger surbma_hc_update_product_price_history when a product price deleted
add_action( 'deleted_post_meta', function( $meta_id, $product_id, $meta_key, $meta_value ) {
	if ( '_price' == $meta_key || '_regular_price' == $meta_key ) {
		surbma_hc_update_product_price_history( $product_id );
	}
}, 10, 4 );
*/

/* The problem with this method is, that it runs 1 or 2 or 3 times, so the condition is not reliable
// Trigger surbma_hc_update_product_price_history when a product is updated
add_action( 'woocommerce_update_product', function( $product_id ) {
	// Fires at the second run to get all price data correctly
	$times = did_action( 'woocommerce_update_product' );
	if ( $times === 2 ) {
		surbma_hc_update_product_price_history( $product_id );
	}
}, 10, 1 );
*/

// Trigger surbma_hc_update_product_price_history when a product is updated or created
add_action( 'wp_insert_post', function( $post_id, $post, $update ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'product' !== $post->post_type ) {
		return;
	}

	surbma_hc_update_product_price_history( $post_id );
}, 10, 3 );

/*
 *
 * Trigger surbma_hc_update_product_price_history when a product price changed with WooCommerce import.
 *
 * $product is a WC_Product
 * $data is an array of data pulled from the CSV
 *
 * Product ID: $data['id']
 *
*/
add_action( 'woocommerce_product_import_inserted_product_object', function( $object, $data ) {
	if ( isset( $data['id'] ) && $data['id'] ) {
		surbma_hc_update_product_price_history( $data['id'] );
	}
}, 10, 2 );

/*
 *
 * Trigger surbma_hc_update_product_price_history when a product price changed with WP All Import.
 * It seems, we don't need this, as WP All Import import process triggers the normal import hooks also.
 *
*/
/*
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'wp-all-import-pro/wp-all-import-pro.php' ) ) {
	add_action( 'pmxi_saved_post', function( $post_id, $xml_node, $is_update ) {
		$post_type = wp_all_import_get_import_post_type();
		if ( 'product' === $post_type ) {
			surbma_hc_update_product_price_history( $post_id );
		}
	}, 10, 3 );
}
*/

// Add custom fields in the General tab of the Product data metabox
add_action( 'woocommerce_product_options_pricing', function() {
	// Abort function if HuCommerce Pro is not active
	if ( ! SURBMA_HC_PREMIUM ) {
		return;
	}

	// We are on the edit product page, right? Let's get the product ID from the URL.
	$product_id = ( isset( $_GET['post'] ) ) ? $_GET['post'] : false;

	// If we are on the product edit page, get the product object. If it is not the product edit page, return.
	if ( $product_id ) {
		$product = wc_get_product( $product_id );
	} else {
		return;
	}

	// This function has to run only for simple products
	if ( ! $product->is_type( 'simple' ) ) {
		return;
	}

	// Show a link to the product price history page
	?>
	<p class="form-field">
		<label><?php esc_html_e( 'Price history', 'surbma-magyar-woocommerce' ); ?></label>
		<a href="<?php echo SURBMA_HC_PLUGIN_URL; ?>/modules-hu/product-price-history-display.php?product_id=<?php echo $product_id; ?>" target="_blank"><?php esc_html_e( 'Show the price history of this product', 'surbma-magyar-woocommerce' ); ?></a> (<?php esc_html_e( 'Open on a new tab', 'surbma-magyar-woocommerce' ); ?>)
	</p>
	<?php

	// Textarea to set custom lowest price text
	woocommerce_wp_textarea_input( array(
		'id' => '_hc_product_lowest_price_text',
		'label' => __( 'Lowest price text', 'surbma-magyar-woocommerce' ),
		'wrapper_class' => 'form-field-wide',
		'description' => __( 'This text will be displayed just below the Product\'s price. It will overwrite the automatic text generated by HuCommerce settings.', 'surbma-magyar-woocommerce' ),
		'desc_tip' => true
	) );

	// Checkbox to hide lowest price text
	woocommerce_wp_checkbox( array(
		'id' => '_hc_product_hide_lowest_price_text',
		'label' => __( 'Hide lowest price text', 'surbma-magyar-woocommerce' ),
		'wrapper_class' => 'form-field-wide',
		'description' => __( 'With this option enabled, you can hide the lowest price text for this product.', 'surbma-magyar-woocommerce' ),
		'desc_tip' => true
	) );
} );

// Add custom fields in the General tab of the Variable product data metabox
add_action( 'woocommerce_variation_options_pricing', function( $loop, $variation_data, $variation ) {
	// Abort function if HuCommerce Pro is not active
	if ( ! SURBMA_HC_PREMIUM ) {
		return;
	}

	// Show a link to the product price history page
	$product_id = ( isset( $_GET['post'] ) ) ? $_GET['post'] : false;
	if ( $variation->ID ) {
	?>
	<p class="form-field form-field-wide form-row form-row-full">
		<label><?php esc_html_e( 'Price history', 'surbma-magyar-woocommerce' ); ?></label>
		<a href="<?php echo SURBMA_HC_PLUGIN_URL; ?>/modules-hu/product-price-history-display.php?product_id=<?php echo $variation->ID; ?>" target="_blank"><?php esc_html_e( 'Show the price history of this product', 'surbma-magyar-woocommerce' ); ?></a> (<?php esc_html_e( 'Open on a new tab', 'surbma-magyar-woocommerce' ); ?>)
	</p>
	<?php
	}

	// Textarea to set custom lowest price text
	woocommerce_wp_textarea_input( array(
		'id' => '_hc_product_lowest_price_text[' . $loop . ']',
		'label' => __( 'Lowest price text', 'surbma-magyar-woocommerce' ),
		'wrapper_class' => 'form-field-wide form-row form-row-full',
		'description' => __( 'This text will be displayed just below the Product\'s price. It will overwrite the automatic text generated by HuCommerce settings.', 'surbma-magyar-woocommerce' ),
		'desc_tip' => true,
		'value' => get_post_meta( $variation->ID, '_hc_product_lowest_price_text', true )
	) );

	// Checkbox to hide lowest price text
	woocommerce_wp_checkbox( array(
		'id' => '_hc_product_hide_lowest_price_text[' . $loop . ']',
		'label' => '&nbsp;' . __( 'Hide lowest price text', 'surbma-magyar-woocommerce' ),
		'wrapper_class' => 'form-field-wide form-row form-row-full',
		'description' => __( 'With this option enabled, you can hide the lowest price text for this product.', 'surbma-magyar-woocommerce' ),
		'desc_tip' => true,
		'value' => get_post_meta( $variation->ID, '_hc_product_hide_lowest_price_text', true )
	) );
}, 10, 3 );

add_action( 'woocommerce_save_product_variation', function( $variation_id, $i ) {
	// Abort function if HuCommerce Pro is not active
	if ( ! SURBMA_HC_PREMIUM ) {
		return;
	}

	$lowestpricetextValue = isset( $_POST['_hc_product_lowest_price_text'][$i] ) ? $_POST['_hc_product_lowest_price_text'][$i] : false;
	$hidelowestpricetextValue = isset( $_POST['_hc_product_hide_lowest_price_text'][$i] ) ? $_POST['_hc_product_hide_lowest_price_text'][$i] : false;

	if ( $lowestpricetextValue ) {
		update_post_meta( $variation_id, '_hc_product_lowest_price_text', esc_attr( $lowestpricetextValue ) );
	}
	if ( $hidelowestpricetextValue ) {
		update_post_meta( $variation_id, '_hc_product_hide_lowest_price_text', esc_attr( $hidelowestpricetextValue ) );
	}

	surbma_hc_update_product_price_history( $variation_id );
}, 10, 2 );

add_action( 'woocommerce_process_product_meta', function( $post_id ) {
	// Abort function if HuCommerce Pro is not active
	if ( ! SURBMA_HC_PREMIUM ) {
		return;
	}

	$lowestpricetextValue = isset( $_POST['_hc_product_lowest_price_text'] ) ? $_POST['_hc_product_lowest_price_text'] : '';
	$hidelowestpricetextValue = isset( $_POST['_hc_product_hide_lowest_price_text'] ) ? $_POST['_hc_product_hide_lowest_price_text'] : '';

	$product = wc_get_product( $post_id );
	$product->update_meta_data( '_hc_product_lowest_price_text', $lowestpricetextValue );
	$product->update_meta_data( '_hc_product_hide_lowest_price_text', $hidelowestpricetextValue );
	$product->save();
} );

// Product price history display
add_shortcode( 'hc-termekartortenet', function( $atts ) {
	// Abort function if HuCommerce Pro is not active
	if ( ! SURBMA_HC_PREMIUM ) {
		return;
	}

	extract( shortcode_atts( array(
		'product_id' => ''
	), $atts ) );

	$options = get_option( 'surbma_hc_fields' );
	$productpricehistory_showlowestpriceValue = isset( $options['productpricehistory-showlowestprice'] ) && 1 == $options['productpricehistory-showlowestprice'] ? 1 : 0;
	$productpricehistory_lowestpricetextValue = isset( $options['productpricehistory-lowestpricetext'] ) && $options['productpricehistory-lowestpricetext'] ? $options['productpricehistory-lowestpricetext'] : __( 'Our lowest price from previous term', 'surbma-magyar-woocommerce' );
	$productpricehistory_showdiscountpriceValue = isset( $options['productpricehistory-showdiscount'] ) && 1 == $options['productpricehistory-showdiscount'] ? 1 : 0;
	$productpricehistory_discounttextValue = isset( $options['productpricehistory-discounttext'] ) && $options['productpricehistory-discounttext'] ? $options['productpricehistory-discounttext'] : __( 'Current discount based on the lowest price', 'surbma-magyar-woocommerce' );
	$productpricehistory_showstatisticslinkValue = isset( $options['productpricehistory-showstatisticslink'] ) && 1 == $options['productpricehistory-showstatisticslink'] ? 1 : 0;
	$productpricehistory_statisticslinktextValue = isset( $options['productpricehistory-statisticslinktext'] ) && $options['productpricehistory-statisticslinktext'] ? $options['productpricehistory-statisticslinktext'] : __( 'Advanced statistics', 'surbma-magyar-woocommerce' );

	global $product;
	$product_id = $product_id ? $product_id : $product->get_id();
	$product_lowestpricetext = get_post_meta( $product_id, '_hc_product_lowest_price_text' ) ? get_post_meta( $product_id, '_hc_product_lowest_price_text', true ) : false;
	$product_hidelowestpricetext = get_post_meta( $product_id, '_hc_product_hide_lowest_price_text' ) ? get_post_meta( $product_id, '_hc_product_hide_lowest_price_text', true ) : false;
	$product_regular_price = intval( $product->get_regular_price() );
	$product_price = intval( $product->get_price() );

	// Stop if not enabled by the settings
	if ( !$productpricehistory_showlowestpriceValue && !$productpricehistory_showdiscountpriceValue && !$product_lowestpricetext ) {
		return;
	}

	// Stop if there is no active sale
	if ( $product_regular_price == $product_price ) {
		return;
	}

	// Stop if this is hidden by the product's settings
	if ( $product_hidelowestpricetext ) {
		return;
	}

	global $woocommerce;
	$curreny_symbol = get_woocommerce_currency_symbol();

	// Build the lowest price array
	$lowest_price_array = array();
	if ( get_post_meta( $product_id, '_hc_product_price_history' ) ) {
		$product_price_history = get_post_meta( $product_id, '_hc_product_price_history', true );

		for( $i = 1; $i < count( $product_price_history ) ; $i++ ) {
			if ( strtotime( $product_price_history[$i][0] ) < strtotime( '-30 day', strtotime( $product_price_history[0][0] ) ) ) {
				break;
			}
			$lowest_price_array[] = $product_price_history[$i][2];
		}

		// Check if last saved price is more, then 30 days old. In this case, empty $lowest_price_array to disable the lowest price display.
		// This check is disabled for now, because we need to check the last 30 days since the last day, the price was dicounted.
		/*
		if ( strtotime( $product_price_history[0][0] ) < strtotime( '-30 day', time() ) ) {
			$lowest_price_array = array();
		}
		*/
	}
	array_multisort( $lowest_price_array, SORT_ASC );

	// Get the lowest price from the array
	$lowest_price = $lowest_price_array ? $lowest_price_array[0] : false;

	// If we have the product price and the lowest price, get the discount
	$discount = $product_price && $lowest_price ? number_format( round( ( ( 1 - ( $product_price / $lowest_price ) ) * 100 ), 2 ), 0 ) : false;

	ob_start();

		echo '<div class="hc-product-price-history product_meta">';
		// Custom text will overwrite the module's settings
		if ( $product_lowestpricetext ) {
			echo wp_kses_post( $product_lowestpricetext );
		} else {
			if ( $productpricehistory_showlowestpriceValue && $lowest_price ) {
				echo '<div class="hc-product-price-history-price">' . wp_kses_post( $productpricehistory_lowestpricetextValue ) . ': <span>' . $lowest_price . ' ' . $curreny_symbol . '</span></div>';
			}
			if ( $productpricehistory_showdiscountpriceValue && $discount && ( 0 <= $discount ) ) {
				echo '<div class="hc-product-price-history-discount">' . wp_kses_post( $productpricehistory_discounttextValue ) . ': <span>' . $discount . '%</span></div>';
			}
		}
		if ( $productpricehistory_showstatisticslinkValue ) {
			echo '<div class="hc-product-price-history-statistics"><a href="' . SURBMA_HC_PLUGIN_URL . '/modules-hu/product-price-history-display.php?product_id=' . $product_id . '" target="_blank">' . esc_html( $productpricehistory_statisticslinktextValue ) . '</a></div>';
		}
		echo '</div>';

	$output_string = ob_get_contents();
	ob_end_clean();

	return $output_string;
} );

// Show the notification under the Product's price
add_action( 'woocommerce_single_product_summary', function() {
	global $product;

	// This function has to run only for simple products
	if ( ! $product->is_type( 'simple' ) ) {
		return;
	}

	echo do_shortcode( '[hc-termekartortenet]' );
}, 11 );
