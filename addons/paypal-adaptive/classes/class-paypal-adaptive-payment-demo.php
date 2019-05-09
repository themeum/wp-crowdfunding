<?php

defined( 'ABSPATH' ) || exit;

class Paypal_Adaptive_Payment extends WC_Payment_Gateway
{


    protected static $_instance = null;

    /**
     * @return null|Wpneo_Crowdfunding
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->id = 'paypal_adaptive_payment';
        $this->method_title = 'PayPal Adaptive Payment';
        $this->method_description = sprintf('PayPal Adaptive Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' );

        // Load the settings.
        //$this->init_form_fields();
        $this->init_settings();
    }
}

//Paypal_Adaptive_Payment::instance();

function add_paypal_adaptive_payment( $methods ) {
    $methods[] = 'Paypal_Adaptive_Payment';
    return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_paypal_adaptive_payment' );