<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_WALLET_FILE', __FILE__);
define('WPCF_WALLET_DIR_PATH', plugin_dir_path( WPCF_WALLET_FILE ) );
define('WPCF_WALLET_BASE_NAME', plugin_basename( WPCF_WALLET_FILE ) );

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_wallet_config');
function wpcf_wallet_config($config){
	$newConfig = array(
		'name'          => __( 'Wallet', 'wp-crowdfunding' ),
		'description'   => __( '', 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_WALLET();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[ WPCF_WALLET_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_WALLET')) {
	function WPCF_WALLET() {
		$info = array(
			'path'              => WPCF_WALLET_DIR_PATH,
			'url'               => plugin_dir_url( WPCF_WALLET_FILE ),
			'basename'          => WPCF_WALLET_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

$addonConfig = get_wpcf_addon_config( WPCF_WALLET_BASE_NAME );
$isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include 'classes/init.php';
}