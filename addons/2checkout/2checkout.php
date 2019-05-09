<?php


if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Defined the tutor main file
 */
define('WPCF_2CHECKOUT_FILE', __FILE__);

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_2checkout_config');
function wpcf_2checkout_config($config){
	$newConfig = array(
		'name'          => __( '2Checkout', 'wp-crowdfunding' ),
		'description'   => __( sprintf('2Checkout Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>'), 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_2CHECKOUT();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[plugin_basename( WPCF_2CHECKOUT_FILE )] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_2CHECKOUT')) {
	function WPCF_2CHECKOUT() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_2CHECKOUT_FILE ),
			'url'               => plugin_dir_url( WPCF_2CHECKOUT_FILE ),
			'basename'          => plugin_basename( WPCF_2CHECKOUT_FILE ),
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}


if (WPCF_TYPE === 'enterprise'){
    include_once '2checkout-init.php';
}else{
    include_once '2checkout-demo.php';
}