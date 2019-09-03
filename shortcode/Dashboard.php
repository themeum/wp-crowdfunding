<?php
/**
 * WP-Crowdfunding dashboard REST API's
 *
 * @category   Crowdfunding
 * @package    Dashboard
 * @author     Themeum <www.themeum.com>
 * @copyright  2019 Themeum <www.themeum.com>
 * @version    Release: @1.0.0
 * @since      2.1.0
 */

namespace WPCF\shortcode;
defined( 'ABSPATH' ) || exit;

class Dashboard {
    /**
     * The version of user dashboard api
     * @var     int $api_version
     * @access  private
     */
    private $api_version = 1;

    /**
     * Logged in user id
     * @var     int $current_user_id
     * @access  private
     */
    private $current_user_id = 0;
    
    /**
     * Namespace of user dashboard api
     * @var     string $api_namespace
     * @access  private
     */
    private $api_namespace = 'wp-crowdfunding/v';
    
    /**
     * @constructor
     * @since 2.1.0
     */
    function __construct() {
        add_action( 'init', array( $this, 'init_rest_api') );
        add_action( 'wp_enqueue_scripts',    array($this, 'dashboard_assets') );
        add_shortcode( 'wpcf_dashboard', array( $this, 'dashboard_callback' ) );
        
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
        $namespace = $this->api_namespace . $this->api_version;
        $method_readable = \WP_REST_Server::READABLE;
        $method_creatable = \WP_REST_Server::CREATABLE;
        register_rest_route( $namespace, '/user-profile', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'user_profile') ),
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
        register_rest_route( $namespace, '/withdraws', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'withdraws'), ),
        ));
        register_rest_route( $namespace, '/withdraw-request', array(
            array( 'methods' => $method_creatable, 'callback' => array($this, 'withdraw_request'), ),
        ));
        register_rest_route( $namespace, '/save-userdata', array(
            array( 'methods' => $method_creatable, 'callback' => array($this, 'save_user_data'), ),
        ));
        register_rest_route( $namespace, '/withdraw-methods', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'withdraw_methods'), ),
        ));
        register_rest_route( $namespace, '/save-withdraw-account', array(
            array( 'methods' => $method_creatable, 'callback' => array($this, 'save_withdraw_account'), ),
        ));
        register_rest_route( $namespace, '/wc-countries', array(
            array( 'methods' => $method_readable, 'callback' => array($this, 'wc_countries'), ),
        ));
    }
    
    /**
     * Enqueue dashboard assets
     * @since   2.1.0
     * @access  public
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
     * @since     2.1.0
     * @access    public
     * @param     {object}  attr
     * @return    {html}    mixed
     */
    function dashboard_callback($attr) {
        return '<div id="wpcf-dashboard"></div>';
    }

    /**
     * Get dashboard profile data
     * @since     2.1.0
     * @access    public
     * @return    {json} mixed
     */
    function user_profile() {
        $user_id = $this->current_user_id;
        $user = get_userdata( $user_id );
        $country_code = get_user_meta( $user_id, 'profile_country', true );
        $profile_image_id = get_user_meta( $user_id, 'profile_image_id', true );
        $data = array(
            'username'          => $user->user_login,
            'first_name'        => isset($user->first_name) ? $user->first_name : '',
            'last_name'         => isset($user->last_name) ? $user->last_name : '',
            'profile_image'     => get_avatar_url( $user_id ),
            'profile_image_id'  => $profile_image_id,
            'profile_email1'    => get_user_meta( $user_id, 'profile_email1', true ),
            'profile_mobile1'   => get_user_meta( $user_id, 'profile_mobile1', true ),
            'profile_country'   => $country_code,
            'country_name'      => WC()->countries->countries[$country_code],
            'profile_city'      => get_user_meta( $user_id, 'profile_city', true ),
            'profile_address'   => get_user_meta( $user_id, 'profile_address', true ),
            'profile_post_code' => get_user_meta( $user_id, 'profile_post_code', true ),
            'profile_facebook'  => get_user_meta( $user_id, 'profile_facebook', true ),
            'profile_twitter'   => get_user_meta( $user_id, 'profile_twitter', true ),
            'profile_instagram' => get_user_meta( $user_id, 'profile_instagram', true ),
            'profile_youtube'   => get_user_meta( $user_id, 'profile_youtube', true ),
            'profile_linkedin'  => get_user_meta( $user_id, 'profile_linkedin', true ),
            'profile_pinterest' => get_user_meta( $user_id, 'profile_pinterest', true ),
            'profile_vk'        => get_user_meta( $user_id, 'profile_vk', true )
        );
        if ( $profile_image_id && $profile_image_id > 0 ) {
            //Overwrite profile image if image meta avaibale
            $data['profile_image'] = wp_get_attachment_image_src($profile_image_id, 'full')[0];
        }
        $data['countries'] = $countries;
        return rest_ensure_response( (object) $data );
    }

    /**
     * Get user my campaigns
     * @since     2.1.0
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
        //Fetch all campaigns by query
        $data = $this->fetch_campaigns( $query ); 
        return rest_ensure_response( $data );
    }

    /**
     * Get user invested campaigns
     * @since     2.1.0
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
            //Fetch all campaigns by query
            $data = $this->fetch_campaigns( $query );
        }
        return rest_ensure_response( $data );
    }

    /**
     * Get user pledge received
     * @since     2.1.0
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
        return rest_ensure_response( $response_data );
    }

    /**
     * Get user bookmark campaigns
     * @since     2.1.0
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
            //Fetch all campaigns by query
            $data = $this->fetch_campaigns( $query );
        }
        return rest_ensure_response( $data );
    }

    /**
     * Fetch campaigns from the query
     * @since     2.1.0
     * @access    private
     * @param     {array}  query
     * @return    {array}  mixed
     */
    private function fetch_campaigns( $query ) {
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
     * @since     2.1.0
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

        return rest_ensure_response( $customer_orders );
    }

    /**
     * Get user withdraw list
     * @since     2.1.0
     * @access    public
     * @return    {json} mixed
     */
    function withdraws() {
        global $woocommerce, $wpdb;
        $product_ids = array();
        $product_ids = $wpdb->get_col(
            "
            SELECT      ID
            FROM        {$wpdb->posts}
            WHERE       post_author = {$this->current_user_id}
                        AND post_type= 'product'
            "
        );
        $where_meta = array();
        $where_meta[] = array(
            'type' => 'order_item_meta',
            'meta_key' => '_product_id',
            'operator' => 'in',
            'meta_value' => $product_ids
        );
        //Get all sold campaigns
        $sold_campaigns = $this->sold_campaigns( $where_meta ); 

        $response = array();
        foreach ($sold_campaigns as $campaign) {
            $campaign_id = $campaign->product_id;
            $campaign_title = get_the_title( $campaign_id );
            $total_goal = get_post_meta( $campaign_id, '_nf_funding_goal', true);
            $total_raised = $campaign->gross;
            $raised_percent = wpcf_function()->get_raised_percent( $campaign_id );
            $receiver_percent = get_post_meta( $campaign_id, 'wpneo_wallet_receiver_percent', true );
            if ( !$receiver_percent ) {
                $receiver_percent = (int) get_option('wallet_receiver_percent');
                update_post_meta( $campaign_id, 'wpneo_wallet_receiver_percent', $receiver_percent);
            }
            $total_receivable = ( $total_raised * $receiver_percent ) / 100;
            //Get withdraw details by campaign id
            $withdraw_details = $this->withdraw_details( $campaign_id );
            //Add response data
            $response[] = array(
                'campaign_id' => $campaign_id,
                'campaign_title' => html_entity_decode( $campaign_title ),
                'total_goal' => wc_price( $total_goal ),
                'total_raised' => wc_price( $total_raised ),
                'raised_percentage' => wpcf_function()->get_raised_percent( $campaign_id ),
                'receiver_percent' => $receiver_percent,
                'total_receivable' => wc_price( $total_receivable ),
                'withdraw' => array(
                    'request_items' => $withdraw_details->request_items,
                    'total_withdraw' => wc_price( $withdraw_details->total_withdraw ),
                    'balance' => wc_price( $total_receivable - $withdraw_details->total_withdraw),
                ),
            );
        }
        return rest_ensure_response( $response );
    }

    /**
     * Post user withdraw request
     * @since     2.1.0
     * @access    public
     * @param     {object}  request
     * @return    {json}    mixed
     */
    function withdraw_request( \WP_REST_Request $request ) {
        global $wpdb, $woocommerce;
        $campaign_id = (int) $request['campaign_id'];
        $requested_withdraw_amount = $request['withdraw_amount'];
        $withdraw_message = sanitize_text_field( $request['withdraw_message'] );

        //return error if invalid data
        if( empty($campaign_id) || $requested_withdraw_amount <= 0  ) {
            return rest_ensure_response(array(
                'success' => 0,
                'msg' => __('Amount must be greater than 0', 'wp-crowdfunding-pro')
            ));
        }

        $date_format = date(get_option('date_format'));
        $time_format = date(get_option('time_format'));

        $where_meta = array();
        $where_meta[] = array(
            'type' => 'order_item_meta',
            'meta_key' => '_product_id',
            'operator' => 'in',
            'meta_value' => array($campaign_id)
        );
        //Get sold campaign data by meta query
        $sold_campaigns = $this->sold_campaigns( $where_meta );
        $total_raised = 0;
        foreach ($sold_campaigns as $campaign) {
            $total_raised = $campaign->gross;
        }
        $receiver_percent = get_post_meta($campaign_id, 'wpneo_wallet_receiver_percent', true);
        $total_receivable = ( $total_raised * $receiver_percent ) / 100;
        $balance = $total_receivable;

        //Get withdraw details by campaign id
        $withdraw_details = $this->withdraw_details( $campaign_id );
        $total_withdraw = $withdraw_details->total_withdraw;
        $request_items = $withdraw_details->request_items;
        $balance = $total_receivable - $total_withdraw;

        //Compare if balance is greater then commission
        if ($requested_withdraw_amount <= $balance) {
            $post_title = 'Withdraw request' . ' - '.$date_format.' @ '.$time_format;
            $deposit_data = array(
                'post_title'    => $post_title,
                'post_type'     => 'wpneo_withdraw',
                'post_status'   => 'publish',
                'post_author'   => $this->current_user_id,
                'post_parent'   => $campaign_id,
                'post_content'  => $withdraw_message,
                'meta_input'    => array(
                    'wpneo_wallet_withdrawal_amount'  => $requested_withdraw_amount,
                ),
            );
            //Insert deposit data now
            $post_id = wp_insert_post($deposit_data);
            if ( $post_id ) {
                update_post_meta( $post_id,'withdraw_request_status','pending');
                WC()->mailer(); // load email classes
                do_action('wpcf_withdrawal_request_email', $post_id);
            }
            //New response data
            $total_withdraw = $total_withdraw+$requested_withdraw_amount;
            //push new item to request items
            array_push( $request_items, (object) [
                'title' => $post_title,
                'amount' => wc_price( $requested_withdraw_amount ),
                'status' => get_post_meta( $post_id, 'withdraw_request_status', true )
            ]);
            $response_data = (object) [
                'campaign_id' => $campaign_id,
                'withdraw' => [
                    'request_items' => $request_items,
                    'total_withdraw' => wc_price( $total_withdraw  ),
                    'balance' => wc_price( $total_receivable - $total_withdraw)
                ]
            ];

            $response = array(
                'success' => 1,
                'data' => $response_data
            );
        } else {
            $response = array(
                'success' => 0, 
                'msg' => __('You are not eligible to make this withdraw', 'wp-crowdfunding-pro')
            );
        }
        return rest_ensure_response( $response );
    }

    /**
     * Get sold campaigns by where_meta
     * @since     2.1.0
     * @access    private
     * @param     {object}  request
     * @return    {array}   mixed
     */
    private function sold_campaigns( $where_meta ) {
        global $wpdb, $woocommerce;
        if( !class_exists('WC_Admin_Report') ) {
            include_once($woocommerce->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');
        }
        //Avoid max join size error
        $wpdb->query('SET SQL_BIG_SELECTS=1');
        $wc_report = new \WC_Admin_Report();
        $sold_campaigns = $wc_report->get_order_report_data(array(
            'data' => array(
                '_product_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'product_id'
                ),
                '_qty' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => 'SUM',
                    'name' => 'quantity'
                ),
                '_line_subtotal' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => 'SUM',
                    'name' => 'gross'
                ),
                '_line_total' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => 'SUM',
                    'name' => 'gross_after_discount'
                )
            ),
            'query_type' => 'get_results',
            'group_by' => 'product_id',
            'where_meta' => $where_meta,
            'order_by' => 'ID DESC',
            'order_types' => wc_get_order_types('order_count'),
            'order_status' => array('completed')
        ));
        return $sold_campaigns;
    }

    /**
     * Get withdraw details by campaign_id
     * @access    private
     * @param     {int}   campaign_id
     * @return    {array} mixed
     */
    private function withdraw_details( $campaign_id ) {
        $withdraw_query = new \WP_Query(array(
            'post_type' => 'wpneo_withdraw',
            'post_parent'   => $campaign_id
        ));
        $total_withdraw = 0;
        $request_items = array();
        if ($withdraw_query->have_posts()) {
            while( $withdraw_query->have_posts() ) { 
                $withdraw_query->the_post();
                $request_status = get_post_meta( get_the_ID(),'withdraw_request_status',true );
                $request_amount = get_post_meta( get_the_ID(),'wpneo_wallet_withdrawal_amount',true );
                $request_items[] = (object) [
                    'title' => get_the_title(),
                    'amount' => wc_price( $request_amount ),
                    'status' => $request_status
                ];
                $total_withdraw += $request_amount;
            }
        }
        return (object) [
            'total_withdraw' => $total_withdraw, 
            'request_items' => $request_items 
        ];
    }

    /**
     * Save user user data
     * @since     2.1.0
     * @access    public
     * @param     {object}  request
     * @return    {json}    mixed
     */
    function save_user_data( \WP_REST_Request $request ) {
        $user_id = $this->current_user_id;
        $json_params = $request->get_json_params();
        $response_data = array();
        if( isset($json_params['password']) ) {
            $password = $json_params['password'];
            unset( $json_params['password'] );
            wp_set_password( $password, $user_id );
        }
        foreach( $json_params as $key => $value ) {
            update_user_meta( $user_id, $key, sanitize_text_field($value) );
            $response_data[$key] = sanitize_text_field( $value );
        }
        $response = array(
            'success' => 1, 
            'data' => $response_data
        );
        return rest_ensure_response( $response );
    }


    /**
     * Get withdraw methods
     * @since     2.1.0
     * @access    public
     * @return    {json}    mixed
     */
    function withdraw_methods() {
        $saved_methods = get_option('wpcf_withdraw_methods');
        $saved_methods = is_array($saved_methods) ? $saved_methods : [];
        $min_withdraw_amount = get_option('walleet_min_withdraw_amount');
        $method_desc = 'Min Withdraw '. wc_price($min_withdraw_amount);
        $withdraw_methods = array(
            //Bank Transfer
			'bank_transfer' => array(
				'method_name' => __('Bank Transfer', 'wp-crowdfunding'),
				'desc' => __($method_desc, 'wp-crowdfunding'),
				'form_fields' => array(
					array(
                        'type' => 'text',
                        'name' => 'account_name',
						'label' => __('Account Name', 'wp-crowdfunding'),
					),
					array(
                        'type' => 'text',
                        'name' => 'account_number',
						'label' => __('Account Number', 'wp-crowdfunding'),
					),
					array(
                        'type'  => 'text',
                        'name' => 'bank_name',
						'label' => __('Bank Name', 'wp-crowdfunding'),
					),
					array(
                        'type' => 'text',
                        'name' => 'iban',
						'label' => __('IBAN', 'wp-crowdfunding'),
					),
					array(
                        'type' => 'text',
                        'name' => 'swift',
						'label' => __('BIC / SWIFT', 'wp-crowdfunding'),
					),
				),
			),
            //ECHECK
			'echeck' => array(
                'method_name'  => __('ECHECK', 'wp-crowdfunding'),
                'desc' => __($method_desc, 'wp-crowdfunding'),
				'form_fields' => array(
					array(
                        'type' => 'textarea',
                        'name' => 'physical_address',
						'label' => __('Your Physical Address', 'wp-crowdfunding'),
						'desc' => __('We will send you an ECHECK to this address directly.', 'wp-crowdfunding'),
					),
				),
            ),
            //PayPal Payment
			'paypal' => array(
                'method_name' => __('PayPal Payment', 'wp-crowdfunding'),
                'desc' => __($method_desc, 'wp-crowdfunding'),
				'form_fields' => array(
					array(
                        'type' => 'email',
                        'name' => 'paypal_email',
						'label' => __('PayPal E-Mail Address', 'wp-crowdfunding'),
						'desc' => __('Write your paypal email address to get payout directly to your paypal account', 'wp-crowdfunding'),
					),
				),
			),
        );
        
        foreach( $withdraw_methods as $key => $method ) {
            if( !in_array($key, $saved_methods) ) {
                unset( $withdraw_methods[$key] ); //remove method if not set from admin
            }
        }

        $selected_method = get_user_meta($this->current_user_id, 'wpcf_user_withdraw_account', true);

        $response = array(
            'success' => 1,
            'methods' => $withdraw_methods,
            'selected_method' => $selected_method
        );

        return rest_ensure_response( $response );
    }

    /**
     * Save user withdraw account
     * @since     2.1.0
     * @access    public
     * @param     {object}  request
     * @return    {json}    mixed
     */
    function save_withdraw_account( \WP_REST_Request $request ) {
        $user_id = $this->current_user_id;
        $json_params = $request->get_json_params();
        
        $saved_data = array(
            'key' => $json_params['key'],
            'data' => $json_params['data']
        );

        update_user_meta($user_id, 'wpcf_user_withdraw_account', $saved_data);

        $response = array(
            'success' => 1, 
            'data' => $saved_data
        );
        return rest_ensure_response( $response );
    }
    

    /**
     * Get wc_countries
     * @since     2.1.0
     * @access    public
     * @return    {json} mixed
     */
    function wc_countries() {
        $countries = (array) WC()->countries->countries;
        return rest_ensure_response( $countries );
    }

}