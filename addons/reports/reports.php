<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the WPCF main file
 */
define('WPCF_REPORTS_FILE', __FILE__);
define('WPCF_REPORTS_BASE_NAME', plugin_basename( WPCF_REPORTS_FILE ) );

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_reports_config');
function wpcf_reports_config($config) {
	$newConfig = array(
		'name'          => __( 'Reports', 'wp-crowdfunding' ),
		'description'   => __( 'Email addon is available in the %s Enterprise version %s', 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_EMAIL();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[ WPCF_REPORTS_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_EMAIL')) {
	function WPCF_EMAIL() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_REPORTS_FILE ),
			'url'               => plugin_dir_url( WPCF_REPORTS_FILE ),
			'basename'          => WPCF_REPORTS_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

$addonConfig = get_wpcf_addon_config( WPCF_REPORTS_BASE_NAME );
$isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include 'classes/init.php';
}