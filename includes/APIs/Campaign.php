<?php
/**
 * WP-Crowdfunding campaign form API's
 *
 * @category   Crowdfunding
 * @package    APIs
 * @author     Themeum <www.themeum.com>
 * @copyright  2019 Themeum <www.themeum.com>
 * @version    Release: @1.0.0
 * @since      2.1.0
 */

namespace WPCF\APIs;
defined( 'ABSPATH' ) || exit;

class API_Campaign {
    /**
     * @constructor
     * @since 2.1.0
     */
    function __construct() {
        add_action( 'init', array( $this, 'init_rest_api') );
    }

    /**
     * Init rest api
     * @since   2.1.0
     * @access  public
     */
    function init_rest_api() {
        $this->current_user_id = get_current_user_id();
        if( $this->current_user_id ) {
            add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
        }
    }

    /**
     * Register rest api routes
     * @since   2.1.0
     * @access  public
     */
    function register_rest_api() {
        $namespace = WPCF_API_NAMESPACE . WPCF_API_VERSION;
        $method_readable = \WP_REST_Server::READABLE;
        $method_creatable = \WP_REST_Server::CREATABLE;
        
        register_rest_route( $namespace, '/campaign-form-fileds', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'report') ),
        ));
        register_rest_route( $namespace, '/campaign-tags', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'report') ),
        ));
        register_rest_route( $namespace, '/campaign-saved-data', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'report') ),
        ));
    }


    function rest_tags_callback($attr){
        $data = array();
        $arg = array(
            'post_type' => 'product',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array( esc_attr($attr['cat']) ),
                ),
            ),
            'post_status'=> 'publish'
        );
        $query = new \WP_Query( $arg );
        while ( $query->have_posts() ) {
            $query->the_post();
            $posttags = get_the_terms( get_the_ID(), 'product_tag' );
            if ($posttags) {
                foreach($posttags as $tag) {
                    $data[$tag->slug] = $tag->name; 
                }
            }
        }
        wp_reset_postdata();

        return rest_ensure_response( $data );
    }


    function rest_forms_callback($attr){
        $data = array();
        
        // Category
        $terms = array();
        $term_return = array();
        $seperate_cat = get_option('seperate_crowdfunding_categories');
        if( $seperate_cat ) {
            $terms = get_terms( 'product_cat', array(
                'hide_empty' => false,
                'meta_query' => array(
                    array(
                        'key' => '_marked_as_crowdfunding',
                        'value' => 1,
                        'compare' => '='
                    )
                )
            ));
        } else {
            $terms = get_terms('post_tag', array('hide_empty' => false));
        }
        if(!empty($terms)) {
            foreach($terms as $val) {
                if($val->parent){
                    $term_return[$val->parent]['child'][] = array( 'name' => $val->name, 'slug' => $val->slug );
                } else {
                    $term_return[$val->term_id] = array( 'name' => $val->name, 'slug' => $val->slug, 'child' => array() );
                }
            }
            $data['tax'] = $term_return;
        }

        $data['permission'] = apply_filters('wpcf_form_fields',array());

        return rest_ensure_response( $data );
    }

}

new API_Campaign();