<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WPNeo_Stripe_Connect extends WC_Payment_Gateway
{

    protected $stripe_checkout_image;
    protected $client_id;
    protected $secret_key;
    protected $publishable_key;
    protected $testmode;

    /**
     * WPNeo_Stripe_Connect constructor.
     */
    public function __construct(){
        $this->id = 'wpneo_stripe_connect';
        $this->has_fields = false;
        $this->method_title = 'WPNeo Stripe connect';
        $this->has_fields           = true;
        $this->init_form_fields();


        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        $this->title              = $this->get_option('title');
        $this->description        = $this->get_option('description');
        $this->instructions       = $this->get_option('instructions');
        $this->stripe_checkout_image  = $this->get_option( 'stripe_checkout_image', '' );

        if ('yes' === $this->enabled){
	        add_action( 'wp_enqueue_scripts', array($this, 'wpneo_stripe_payment_script'), 10 ); //Do
        }

        if ($this->get_option('test_mode') === 'yes'){
            $this->testmode = true;
            $this->client_id = $this->get_option('test_client_id');
            $this->secret_key = $this->get_option('test_secret_key');
            $this->publishable_key = $this->get_option('test_publishable_key');
        }else{
            $this->testmode = false;
            $this->client_id = $this->get_option('live_client_id');
            $this->secret_key = $this->get_option('secret_key');
            $this->publishable_key = $this->get_option('publishable_key');
        }


        if ( $this->testmode ) {
            $this->description .= ' ' . sprintf( __( 'TEST MODE ENABLED. In test mode, you can use the card number 4242424242424242 with any CVC and a valid expiration date or check the documentation "<a href="%s">Testing Stripe</a>" for more card numbers.', 'woocommerce-gateway-stripe' ), 'https://stripe.com/docs/testing' );
            $this->description  = trim( $this->description );
        }


    }


    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title'       => __( 'Enable Stripe Connect Payment', 'wp-crowdfunding' ),
                'label'       => __( 'Enable Stripe Connect Payment', 'wp-crowdfunding' ),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no'
            ),

            'test_mode' => array(
                'title' 		=> __( 'Enable/Disable', 'wp-crowdfunding' ),
                'type' 			=> 'checkbox',
                'label' 		=> __( 'Enable Stripe Test Mode', 'wp-crowdfunding' ),
                'default' 		=> 'no'
            ),


            'receivers_percent' => array(
                'title' 		=> __( 'Receivers percent', 'wp-crowdfunding' ),
                'type' 			=> 'number',
                'desc_tip' 		=> true,
                'description' 	=> __( 'Campaign owner will get this percent, rest amount will credited stripe owner account as application fee', 'wp-crowdfunding' ),
                'default' 		=> ''
            ),

            'title' => array(
                'title' 		=> __( 'Title', 'wp-crowdfunding' ),
                'type' 			=> 'text',
                'desc_tip' 		=> true,
                'description' 	=> __( 'Title will see user during checkout as payment method', 'wp-crowdfunding' ),
                'default' 		=> ''
            ),
            'description' => array(
                'title' 		=> __( 'Description', 'wp-crowdfunding' ),
                'type' 			=> 'text',
                'desc_tip' 		=> true,
                'description' 	=> __( 'This description will see user during checkout', 'wp-crowdfunding' ),
                'default' 		=> ''
            ),

            'test_client_id' => array(
                'title'       => __( 'Stripe Connect Test Client ID', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your client ID from stripe app settings', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'live_client_id' => array(
                'title'       => __( 'Stripe Connect Live Client ID', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your client ID from stripe app settings', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'secret_key' => array(
                'title'       => __( 'Live Secret Key', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your API keys from your stripe account.', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'publishable_key' => array(
                'title'       => __( 'Live Publishable Key', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your API keys from your stripe account.', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_secret_key' => array(
                'title'       => __( 'Test Secret Key', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your API keys from your stripe account.', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_publishable_key' => array(
                'title'       => __( 'Test Publishable Key', 'wp-crowdfunding' ),
                'type'        => 'text',
                'description' => __( 'Get your API keys from your stripe account.', 'wp-crowdfunding' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'stripe_checkout_image' => array(
                'title'       => __( 'Stripe Checkout Image', 'wp-crowdfunding' ),
                'description' => __( 'Optionally enter the URL to a 128x128px image of your brand or product. e.g. <code>https://yoursite.com/wp-content/uploads/2016/07/yourimage.jpg</code>', 'woocommerce-gateway-stripe' ),
                'type'        => 'text',
                'default'     => '',
                'desc_tip'    => true,
            ),
            
        );
    }


    /**
     * Payment form on checkout page
     */
    public function payment_fields() {

        echo '<fieldset class="stripe-legacy-payment-fields">';

        echo $this->description;

        $user = wp_get_current_user();

        if ( $user ) {
            $user_email = get_user_meta( $user->ID, 'billing_email', true );
            $user_email = $user_email ? $user_email : $user->user_email;
        } else {
            $user_email = '';
        }

        echo '<div style="display:none;" id="stripe-payment-data"
					data-description=""
					data-email="' . esc_attr( $user_email ) . '"
					data-amount="' . esc_attr( $this->get_stripe_amount( WC()->cart->total ) ) . '"
					data-name="' . esc_attr( sprintf( __( '%s', 'woocommerce-gateway-stripe' ), get_bloginfo( 'name', 'display' ) ) ) . '"
					data-currency="' . esc_attr( strtolower( get_woocommerce_currency() ) ) . '"
					data-image="' . esc_attr( $this->stripe_checkout_image ) . '"
					data-locale="en">';

        echo '</div>';


        echo '</fieldset>';
    }

    /**
     * Get Stripe amount to pay
     * @return float
     */
    public function get_stripe_amount( $total, $currency = '' ) {
        if ( ! $currency ) {
            $currency = get_woocommerce_currency();
        }
        switch ( strtoupper( $currency ) ) {
            // Zero decimal currencies
            case 'BIF' :
            case 'CLP' :
            case 'DJF' :
            case 'GNF' :
            case 'JPY' :
            case 'KMF' :
            case 'KRW' :
            case 'MGA' :
            case 'PYG' :
            case 'RWF' :
            case 'VND' :
            case 'VUV' :
            case 'XAF' :
            case 'XOF' :
            case 'XPF' :
                $total = absint( $total );
                break;
            default :
                $total = round( $total, 2 ) * 100; // In cents
                break;
        }
        return $total;
    }

    /**
     * stripe payment script
     */

    public function wpneo_stripe_payment_script(){

        wp_enqueue_script( 'wpcf_stripe_checkout', 'https://checkout.stripe.com/v2/checkout.js', '', '2.0', true );
        wp_enqueue_script( 'wpneo_crowdfunding_stripe', plugin_dir_url( __DIR__).'assets/js/stripe_checkout.js', array( 'wpcf_stripe_checkout' ), WPNEO_CROWDFUNDING_VERSION, true );
        
        $stripe_params = array(
            'key'                  => $this->publishable_key,
            'i18n_terms'           => __( 'Please accept the terms and conditions first', 'woocommerce-gateway-stripe' ),
            'i18n_required_fields' => __( 'Please fill in required checkout fields first', 'woocommerce-gateway-stripe' ),
        );

        // If we're on the pay page we need to pass stripe.js the address of the order.
        if ( is_checkout_pay_page() && isset( $_GET['order'] ) && isset( $_GET['order_id'] ) ) {
            $order_key = urldecode( $_GET['order'] );
            $order_id  = absint( $_GET['order_id'] );
            $order     = wc_get_order( $order_id );

            if ( $order->get_id() === $order_id && $order->order_key === $order_key ) {
                $stripe_params['billing_first_name'] = $order->billing_first_name;
                $stripe_params['billing_last_name']  = $order->billing_last_name;
                $stripe_params['billing_address_1']  = $order->billing_address_1;
                $stripe_params['billing_address_2']  = $order->billing_address_2;
                $stripe_params['billing_state']      = $order->billing_state;
                $stripe_params['billing_city']       = $order->billing_city;
                $stripe_params['billing_postcode']   = $order->billing_postcode;
                $stripe_params['billing_country']    = $order->billing_country;
            }
        }

        wp_localize_script( 'wpneo_crowdfunding_stripe', 'wpneo_stripe_params', apply_filters( 'wpneo_stripe_params', $stripe_params ) );
    }



    /**
     * Process the payment
     */
    public function process_payment( $order_id, $retry = true, $force_customer = false ) {
        try {
            $order  = wc_get_order( $order_id );
            
            // Handle payment
            if ( $order->get_total() > 0 ) {
               // die(print_r($_POST));

                $campaign_owner_stripe_account = '';

                //Get total ordered amount from order item

                $stripe_receiver_account = null;
                foreach ($order->get_items() as $item) {
                    $product = wc_get_product($item['product_id']);
                    if($product->get_type() == 'crowdfunding'){
                        $campaign_author = $product->post->post_author;
                        $stripe_receiver_account = get_user_meta($campaign_author, 'stripe_user_id',true);
                    }
                }

                $receivers_percent = $this->get_option('receivers_percent');
                $receivers_percent = absint($receivers_percent);
                $total_amount = $this->get_stripe_amount($order->get_total());

                $reciever_amount = ($total_amount * $receivers_percent) / 100;
                $application_fee = $total_amount - $reciever_amount;
                $currency = get_woocommerce_currency();

                $actual_application_fee = $application_fee / 100;

                if ( ! empty($_REQUEST['stripe_token']) && $stripe_receiver_account){

                    require_once 'stripe-php/init.php';
                    try{
                        $stripeToken = empty($_REQUEST['stripe_token']) ? '' : $_REQUEST['stripe_token'];
                        $stripeToken = wc_clean($stripeToken);

                        \Stripe\Stripe::setApiKey($this->secret_key);
                        \Stripe\Charge::create(array(
                            'amount'            => $total_amount,
                            'currency'          => $currency,
                            'source'            => $stripeToken,
                            'application_fee'   => $application_fee,
                            //'destination' => $stripe_receiver_account,

                            "description"       => "Charged applicatin fee {$currency}{$actual_application_fee}",
                        ), array('stripe_account' => $stripe_receiver_account));


                       $order->payment_complete();
                    }catch (\Exception $e){
                        //die(print_r($e->getMessage()));
                        die(json_encode( array( 'result'   => 'failure', 'messages' => '<div class="woocommerce-error">'.$e->getMessage().'</div>' ) ));
                        //$order->update_status( 'failed', $e->getMessage() );
                    }
                }

                
            } else {
                $order->payment_complete();
            }

            // Remove cart
            WC()->cart->empty_cart();

            // Return thank you page redirect
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order )
            );

        } catch ( Exception $e ) {
            wc_add_notice( $e->getMessage(), 'error' );
            WC()->session->set( 'refresh_totals', true );
            return;
        }
    }


}

function add_wpneo_stripe_connect( $methods ) {
    $methods[] = 'WPNeo_Stripe_Connect';
    return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_wpneo_stripe_connect' );