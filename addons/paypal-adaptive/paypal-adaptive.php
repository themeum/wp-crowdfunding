<?php
/**
 *
 * Paypal Addon for woocommerce
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

//Check is WooCommerce is Active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {

    if (WPCF_TYPE === 'enterprise'){
        include_once 'classes/class-paypal-adaptive-payment.php';
        include_once 'classes/class-wpneo-adaptive-payment-status-dashboard.php';
        include_once 'classes/class-wpneo-adaptive-payment-initiate.php';
    }else{
        include_once 'classes/class-paypal-adaptive-payment-demo.php';
    }
    
}