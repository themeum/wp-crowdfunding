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
     * current logged user id
     * @since   2.1.0
     * @access  private
     */
    private $current_user_id;

    /**
     * @constructor
     * @since 2.1.0
     */
    function __construct() {
        add_action( 'init', array( $this, 'init_rest_api') );
        add_filter( 'wpcf_form_basic_fields', array( $this, 'form_basic_fields') );
        add_filter( 'wpcf_form_story_tools', array( $this, 'form_story_tools') );
        add_filter( 'wpcf_form_reward_types', array( $this, 'form_reward_types') );
        add_filter( 'wpcf_form_reward_fields', array( $this, 'form_reward_fields') );
        add_filter( 'wpcf_form_team_fields', array( $this, 'form_team_fields') );
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

        register_rest_route( $namespace, '/form-fields', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_basic_fields') ),
        ));
        register_rest_route( $namespace, '/story-tools', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_story_tools') ),
        ));
        register_rest_route( $namespace, '/sub-categories', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'sub_categories') ),
        ));
        /* register_rest_route( $namespace, '/states', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_states') ),
        )); */
        register_rest_route( $namespace, '/reward-fields', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_reward_fields') ),
        ));
        register_rest_route( $namespace, '/team-fields', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_team_fields') ),
        ));
        register_rest_route( $namespace, '/form-values', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'get_form_values') ),
        ));
        register_rest_route( $namespace, '/save-campaign', array(
            array( 'methods' => $method_creatable, 'callback' => array($this, 'save_campaign') ),
        ));
    }

    /**
     * Get campaign form fields
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_form_basic_fields() {
        $response = apply_filters( 'wpcf_form_basic_fields', [] );
        return rest_ensure_response( $response );
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
                'value' => $category->slug
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
                    'class'         => '',
                    'options'       => $res_categories,
                    'required'      => true,
                    'show'          => true
                ),
                'sub_category' => array(
                    'type'          => 'select',
                    'title'         => __("Campaign Sub- Catagory", "wp-crowdfunding"),
                    'desc'          => __("Reach a more specific community by also choosing a subcategory", "wp-crowdfunding"),
                    'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                    'class'         => '',
                    'options'       => array(),
                    'required'      => false,
                    'show'          => true
                ),
                'campaign_type' => array(
                    'type'          => 'radio',
                    'title'         => __("Raising Money For *", "wp-crowdfunding"),
                    'desc'          => __("Estimated Shipping Date Rewards to be Recieved", "wp-crowdfunding"),
                    'class'         => '',
                    'options'       => array(
                        array(
                            'value' => 'individual',
                            'label' => __("Individual", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
                        ),
                        array(
                            'value' => 'business',
                            'label' => __("Business", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
                        ),
                        array(
                            'value' => 'non-profit',
                            'label' => __("Non-Profit", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
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
                    'class'         => '',
                    'options'       => $res_countries,
                    'required'      => false,
                    'show'          => true,
                ),
                'location' => array(
                    'type'          => 'text',
                    'title'         => __("Location","wp-crowdfunding"),
                    'desc'          => __("Put the campaign location here", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'class'         => '',
                    'required'      => false,
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
                    'class'         => '',
                    'required'      => true,
                    'show'          => true,
                ),
                'subtitle' => array(
                    'type'          => 'text',
                    'title'         => __("Campaign Sub-Title", "wp-crowdfunding"),
                    'desc'          => __("Use Words People Might Search For..", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'class'         => '',
                    'required'      => false,
                    'show'          => true,
                ),
                'short_desc' => array(
                    'type'          => 'textarea',
                    'title'         => __("Campaign Description *", "wp-crowdfunding"),
                    'desc'          => __("Keep It Short. Just Small Brief About your Project", "wp-crowdfunding"),
                    'placeholder'   => __("", "wp-crowdfunding"),
                    'class'         => '',
                    'required'      => true,
                    'show'          => true,
                ),
                'tags' => array(
                    'type'          => 'tags',
                    'title'         => __("Tags", "wp-crowdfunding"),
                    'desc'          => __("Reach a more specific community by also choosing right Tags. Max Tag : 20", "wp-crowdfunding"),
                    'placeholder'   => __("Type tag and press enter", "wp-crowdfunding"),
                    'class'         => '',
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
                    'open_first_item' => true,
                    'class'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
                'video' => array(
                    'type'      => 'video',
                    'title'     => __("Video Upload", "wp-crowdfunding"),
                    'desc'      => __("Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.", "wp-crowdfunding"),
                    'button'    => '<i class="fa fa-file"/> '.__('Upload Video', 'wp-crowdfunding'),
                    'class'     => '',
                    'is_media'  => true,
                    'multiple'  => true,
                    'required'  => false,
                    'show'      => true
                ),
                'image' => array(
                    'type'      => 'image',
                    'title'     => __("Image Upload *","wp-crowdfunding"),
                    'desc'      => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding"),
                    'button'    => '<i class="fa fa-plus"/> '.__('Add More Image', 'wp-crowdfunding'),
                    'class'     => '',
                    'is_media'  => true,
                    'multiple'  => true,
                    'required'  => true,
                    'show'      => true
                ),
                'funding_goal' => array(
                    'type'      => 'range',
                    'title'     => __("Funding Goals *", "wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing", "wp-crowdfunding"),
                    'class'     => '',
                    'value'     => 30000,
                    'minVal'    => 0,
                    'maxVal'    => 5000000,
                    'required'  => true,
                    'show'      => true,
                ),
                'fund_type' => array(
                    'type'      => 'radio',
                    'title'     => __("Funding Type *","wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding"),
                    'class'     => '',
                    'options'   => array(
                        array(
                            'value' => 'fixed_funding',
                            'label' => __("Fixed Funding", "wp-crowdfunding"),
                            'desc'  => __("Accept All or Nothing", "wp-crowdfunding"),
                            'class' => '',
                        ),
                        array(
                            'value' => 'flexible_funding',
                            'label' => __("Flexible Funding", "wp-crowdfunding"),
                            'desc'  => __("Accept if doesnot meet goal", "wp-crowdfunding"),
                            'class' => '',
                        )
                    ),
                    'required'  => true,
                    'show'      => true,
                ),
                'goal_type' => array(
                    'type'      => 'radio',
                    'title'     => __("Goal Type *","wp-crowdfunding"),
                    'desc'      => __("Lorem ipsum dolor sit amet, consectetur adipiscing","wp-crowdfunding"),
                    'class'     => '',
                    'options'   => array(
                        array(
                            'value' => 'target_goal',
                            'label' => __("Target Goal", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
                        ),
                        array(
                            'value' => 'target_date',
                            'label' => __("Target Date", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
                        ),
                        array(
                            'value' => 'never_end',
                            'label' => __("Campaign Never End", "wp-crowdfunding"),
                            'desc'  => __("", "wp-crowdfunding"),
                            'class' => '',
                        )
                    ),
                    'required'  => false,
                    'show'      => true,
                ),
                'if_target_date' => array(
                    'type'          => 'form_group',
                    'title'         => __("Specific Date & Time", "wp-crowdfunding"),
                    'desc'          => __("Max Campaign Duration 60 Days", "wp-crowdfunding"),
                    'class'         => '',
                    'fields' => array(
                        'start_date' => array(
                            'type'          => 'date',
                            'placeholder'   => __("-form-", "wp-crowdfunding"),
                            'class'         => 'col-md-6',
                            'value'         => '',
                            'required'      => true
                        ),
                        'end_date' => array(
                            'type'          => 'date',
                            'placeholder'   => __("-to-", "wp-crowdfunding"),
                            'class'         => 'col-md-6',
                            'value'         => '',
                            'required'      => true
                        )
                    ),
                    'show' => false,
                ),
                'pledge_amount' => array(
                    'type'      => 'range',
                    'title'     => __("Amount Range *","wp-crowdfunding"),
                    'desc'      => __("You can Fixed a Maximum and Minimum Amount", "wp-crowdfunding"),
                    'class'     => '',
                    'value'     => 30000,
                    'minVal'    => 0,
                    'maxVal'    => 5000000,
                    'required'  => true,
                    'show'      => true,
                ),
                'recommended_amount' => array(
                    'type'      => 'text',
                    'title'     => __("Recommended Amount *","wp-crowdfunding"),
                    'desc'      => __("You can Fixed a Maximum Amount","wp-crowdfunding"),
                    'class'     => '',
                    'required'  => true,
                    'show'      => true,
                ),
                'predefined_amount' => array(
                    'type'      => 'text',
                    'title'     => __("Predefined Pledge Amount","wp-crowdfunding"),
                    'desc'      => __("Predefined amount allow you to place the amount in donate box by click, price should separated by comma (,), example: 10,20,30,40","wp-crowdfunding"),
                    'class'     => '',
                    'required'  => false,
                    'show'      => true,
                ),
            ),
            // Contributor
            'contributor' => array(
                'contributor_table' => array(
                    'type'      => 'checkbox',
                    'title'     => __("Contributor Table", "wp-crowdfunding"),
                    'desc'      => __("You can make contributors table", "wp-crowdfunding"),
                    'class'     => '',
                    'options'   => array(
                        array(
                            'value' => 1,
                            'label' => __("Show contributor table on campaign single page", "wp-crowdfunding"),
                            'class' => '',
                        )
                    ),
                    'required'  => false,
                    'show'      => true,
                ),
                'contributor_anonymity' => array(
                    'type'      => 'checkbox',
                    'title'     => __("Contributor Anonymity", "wp-crowdfunding"),
                    'desc'      => __("You can make contributors anonymus visitors will not see the backers", "wp-crowdfunding"),
                    'class'     => '',
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
     * Get campaign form story tools
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_form_story_tools() {
        $response = apply_filters( 'wpcf_form_story_tools', [] );
        return rest_ensure_response( $response );
    }


    /**
     * Default team fields
     * @since     2.1.0
     * @access    public
     * @param     {array}   fields
     * @return    [array]   mixed
     */
    function form_story_tools($tools = []) {
        $default_tools = array(
            'image' => array(
                'name'  => __("Image", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'video' => array(
                'name'  => __("Video", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'embeded' => array(
                'name'  => __("Embeded File", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'text' => array(
                'name'  => __("Text", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'text_image' => array(
                'name'  => __("Text + Image", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'image_image' => array(
                'name'  => __("Image + Image", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'text_text' => array(
                'name'  => __("Text + Text", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
            'text_video' => array(
                'name'  => __("Text + Video", "wp-crowdfunding"),
                'icon'  => '',
                'show'  => true
            ),
        );
        return array_merge($default_tools, $tools);
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
            ),
            'post_status'=> 'publish'
        );
        $uniqeTags = [];
        $query = new \WP_Query( $arg );
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_tags = get_the_terms( get_the_ID(), 'product_tag' );
            if ($post_tags) {
                foreach($post_tags as $tag) {
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
    /* function get_states() {
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
    } */


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
    function get_subcategories($slug) {
        $taxonomy = 'product_cat';
        // Get the product category (parent) WP_Term object
        $parent = get_term_by( 'slug', $slug, $taxonomy );
        // Get an array of the subcategories IDs (children IDs)
        $children_ids = get_term_children( $parent->term_id, $taxonomy );
        $data = array();
        foreach($children_ids as $children_id){
            $term = get_term( $children_id, $taxonomy ); // WP_Term object
            $data[] = array(
                'label' => $term->name,
                'value' => $term->slug
            );
        }
        return $data;
    }

    /**
     * Default form reward types
     * @since     2.1.0
     * @access    public
     * @param     {array}   fields
     * @return    [array]   mixed
     */
    function form_reward_types($fields = []) {
        
        $default_fields = array(
            array(
                'title'     => __("Giving Thanks", "wp-crowdfunding"),
                'icon'      => '',
                'show'      => true
            ),
            array(
                'title'     => __("Digital Goods", "wp-crowdfunding"),
                'icon'      => '',
                'show'      => true
            ),
            array(
                'title'     => __("Physical Goods", "wp-crowdfunding"),
                'icon'      => '',
                'show'      => true
            )
        );

        return array_merge($default_fields, $fields);
    }


    /**
     * Get campaign form fields
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_form_reward_fields() {
        $response = array(
            'types' => apply_filters( 'wpcf_form_reward_types', [] ),
            'fields' => apply_filters( 'wpcf_form_reward_fields', [] )
        );
        return rest_ensure_response( $response );
    }


    /**
     * Default reward fields
     * @since     2.1.0
     * @access    public
     * @param     {array}   fields
     * @return    [array]   mixed
     */
    function form_reward_fields($fields = []) {

        $month_list = array(
            array( 'value' => 'jan', 'label' => __('January', "wp-crowdfunding") ),
            array( 'value' => 'feb', 'label' => __('February', "wp-crowdfunding") ),
            array( 'value' => 'mar', 'label' => __('March', "wp-crowdfunding") ),
            array( 'value' => 'apr', 'label' => __('April', "wp-crowdfunding") ),
            array( 'value' => 'may', 'label' => __('May', "wp-crowdfunding") ),
            array( 'value' => 'jun', 'label' => __('June', "wp-crowdfunding") ),
            array( 'value' => 'jul', 'label' => __('July', "wp-crowdfunding") ),
            array( 'value' => 'aug', 'label' => __('August', "wp-crowdfunding") ),
            array( 'value' => 'sep', 'label' => __('September', "wp-crowdfunding") ),
            array( 'value' => 'oct', 'label' => __('October', "wp-crowdfunding") ),
            array( 'value' => 'nov', 'label' => __('November', "wp-crowdfunding") ),
            array( 'value' => 'dec', 'label' => __('December', "wp-crowdfunding") ),
        );

        $year_list = array();
        foreach( range(date('Y'), date('Y')+10) as $year ) {
            $year_list[] = array( 'value' => $year, 'label' => __($year, "wp-crowdfunding") );
        }
        
        
        $default_fields = array(
            'title' => array(
                'type'          => 'text',
                'title'         => __("Title *", "wp-crowdfunding"),
                'desc'          => __("Briefly describe this reward.", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => true,
                'show'          => true
            ),
            'amount' => array(
                'type'          => 'text',
                'title'         => __("Pledge Amount *", "wp-crowdfunding"),
                'desc'          => __("Briefly describe this reward.", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => true,
                'show'          => true
            ),
            'image' => array(
                'type'      => 'image',
                'title'     => __("Rewards Image *","wp-crowdfunding"),
                'desc'      => __("Dimention Should be 560x340px ; Max Size : 5MB","wp-crowdfunding"),
                'button'    => '<i class="fa fa-plus"/> '.__('Add Image', 'wp-crowdfunding'),
                'class'     => '',
                'multiple'  => false,
                'required'  => true,
                'show'      => true
            ),
            'description' => array(
                'type'          => 'textarea',
                'title'         => __("Rewards Description *", "wp-crowdfunding"),
                'desc'          => __("Keep It Short. Just Small Brief About your Project", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => true,
                'show'          => true,
            ),
            'estimate_delivery' => array(
                'type'          => 'form_group',
                'title'         => __("Estimate Delivery *", "wp-crowdfunding"),
                'desc'          => __("Reach a more specific community by also choosing a subcategory", "wp-crowdfunding"),
                'class'         => '',
                'fields' => array(
                    'end_month' => array(
                        'type'          => 'select',
                        'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                        'class'         => 'col-md-7',
                        'options'       => $month_list,
                        'required'      => true,
                        'show'          => true
                    ),
                    'end_year' => array(
                        'type'          => 'select',
                        'placeholder'   => __("Select Sub-Catagory", "wp-crowdfunding"),
                        'class'         => 'col-md-5',
                        'options'       => $year_list,
                        'required'      => true,
                        'show'          => true
                    )
                )
            ),
            'rewards_items' => array(
                'type'      => 'repeatable',
                'title'     => __("Rewards Item *", "wp-crowdfunding"),
                'desc'      => __("Be Specific About What are Included in the Perks", "wp-crowdfunding"),
                'button'    => '<i class="fa fa-plus"/> '.__('Add More Item', 'wp-crowdfunding'),
                'class'     => '',
                'fields'    => array(
                    'name' => array(
                        'type'          => 'text',
                        'placeholder'   => __("", "wp-crowdfunding"),
                        'class'         => '',
                        'required'      => true,
                        'show'          => true,
                    ),
                ),
                'open_first_item' => true,
                'required'  => false,
                'show'      => true,
            ),
            'no_of_items' => array(
                'type'          => 'text',
                'title'         => __("Total Number of Rewards *", "wp-crowdfunding"),
                'desc'          => __("", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => true,
                'show'          => true
            ),
        );

        return array_merge($default_fields, $fields);
    }


    /**
     * Get campaign team fields
     * @since     2.1.0
     * @access    public
     * @return    [array]   mixed
     */
    function get_form_team_fields() {
        $response = apply_filters( 'wpcf_form_team_fields', [] );
        return rest_ensure_response( $response );
    }


    /**
     * Default team fields
     * @since     2.1.0
     * @access    public
     * @param     {array}   fields
     * @return    [array]   mixed
     */
    function form_team_fields($fields = []) {
        $default_fields = array(
            'email' => array(
                'type'          => 'email',
                'title'         => __("Email *", "wp-crowdfunding"),
                'desc'          => __("", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => false,
                'show'          => true
            ),
            'name' => array(
                'type'          => 'text',
                'title'         => __("Collaborator Name", "wp-crowdfunding"),
                'desc'          => __("", "wp-crowdfunding"),
                'placeholder'   => __("", "wp-crowdfunding"),
                'class'         => '',
                'required'      => false,
                'show'          => true
            ),
            'manage_campaign' => array(
                'type'      => 'checkbox',
                'title'     => __("If you Want to Show Contributor List", "wp-crowdfunding"),
                'desc'      => __("", "wp-crowdfunding"),
                'class'     => '',
                'options'   => array(
                    array(
                        'value' => 1,
                        'label' => __("Give Permission to Manage Campaign", "wp-crowdfunding"),
                        'class' => '',
                    )
                ),
                'required'  => false,
                'show'      => true,
            ),
            'edit_campaign' => array(
                'type'      => 'checkbox',
                'title'     => __("If you Want to Show Contributor List", "wp-crowdfunding"),
                'desc'      => __("", "wp-crowdfunding"),
                'class'     => '',
                'options'   => array(
                    array(
                        'value' => 1,
                        'label' => __("Give Permission to Edit Campaign", "wp-crowdfunding"),
                        'class' => '',
                    )
                ),
                'required'  => false,
                'show'      => true,
            ),
        );

        return array_merge($default_fields, $fields);
    }

    
    /**
     * Get campagin form data
     * @since     2.1.0
     * @access    public
     * @param     {object}  request
     * @return    [array]   mixed
     */
    function get_form_values() {
        $post_id = $_GET['id'];
        
        $media = get_post_meta($post_id, 'wpneo_media', true);
        $media = json_decode($media, true);
        if( !$media ) { //If empty media then set data from prev fields
            $media = array();
            $image_id = get_post_meta($post_id, '_thumbnail_id', true);
            if($image_id) {
                $thumb = wp_get_attachment_image_src( $image_id );
                $main_img = wp_get_attachment_image_src( $image_id, 'full' );
                $image_name = get_the_title($image_id);
                $image = array(
                    'id'    => $image_id,
                    'type'  => 'image',
                    'src'   => $main_img[0],
                    'name'  => $image_name,
                    'thumb' => $thumb[0],
                );
                array_push($media, $image);
            }
            $video_link = get_post_meta($post_id, 'wpneo_funding_video', true);
            if($video_link) {
                $video_id = $this->extractVideoID($video_link);
                if($video_id) {
                    $video = array(
                        'id'    => $video_id,
                        'type'  => 'video_link',
                        'src'   => $video_link,
                        'thumb' => "https://img.youtube.com/vi/{$video_id}/default.jpg",
                    );
                    array_push($media, $video);
                }
            }
        }

        $image = array();
        $video = array();
        $video_link = array();
        foreach($media as $m) {
            if($m['type'] == 'image') {
                array_push($image, $m);
            } else if($m['type'] == 'video') {
                array_push($video, $m);
            } else if($m['type'] == 'video_link') {
                array_push($video_link, $m);
            }
        }

        $category = false;
        $sub_category = false;
        $sub_categories = false;
        $cat_terms = get_the_terms( $post_id, 'product_cat' );
        foreach ( $cat_terms as $term ) {
            if($term->parent) {
                $sub_category = $term->slug;
            } else {
                $category = $term->slug;
            }
        }

        if($sub_category) {
            $options = $this->get_subcategories($category);
            $sub_categories = array(
                'section' => 'campaign_info',
                'field' => 'sub_category',
                'options' => $options,
            );
        }

        $tags = [];
        $post_tags = get_the_terms( $post_id, 'product_tag' );
        if ($post_tags) {
            foreach($post_tags as $tag) {
                $tags[] = array(
                    'value' => $tag->slug,
                    'label' => $tag->name
                );
            }
        }

        $res_story = get_post_meta($post_id, 'wpneo_story', true);
        $res_story = stripslashes($res_story);
        $res_story = json_decode($res_story, true);
        if($res_story==null) {
            $content = get_the_content(null, null, $post_id);
            $res_story = array(
                array(
                    array(
                        'type'  => 'text',
                        'value' => str_replace('"', "'", $content)
                    ),
                )
            );
        }

        $rewards = get_post_meta($post_id, 'wpneo_reward', true);
        $rewards = json_decode(stripslashes($rewards), true);
        
        $res_rewards = array();
        if ($rewards) {
            foreach( $rewards as $reward ) {
                $image = array();
                $image_id = $reward['wpneo_rewards_image_field'];
                if($image_id) {
                    $thumb = wp_get_attachment_image_src( $image_id );
                    $main_img = wp_get_attachment_image_src( $image_id, 'full' );
                    $image_name = get_the_title($image_id);
                    $m = array(
                        'id'    => $image_id,
                        'type'  => 'image',
                        'src'   => $main_img[0],
                        'name'  => $image_name,
                        'thumb' => $thumb[0],
                    );
                    array_push($image, $m);
                }
                $res_rewards[] = array(
                    'amount'        => $reward['wpneo_rewards_pladge_amount'],
                    'type'          => isset($reward['wpneo_rewards_type']) ? $reward['wpneo_rewards_type'] : '',
                    'title'         => isset($reward['wpneo_rewards_title']) ? $reward['wpneo_rewards_title'] : '',
                    'description'   => $reward['wpneo_rewards_description'],
                    'end_month'     => $reward['wpneo_rewards_endmonth'],
                    'end_year'      => $reward['wpneo_rewards_endyear'],
                    'no_of_items'   => $reward['wpneo_rewards_item_limit'],
                    'image'         => $image,
                    'rewards_items' => isset($reward['wpneo_rewards_items']) ? $reward['wpneo_rewards_items'] : []
                );
            }
        }

        $values = array(
            'basic' => array(
                'media'         => $media,
                'funding_goal'  => (float) get_post_meta($post_id, '_nf_funding_goal', true),
                'pledge_amount' => Array(
                    'min'       => (int) get_post_meta($post_id, 'wpneo_funding_minimum_price', true),
                    'max'       => (int) get_post_meta($post_id, 'wpneo_funding_maximum_price', true),
                ),
                'image'         => $image,
                'video'         => $video,
                'video_link'    => $video_link,
                'tags'          => $tags,
                'category'      => $category,
                'sub_category'  => $sub_category,
                'campaign_type' => get_post_meta($post_id, 'wpneo_campaign_type', true),
                'country'       => get_post_meta($post_id, 'wpneo_country', true),
                'location'      => get_post_meta($post_id, '_nf_location', true),
                'title'         => get_the_title($post_id),
                'short_desc'    => get_the_excerpt($post_id),
                'subtitle'      => get_post_meta($post_id, 'wpneo_subtitle', true),
                'fund_type'     => get_post_meta($post_id, 'wpneo_fund_type', true),
                'goal_type'     => get_post_meta($post_id, 'wpneo_campaign_end_method', true),
                'start_date'    => get_post_meta($post_id, '_nf_duration_start', true),
                'end_date'      => get_post_meta($post_id, '_nf_duration_end', true),

                'recommended_amount'    => (float) get_post_meta($post_id, 'wpneo_funding_recommended_price', true),
                'predefined_amount'     => get_post_meta($post_id, 'wpcf_predefined_pledge_amount', true),
                'contributor_table'     => get_post_meta($post_id, 'wpneo_show_contributor_table', true),
                'contributor_anonymity' => get_post_meta($post_id, 'wpneo_mark_contributors_as_anonymous', true),
            ),
            'story' => $res_story,
            'rewards' => $res_rewards,
            'team' => '',
        );

        $modified_date = get_the_modified_date('F j, Y', $post_id);
        $response = array(
            'postId'            => $post_id,
            'saveDate'          => $modified_date,
            'values'            => $values,
            'sub_categories'    => $sub_categories,
        );
        return rest_ensure_response($response);
    }


    /**
     * Save campagin data
     * @since     2.1.0
     * @access    public
     * @param     {object}  request
     * @return    [array]   mixed
     */
    function save_campaign( \WP_REST_Request $request ) {
        $user_id = $this->current_user_id;
        $json_params = $request->get_json_params();
        $post_id = $json_params['postId'];
        $submit = $json_params['submit'];
        $basic = $json_params['basic'];
        $story = $json_params['story'];
        $rewards = $json_params['rewards'];
        $team = $json_params['team'];

        if($story) {
            foreach($story as $key => $st) {
                foreach($st as $index => $s) {
                    $s['value'] = str_replace('"', "'", $s['value']);
                    $st[$index] = $s;
                }
                $story[$key] = $st;
            }
        }

        /* print_r($story);
        die(); */

        $campaign = array(
            'post_type'		=>'product',
            'post_title'    => sanitize_text_field($basic['title']),
            'post_excerpt'  => sanitize_text_field($basic['short_desc']),
            'post_author'   => $user_id,
        );

        //do_action('wpcf_before_campaign_submit_action');

        if($post_id) {
            //Prevent if unauthorised access
            $user_campaign_ids = $this->get_user_campaign_ids();
            $campaign['ID'] = $post_id;
            $campaign['post_status'] = ($submit) ? get_option('wpneo_campaign_edit_status', 'pending') : 'draft';

            if ( !in_array($campaign['ID'], $user_campaign_ids) ) {
                $response = array(
                    'success' => 0,
                    'message' => __('Unauthorized action', 'wp-crowdfunding'),
                );
                return rest_ensure_response($response);
            }
            wp_update_post( $campaign ); //update post
        } else {
            $campaign['post_status'] = get_option( 'wpneo_default_campaign_status' );
            $post_id = wp_insert_post( $campaign );
            if ($post_id) {
                /* WC()->mailer(); //load email classes
                do_action('wpcf_after_campaign_email', $post_id); */
            }
        }

        if ($post_id) {
            $tags = array();
            $category = array();
            if( $basic['category'] != '' ) {
                array_push($category, $basic['category']);
            }
            if( $basic['sub_category'] != '' ) {
                array_push($category, $basic['sub_category']);
            }
            if($basic['tag']) {
                $tags = array_column($basic['tag'], 'label');
            }
            //Update object terms
            wp_set_object_terms( $post_id , $tags, 'product_tag' );
            wp_set_object_terms( $post_id , $category, 'product_cat' );
            wp_set_object_terms( $post_id , 'crowdfunding', 'product_type', true );

            $media = $basic['media'];
            $image_id = '';
            $video_src = '';
            foreach($media as $m) {
                if($m['type'] == 'image') {
                    $image_id = $m['id'];
                    break;
                }
            }
            foreach($media as $m) {
                if($m['type'] == 'video' || $m['type'] == 'video_link') {
                    $video_src =  $m['src'];
                    break;
                }
            }

            wpcf_function()->update_meta($post_id, '_thumbnail_id', esc_attr($image_id));
            wpcf_function()->update_meta($post_id, 'wpneo_funding_video', esc_url($video_src));
            wpcf_function()->update_meta($post_id, 'wpneo_media', json_encode($media));
            wpcf_function()->update_meta($post_id, 'wpneo_story', json_encode($story));

            wpcf_function()->update_meta($post_id, 'wpneo_campaign_end_method', esc_attr($basic['goal_type']));
            if( isset($basic['goal_type']) && $basic['goal_type'] == 'target_date') {
                wpcf_function()->update_meta($post_id, '_nf_duration_start', esc_attr($basic['start_date']));
                wpcf_function()->update_meta($post_id, '_nf_duration_end', esc_attr($basic['end_date']));
            }

            wpcf_function()->update_meta($post_id, 'wpneo_subtitle', esc_attr($basic['subtitle']));
            wpcf_function()->update_meta($post_id, 'wpneo_fund_type', esc_attr($basic['fund_type']));
            wpcf_function()->update_meta($post_id, 'wpneo_campaign_type', esc_attr($basic['campaign_type']));
            wpcf_function()->update_meta($post_id, 'wpneo_funding_minimum_price', (int) esc_attr($basic['pledge_amount']['min']));
            wpcf_function()->update_meta($post_id, 'wpneo_funding_maximum_price', (int) esc_attr($basic['pledge_amount']['max']));
            wpcf_function()->update_meta($post_id, 'wpneo_funding_recommended_price', (float) esc_attr($basic['recommended_amount']));
            wpcf_function()->update_meta($post_id, 'wpcf_predefined_pledge_amount', esc_attr($basic['predefined_amount']));
            wpcf_function()->update_meta($post_id, '_nf_funding_goal', (float) esc_attr($basic['funding_goal']));
            wpcf_function()->update_meta($post_id, 'wpneo_show_contributor_table', esc_attr($basic['contributor_table']));
            wpcf_function()->update_meta($post_id, 'wpneo_mark_contributors_as_anonymous', esc_attr($basic['contributor_anonymity']));
            wpcf_function()->update_meta($post_id, 'wpneo_country', esc_attr($basic['country']));
            wpcf_function()->update_meta($post_id, '_nf_location', esc_html($basic['location']));

            //Saved repeatable rewards
            if ($rewards && is_array($rewards)) {
                $data = array();
                foreach( $rewards as $reward ) {
                    $reward_image = ($reward['image'] && is_array($reward['image'])) ? $reward['image'][0]['id'] : '';
                    $data[] = array(
                        'wpneo_rewards_pladge_amount'   => intval( $reward['amount'] ),
                        'wpneo_rewards_type'            => esc_html( $reward['type'] ),
                        'wpneo_rewards_title'           => esc_html( $reward['title'] ),
                        'wpneo_rewards_description'     => esc_html( $reward['description'] ),
                        'wpneo_rewards_endmonth'        => esc_html( $reward['end_month'] ),
                        'wpneo_rewards_endyear'         => esc_html( $reward['end_year'] ),
                        'wpneo_rewards_item_limit'      => esc_html( $reward['no_of_items'] ),
                        'wpneo_rewards_image_field'     => esc_html( $reward_image ),
                        'wpneo_rewards_items'           => $reward['rewards_items'],
                    );
                }
                $data_json = json_encode($data);
                wpcf_function()->update_meta($post_id, 'wpneo_reward', $data_json);
            }
        }

        if($submit) {
            $response = array(
                'submit'    => 1,
                'message'   => __('Campaign successfully submitted', 'wp-crowdfunding'),
                'redirect'  => get_permalink(get_option('wpneo_crowdfunding_dashboard_page_id')).'#/my-campaigns'
            );
        } else {
            $response = array(
                'submit'    => 0,
                'postId'    => $post_id,
                'saveDate'  => get_the_modified_date('F j, Y', $post_id)
            );
        }
        return rest_ensure_response($response);
    }

    /**
     * Get user campaign ids
     * @since     2.1.0
     * @access    public
     * @return    array
     */
    public function get_user_campaign_ids() {
        global $wpdb;
        $user_id = $this->current_user_id;
        $user_product_ids = $wpdb->get_col("select ID from {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'product' ");
        return $user_product_ids;
    }

    /**
     * Get video id from url
     * @since     2.1.0
     * @access    public
     * @return    array
     */
    public function extractVideoID($url) {
        preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches);
        if ( $matches[0][0] && strlen($matches[0][0]) == 11 ){
            return $matches[0][0];
        } else {
            return false;
        }
    }
}

new API_Campaign();