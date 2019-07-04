<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('WPCF_SOCIAL_SHARE_FILE', __FILE__);
define('WPCF_SOCIAL_SHARE_DIR_PATH', plugin_dir_path( WPCF_SOCIAL_SHARE_FILE ) );
define('WPCF_SOCIAL_SHARE_BASE_NAME', plugin_basename( WPCF_SOCIAL_SHARE_FILE ) );

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_social_share_config');
function wpcf_social_share_config($config){
	$newConfig = array(
		'name'          => __( 'Social Share', 'wp-crowdfunding' ),
		'description'   => __( 'Connect with more visitors by sharing your site with Social Share addon.', 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_SOCIAL_SHARE();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[ WPCF_SOCIAL_SHARE_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_SOCIAL_SHARE')) {
	function WPCF_SOCIAL_SHARE() {
		$info = array(
			'path'              => WPCF_SOCIAL_SHARE_DIR_PATH,
			'url'               => plugin_dir_url( WPCF_SOCIAL_SHARE_FILE ),
			'basename'          => WPCF_SOCIAL_SHARE_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}


$addonConfig = wpcf_function()->addon_config( WPCF_SOCIAL_SHARE_BASE_NAME );
$isEnable = (bool)wpcf_function()->avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include_once 'classes/Init.php';
}