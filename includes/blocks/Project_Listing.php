<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class ProjectListing{
    
    public function __construct(){
        $this->register_project_listing();
    }
 
    public function register_project_listing(){
        register_block_type(
            'wp-crowdfunding/projectlisting',
            array(
                'attributes' => array(
                    'categories'   => array(
                        'type'      => 'string',
                        'default'   => 'all'
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
                'render_callback' => array( $this, 'project_listing_block_callback' ),
            )
        );
    }

    public function project_listing_block_callback( $att ){

        $cat_name       = ( $att['categories'] != 'all') ? get_the_category_by_ID( $att['categories'] ) : $att['categories']; 
        $post_limit     = isset( $att['numbers']) ? $att['numbers'] : 10;
        $order          = isset( $att['order']) ? $att['order'] : 'desc';

        if( function_exists('wpcf_function') ){

            $paged = 1;
            if ( get_query_var('paged') ){
                $paged = absint( get_query_var('paged') );
            } elseif (get_query_var('page')) {
                $paged = absint( get_query_var('page') );
            }

            $a = array(
                'cat'         => $cat_name,
                'number'      => $post_limit,
                'order'       => $order,
            );

            $query_args = array(
                'post_type'     => 'product',
                'post_status'   => 'publish',
                'tax_query'     => array(
                    'relation'  => 'AND',
                    array(
                        'taxonomy'  => 'product_type',
                        'field'     => 'slug',
                        'terms'     => 'crowdfunding',
                    ),
                ),
                'posts_per_page'    => $post_limit,
                'paged'             => $paged,
                'order'             => $order,
            );

            if( $a['cat'] != 'all' ){
                $cat_array = explode(',', $a['cat']);
                $query_args['tax_query'][] = array(
                    'taxonomy'  => 'product_cat',
                    'field'     => 'slug',
                    'terms'     => $cat_array,
                );
            }

            query_posts($query_args);
            ob_start();
            wpcf_function()->template('wpneo-listing');
            $html = ob_get_clean();
            wp_reset_query();
            return $html;
        }
           
    }
}
