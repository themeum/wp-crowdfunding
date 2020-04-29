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

                    'fontSize'   => array(
                        'type'      => 'number',
                        'default'   => ''
                    ),
                    'columns'   => array(
                        'type'      => 'number',
                        'default'   => 3
                    ),
                    
                ),
                'render_callback' => array( $this, 'project_listing_block_callback' ),
            )
        );
    }

    public function project_listing_block_callback( $att ){
        $formSize           = isset($att['formSize']) ? $att['formSize'] : '';

        // $columns 		= isset( $att['columns']) ? $att['columns'] : 3;
        $post_limit 		= isset( $att['numbers']) ? $att['numbers'] : 6;
        $order 		        = isset( $att['order']) ? $att['order'] : 'desc';
        // $fontSize 		= isset( $att['fontSize']) ? $att['fontSize'] : '14';
        // $lineheight 	= isset( $att['lineheight']) ? $att['lineheight'] : '24';
        // $fontWeight 	= isset( $att['fontWeight']) ? $att['fontWeight'] : '400';
        // $blogStyle 		= isset( $att['blogStyle']) ? $att['blogStyle'] : 'style1';
        // $colorpalette 	= isset( $att['colorpalette']) ? $att['colorpalette'] : '#212127';
        // $selectedCategory = isset( $att['selectedCategory']) ? $att['selectedCategory'] : 'all';



        if( function_exists('wpcf_function') ){

            $a = shortcode_atts(array(
                'cat'         => null,
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
                'orderby'           => 'post_title',
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
