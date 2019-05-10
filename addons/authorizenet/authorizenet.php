<?php


if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Defined the tutor main file
 */
define('WPCF_AUTHORIZENET_FILE', __FILE__);

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_authorizenet_config');
function wpcf_authorizenet_config($config){
	$newConfig = array(
		'name'          => __( 'Authorize.Net', 'wp-crowdfunding' ),
		'description'   => __( sprintf('Authorize.Net Payment gateway is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' ), 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_AUTHORIZENET();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[plugin_basename( WPCF_AUTHORIZENET_FILE )] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_AUTHORIZENET')) {
	function WPCF_AUTHORIZENET() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_AUTHORIZENET_FILE ),
			'url'               => plugin_dir_url( WPCF_AUTHORIZENET_FILE ),
			'basename'          => plugin_basename( WPCF_AUTHORIZENET_FILE ),
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}


if (WPCF_TYPE === 'enterprise'){
    include_once 'authorizenet-base.php';
}else{
    include_once 'authorizenet-demo.php';
}
