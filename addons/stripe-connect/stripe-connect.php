<?php

/**
 *
 * Paypal Addon for woocommerce
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check is plugin.php file loaded
 */
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

//Check is WooCommerce is Active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    
    if (WPNEO_CROWDFUNDING_TYPE === 'enterprise'){
        include_once 'classes/class-wpneo-stripe-connect.php';
        include_once 'classes/class-wpneo-stripe-connect-init.php';
    }else{
        include_once 'classes/class-wpneo-stripe-connect-demo.php';
    }
    
}
