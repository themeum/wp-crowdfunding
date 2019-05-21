<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the WPCF main file
 */
define('WPCF_RECAPTCHA_FILE', __FILE__);
define('WPCF_RECAPTCHA_VERSION', '1.0');
define('WPCF_RECAPTCHA_DIR_PATH', plugin_dir_path( WPCF_RECAPTCHA_FILE ) );
define('WPCF_RECAPTCHA_BASE_NAME', plugin_basename( WPCF_RECAPTCHA_FILE ) );

/**
 * Showing config for addons central lists
 */
add_filter('wpcf_addons_lists_config', 'wpcf_recaptcha_config');
function wpcf_recaptcha_config($config) {
	$newConfig = array(
		'name'          => __( 'reCAPTCHA', 'wp-crowdfunding' ),
		'description'   => __( 'Stay away from all spam comments and unauthorized login attempts by reCAPTCHA', 'wp-crowdfunding' ),
	);

	$basicConfig = (array) WPCF_RECAPTCHA();
	$newConfig = array_merge($newConfig, $basicConfig);

	$config[ WPCF_RECAPTCHA_BASE_NAME ] = $newConfig;
	return $config;
}

if ( ! function_exists('WPCF_RECAPTCHA')) {
	function WPCF_RECAPTCHA() {
		$info = array(
			'path'              => plugin_dir_path( WPCF_RECAPTCHA_FILE ),
			'url'               => plugin_dir_url( WPCF_RECAPTCHA_FILE ),
			'basename'          => WPCF_RECAPTCHA_BASE_NAME,
			'nonce_action'      => 'wpcf_nonce_action',
			'nonce'             => '_wpnonce',
		);
		return (object) $info;
	}
}

/**
 * Some task during plugin activation
 */
register_activation_hook( WPCF_RECAPTCHA_FILE, array('Recaptcha_Init', 'initial_plugin_setup') );


$addonConfig = get_wpcf_addon_config( WPCF_RECAPTCHA_BASE_NAME );
$isEnable = (bool) wpcf_avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include 'recaptcha-functions.php';
	include 'classes/init.php';
}