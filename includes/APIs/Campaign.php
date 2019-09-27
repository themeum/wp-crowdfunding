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
        add_filter( 'wpcf_form_basic_fields', array( $this, 'form_basic_fields') );
        add_filter( 'wpcf_form_reward_fields', array( $this, 'form_reward_fields') );
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
                array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_basic_fields') ),
            ));            
            register_rest_route( $namespace, '/form-tags', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'form_tags') ),
            ));
            register_rest_route( $namespace, '/sub-categories', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'sub_categories') ),
            ));
            register_rest_route( $namespace, '/states', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'get_states') ),
            ));
            register_rest_route( $namespace, '/reward-fields', array(
                array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_reward_fields') ),
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
     * @return    [array]   mixed
     */
    function get_form_basic_fields() {
        $data = apply_filters( 'wpcf_form_basic_fields', [] );
        return rest_ensure_response( $data );
    }

    /**
     * Campaign form fields
     * @since     2.1.0
     * @access    public
     * @param     {fields}  fields
     * @return    [array]   mixed
     */
    function form_basic_fields($fields = []) {
        $cat_args = array(
            'taxonomy'      => 'product_cat',
            'hide_empty'    => false,
            'parent'        => 0
        );
        //Get is Crowdfunding Categories only
        $is_only_crowdfunding_categories = get_option('seperate_crowdfunding_categories');
        if ('true' === $is_only_crowdfunding_categories){
            $cat_args['meta_query'] = array(
                array(
                    'key' => '_marked_as_crowdfunding',
                    'value' => '1'
                )
            );
        }
        $categories = get_terms($cat_args);
        $res_categories = array();
        foreach($categories as $category) {
            $res_categories[] = array(
                'label' => $category->name,
                'value' => $category->term_id
            );
        }

        $countries = WC()->countries->countries;
        $res_countries = array();
        foreach($countries as $key => $country) {
            $res_countries[] = array(
                'label' => $country,
                'value' => $key
            );
        }

        $default_fields = array(
            //Information
            'campaign_info' => array(
                'category' => array(
                    'type'          => 'select',
                    'title'         => __("Campaign Catagory *", "wp-crowdfunding"),
                    'desc'          => __("Choose the Category That Most closely aligns with your project", "wp-crowdfunding"),
                    'placeholder'   => __("Select Catagory", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => $res_categories,
                    'required'      => true,
                    'show'          => true
                ),
                'sub_category' => array(
                    'type'          => 'select',
                    'title'         => __("Campaign Sub- Catagory", "wp-crowdfunding"),
                    'desc'          => __("Reach a more specific community by also choosing a subcategory", "wp-crowdfunding"),
                    'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => array(),
                    'required'      => false,
                    'show'          => true
                ),
                'types' => array(
                    'type'          => 'radio',
                    'title'         => __("Raising Money For *", "wp-crowdfunding"),
                    'desc'          => __("Estimated Shipping Date Rewards to be Recieved", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => array(
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
                    'required'      => true,
                    'show'          => true
                ),
                'country' => array(
                    'type'          => 'select',
                    'title'         => __("Country *","wp-crowdfunding"),
                    'desc'          => __("", "wp-crowdfunding"),
                    'placeholder'   => __("Select Country", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => $res_countries,
                    'required'      => false,
                    'show'          => true,
                ),
                'state' => array(
                    'type'          => 'select',
                    'title'         => __("State *","wp-crowdfunding"),
                    'desc'          => __("", "wp-crowdfunding"),
                    'placeholder'   => __("Select State", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => array(),
                    'required'      => true,
                    'show'          => true,
                ),
            ),
            // Details
            'details' => array(
                'title' => array(
                    'type'          => 'text',
                    'title'         => __("Campaign Title *", "wp-crowdfunding"),
                    'desc'          => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'value'         => '',
                    'required'      => true,
                    'show'          => true,
                ),
                'subtitle' => array(
                    'type'          => 'text',
                    'title'         => __("Campaign Sub-Title", "wp-crowdfunding"),
                    'desc'          => __("Use Words People Might Search For..", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'value'         => '',
                    'required'      => false,
                    'show'          => true,
                ),
                'short_desc' => array(
                    'type'          => 'textarea',
                    'title'         => __("Campaign Description *", "wp-crowdfunding"),
                    'desc'          => __("Keep It Short. Just Small Brief About your Project", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'value'         => '',
                    'required'      => true,
                    'show'          => true,
                ),
                'tags' => array(
                    'type'          => 'tags',
                    'title'         => __("Tags", "wp-crowdfunding"),
                    'desc'          => __("Reach a more specific community by also choosing right Tags. Max Tag : 20", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'value'         => '',
                    'options'       => $this->get_form_tags(),
                    'required'      => false,
                    'show'          => true,
                ),
            ),
            // Media
            'media' => array(
                'video_link' => array(
                    'type'      => 'repeatable',
                    'title'     => __("Video", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'button'    => '<i class="fa fa-plus"/> '.__('Add More Link', 'wp-crowdfunding'),
                    'fields'    => array(
                        'src' => array(
                            'type'          => 'text',
                            'placeholder'   => __("", "wp-crowdfunding"),
                            'required'      => true,
                            'show'          => true,
                        ),
                    ),
                    'value'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
                'video' => array(
                    'type'      => 'file',
                    'title'     => __("Video Upload", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'button'    => '<i class="fa fa-file"/> '.__('Upload Video', 'wp-crowdfunding'),
                    'multiple'  => true,
                    'required'  => false,
                    'show'      => true
                ),
                'image' => array(
                    'type'      => 'file',
                    'title'     => __("Image Upload *","wp-crowdfunding"),
                    'desc'      => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding"),
                    'button'    => '<i class="fa fa-plus"/> '.__('Add More Image', 'wp-crowdfunding'),
                    'value'     => '',
                    'multiple'  => true,
                    'required'  => true,
                    'show'      => true
                ),
                'goal' => array(
                    'type'      => 'range',
                    'title'     => __("Funding Goals *", "wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing", "wp-crowdfunding"),
                    'value'     => 30000,
                    'minVal'    => 1,
                    'maxVal'    => 5000000,
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
                    'type'      => 'range',
                    'title'     => __("Amount Range *","wp-crowdfunding"),
                    'desc'      => __("You can Fixed a Maximum and Minimum Amount", "wp-crowdfunding"),
                    'value'     => '',
                    'minVal'    => 1,
                    'maxVal'    => 5000000,
                    'required'  => true,
                    'show'      => true,
                ),
                'recommended' => array(
                    'type'      => 'text',
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


    /**
     * Campaign form tags
     * @since     2.1.0
     * @access    public
     * @param     {array}   attr
     * @return    [array]   mixed
     */
    function form_tags() {
        $data = $this->get_form_tags();
        return rest_ensure_response( $data );
    }


    /**
     * Get campaign form tags
     * @since     2.1.0
     * @access    public
     * @param     {array}   attr
     * @return    [array]   mixed
     */
    function get_form_tags() {
        $data = array();
        $arg = array(
            'post_type' => 'product',
            'tax_query' 		=> array(
                array(
                    'taxonomy'  => 'product_type',
                    'field'     => 'slug',
                    'terms'     => 'crowdfunding',
                ),
                /* array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array( esc_attr($_GET['cat']) ),
                ), */
            ),
            'post_status'=> 'publish'
        );
        $uniqeTags = [];
        $query = new \WP_Query( $arg );
        while ( $query->have_posts() ) {
            $query->the_post();
            $posttags = get_the_terms( get_the_ID(), 'product_tag' );
            if ($posttags) {
                foreach($posttags as $tag) {
                    if( !in_array($tag->slug, $uniqeTags) ) {
                        $data[] = array(
                            'value' => $tag->slug,
                            'label' => $tag->name
                        );
                        $uniqeTags[] = $tag->slug;
                    }
                }
            }
        }

        return $data;
    }


    /**
     * Get states from country code
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_states() {
        $code = $_GET['code'];
        $country = new \WC_Countries();
        $states = $country->get_states($code);
        $options = array();
        if($states) {
            foreach($states as $key => $state) {
                $options[] = array(
                    'value' => $key,
                    'label' => $state
                );
            }
        }
        $response = array(
            'section' => 'campaign_info',
            'field' => 'state',
            'options' => $options,
        );
        return rest_ensure_response( $response );
    }

    
    /**
     * Get Sub categories from main category
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function sub_categories() {
        $id = $_GET['id'];
        $options = $this->get_subcategories($id);
        $response = array(
            'section' => 'campaign_info',
            'field' => 'sub_category',
            'options' => $options,
        );
        return rest_ensure_response( $response );
    }


    /**
     * Get Sub categories from main category
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_subcategories($id) {
        $cat_args = array(
            'taxonomy'      => 'product_cat',
            'hide_empty'    => false,
            'parent'        => $id
        );
        //Get is Crowdfunding Categories only
        $is_only_crowdfunding_categories = get_option('seperate_crowdfunding_categories');
        if ('true' === $is_only_crowdfunding_categories){
            $cat_args['meta_query'] = array(
                array(
                    'key' => '_marked_as_crowdfunding',
                    'value' => '1'
                )
            );
        }
        $data = array();
        $categories = get_terms($cat_args);
        foreach($categories as $category) {
            $data[] = array(
                'label' => $category->name,
                'value' => $category->term_id
            ); 
        }
        return $data;
    }


    /**
     * Get campaign form fields
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_form_reward_fields() {
        $data = apply_filters( 'wpcf_form_reward_fields', [] );
        return rest_ensure_response( $data );
    }


    /**
     * Default reward fields
     * @since     2.1.0
     * @access    public
     * @param     {array}   fields
     * @return    [array]   mixed
     */
    function form_reward_fields($fields = []) {
        
        $default_fields = array(
            'title' => array(
                'type'          => 'text',
                'title'         => __("Title *", "wp-crowdfunding"),
                'desc'          => __("Briefly describe this reward.", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'value'         => '',
                'required'      => true,
                'show'          => true
            ),
            'amount' => array(
                'type'          => 'text',
                'title'         => __("Pledge Amount *", "wp-crowdfunding"),
                'desc'          => __("Briefly describe this reward.", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'value'         => '',
                'required'      => true,
                'show'          => true
            ),
            'image' => array(
                'type'      => 'file',
                'title'     => __("Rewards Image *","wp-crowdfunding"),
                'desc'      => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding"),
                'button'    => '<i class="fa fa-plus"/> '.__('Add Image', 'wp-crowdfunding'),
                'value'     => '',
                'multiple'  => false,
                'required'  => true,
                'show'      => true
            ),
            'description' => array(
                'type'          => 'textarea',
                'title'         => __("Rewards Description *", "wp-crowdfunding"),
                'desc'          => __("Keep It Short. Just Small Brief About your Project", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'value'         => '',
                'required'      => true,
                'show'          => true,
            ),
            'estimate_delivery' => array(
                'type'          => 'item_group',
                'title'         => __("Estimate Delivery *", "wp-crowdfunding"),
                'desc'          => __("Reach a more specific community by also choosing a subcategory", "wp-crowdfunding"),
                'fields' => array(
                    'month' => array(
                        'type'          => 'select',
                        'class'         => 'col-md-7',
                        'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                        'value'         => '',
                        'options'       => array(),
                        'required'      => true,
                        'show'          => true
                    ),
                    'year' => array(
                        'type'          => 'select',
                        'class'         => 'col-md-5',
                        'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                        'value'         => '',
                        'options'       => array(),
                        'required'      => true,
                        'show'          => true
                    )
                )
            ),
            'ewards_items' => array(
                'type'      => 'repeatable',
                'title'     => __("Rewards Item *", "wp-crowdfunding"),
                'desc'      => __("Be Specific About What are Included in the Perks", "wp-crowdfunding"),
                'button'    => '<i class="fa fa-plus"/> '.__('Add More Link', 'wp-crowdfunding'),
                'fields'    => array(
                    'name' => array(
                        'type'          => 'text',
                        'placeholder'   => __("", "wp-crowdfunding"),
                        'required'      => true,
                        'show'          => true,
                    ),
                ),
                'value'     => '',
                'required'  => false,
                'show'      => true,
            ),
            'no_of_items' => array(
                'type'          => 'text',
                'title'         => __("Total Number of Rewards *", "wp-crowdfunding"),
                'desc'          => __("", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'value'         => '',
                'required'      => true,
                'show'          => true
            ),
        );

        return array_merge($default_fields, $fields);
    }

}

new API_Campaign();