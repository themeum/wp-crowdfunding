<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'wpneo_crowdfunding_listing', 'wpcf_listing_callback'); //@comparability
add_shortcode( 'wpcf_listing', 'wpcf_listing_callback');

function wpcf_listing_callback($atts = array()){
    if( function_exists('WPNEOCF') ){

        $a = shortcode_atts(array(
            'cat'         => null,
            'number'      => -1,
            'pagination'  => false
        ), $atts );

        $paged = 1;
        if (get_query_var('paged')){
            $paged = absint( get_query_var( 'paged' ) );
        }elseif (get_query_var('page')){
            $paged = absint( get_query_var( 'page' ) );
        }

        $query_args = array(
            'post_type'     => 'product',
            'tax_query'     => array(
                'relation'  => 'AND',
                array(
                    'taxonomy'  => 'product_type',
                    'field'     => 'slug',
                    'terms'     => 'crowdfunding',
                ),
            ),
            'posts_per_page' => $a['number'],
            'paged' => $paged
        );

        if (!empty($_GET['author'])) {
            $user_login     = sanitize_text_field( trim( $_GET['author'] ) );
            $user           = get_user_by( 'login', $user_login );
            if ($user) {
                $user_id    = $user->ID;
                $query_args = array(
                    'post_type'   => 'product',
                    'author'      => $user_id,
                    'tax_query'   => array(
                        array(
                            'taxonomy'  => 'product_type',
                            'field'     => 'slug',
                            'terms'     => 'crowdfunding',
                        ),
                    ),
                    'posts_per_page' => -1
                );
            }
        }

        if( $a['cat'] ){
            $cat_array = explode(',', $a['cat']);
            $query_args['tax_query'][] = array(
                'taxonomy'  => 'product_cat',
                'field'     => 'slug',
                'terms'     => $cat_array,
            );
        }
        query_posts($query_args);

        ob_start();
        wpneo_crowdfunding_load_template('wpneo-listing');
        $html = ob_get_clean();
        wp_reset_query();
        return $html;
    }
}
