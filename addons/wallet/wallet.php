<?php
defined( 'ABSPATH' ) || exit;

/**
 * Defined the tutor main file
 */
define('WPCF_WALLET_FILE', __FILE__);

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

	$config[plugin_basename( WPCF_WALLET_FILE )] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_WALLET')) {
	function WPCF_WALLET() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_WALLET_FILE ),
			'url'               => plugin_dir_url( WPCF_WALLET_FILE ),
			'basename'          => plugin_basename( WPCF_WALLET_FILE ),
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}


/**
 * Include necessary version
 */
if (WPCF_TYPE === 'enterprise'){
    $load_tab = WPCF_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet.php';
}else{
    $load_tab = WPCF_DIR_PATH.'addons/wallet/wpneo-crowdfunding-wallet-demo.php';
}
include_once $load_tab;