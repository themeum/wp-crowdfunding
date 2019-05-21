<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the WPCF main file
 */
define('WPCF_2CHECKOUT_FILE', __FILE__);
define('WPCF_2CHECKOUT_BASE_NAME', plugin_basename( WPCF_2CHECKOUT_FILE ) );

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
	$config[ WPCF_2CHECKOUT_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_2CHECKOUT')) {
	function WPCF_2CHECKOUT() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_2CHECKOUT_FILE ),
			'url'               => plugin_dir_url( WPCF_2CHECKOUT_FILE ),
			'basename'          => WPCF_2CHECKOUT_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

$addonConfig = get_wpcf_addon_config( WPCF_2CHECKOUT_BASE_NAME );
$isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include 'classes/init.php';
}