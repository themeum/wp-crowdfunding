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
        add_filter( 'wpcf_form_fields', array( $this, 'form_fields') );
    }

    /**
     * Init rest api
     * @since   2.1.0
     * @access  public
     */
    function init_rest_api() {
        add_action( 'rest_api_init', function() {
            $namespace = WPCF_API_NAMESPACE . WPCF_API_VERSION;
            $method_readable = \WP_REST_Server::READABLE;
            $method_creatable = \WP_REST_Server::CREATABLE;
            
            register_rest_route( $namespace, '/form-fields', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_fields') ),
            ));
            register_rest_route( $namespace, '/form-tags', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'form_tags') ),
            ));
            register_rest_route( $namespace, '/form-saved-data', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'form_saved_data') ),
            ));
        });
    }

    /**
     * Get campaign form fields
     * @since     2.1.0
     * @access    public
     * @param     {array}   attr
     * @return    [array]   mixed
     */
    function get_form_fields($attr) {
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
        if( !empty($terms) ) {
            foreach($terms as $val) {
                if($val->parent){
                    $term_return[$val->parent]['child'][] = array( 'name' => $val->name, 'slug' => $val->slug );
                } else {
                    $term_return[$val->term_id] = array( 'name' => $val->name, 'slug' => $val->slug, 'child' => array() );
                }
            }
            $data['tax'] = $term_return;
        }

        $data['fields'] = apply_filters( 'wpcf_form_fields' );

        return rest_ensure_response( $data );
    }


    function form_tags($attr){
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

    function form_fields($fields= array()) {
        $default_fields = array(
            //Information
            'info' => array(
                'category' => array(
                    'show' => true,
                    'required' => true,
                    'title' => __("Campaign Title *","wp-crowdfunding"),
                    'desc' => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.","wp-crowdfunding")
                ),
                'sub_category' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Campaign Sub-Title","wp-crowdfunding"),
                    'desc' => __("Use Words People Might Search For..","wp-crowdfunding")
                ),
                'types' => array(
                    'show' => true, 
                    'required' => true,
                    'title' => __("","wp-crowdfunding"),
                    'desc' => __("","wp-crowdfunding")
                ),
                'country' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Campaign Description *","wp-crowdfunding"),
                    'desc' => __("Keep It Short. Just Small Brief About your Project","wp-crowdfunding")
                ),
                'city' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Campaign Description *","wp-crowdfunding"),
                    'desc' => __("Keep It Short. Just Small Brief About your Project","wp-crowdfunding")
                ),
            ),
            // Details
            'details' => array(
                'title' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("","wp-crowdfunding"),
                    'desc' => __("","wp-crowdfunding")
                ),
                'subtitle' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("","wp-crowdfunding"),
                    'desc' => __("","wp-crowdfunding")
                ),
                'short_desc' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("","wp-crowdfunding"),
                    'desc' => __("","wp-crowdfunding")
                ),
                'tag' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("","wp-crowdfunding"),
                    'desc' => __("","wp-crowdfunding")
                ),
            ),
            // Media
            'media' => array(
                'video' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Video","wp-crowdfunding"),
                    'desc' => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.","wp-crowdfunding")
                ),
                'image' => array(
                    'show' => true, 
                    'required' => true,
                    'title' => __("Image Upload *","wp-crowdfunding"),
                    'desc' => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding")
                ),
                'goal' => array(
                    'show' => true, 
                    'required' => true,
                    'title' => __("Funding Goals *","wp-crowdfunding"),
                    'desc' => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding")
                ),
                'fund_type' => array(
                    'show' => true, 
                    'required' => true,
                    'title' => __("Funding Type *","wp-crowdfunding"),
                    'desc' => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding")
                ),
                'goal_type' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Goal Type *","wp-crowdfunding"),
                    'desc' => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding"),
                ),
                'range' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Amount Range *","wp-crowdfunding"),
                    'desc' => __("You can Fixed a Maximum and Minimum Amount","wp-crowdfunding")
                ),
                'recommended' => array(
                    'show' => true, 
                    'required' => true,
                    'title' => __("Recommended Amount *","wp-crowdfunding"),
                    'desc' => __("You can Fixed a Maximum Amount","wp-crowdfunding")
                ),
            ),
            // Contributor
            'contributor' => array(
                'table' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Contributor Table","wp-crowdfunding"),
                    'desc' => __("You can make contributors table","wp-crowdfunding"),
                    'label' => __("Show contributor table on campaign single page","wp-crowdfunding"),
                ),
                'anonymity' => array(
                    'show' => true, 
                    'required' => false,
                    'title' => __("Contributor Anonymity","wp-crowdfunding"),
                    'desc' => __("You can make contributors anonymus visitors will not see the backers","wp-crowdfunding"),
                    'label' => __("Make contributors anonymous on the contributor table","wp-crowdfunding"),
                ),
            )
        );

        foreach($fields as $key => $field) {
            if( isset($default_fields[$key]) ) {
                $default_fields[$key] = array_merge($default_fields[$key], $field);
            } else {
                $default_fields[$key] = $field;
            }
        }

        return $default_fields;
    }

}

new API_Campaign();