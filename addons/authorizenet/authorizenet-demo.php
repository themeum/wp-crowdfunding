<?php

defined( 'ABSPATH' ) || exit;

class WC_Authorizenet_Gateway extends WC_Payment_Gateway
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
        $this->id               = 'wpcrowdfunding_authorizenet';
        $this->method_title     = 'Authorize.Net Settings';

        $this->method_description = sprintf('Authorize.Net Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' );

        // Load the settings.
        //$this->init_form_fields();
        $this->init_settings();
    }
}

/**
 * @param $methods
 * @return array
 */
function add_wpcrowdfunding_authorizenet_gateway_class( $methods )
{
    $methods[] = 'WC_Authorizenet_Gateway';
    return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_wpcrowdfunding_authorizenet_gateway_class' );
