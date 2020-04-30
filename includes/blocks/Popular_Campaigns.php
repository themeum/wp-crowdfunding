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

        if( function_exists('wpcf_function') ){
            $a = shortcode_atts(array(
                'cat'         => $cat_name,
                'number'      => $post_limit,
                'order'       => $order,
            ), $atts );

            $paged = 1;
            if ( get_query_var('paged') ){
                $paged = absint( get_query_var('paged') );
            } elseif (get_query_var('page')) {
                $paged = absint( get_query_var('page') );
            }

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
                'posts_per_page'    => $a['number'],
                'paged'             => $paged,
                'order'             => $a['order'],
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
            wpcf_function()->template('wpneo-listing');
            $html = ob_get_clean();
            wp_reset_query();
            return $html;
        }
           
    }
}
