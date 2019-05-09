<?php

defined( 'ABSPATH' ) || exit;

class WC_Gateway_Twocheckout extends WC_Payment_Gateway
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
        $this->id = 'twocheckout';
        $this->method_title = '2Checkout';
        $this->method_description = sprintf('2Checkout Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' );

        // Load the settings.
        //$this->init_form_fields();
        $this->init_settings();
    }
}

/**
 * Add the gateway to WooCommerce
 **/
function add_twocheckout_gateway($methods){
    $methods[] = 'WC_Gateway_Twocheckout';
    return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_twocheckout_gateway');
