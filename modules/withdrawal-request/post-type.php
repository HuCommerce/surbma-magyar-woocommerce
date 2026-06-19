<?php

/**
 * Withdrawal request custom post type registration.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_type(
			cps_hc_gems_withdraw_get_post_type(),
			array(
				'labels'              => array(
					'name'               => __( 'Withdrawal requests', 'surbma-magyar-woocommerce' ),
					'singular_name'      => __( 'Withdrawal request', 'surbma-magyar-woocommerce' ),
					'menu_name'          => __( 'Withdrawal requests', 'surbma-magyar-woocommerce' ),
					'all_items'          => __( 'Withdrawal requests', 'surbma-magyar-woocommerce' ),
					'view_item'          => __( 'View withdrawal request', 'surbma-magyar-woocommerce' ),
					'search_items'       => __( 'Search withdrawal requests', 'surbma-magyar-woocommerce' ),
					'not_found'          => __( 'No withdrawal requests found.', 'surbma-magyar-woocommerce' ),
					'not_found_in_trash' => __( 'No withdrawal requests found in Trash.', 'surbma-magyar-woocommerce' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_rest'        => false,
				'capability_type'     => 'shop_order',
				'map_meta_cap'        => true,
				'capabilities'        => array(
					'create_posts' => 'do_not_allow',
				),
				'hierarchical'        => false,
				'supports'            => array( 'title' ),
				'has_archive'         => false,
				'rewrite'             => false,
				'query_var'           => false,
				'can_export'          => true,
				'delete_with_user'    => false,
			)
		);
	}
);
