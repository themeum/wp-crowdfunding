<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the WPCF main file
 */
define('WPCF_EMAIL_FILE', __FILE__);
define('WPCF_EMAIL_DIR_PATH', plugin_dir_path( WPCF_EMAIL_FILE ) );
define('WPCF_EMAIL_BASE_NAME', plugin_basename( WPCF_EMAIL_FILE ) );

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_email_config');
function wpcf_email_config($config) {
	$newConfig = array(
		'name'          => __( 'Email', 'wp-crowdfunding' ),
		'description'   => __( sprintf('Email addon is available in the %s Enterprise version %s', '<a href="https://www.themeum.com/product/wp-crowdfunding-plugin/" target="_blank">', '</a>' ), 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_EMAIL();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[ WPCF_EMAIL_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_EMAIL')) {
	function WPCF_EMAIL() {
		$info = array(
			'path'              => WPCF_EMAIL_DIR_PATH,
			'url'               => plugin_dir_url( WPCF_EMAIL_FILE ),
			'basename'          => WPCF_EMAIL_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

$addonConfig = get_wpcf_addon_config( WPCF_EMAIL_BASE_NAME );
$isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include 'classes/init.php';
}