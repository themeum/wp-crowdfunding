<?php
/**
 *
 * Paypal Addon for woocommerce
 */
defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_PAYPAL_ADAPTIVE_FILE', __FILE__);


if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

//Check is WooCommerce is Active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {

    /**
     * Showing config for addons central lists
     */
    add_filter('wpcf_addons_lists_config', 'wpcf_paypal_adaptive_config');
    function wpcf_paypal_adaptive_config($config){
        $newConfig = array(
            'name'          => __('PayPal Adaptive Payment', 'wp-crowdfunding'),
            'description'   => __( sprintf('PayPal Adaptive Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' ), 'wp-crowdfunding' ),
        );
        $basicConfig = (array) WPCF_PAYPAL_ADAPTIVE();
        $newConfig = array_merge($newConfig, $basicConfig);

        $config[plugin_basename( WPCF_PAYPAL_ADAPTIVE_FILE )] = $newConfig;
        return $config;
    }

    if ( ! function_exists('WPCF_PAYPAL_ADAPTIVE')) {
        function WPCF_PAYPAL_ADAPTIVE() {
            $info = array(
                'path'              => plugin_dir_path( WPCF_PAYPAL_ADAPTIVE_FILE ),
                'url'               => plugin_dir_url( WPCF_PAYPAL_ADAPTIVE_FILE ),
                'basename'          => plugin_basename( WPCF_PAYPAL_ADAPTIVE_FILE ),
                'nonce_action'      => 'wpcf_nonce_action',
                'nonce'             => '_wpnonce',
            );
            return (object) $info;
        }
    }

    if (WPCF_TYPE === 'enterprise'){
        include_once 'classes/class-paypal-adaptive-payment.php';
        include_once 'classes/class-wpneo-adaptive-payment-status-dashboard.php';
        include_once 'classes/class-wpneo-adaptive-payment-initiate.php';
    }else{
        include_once 'classes/class-paypal-adaptive-payment-demo.php';
    }
    
}