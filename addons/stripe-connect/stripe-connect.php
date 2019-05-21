<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_STRIPTE_CONNECT_FILE', __FILE__);
define('WPCF_STRIPTE_CONNECT_BASE_NAME', plugin_basename( WPCF_REPORTS_FILE ) );


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
            'description'   => __( 'WPNeo Stripe Connect gateway is available', 'wp-crowdfunding' ),
        );

        $basicConfig = (array) WPCF_STRIPTE_CONNECT();
        $newConfig = array_merge($newConfig, $basicConfig);

        $config[ WPCF_STRIPTE_CONNECT_BASE_NAME ] = $newConfig;
        return $config;
    }

    if ( ! function_exists('WPCF_STRIPTE_CONNECT')) {
        function WPCF_STRIPTE_CONNECT() {
            $info = array(
                'path'              => plugin_dir_path( WPCF_STRIPTE_CONNECT_FILE ),
                'url'               => plugin_dir_url( WPCF_STRIPTE_CONNECT_FILE ),
                'basename'          => WPCF_STRIPTE_CONNECT_BASE_NAME,
                'nonce_action'      => 'wpcf_nonce_action',
                'nonce'             => '_wpnonce',
            );
            return (object) $info;
        }
    }

    $addonConfig = get_wpcf_addon_config( WPCF_STRIPTE_CONNECT_BASE_NAME );
    $isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
    if ( $isEnable ) {
        include_once 'classes/class-wpneo-stripe-connect.php';
        include_once 'classes/class-wpneo-stripe-connect-init.php';
    }
}
