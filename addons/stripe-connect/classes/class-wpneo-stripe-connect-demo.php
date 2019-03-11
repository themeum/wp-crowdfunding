<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WPNeo_Stripe_Connect extends WC_Payment_Gateway
{
    /**
     * WPNeo_Stripe_Connect constructor.
     */
    public function __construct(){
        $this->id = 'wpneo_stripe_connect';
        $this->method_title = 'WPNeo Stripe connect';
        $this->method_description = sprintf('WPNeo Stripe Connect gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' );

        $this->init_settings();
    }
    
}

//Paypal_Adaptive_Payment::instance();

function add_wpneo_stripe_connect( $methods ) {
    $methods[] = 'WPNeo_Stripe_Connect';
    return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_wpneo_stripe_connect' );