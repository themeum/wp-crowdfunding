<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class PopularCampaigns{
    
    public function __construct(){
        $this->register_popular_campaigns();
    }
 
    public function register_popular_campaigns(){
        register_block_type(
            'wp-crowdfunding/popularcampaigns',
            array(
                'attributes' => array(
                    'categories'   => array(
                        'type'      => 'string',
                        'default'   => ''
                    ),
                    'order'   => array(
                        'type'      => 'string',
                        'default'   => 'desc'
                    ),
                    'order_by' => array(
                        'type'      => 'string',
                        'default'   => 'date'
                    ),
                    'numbers'   => array(
                        'type'      => 'number',
                        'default'   => 10
                    ),
                    
                ),
                'render_callback' => array( $this, 'popular_campaigns_block_callback' ),
            )
        );
    }

    public function popular_campaigns_block_callback( $att ){
         
        $cat_name       = ($categories) ? get_the_category_by_ID($categories) : null; 
        $post_limit     = isset( $att['numbers']) ? $att['numbers'] : 6;
        $order          = isset( $att['order']) ? $att['order'] : 'desc';
        $categories     = isset( $att['categories']) ? $att['categories'] : '';

        $paged = 1;
        if ( get_query_var('paged') ) {
            $paged = absint( get_query_var('paged') );
        } elseif (get_query_var('page')) {
            $paged = absint( get_query_var('page') );
        }

        $query_args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'meta_key'              => 'total_sales',
            'posts_per_page'        => -1,
            'paged'                 => $paged,
            'orderby'               => 'meta_value_num',
            'order'                 => $order,
            'meta_query' => array(
                array(
                    'key'       => 'total_sales',
                    'value'     => 0,
                    'compare'   => '>',
                )
            ),

            'tax_query' => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            )
        );

        query_posts($query_args);

        print_r($query_args);

        ob_start();
        wpcf_function()->template('wpneo-listing');
        $html = ob_get_clean();
        wp_reset_query();
        return $html;     
    }
}
