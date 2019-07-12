<?php
namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;

class Popular_Campaigns {

    function __construct() {
        add_shortcode( 'wpcf_popular_campaigns', array( $this, 'popular_campaigns_callback' ) );
    }

    function popular_campaigns_callback( $atts, $shortcode ){
        $atts = shortcode_atts( array(
            'limit'     => 4,
            'column'    => 4,
            'order'     => 'DESC',
            'class'     => '',
        ), $atts, $shortcode );

        $args = array(
            'post_type' 			=> 'product',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'		=> $atts['limit'],
            'meta_key' 		 		=> 'total_sales',
            'orderby' 		 		=> 'meta_value_num',
            'order'                 => $atts['order'],
            'fields'                => 'ids',

            'meta_query' => array(
                array(
                    'key' 		=> 'total_sales',
                    'value' 	=> 0,
                    'compare' 	=> '>',
                )
            ),

            'tax_query' => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
        );

        $columns  = $atts['column'];

        $classes    = array('woocommerce', 'columns-'.$columns);
        $attr_class = explode(',', $atts['class']);
        $classes    = array_merge($classes, $attr_class);

        $query = new \WP_Query($args);

        $paginated = ! $query->get( 'no_found_rows' );
        $products = (object) array(
            'ids'          => wp_parse_id_list( $query->posts ),
            'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
            'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
            'per_page'     => (int) $query->get( 'posts_per_page' ),
            'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
        );

        ob_start();

        if ( $products && $products->ids ) {
            // Prime meta cache to reduce future queries.
            update_meta_cache( 'post', $products->ids );
            update_object_term_cache( $products->ids, 'product' );

            // Setup the loop.
            wc_setup_loop( array(
                'columns'      => $columns,
                'name'         => 'products',
                'is_shortcode' => true,
                'is_search'    => false,
                'is_paginated' => false,
                'total'        => $products->total,
                'total_pages'  => $products->total_pages,
                'per_page'     => $products->per_page,
                'current_page' => $products->current_page,
            ) );

            $original_post = $GLOBALS['post'];

            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                foreach ( $products->ids as $product_id ) {
                    $GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
                    setup_postdata( $GLOBALS['post'] );

                    // Render product template.
                    wc_get_template_part( 'content', 'product' );
                }
            }

            $GLOBALS['post'] = $original_post; // WPCS: override ok.
            woocommerce_product_loop_end();

            wp_reset_postdata();
            wc_reset_loop();
        }

        return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
    }
}