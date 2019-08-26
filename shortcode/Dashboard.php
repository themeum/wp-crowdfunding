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
        register_rest_route( $namespace, '/pledge-received', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'pledge_received'), ),
        ));
        register_rest_route( $namespace, '/bookmark-campaigns', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'bookmark_campaigns'), ),
        ));
        register_rest_route( $namespace, '/orders', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'orders'), ),
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
        $invested_campaign_ids = $wpdb->get_col($wpdb->prepare(
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
        ));

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
     * Get user pledge received
     * @access    public
     * @return    {json} mixed
     */
    function pledge_received() {
        global $wpdb;
        $total_goal = 0;
        $total_raised = 0;
        $campaign_ids = array();
        $order_ids = array();
        $customer_orders = array();
        $receiver_percent = get_option( 'wallet_receiver_percent' );
        //Get current user campaign list
        $args = array(
            'post_type' 		=> 'product',
            'author'    		=> get_current_user_id(),
            'tax_query' 		=> array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'crowdfunding',
                ),
            ),
            'posts_per_page'    => -1
        );
        $campaign_list = get_posts( $args );
        //Generate campaign_ids array and add total goal from campaign loop
        foreach ($campaign_list as $value) {
            $campaign_ids[] = $value->ID;
            $funding_goal = get_post_meta($value->ID, '_nf_funding_goal', true);
            $total_goal += $funding_goal;
        }
        //Get order_ids array from order_items by campaign ids
        if(!empty($campaign_ids)) {
            $campaign_ids = implode( ', ', $campaign_ids );
            $prefix = $wpdb->prefix;
            $order_ids = $wpdb->get_col(
                "
                SELECT      oi.order_id
                FROM        " . $wpdb->prefix . "woocommerce_order_items oi
                INNER JOIN  " . $wpdb->prefix . "woocommerce_order_itemmeta woim
                            ON woim.order_item_id = oi.order_item_id
                WHERE       woim.meta_key='_product_id'
                            AND woim.meta_value IN ( {$campaign_ids} )
                ORDER BY    oi.order_id DESC
                " 
            );
        }
        //Get customers orders with injecting details
        if(!empty($order_ids)) {
            $customer_orders = get_posts(array(
                'post__in'	  => $order_ids,
                'meta_key'    => '_customer_user',
                'post_type'   => wc_get_order_types( 'view-orders' ),
                'post_status' => array_keys( wc_get_order_statuses() )
            ));
            //Injecting order details to customer orders
            foreach ( $customer_orders as $customer_order )  {
                $order = wc_get_order( $customer_order );
                $order_details = $order->get_data();

                $total = $order_details['total'];
                $receivable = wc_price( $total );
                $marketplace = wc_price( 0 );
                //If receiver_percent available overwrite receivable adn marketplace value
                if( $receiver_percent ) {
                    $receivable = wc_price( ($total*$receiver_percent) / 100 );
                    $marketplace = wc_price( ($total* (100-$receiver_percent) ) / 100 );
                }
                $order_details['receivable'] = $receivable;
                $order_details['marketplace'] = $marketplace;
                $order_details['raised'] = wc_price( $total );
                //Plus fund raised if order is completed
                if( $order_details['status'] == 'completed' ) {
                    $total_raised += $total;
                }
                //Inject reward data if available
                $reward = get_post_meta($customer_order->ID, 'wpneo_selected_reward', true);
                if ( !empty($reward) && is_array($reward) ) {
                    $reward_html = '';
                    $reward_html .="<h3>".__('Selected Reward', 'wp-crowdfunding')."</h3>";
                    if ( !empty($reward['wpneo_rewards_description'])) {
                        $reward_html .= "<div>{$reward['wpneo_rewards_description']}</div>";
                    }
                    if ( !empty($reward['wpneo_rewards_pladge_amount'])) {
                        $reward_html .= "<div><abbr>".__('Amount','wp-crowdfunding').' : '.wc_price($reward['wpneo_rewards_pladge_amount']).', '.__(' Delivery','wp-crowdfunding').' : '.$reward['wpneo_rewards_endmonth'].', '.$reward['wpneo_rewards_endyear'];
                    }
                    $order_details['selected_reward'] = $reward_html;
                }
                $order_details['subtotal'] = wc_price($order->get_subtotal());
                $order_details['formatted_b_addr'] = $order->get_formatted_billing_address();
                $order_details['formatted_c_date'] = wc_format_datetime($order->get_date_created());
                $order_details['status_name'] = wc_get_order_status_name( $order_details['status'] );

                //Overwrite line items of campaign details
                $line_items = array();
                foreach ( $order->get_items() as $item_key => $item_values ) {
                    $item_data = $item_values->get_data();
                    $line_items[] = array(
                        'product_name' => $item_data['name'],
                        'line_subtotal' => $item_data['subtotal'],
                        'line_total' => $item_data['total']
                    );
                }
                //Overwrite line items of campaign details
                $order_details['line_items'] = $line_items;
                //Inject details to cutomer order
                $customer_order->details = $order_details;
            }
        }

        $response_data = array(
            'total_goal' => wc_price( $total_goal ),
            'total_raised' => wc_price( $total_raised ),
            'total_available' => wc_price( $total_goal - $total_raised ),
            'receiver_percent' => $receiver_percent ? $receiver_percent : '',
            'orders' => $customer_orders,
        );

       /*  echo "<pre>";
        print_r( $response_data ); */

        return rest_ensure_response( $response_data );
    }

    /**
     * Get user bookmark campaigns
     * @access    public
     * @return    {json} mixed
     */
    function bookmark_campaigns() {
        $campaign_ids = get_user_meta( $this->current_user_id, 'loved_campaign_ids', true );
        $campaign_ids = json_decode( $campaign_ids, true );
        $data = array();
        if( !empty( $campaign_ids ) ) {
            $query = array(
                'post_type' => 'product',
                'post__in' => $campaign_ids,
                'orderby' => 'post__in',
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


    /**
     * Get user order list
     * @access    public
     * @return    {json} mixed
     */
    function orders() {
        //Get customers orders
        $customer_orders = get_posts(array(
            'numberposts' => -1,
            'meta_key'    => '_customer_user',
            'meta_value'  => $this->current_user_id,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ));
        //Injecting order details to customer orders
        foreach ( $customer_orders as $customer_order )  {
            $order = wc_get_order( $customer_order );
            $order_details = $order->get_data(); //get order data
            $order_details['total'] = $order->get_formatted_order_total();
            $order_details['formatted_b_addr'] = $order->get_formatted_billing_address();
            $order_details['formatted_c_date'] = wc_format_datetime($order->get_date_created());
            $order_details['formatted_oc_date'] = wc_format_datetime($order->get_date_completed());
            $order_details['status_name'] = wc_get_order_status_name( $order_details['status'] );;

            //Get line items
            $line_items = array();
            foreach ( $order->get_items() as $item_key => $item_values ) {
                $item_data = $item_values->get_data();
                $line_items[] = array(
                    'product_id' => $item_data['product_id'],
                    'product_name' => $item_data['name']
                );
            }
            $order_details['line_items'] = $line_items; //Overwrite line items of campaign details
            $order_details['fulfillment'] =  ( wpcf_function()->is_campaign_valid( $line_items[0]['product_id'] ) ) ? 'On Process' : 'Done';
            $order_details['billing']['country_name'] =  WC()->countries->countries[ $order_details['billing']['country'] ];
            //Inject details to cutomer order
            $customer_order->details = $order_details;
        }

        /* echo "<pre>";
        print_r( $customer_orders ); */

        return rest_ensure_response( $customer_orders );
    }

}