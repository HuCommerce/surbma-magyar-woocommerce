<?php

$options = get_option( 'surbma_hc_fields' );
/*
** Product Subtitle
*/
$productsubtitleValue = ( isset( $options['productsubtitle'] ) ? $options['productsubtitle'] : 0 );

if ( $productsubtitleValue ) {
    // The Title filter
    add_filter(
        'the_title',
        function ( $title, $id ) {
        $productsubtitle = get_post_meta( $id, 'surbma_hc_product_subtitle', true );
        if ( 'product' == get_post_type( $id ) && in_the_loop() && $productsubtitle ) {
            $title = $title . ' <span class="product_subtitle" itemprop="description">' . $productsubtitle . '</span>';
        }
        return $title;
    },
        10,
        2
    );
    // Custom style for the Subtitle
    add_action( 'wp_head', function () {
        echo  '<style>.product .product_subtitle {display: block;font-size: smaller;opacity: .75;}</style>' ;
    } );
}
