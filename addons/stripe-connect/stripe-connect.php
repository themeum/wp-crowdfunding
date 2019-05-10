<?php

/**
 *
 * Paypal Addon for woocommerce
 */


defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_STRIPTE_CONNECT_FILE', __FILE__);

/**
 * Check is plugin.php file loaded
 */
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

//Check is WooCommerce is Active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    
    /**
     * Showing config for addons central lists
     */
    add_filter('wpcf_addons_lists_config', 'wpcf_stripe_connect_config');
    function wpcf_stripe_connect_config($config){
        $newConfig = array(
            'name'          => __( 'Stripe connect', 'wp-crowdfunding' ),
            'description'   => __( sprintf('WPNeo Stripe Connect gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' ), 'wp-crowdfunding' ),
        );

        $basicConfig = (array) WPCF_STRIPTE_CONNECT();
        $newConfig = array_merge($newConfig, $basicConfig);

        $config[plugin_basename( WPCF_STRIPTE_CONNECT_FILE )] = $newConfig;
        return $config;
    }

    if ( ! function_exists('WPCF_STRIPTE_CONNECT')) {
        function WPCF_STRIPTE_CONNECT() {
            $info = array(
                'path'              => plugin_dir_path( WPCF_STRIPTE_CONNECT_FILE ),
                'url'               => plugin_dir_url( WPCF_STRIPTE_CONNECT_FILE ),
                'basename'          => plugin_basename( WPCF_STRIPTE_CONNECT_FILE ),
                'nonce_action'      => 'wpcf_nonce_action',
                'nonce'             => '_wpnonce',
            );
            return (object) $info;
        }
    }

    if (WPCF_TYPE === 'enterprise'){
        include_once 'classes/class-wpneo-stripe-connect.php';
        include_once 'classes/class-wpneo-stripe-connect-init.php';
    }else{
        include_once 'classes/class-wpneo-stripe-connect-demo.php';
    }
}
