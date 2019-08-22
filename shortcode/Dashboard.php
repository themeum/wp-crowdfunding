<?php
namespace WPCF\shortcode;

defined( 'ABSPATH' ) || exit;

class Dashboard {

	public $api_version = 1;
	public $current_user_id = 0;
    public $api_namespace = 'wp-crowdfunding/v';
    
    /**
     * Constructor function
     * @constructor
     */
    function __construct() {
        add_action( 'init', array( $this, 'init_rest_api') );
        add_action( 'wp_enqueue_scripts',    array($this, 'dashboard_assets') );
        add_shortcode( 'wpcf_dashboard', array( $this, 'dashboard_callback' ) );
        
    }

    /**
     * Init rest api
     * @access    public
     */
    function init_rest_api() {
        $this->current_user_id = get_current_user_id();
        if( $this->current_user_id )
        add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
    }

    /**
     * Register rest api routes
     * @access    public
     */
    function register_rest_api() {
        $namespace = $this->api_namespace . $this->api_version;
        $method_readable = \WP_REST_Server::READABLE;
        register_rest_route( $namespace, '/dashbord-profile', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'dashbord_profile') ),
        ));
        register_rest_route( $namespace, '/my-campaigns', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'my_campaigns') ),
        ));
        register_rest_route( $namespace, '/invested-campaigns', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'invested_campaigns'), ),
        ));
    }
    
    /**
     * Enqueue dashboard assets
     * @access    public
     */
    function dashboard_assets() {
        $api_namespace = $this->api_namespace . $this->api_version;
        $page_id = get_option('wpneo_crowdfunding_dashboard_page_id');
        if( get_the_ID() && get_the_ID() == $page_id ) {
            wp_enqueue_script( 'wpcf-dashboard-script', WPCF_DIR_URL.'assets/js/dashboard.js', array('jquery'), WPCF_VERSION, true );
            wp_localize_script( 'wpcf-dashboard-script', 'WPCF', array (
                'dashboard_url' => get_permalink(),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'rest_url' => rest_url( $api_namespace )
            ) );
        }
    }

    /**
     * Dashboard shortcode callback
     * @param     {object}  attr
     * @access    public
     * @return    {html} mixed
     */
    function dashboard_callback($attr) {
        return '<div id="wpcf-dashboard"></div>';
    }

    /**
     * Get dashboard profile data
     * @access    public
     * @return    {json} mixed
     */
    function dashbord_profile() {
        $current_user_id = $this->current_user_id;
        $user = get_user_by('ID', $current_user_id);
        $data = ( object ) get_user_meta($current_user_id);
        $data->display_name = isset($user->display_name) ? $user->display_name : '';
        $data->first_name = isset($user->first_name) ? $user->first_name : '';
        $data->last_name = isset($user->last_name) ? $user->last_name : '';

        $data->img_src = get_avatar_url( $current_user_id );
        $image_id = get_user_meta( $current_user_id, 'profile_image_id', true );
        if ( $image_id && $image_id > 0 ) {
            $data->img_src = wp_get_attachment_image_src($image_id, 'full')[0];
        }
        return rest_ensure_response( $data );
    }

    /**
     * Get user my campaigns
     * @access    public
     * @return    {json} mixed
     */
    function my_campaigns() {
        $query = array(
            'post_type' 		=> 'product',
            'post_status'       => array( 'pending', 'draft', 'publish' ),
            'author'    		=> $this->current_user_id,
            'tax_query' 		=> array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
        );
        $data = $this->fetchCampaigns( $query ); //call to private function fetchCampaigns
        return rest_ensure_response( $data );
    }

    /**
     * Get user invested campaigns
     * @access    public
     * @return    {json} mixed
     */
    function invested_campaigns() {
        global $wpdb;
        $invested_campaign_ids = $wpdb->get_col( $wpdb->prepare(
            "
            SELECT      itemmeta.meta_value
            FROM        " . $wpdb->prefix . "woocommerce_order_itemmeta itemmeta
            INNER JOIN  " . $wpdb->prefix . "woocommerce_order_items items
                        ON itemmeta.order_item_id = items.order_item_id
            INNER JOIN  $wpdb->posts orders
                        ON orders.ID = items.order_id
            INNER JOIN  $wpdb->postmeta ordermeta
                        ON orders.ID = ordermeta.post_id
            WHERE       itemmeta.meta_key = '_product_id'
                        AND ordermeta.meta_key = '_customer_user'
                        AND ordermeta.meta_value = %s
            ORDER BY    orders.post_date DESC
            ",
            $this->current_user_id
        ) );
        $invested_campaign_ids = array_unique( $invested_campaign_ids );
    
        $data = array();
        if( !empty( $invested_campaign_ids ) ) {
            $query = array(
                'post_type' => 'product',
                'post_status' => array( 'pending', 'draft', 'publish' ),
                'post__in' => $invested_campaign_ids,
                'orderby' => 'post__in',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_type',
                        'field'    => 'slug',
                        'terms'    => 'crowdfunding',
                    ),
                ),
            );
            $data = $this->fetchCampaigns( $query ); //call to private function fetchCampaigns
        }
        return rest_ensure_response( $data );
        
    }

    /**
     * Fetch campaigns from the query
     * @param     {array}  query
     * @access    public
     * @return    {array} mixed
     */
    private function fetchCampaigns( $query ) {
        $wp_query = new \WP_Query( $query );
        $data = array();
        if ( $wp_query->have_posts() ) : global $post; $i = 0;
            while ( $wp_query->have_posts() ) : $wp_query->the_post();
                $total_raised = wpcf_function()->get_total_fund();
                $total_raised = ($total_raised) ? $total_raised : 0;
                $funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true);
                $end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
                $data[$i] = array(
                    'title'             => get_the_title(),
                    'permalink'         => get_permalink(),
                    'thumbnail'         => woocommerce_get_product_thumbnail(),
                    'author_name'       => wpcf_function()->get_author_name(),
                    'location'          => wpcf_function()->campaign_location(),
                    'raised_percent'    => wpcf_function()->get_fund_raised_percent_format(),
                    'total_raised'      => wc_price( $total_raised ),
                    'funding_goal'      => wc_price( $funding_goal ),
                    'end_method'        => $end_method,
                    'is_started'        => wpcf_function()->is_campaign_started(),
                    'days_remaining'    => wpcf_function()->get_date_remaining(),
                    'days_until_launch' => wpcf_function()->days_until_launch(),
                    'status'            => wpcf_function()->get_campaign_status()
                );
                $i++;
            endwhile;
        endif;
        return $data;
    }

}