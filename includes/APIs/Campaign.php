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
                array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_tags') ),
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

        $data['fields'] = apply_filters( 'wpcf_form_fields', [] );

        return rest_ensure_response( $data );
    }

    /**
     * Get campaign form tags
     * @since     2.1.0
     * @access    public
     * @param     {array}   attr
     * @return    [array]   mixed
     */
    function get_form_tags($attr) {
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

    /**
     * Campaign form fields
     * @since     2.1.0
     * @access    public
     * @param     {fields}  fields
     * @return    [array]   mixed
     */
    function form_fields($fields = []) {
        $default_fields = array(
            //Information
            'info' => array(
                'category' => array(
                    'type'      => 'select',
                    'title'     => __("Campaign Catagory *", "wp-crowdfunding"),
                    'desc'      => __("Choose the Category That Most closely aligns with your project", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true
                ),
                'sub_category' => array(
                    'type'      => 'select',
                    'title'     => __("Campaign Sub- Catagory", "wp-crowdfunding"),
                    'desc'      => __("Reach a more specific community by also choosing a subcategory", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true
                ),
                'types' => array(
                    'type'      => 'radio',
                    'title'     => __("Raising Money For *", "wp-crowdfunding"),
                    'desc'      => __("Estimated Shipping Date Rewards to be Recieved", "wp-crowdfunding"),
                    'value'     => '',
                    'options'   => array(
                        array(
                            'value' => 'individual',
                            'label' => __("Individual", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        ),
                        array(
                            'value' => 'business',
                            'label' => __("Business", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        ),
                        array(
                            'value' => 'non-profit',
                            'label' => __("Non-Profit", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        )
                    ),
                    'required'  => true,
                    'show'      => true
                ),
                'country' => array(
                    'type'      => 'select',
                    'title'     => __("Country *","wp-crowdfunding"),
                    'desc'      => __("", "wp-crowdfunding"),
                    'value'     => '',
                    'options'   => WC()->countries->countries,
                    'required'  => false,
                    'show'      => true,
                ),
                'city' => array(
                    'type'      => 'select',
                    'title'     => __("City *","wp-crowdfunding"),
                    'desc'      => __("", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
            ),
            // Details
            'details' => array(
                'title' => array(
                    'type'      => 'text',
                    'title'     => __("Campaign Title *", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
                'subtitle' => array(
                    'type'      => 'text',
                    'title'     => __("Campaign Sub-Title", "wp-crowdfunding"),
                    'desc'      => __("Use Words People Might Search For..", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
                'short_desc' => array(
                    'type'      => 'textarea',
                    'title'     => __("Campaign Description *", "wp-crowdfunding"),
                    'desc'      => __("Keep It Short. Just Small Brief About your Project", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
                'tags' => array(
                    'type'      => 'tags',
                    'title'     => __("Tags", "wp-crowdfunding"),
                    'desc'      => __("Reach a more specific community by also choosing right Tags. Max Tag : 20", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
            ),
            // Media
            'media' => array(
                'video' => array(
                    'type'      => 'text',
                    'title'     => __("Video", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
                'video_upload' => array(
                    'type'      => 'file',
                    'title'     => __("Video Upload", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true
                ),
                'image' => array(
                    'type'      => 'file',
                    'title'     => __("Image Upload *","wp-crowdfunding"),
                    'desc'      => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true
                ),
                'goal' => array(
                    'type'      => 'range',
                    'title'     => __("Funding Goals *", "wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
                'fund_type' => array(
                    'type'      => 'radio',
                    'title'     => __("Funding Type *","wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding"),
                    'value'     => '',
                    'options'   => array(
                        array(
                            'value' => 'fixed_funding',
                            'label' => __("Fixed Funding", "wp-crowdfunding"),
                            'desc'  => __("Accept All or Nothing", "wp-crowdfunding"),
                        ),
                        array(
                            'value' => 'flexible_funding',
                            'label' => __("Flexible Funding", "wp-crowdfunding"),
                            'desc'  => __("Accept if doesnot meet goal", "wp-crowdfunding"),
                        )
                    ),
                    'required'  => true,
                    'show'      => true,
                ),
                'goal_type' => array(
                    'type'      => 'radio',
                    'title'     => __("Goal Type *","wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding"),
                    'value'     => '',
                    'options'   => array(
                        array(
                            'value' => 'target_goal',
                            'label' => __("Target Goal", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        ),
                        array(
                            'value' => 'target_date',
                            'label' => __("Target Date", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        ),
                        array(
                            'value' => 'never_end',
                            'label' => __("Campaign Never End", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                        )
                    ),
                    'required'  => false,
                    'show'      => true,
                ),
                'amount_range' => array(
                    'type'      => 'amount_range',
                    'title'     => __("Amount Range *","wp-crowdfunding"),
                    'desc'      => __("You can Fixed a Maximum and Minimum Amount", "wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
                'recommended' => array(
                    'type'      => 'recommended_amount',
                    'title'     => __("Recommended Amount *","wp-crowdfunding"),
                    'desc'      => __("You can Fixed a Maximum Amount","wp-crowdfunding"),
                    'value'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
            ),
            // Contributor
            'contributor' => array(
                'table' => array(
                    'type'      => 'checkbox',
                    'title'     => __("Contributor Table", "wp-crowdfunding"),
                    'desc'      => __("You can make contributors table", "wp-crowdfunding"),
                    'value'     => '',
                    'options'   => array(
                        array(
                            'value' => 1,
                            'label' => __("Show contributor table on campaign single page", "wp-crowdfunding"),
                        )
                    ),
                    'required'  => false,
                    'show'      => true,
                ),
                'anonymity' => array(
                    'type'      => 'checkbox',
                    'title' => __("Contributor Anonymity", "wp-crowdfunding"),
                    'desc' => __("You can make contributors anonymus visitors will not see the backers", "wp-crowdfunding"),
                    'value'     => '',
                    'options'   => array(
                        array(
                            'value' => 1,
                            'label' => __("Make contributors anonymous on the contributor table", "wp-crowdfunding"),
                        )
                    ),
                    'required' => false,
                    'show' => true,
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