<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WPCF_Stripe_Connect_Init
{

    protected $is_active = false;
    protected $client_id;
    protected $client_secret;
    protected $publishable_key;

    /**
     * @var null
     *
     * Instance of this class
     */
    protected static $_instance = null;

    /**
     * @return null|WPCF
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $settings = get_option('woocommerce_wpneo_stripe_connect_settings');
        if ($settings['enabled'] === 'yes'){
            $this->is_active = true;
            add_action( 'init', array($this, 'wpneo_stripe_enqueue_frontend_script') ); //Do
            add_action( 'wpneo_crowdfunding_dashboard_after_dashboard_form', array($this, 'generate_stripe_connect_form'),10);
        }

        if ($settings['test_mode'] === 'yes'){
            $this->client_id = empty($settings['test_client_id']) ? '' : $settings['test_client_id'];
            $this->client_secret = empty($settings['test_secret_key']) ? '' : $settings['test_secret_key'];
            $this->publishable_key = empty($settings['test_publishable_key']) ? '' : $settings['test_publishable_key'];
        }else{
            $this->client_id = empty($settings['live_client_id']) ? '' : $settings['live_client_id'];
            $this->client_secret = empty($settings['secret_key']) ? '' : $settings['secret_key'];
            $this->publishable_key = empty($settings['publishable_key']) ? '' : $settings['publishable_key'];
        }

        if ( ! empty($settings['enabled'])){
            if ($settings['enabled'] == 'yes')
                add_filter( 'woocommerce_available_payment_gateways', array($this, 'wpneo_crowdfunding_filter_gateways'), 1);
        }
    }

    public function get_authorized_from_stripe_application(){
        $user = wp_get_current_user();

        if (isset($_GET['code'])) { // Redirect w/ code
            $code = sanitize_text_field($_GET['code']);

            $token_request_body = array(
                        'grant_type'    => 'authorization_code',
                        'client_id'     => $this->client_id,
                        'code'          => $code,
                        'client_secret' => $this->client_secret
            );

            $req = curl_init("https://connect.stripe.com/oauth/token");
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true );
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

            // TODO: Additional error handling
            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
            $resp = json_decode(curl_exec($req), true);
            curl_close($req);

            if (! empty($resp['stripe_user_id'])){
                update_user_meta($user->ID, 'stripe_user_id', $resp['stripe_user_id']);
                return $resp['stripe_user_id'];
            }
            //echo $resp['access_token'];
        }else{
            return get_user_meta($user->ID, 'stripe_user_id', true);
        }
        return false;
    }

    public function wpneo_stripe_enqueue_frontend_script(){
        wp_enqueue_style('wpneo-crowdfunding-stripe-connect-css', plugin_dir_url(__DIR__).'assets/stripe-connect.css', array(),WPNEO_CROWDFUNDING_VERSION);
    }

    public function generate_stripe_connect_form(){
        $stripe_user_id = $this->get_authorized_from_stripe_application();
        $authorize_request_body = array(
            'response_type' => 'code',
            'scope' => 'read_write',
            'client_id' => $this->client_id
        );

        $url = 'https://connect.stripe.com/oauth/authorize' . '?' . http_build_query($authorize_request_body);
        $deauthorize_url = 'https://connect.stripe.com/oauth/authorize' . '?' . http_build_query( array( 'deauth' => $stripe_user_id ) );

        $html = '';

        $html .= '<div class="wpneo-single"><div class="wpneo-name float-left"><p>Stripe:</p></div><div class="wpneo-fields float-right">';

        if ($stripe_user_id){
            $html .= '<a href="'.$url.'" class="stripe-connect"><span>'.__('Connected', 'wp-crowdfunding').'</span></a>'; // Connect Button
            $html .= '<a class="stripe-connect" href="'.$deauthorize_url.'"><span>'.__('Disconnect', 'wp-crowdfunding').'</span></a>'; // Disconnect Button
        }else{
            $html .= '<a href="'.$url.'" class="stripe-connect"><span>'.__('Connect with Stripe', 'wp-crowdfunding').'</span></a>';
        }
        $html .= '</div></div>';

        echo $html;
    }

    /**
     * @param $gateways
     * @return mixed
     */

    function wpneo_crowdfunding_filter_gateways($gateways){
        global $woocommerce;

        foreach ($woocommerce->cart->cart_contents as $key => $values) {
            if (isset($values['product_id'])) {
                $_product = wc_get_product($values['product_id']);
                if ($_product->is_type('crowdfunding')) {
                    if (is_array($gateways)) {

                        $acceptable_only_gateway = array( 'wpneo_stripe_connect');
                        //Check if this campaign owner connected with stripe?
                        $post = get_post($_product->get_id());
                        $campaign_owner_id = $post->post_author;
                        $campaign_owner = get_user_meta($campaign_owner_id, 'stripe_user_id', true);
                        if (! $campaign_owner){
                            if(($key = array_search('wpneo_stripe_connect', $acceptable_only_gateway)) !== false) {
                                unset($acceptable_only_gateway[$key]);
                            }
                        }

                        foreach ($gateways as $key => $value) {
                            if (! in_array($key, $acceptable_only_gateway)) {
                                unset($gateways[$key]);
                            }
                        }
                    }
                } else {
                    unset($gateways['wpneo_stripe_connect']);
                }
            }
        }
        return $gateways;
    }
    
}
WPCF_Stripe_Connect_Init::instance();

